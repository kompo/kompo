<?php

namespace Kompo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Support\Str;
use Kompo\Core\KompoTarget;
use Kompo\Core\RequestData;
use Kompo\Core\ValidationManager;
use Kompo\Komponents\Field;
use Kompo\Komposers\Form\FormBooter;
use Kompo\Komposers\Form\FormSubmitter;
use Kompo\Routing\RouteFinder;

class MultiForm extends Field
{
    public $vueComponent = 'MultiForm';

    public $komponents;

    public $multiple = true;

    protected $formClass;

    protected $childStore;

    protected static $multiFormKey = 'multiFormKey';

    protected function vlInitialize($name)
    {
        parent::vlInitialize($name);
        $this->name = lcfirst(Str::camel($name));
    }

    public function prepareForFront($komposer)
    {
        $this->komponents = !$this->value ? 

            [ $this->prepareChildForm(null) ] : 

            $this->value->map(function($item){

                return $this->prepareChildForm($item->getKey());

            })->all();
    }

    protected function prepareChildForm($modelKey)
    {
        if(!($formClass  = $this->formClass))
            return;

        $form = new $formClass($modelKey, $this->childStore);
        $form->{static::$multiFormKey} = $modelKey;
        return $form;
    }

    public function mounted($form)
    {
        if(!($childForm = $this->prepareChildForm(null))) 
            return;

        $rules = collect(ValidationManager::getRules($childForm))->flatMap(function($v, $k){

            return [($this->name.'.*.'.$k) => $v];

        })->all();

        ValidationManager::addRulesToKomposer($rules, $form);
    }



    public function setRelationFromRequest($requestName, $name, $model, $key = null)
    {
        collect(RequestData::get($requestName))->map(function($subrequest){

            $form = FormBooter::bootForAction([
                'kompoClass' => $this->formClass,
                'store' => $this->childStore,
                'parameters' => [], // is this feature needed?
                'modelKey' => $subrequest[static::$multiFormKey] ?? null
            ]);

            //No Validation or Authorization step - it has already been done on the parent Form

            //Then we swap the requests for save
            $mainRequest = request();            
            $subrequest = new Request($subrequest);

            RequestFacade::swap($subrequest);

            FormSubmitter::saveModel($form);

            RequestFacade::swap($mainRequest); //then swap back the original

        });
    }

    /**
     * Sets the fully qualified class name of the form that will be loaded from the Back-end or displayed multiple times when displaying relationships.
     *
     * @param string  $formClass  The fully qualified form class. Ex: App\Http\Komposers\MyForm::class
     * @param array|null  $ajaxPayload  Associative array of custom data to include in the form's store (optional).
     */
    public function formClass($formClass, $ajaxPayload = null)
    {
        $this->formClass = $formClass;
        $this->childStore = $ajaxPayload ?: [];

        return $this->data(array_merge([
            'route' => RouteFinder::getKompoRoute(),
            'routeMethod' => 'POST', //had to be POST to send ajaxPayload
            'ajaxPayload' => $ajaxPayload,
            'sessionTimeoutMessage' => __('sessionTimeoutMessage')
        ],
            KompoTarget::getEncryptedArray($formClass)
        ));
    }

}
