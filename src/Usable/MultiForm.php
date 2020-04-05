<?php

namespace Kompo;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Kompo\Core\ValidationManager;
use Kompo\Komponents\Field;
use Kompo\Routing\RouteFinder;

class MultiForm extends Field
{
    public $component = 'MultiForm';

    public $components;

    public $multiple = true;

    public $formClass;

    protected function vlInitialize($name)
    {
        parent::vlInitialize($name);
        $this->name = lcfirst(Str::camel($name));
    }

    public function prepareValueForFront($name, $value, $komposer)
    {
        if($value){
            //Only works for Eloquent relation, MultiForm cannot be an attribute currently
            $this->components = $value->map(function($item){
                $formClass = $this->formClass;
                return $formClass::find($item->getKey());
            })->all();
        }
    }

    public function mounted($form)
    {
        $rules = $this->components ? collect(ValidationManager::getRules($this->components[0]))->flatMap(function($v, $k){
            $k = $this->name.'.*.'.$k;
            return [$k => $v];
        })->all() : [];

        ValidationManager::addRulesToKomposer($rules, $form);
    }



    protected function setRelationFromRequest($requestName, $name, $model)
    {
        $this->value = collect(request()->__get($requestName))->map(function($subrequest){
            
            $request = new Request($subrequest);
            
            $formClass = $this->formClass;
            $form = with(new $formClass(true))->bootFromRequest($request);

            if($form->modelKey){
                $form->updateRecordFromRequest($request);
                return null; //the work has been done
            }else{
                return $form->newModelInstanceFromRequest($request)->toArray(); //saved in next step BUT IT'S MISSING RELATIONSHIP SAVING STEP :(
            }
        })->filter();
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
        $this->components = [ new $formClass() ];

        return $this->data([
            'route' => RouteFinder::getKompoRoute(),
            'routeMethod' => 'GET',
            'formClass' => $formClass,
            'ajaxPayload' => $ajaxPayload,
            'sessionTimeoutMessage' => __('sessionTimeoutMessage')
        ]);
    }

}
