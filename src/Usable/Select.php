<?php

namespace Kompo;

use Closure;
use Illuminate\Support\Str;
use Kompo\Eloquent\ModelManager;
use Kompo\Komponents\Field;
use Vuravel\Catalog\Card;
use Illuminate\Database\Eloquent\Model;

class Select extends Field
{
    public $component = 'Select';

    const DB_OPTIONS_ROUTE = 'vuravel-form.select-ajax-options';

    const NO_OPTIONS_FOUND = 'No results found';

    const ENTER_MORE_CHARACTERS = 'Please enter more than :MIN characters...';

    public $options = [];

    protected $optionsKey;
    protected $optionsLabel;

    protected $orderBy = [];

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);
        $this->data(['noOptionsFound' => __(self::NO_OPTIONS_FOUND)]);
    }

    public function prepareValueForFront($name, $value, $model)
    {
        //Load options...
        if($this->optionsKey && $this->optionsLabel)
            $this->options( ModelManager::getRelatedCandidates($model, $name), $this->optionsKey, $this->optionsLabel );

        $this->setValueForFront($value);
    }

    protected function setValueForFront($value)
    {
        if(!$value)
            return;

        $this->value = ($key = $this->valueKeyName($value)) ? $value->{$key} : $value;
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

        $results = [];
        foreach ($options as $key => $value) {

            if($optionsLabel)
            {
                if($optionsLabel instanceof Card){
                    $computedLabel = clone $optionsLabel;
                    $components = collect($computedLabel->components)->map(function($mapping, $column) use($value) {
                        return $mapping instanceof Closure && is_callable($mapping) ? 
                                $mapping($value) : $value->{$mapping};
                    })->all();
                    $computedLabel->components = $components;

                }elseif(is_array($optionsLabel)){
                    $computedLabel = collect($optionsLabel)->map(function($mapping, $column) use($value) {
                        return $mapping instanceof Closure && is_callable($mapping) ? 
                                $mapping($value) : $value->{$mapping};
                    });
                }else{ //if string 
                    $computedLabel = $value->{$optionsLabel};
                }
            }

            array_push($results, [
                'label' => $optionsLabel ? $computedLabel : $value, 
                'value' => $optionsKey ? $value->{$optionsKey} : $key 
            ]);
        }

        return $results;
    }


    /**
     * A cleaner way, <u>when you are using Eloquent relationships</u>, is to use this method that does the query for you. You need to specify the value/label columns in the parameters. For example:
     * <php>Select::form('Pick the tags')
     *    ->name('tags')  //<--Vuravel will know this is the Tag Model
     *    ->optionsFrom('tag_id', 'tag_name') //<-- value / label convention</php>
     * When displaying a <b>CustomLabel</b>, `$labelColumns` accepts an array of <b>strings</b> or <b>Closures</b>:
     * <php>Select::form('Pick the tags')->name('tags')
     *    ->optionsFrom('id', IconText::form([ //<-- using a custom Label component
     *       'text' => 'name',  //$tag->name
     *       'icon' => `function`($tag){ return $tag->published ? 'icon-check' : 'icon-edit'; }
     *    ]))</php>
     * 
     * @param  string  $keyColumn The key representing the value of the element saved in the DB.
     * @param  string|array|Vuravel\Components\Card  $labelColumns Can be a simple string, an associative array of <b>strings</b> or <b>Closures</b> or a Card component.
     * @return self
     */
    public function optionsFrom($keyColumn, $labelColumns)
    {
        $this->optionsKey = $keyColumn;
        $this->optionsLabel = $labelColumns;
        return $this;
    }

    /**
     * You may load the select options from the backend using the user's input. 
     * For that, a new public method in your class is needed to return the matched options. 
     * Note that the requests are debounced.
     * For example:
     * <php>public function components()
     * {
     *    return [
     *       //User can search and matched options will be loaded from the backend
     *       Select::form('Users')
     *          ->optionsFrom('id','name')
     *          ->searchOptions(2, 'getMatchedUsers')  
     *    ]
     * }
     * 
     * //A new method is added to the Form class to send the matched options back.
     * public function getMatchedUsers($value = '') //<-- The search value (can be empty)
     * {
     *     return Users::where('name', 'LIKE', '%'.$value.'%')
     *        ->pluck('name', 'id'); //return an associative array.
     * }
     * </php>
     * If the `$methodName` parameter is left blank, the default method will be 'search{camel_case(field_name)}'. For example, for a field name of users, you may directly declare a searchUsers method in your Form Class to return the options.
     *
     * @param      integer  $minSearchLength  The minimum search length
     * @param      string   $methodName       The public method name
     *
     * @return     self 
     */
    public function searchOptions($minSearchLength = 0, $methodName = null)
    {
        $this->setAjaxOptionsRoute($methodName);
        $this->data([
            'ajaxMinSearchLength' => $minSearchLength,
            'enterMoreCharacters' => __(self::ENTER_MORE_CHARACTERS, ['min' => $minSearchLength])
        ]);
        return $this;
    }

    /**
     * You may load the select options from the backend using another field's value. 
     * For that, a new public method in your class is needed to return the new options. 
     * For example:
     * <php>public function components()
     * {
     *    return [
     *       Select::form('Category')
     *          ->loadFrom('category_id', 'category_name'),
     *       //Tags options will load by Ajax when a category changes
     *       Select::form('Tags')
     *          ->loadFromField('category', 'getTags')  
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
     * If the `$methodName` parameter is left blank, the default method will be 'search{camel_case(field_name)}'. For example, for a field name of first_name, you may directly declare a searchFirstName method in your Form Class to return the options.
     * 
     * @param      string  $otherFieldName  The other field's name.
     * @param      string|null  $methodName      The public method name
     *
     * @return     self 
     */
    public function optionsFromField($otherFieldName, $methodName = null)
    {
        $this->setAjaxOptionsRoute($methodName);
        $this->data([
            'ajaxOptionsFromField' => $otherFieldName,
        ]);
        return $this;
    }

    protected function setAjaxOptionsRoute($methodName = null)
    {
        $this->data(['ajaxOptionsRoute' => route(self::DB_OPTIONS_ROUTE)]);
        $this->data([
            'ajaxOptionsMethod' => $methodName ?: ('search'.ucfirst(Str::camel($this->name)))
        ]);
    }

}
