<?php

namespace Kompo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Support\Str;
use Kompo\Core\KompoTarget;
use Kompo\Core\RequestData;
use Kompo\Core\ValidationManager;
use Kompo\Database\Lineage;
use Kompo\Database\ModelManager;
use Kompo\Exceptions\NoMultiFormClassException;
use Kompo\Form;
use Kompo\Elements\Field;
use Kompo\Elements\Traits\HasAddLabel;
use Kompo\Komponents\Form\FormSubmitter;
use Kompo\Routing\RouteFinder;

use Illuminate\Database\Eloquent\Model;

class MultiForm extends Field
{
    use HasAddLabel;

    public $vueComponent = 'MultiForm';

    public $elements;

    public $multiple = true;

    protected $formClass;

    protected $childStore;

    protected static $multiFormKey = 'multiFormKey';

    protected $relationScope;

    protected $preloadIfEmpty = false;

    protected function initialize($name)
    {
        parent::initialize($name);
        $this->name = lcfirst(Str::camel($name));

        $this->addLabel('Add a new item');
    }

    public function getValueFromModel($model, $name)
    {
        return ModelManager::getValueFromDb($model, $name, $this->relationScope);
    }

    /**
     * When updating children items, we may adda scope to show specific children only in the MultiForm.
     * <php>_MultiForm()->name('postItems')
            ->relationScope(function($query){
                $query->where('type', 'news-articles');
            })</php>
     *
     * @param   Closure  $callback  The callback
     *
     * @return     self
     */
    public function relationScope($callback)
    {
        $this->relationScope = $callback;

        return $this;
    }

    protected function prepareChildForm($parentForm, $model = null)
    {
        if (!($formClass = $this->formClass)) {
            throw new NoMultiFormClassException($this->name);
        }

        $modelKey = $model ? $model->getKey() : null;
        $childForm = new $formClass($modelKey, $this->childStore);
        if ($model) {
            $childForm->model($model);
        } //set the model
        $childForm->boot();

        $childForm->{static::$multiFormKey} = $modelKey;

        return $childForm;
    }

    public function mounted($parentForm)
    {
        //Pass rules upstream
        ValidationManager::addRulesToKomponent(
            collect(ValidationManager::getRules($this->prepareChildForm($parentForm)))
                ->flatMap(function ($v, $k) {
                    return [($this->name.'.*.'.$k) => $v];
                })->all(),
            $parentForm
        );

        $this->elements = !$this->value ? 

            ($this->preloadIfEmpty ? [$this->prepareChildForm($parentForm)] : []) :

            $this->value->map(function ($item) use ($parentForm) {
                return $this->prepareChildForm($parentForm, $item);
            })->all();
    }

    public function setRelationFromRequest($requestName, $name, $model, $key = null)
    {
        \DB::transaction(
            fn() => collect(RequestData::get($requestName))->each(
                fn($subrequest, $subKey) => $this->saveSingleRelation($model, $requestName, $subrequest, $subKey)
            )
        );
    }

    protected function saveSingleRelation($model, $requestName, $subrequest, $subKey)
    {
        $form = Form::constructFromBootInfo([
            'kompoClass' => $this->formClass,
            'store'      => $this->childStore,
            'parameters' => [], // is this feature needed?
            'modelKey'   => static::getModelKeyFromRequest($subrequest, $this->value, $subKey),
        ])->bootForAction();

        //No Validation or Authorization step - it has already been done on the parent Form

        if (Lineage::isOneToMany($model, $requestName)) {
            $relation = Lineage::findRelation($model, $requestName);
            $form->model->{$relation->getForeignKeyName()} = $model->id;
        }

        //If all fields are null, don't create a relation for nothing, unless user configured it to do so
        if (collect($subrequest)->filter()->count() == 0 && !$this->acceptsNullRelations()) {
            return;
        }

        //Then we swap the requests for save
        $mainRequest = request();
        $subrequest = new Request($subrequest);

        RequestFacade::swap($subrequest);

        FormSubmitter::saveModel($form);

        RequestFacade::swap($mainRequest); //then swap back the original
    }

    protected static function getModelKeyFromRequest($subrequest, $value, $subKey)
    {
        if (array_key_exists(static::$multiFormKey, $subrequest)) {
            
            return $subrequest[static::$multiFormKey];

        }else if ($value) {
            
            $model = $value[$subKey] ?? null;

            return $model instanceof Model ? $model->getKey() : $model; //TODO: check if the else case here is relevant
        }
    }

    /**
     * Sets the fully qualified class name of the form that will be loaded from the Back-end or displayed multiple times when displaying relationships.
     *
     * @param string     $formClass   The fully qualified form class. Ex: App\Http\Komponents\MyForm::class
     * @param array|null $ajaxPayload Associative array of custom data to include in the form's store (optional).
     */
    public function formClass($formClass, $ajaxPayload = null)
    {
        $this->formClass = $formClass;
        $this->childStore = $ajaxPayload ?: [];

        return $this->config(array_merge(
            [
                'route'                 => RouteFinder::getKompoRoute(),
                'routeMethod'           => 'POST', //had to be POST to send ajaxPayload
                'ajaxPayload'           => $ajaxPayload,
            ],
            KompoTarget::getEncryptedArray($formClass)
        ));
    }

    /**
     * When we want to display the children Elements in a table, we may use this method and pass it the table's headers as parameters.
     *
     * @param      array  $headers  The headers
     *
     * @return     self   ( description_of_the_return_value )
     */
    public function asTable($headers = [])
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Disables adding more child items to the MultiForm
     *
     * @return  self
     */
    public function noAdding()
    {
        return $this->config([
            'noAdding' => true,
        ]);
    }

    /** TODO: DOCUMENT
     * Adding more child items to the MultiForm is positionned on top instead of on bottom
     *
     * @return  self
     */
    public function topAdding()
    {
        return $this->config([
            'topAdding' => true,
        ]);
    }

    /**
     * TODO:
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function acceptNullRelations()
    {
        return $this->config([
            'acceptNullRelations' => true,
        ]);
    }

    /**
     * If the parent does not have any children yet, we may preload a line of the child form so that the user may start filling the fields directly. (It saves a click on the link to add an itemand helps UI wise).
     *
     * @return     self 
     */
    public function preloadIfEmpty()
    {
        $this->preloadIfEmpty = true;

        return $this;
    }

    protected function isNotAdding()
    {
        return $this->config('noAdding');
    }

    protected function acceptsNullRelations()
    {
        return $this->config('acceptNullRelations');
    }
}
