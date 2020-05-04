<?php

namespace Kompo;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Kompo\Card;
use Kompo\Core\DependencyResolver;
use Kompo\Core\KompoTarget;
use Kompo\Core\Util;
use Kompo\Database\EloquentField;
use Kompo\Komponents\Field;
use Kompo\Komponents\FormField;
use Kompo\Routing\RouteFinder;

class Select extends Field
{
    public $vueComponent = 'Select';

    const NO_OPTIONS_FOUND = 'No results found';

    const ENTER_MORE_CHARACTERS = 'Please enter more than :MIN characters...';

    public $options = [];

    protected $optionsKey;
    protected $optionsLabel;
    protected $morphToModel;

    protected $retrieveMethod;

    protected $orderBy = [];

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);
        $this->data(['noOptionsFound' => __(self::NO_OPTIONS_FOUND)]);
    }

    public function prepareForFront($komposer)
    {
        //Load options...
        if($this->optionsKey && $this->optionsLabel && !$this->data('ajaxOptions'))
            $this->options( 
                EloquentField::getRelatedCandidates($komposer->model, $this->name, FormField::getConfig($this, 'morphToModel')),
                $this->optionsKey, 
                $this->optionsLabel 
            );

        if($this->data('ajaxOptions') && $this->value)
            $this->retrieveOptionsFromValue($komposer);

        $this->setValueForFront();
    }

    protected function retrieveOptionsFromValue($komposer)
    {
        if($this->retrieveMethod && method_exists($komposer, $this->retrieveMethod)){
            $this->options(Util::collect($this->value)->mapWithKeys(function($optionKey) use($komposer){
                return $komposer->{$this->retrieveMethod}($optionKey)->all();
            }));
        }elseif($this->optionsKey && $this->optionsLabel){
            $this->options(Util::collect($this->value), $this->optionsKey, $this->optionsLabel);
        }else{
            //Not recommended last case scenario. This will load all options which may not be desired.
            //User should fix by using the above scenarios. Should I throw error or not?? No for now: worst case, the query will be slow.
            $allOptions = DependencyResolver::callKomposerMethod(
                $komposer, 
                KompoTarget::getDecrypted($this->data('ajaxOptionsMethod')), 
                ['search' => '']
            )->all();

            $this->options(Util::collect($this->value)->mapWithKeys(function($optionKey) use($allOptions){

                if($optionKey instanceOf Model)
                    $optionKey = $optionKey->getKey();

                return [$optionKey => $allOptions[$optionKey]];
            }));
        }
    }

    protected function setValueForFront()
    {
        $this->value = !$this->value ? null : (($key = $this->valueKeyName($this->value)) ? $this->value->{$key} : $this->value);
    }

    protected function valueKeyName($value)
    {
        return $this->optionsKey ?: ($value instanceOf Model ? $value->getKeyName() : null);
    }

    /**
     * Sets the Select's options. 
     * You may use an <b>associative array</b> directly:
     * <php>->options([
     *    'key1' => 'value1',
     *    'key2' => 'value2',
     *    ...
     * ])</php>
     * Or Laravel's <b>pluck</b> method :
     * <php>->options(Tags::pluck('tag_name', 'tag_id'))</php>
     *
     * @param  array|Collection  $options An associative array the values and labels of the options.
     * 
     * @return self
     */
    public function options($options = [], $optionsKey = null, $optionsLabel = null)
    {
    	$this->options = self::transformOptions($options, $optionsKey, $optionsLabel);

    	return $this;
    }

    /**
     * Transforms an associative array of options to the required format for the Front End select plugin.
     *
     * @param  array|Illuminate\Support\Collection  $options
     * @param  null|string  $optionsKey
     * @param  null|Array  $optionsLabel
     * @return self
     */
    public static function transformOptions($options = [], $optionsKey = null, $optionsLabel = null)
    {
        foreach ($options as $key => $value) {

            $results[] = [
                'label' => $optionsLabel ? static::transformLabel($optionsLabel, $value) : $value, 
                'value' => $optionsKey ? $value->{$optionsKey} : $key 
            ];
        }

        return $results ?? [];
    }

    protected static function transformLabel($optionsLabel, $value)
    {
        if($optionsLabel instanceof Card){
            $computedLabel = clone $optionsLabel;
            $computedLabel->komponents = static::transformLabelKey($computedLabel->komponents, $value);
            return $computedLabel;

        }elseif(is_array($optionsLabel)){
            return static::transformLabelKey($optionsLabel, $value);

        }elseif($optionsLabel instanceof Closure && is_callable($optionsLabel)){
            return $optionsLabel($value);

        }else{ //if string 
            return $value->{$optionsLabel};
        }
        
    }

    protected static function transformLabelKey($specsArray, $value)
    {
        return collect($specsArray)->map(function($mapping) use($value) {
            return $mapping instanceof Closure && is_callable($mapping) ? 

                    $mapping($value) : 

                    $mapping;
        })->all();
    }


    /**
     * A cleaner way, <u>when you are using Eloquent relationships</u>, is to use this method that does the query for you. You need to specify the value/label columns in the parameters. For example:
     * <php>Select::form('Pick the tags')
     *    ->name('tags')  //<-- Kompo will know this is the Tag Model
     *    ->optionsFrom('tag_id', 'tag_name') //<-- value / label convention</php>
     * When displaying a <b>CustomLabel</b>, `$labelColumns` accepts an array of <b>strings</b> or <b>Closures</b>:
     * <php>Select::form('Pick the tags')->name('tags')
     *    ->optionsFrom('id', IconText::form([ //<-- using a custom Label component
     *       'text' => 'name',  //$tag->name
     *       'icon' => `function`($tag){ return $tag->published ? 'icon-check' : 'icon-edit'; }
     *    ]))</php>
     * 
     * @param  string  $keyColumn The key representing the value of the element saved in the DB.
     * @param  string|array|Kompo\Card  $labelColumns Can be a simple string, an associative array of <b>strings</b> or <b>Closures</b> or a Card component.
     * @param string|null $morphToModel If MorphTo relation, we need to specify the model because it is unknown in the model's relationship.
     * 
     * @return self
     */
    public function optionsFrom($keyColumn, $labelColumns, $morphToModel = null)
    {
        $this->optionsKey = $keyColumn;
        $this->optionsLabel = $labelColumns;
        
        $this->morphToModel($morphToModel);

        return $this;
    }

    /**
     * If dealing with a MorphTo relation, we need to specify the model because it is unknown in the model's relationship.
     * No need to use this method if already specified with optionsFrom().
     *
     * @param string $morphToModel  The morph to model class as stored in the DB.
     *
     * @return self
     */
    public function morphToModel($morphToModel)
    {        
        FormField::setConfig($this, 'morphToModel', $morphToModel);

        return $this;
    }

    /**
     * You may load the select options from the backend using the user's input. 
     * For that, a new public method in your class is needed to return the matched options. 
     * Note that the requests are debounced.
     * For example:
     * <php>public function komponents()
     * {
     *    return [
     *       //User can search and matched options will be loaded from the backend
     *       Select::form('Users')
     *          ->optionsFrom('id','name')
     *          ->searchOptions(2, 'searchUsers')  
     *    ]
     * }
     * 
     * //A new method is added to the Form class to send the matched options back.
     * public function searchUsers($search = '') //<-- The search value (can be empty)
     * {
     *     return Users::where('name', 'LIKE', '%'.$search.'%')
     *        ->pluck('name', 'id'); //return an associative array.
     * }
     * </php>
     * If the `$searchMethod` parameter is left blank, the default method will be 'search{ucfirst(field_name)}'. 
     * For example, for a field name of users, you may directly declare a searchUsers method in your Form Class to return the options.
     *
     * @param      integer  $minSearchLength  The minimum search length
     * @param      string   $searchMethod     The method to search the options from the AJAX request.
     * @param      string|null   $retrieveMethod   (optional) The method to convert a DB value into an option on display.
     *
     * @return self 
     */
    public function searchOptions($minSearchLength = 0, $searchMethod = null, $retrieveMethod = null)
    {
        $this->retrieveMethod = $retrieveMethod ?: $this->inferOptionsMethod('retrieve', $retrieveMethod);

        return RouteFinder::activateRoute($this)->data([
            'ajaxOptions' => true,
            'ajaxMinSearchLength' => $minSearchLength,
            'enterMoreCharacters' => __(self::ENTER_MORE_CHARACTERS, ['min' => $minSearchLength]),
            'ajaxOptionsMethod' => KompoTarget::getEncrypted($searchMethod ?: $this->inferOptionsMethod('search', $searchMethod)),
        ]);
    }

    /**
     * You may load the select options from the backend using another field's value. 
     * For that, a new public method in your class is needed to return the new options. 
     * For example:
     * <php>public function komponents()
     * {
     *    return [
     *       Select::form('Category')
     *          ->optionsFrom('category_id', 'category_name'),
     *       //Tags options will load by Ajax when a category changes
     *       Select::form('Tags')
     *          ->optionsFromField('category', 'getTags')  
     *    ]
     * }
     * 
     * //A new method is added to the Form class to send the new options back.
     * public function getTags($value) //<-- the selected category's value.
     * {
     *     return Tags::where('category_id', $value)
     *       ->pluck('tag_name', 'tag_id'); //return an associative array.
     * }
     * </php>
     * If the `$searchMethod` parameter is left blank, the default method will be 'search{ucfirst(field_name)}'. 
     * For example, for a field name of first_name, you may directly declare a searchFirstName method in your Form Class to return the options.
     * 
     * @param      string  $otherFieldName  The other field's name.
     * @param      string|null  $searchMethod      The public method name
     * @param      string   $retrieveMethod   The method to convert a DB value into an option on display.
     *
     * @return self 
     */
    public function optionsFromField($otherFieldName, $searchMethod = null, $retrieveMethod = null)
    {
        $this->retrieveMethod = $searchMethod ?: $this->inferOptionsMethod('retrieve', $searchMethod);

        return RouteFinder::activateRoute($this)->data([
            'ajaxOptions' => true,
            'ajaxOptionsFromField' => $otherFieldName,
            'ajaxOptionsMethod' => KompoTarget::getEncrypted($searchMethod ?: $this->inferOptionsMethod('search', $searchMethod)),
        ]);
    }

    protected function inferOptionsMethod($step = 'search', $methodName = null)
    {
        $cleanName = array_map('ucfirst', explode('.', $this->name));
        return $step.implode('', $cleanName);
    }

}
