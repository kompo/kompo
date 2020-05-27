<?php

namespace Kompo\Komponents;

use Illuminate\Support\Str;
use Kompo\Query;
use Kompo\Core\RequestData;
use Kompo\Core\Util;
use Kompo\Core\ValidationManager;
use Kompo\Database\ModelManager;
use Kompo\Form;
use Kompo\Interactions\Traits\HasInteractions;
use Kompo\Interactions\Traits\ForwardsInteraction;
use Kompo\Komponents\FormField;
use Kompo\Komposers\KomposerManager;

abstract class Field extends Komponent
{
    use HasInteractions, ForwardsInteraction;
    use Traits\AjaxConfigurations,
        Traits\FormSubmitConfigurations,
        Traits\LabelInfoComment; 

    public $bladeComponent = 'Field';

    /**
     * The field's HTML attribute in the form (also the formData key).
     *
     * @var string
     */
    public $name;
    
    /**
     * The field's value.
     *
     * @var string|array
     */
    public $value;

    /**
     * The field's placeholder.
     *
     * @var string|array
     */
    public $placeholder;

    /**
     * The field's sluggable column.
     *
     * @var string|false
     */
    protected $slug = false;


    /**
     * The field's config for internal usage. Contains submit handling configs, field relation to model, etc...
     *
     * @var array
     */
    protected $_kompo = [
        'eloquent' => [
            'ignoresModel' => false, //@var bool   Doesn't interact with model on display or submit.
            'doesNotFill' => false,  //@var bool   Gets the model's value on display but does not on submit.
            'extraAttributes' => [], //@var array  Additional attributes (key/value constants) to save in DB
            'morphToModel' => null   //@var string When a morphTo relationship is referenced in the field, we need to specify a Model class.
        ]
    ];

    /**
     * Initializes a Field component.
     *
     * @param  string $label
     * @return void
     */
    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->name = Str::snake($label); //not $this->label because it could be already translated

    }

    /**
     * Appends <a href="https://laravel.com/docs/master/validation#available-validation-rules" target="_blank">Laravel input validation rules</a> for the field.
     *
     * @param  string|array $rules A | separated string of validation rules or Array of rules.
     * @return self
     */
    public function rules($rules)
    {
        return ValidationManager::setFieldRules($rules, $this);
    }

    /**
     * Sets the name for the field corresponding the attribute it will fill.
     *
     * @param  string|array $name The name attribute of the field.
     * 
     * @return self
     */
    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Sets the value of the field for the output (display) phase. 
     * <u>Note</u>: if the Form is connected to an Eloquent Model, the DB value takes precedence.
     *
     * @param  string|array $value The value to be set.
     * 
     * @return self
     */
    public function value($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Sets the placeholder of this field. 
     * By default, the fields have no placeholder.
     *
     * @param  string $placeholder The placeholder for the field.
     * @return self
     */
    public function placeholder($placeholder)
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * Sets a default value to the field. Applies if the value is empty.
     *
     * @param  string $defaultValue The default value
     * @return self
     */
    public function default($defaultValue)
    {
        if($this->pristine())
            $this->value($defaultValue);
        return $this;
    }

    /**
     * Determines if the field has a value or is pristine.
     *
     * @return Boolean
     */
    public function pristine()
    {
        return $this->value ? false : true;
    }

    /**
     * Adds a slug in the table from this field. For example, this will populate the `title` column with the field's value and the `slug` column with it's corresponding slug. 
     * <php>Input::form('Title')->sluggable('slug')</php>
     *
     * @param  string|null $slugColumn The name of the column that contains the slug
     * @return self
     */
    public function sluggable($slugColumn = 'slug')
    {
        $this->slug = $slugColumn;
        return $this;
    }

    /**
     * Sets a required (&#42;) indicator and adds a required validation rule to the field.
     * 
     * @param string|null The required indicator Html. The default is (&#42;).
     *
     * @return self
     */
    public function required($indicator = '*')
    {
        $this->data(['required' => $indicator]);
        $this->rules('required');
        return $this;
    }

    /**
     * Makes the field readonly (not editable).
     *
     * @return self
     */
    public function readOnly()
    {
        return $this->data(['readOnly' => true]);
    }

    /**
     * Removes the browser's default autocomplete behavior from the field.
     *
     * @return self
     */
    public function noAutocomplete()
    {
        return $this->data(['noAutocomplete' => true]);
    }
    
    /**
     * This specifies extra attributes (constant columns/values) to add to the model.
     *
     * @param      array  $attributes  Constant columns/values pairs (associative array).
     *
     * @return self  
     */
    public function extraAttributes($attributes = [])
    {
        FormField::setExtraAttributes($this, $attributes);
        return $this;
    }
    
    /**
     * Removes a specific field from the database interaction process.
     *
     * @return self
     */
    public function ignoresModel()
    {
        return FormField::setConfig($this, 'ignoresModel', true);
    }

    /**
     * Has a value but it is not persisted in DB.
     *
     * @return self
     */
    public function doesNotFill()
    {
        return FormField::setConfig($this, 'doesNotFill', true);
    }


    /**
     * Internally used to disable the default Vue input wrapper in fields.
     *
     * @return  self
     */
    public function noInputWrapper()
    {
    	return $this->data([
    		'noInputWrapper' => true
    	]);
    }

    /**
     * Removes the default margin applied to fields. 
     * To disable ALL the fields in a form, use the $noMargins property on the Form.
     *
     * @return     self
     */
    public function noMargins()
    {
        return $this->data(['noMargins' => true]);
    }

    /**
     * Passes Form attributes to the component and sets it's value if it is a Field.
     * 
     * @param Kompo\Komposers\Komposer  $komposer
     *
     * @return void
     */
    public function prepareForDisplay($komposer)
    {
        if(property_exists($komposer, 'noMargins') && $komposer->noMargins)
            $this->noMargins();

        ValidationManager::pushCleanRulesToKomposer($this, $komposer);

        $this->checkSetReadonly($komposer);

        if($komposer instanceOf Form)
            FormField::retrieveValueFromModel($this, $komposer->model);

        if($komposer instanceOf Query)
            KomposerManager::pushField($komposer, $this); //when the filters have a value on display

        $this->prepareForFront($komposer);
    }

    /**
     * Passes Form attributes to the component and sets it's value if it is a Field.
     * 
     * @param Kompo\Komposers\Komposer  $komposer
     *
     * @return void
     */
    public function prepareForAction($komposer)
    {
        KomposerManager::pushField($komposer, $this);
        
        parent::prepareForAction($komposer);

        ValidationManager::pushCleanRulesToKomposer($this, $komposer);
    }

    /**
     * Checks authorization and sets a readonly field if necessary.
     *
     * @param Kompo\Komposers\Komposer  $komposer
     * 
     * @return void
     */
    public function checkSetReadonly($komposer)
    {
        if(config('kompo.smart_readonly_fields') && method_exists($komposer, 'authorize')){

            $authorize = $komposer->authorize();

            Util::collect($this->name)->each(function($name) use($authorize) {
            
                if(!$authorize || (is_array($authorize) && !in_array($name, $authorize)))
                    $this->readOnly();

            });
        }
    }

    /**
     * Sets the field value before persisting in DB.
     *
     * @param  string|array $value
     * @return void
     */
    public function setInput($value, $key)
    {
        $this->value($value);
        return $this->value; 
    }

    /**
     * Sets the field value before preparing for Front.
     *
     * @param mixed $value
     * @param integer $key
     * 
     * @return void
     */
    public function setOutput($value, $key)
    {
        if($value)
            $this->value($value); //to be overriden
    }

    /**
     * Performing operations necessary for Field display on the Front-End
     *
     * @param Kompo\Komposers\Komposer  $komposer
     */
    public function prepareForFront($komposer)
    {
        //do nothing... overriden in Fields when needed
    }

    /**
     * Gets the value from model.
     *
     * @param      <type>  $model  The model
     * @param      <type>  $name   The name
     */
    public function getValueFromModel($model, $name)
    {
        return ModelManager::getValueFromDb($model, $name);
    }

    /**
     * Processes and returns the request value when the field is an attribute.
     *
     * @param string                             $requestName
     * @param string                             $name
     * @param Illuminate\Database\Eloquent\Model $model
     * @param integer|null                       $key
     */
    public function setAttributeFromRequest($requestName, $name, $model, $key = null)
    {
        return $this->setInput(RequestData::get($requestName), $key);
    }

    /**
     * Processes and returns the request value when the field is a relation.
     *
     * @param string                             $requestName
     * @param string                             $name
     * @param Illuminate\Database\Eloquent\Model $model
     * @param integer|null                       $key
     */
    public function setRelationFromRequest($requestName, $name, $model, $key = null)
    {
        return $this->setInput(RequestData::get($requestName), $key);
    }

    /**
     * Checks if the field deals with array value
     *
     * @param Illuminate\Database\Eloquent\Model $model
     * @param string                             $name
     * 
     * @return     Boolean  
     */
    public function shouldCastToArray($model, $name)
    {
        return !$model->hasCast($name) && 
            (property_exists($this, 'castsToArray') && $this->castsToArray) && 
            (!property_exists($this, 'attributesToColumns') || (property_exists($this, 'attributesToColumns') && !$this->attributesToColumns));
    }
    
}