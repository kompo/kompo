<?php

namespace Kompo;

use Kompo\Eloquent\ModelManager;
use Kompo\Komponents\Field;
use Kompo\Utilities\Arr;

class Place extends Field
{
    public $component = 'Place';

    /**
     * Adds a cast to array to the attribute if no cast is present.
     *
     * @var boolean
     */
    protected $castsToArray = true;

    /**
     * Boolean flag to indicate whether to store the place attributes in separate columns.
     * 
     * @var array
     */
    protected $attributesToColumns = false;

    protected $allKeys;
    protected $addressKey;
    protected $street_numberKey;
    protected $streetKey;
    protected $cityKey;
    protected $stateKey;
    protected $countryKey;
    protected $postal_codeKey;
    protected $latKey;
    protected $lngKey;
    protected $external_idKey;

    /**
     * Assign the config columns
     *
     * @param  string  $label  The label
     */
    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        collect($this->allKeys = config('kompo.places_attributes'))->each(function($column, $key){
            $this->{$key.'Key'} = $column;
        });
    }

    /**
     * Use this flag if your files table has this default schema: id, name, path, mime_type, size.
     * Note: the name of the field should correspond to the path column.
     *
     * @return     self 
     */
    public function attributesToColumns()
    {
        $this->attributesToColumns = true;
        return $this;
    }

    public function getValueFromModel($model, $name)
    {
        return !$this->attributesToColumns ? ModelManager::getValueFromDb($model, $name) : collect($this->allKeys)->map(function($key) use ($model){
            return $model->{$key};
        })->all();      
    }

    protected function setAttributeFromRequest($name, $model)
    {
        $oldPlace = $this->attributesToColumns ? $model : ModelManager::getValueFromDb($model, $name);

        if($newPlace = request()->input($name)){

        	$newPlace = $this->placeToDB($newPlace[0]);

        	if(!$this->attributesToColumns)
                return $newPlace;

            collect($newPlace)->each(function($attribute, $column) use($name){
                if($column !== $name)
                    $this->extraAttributes[$column] = $attribute;
            });

            return $newPlace[$name];
        }else{
        	if(!$this->attributesToColumns)
                return null;

            if($oldPlace->exists)
                collect($this->allKeys)->each(function($key){
                    $this->extraAttributes[$key] = null;
                });

            return null;
        }
    }

    protected function setRelationFromRequest($name, $model)
    {
    	$oldPlace = ModelManager::getValueFromDb($model, $name);

        if($place = request()->input($name)){

        	$place = $this->placeToDB($place[0]);

        	if($oldPlace){
        		if($oldPlace->{$this->external_idKey} == $place[$this->external_idKey]){
        			return null;
        		}else{
        			$oldPlace->delete();
        			return $place;
        		}
        	}else{
        		return $place;
        	}

        }else{

        	$oldPlace->delete();

        	return null;
        }
    }

    /**
     * Overriden because of attributesToColumns. Checks if the field deals with array value
     *
     * @return     Boolean  
     */
    protected function shouldCastToArray($model, $name)
    {
        return parent::shouldCastToArray($model, $name) && !$this->attributesToColumns;
    }

    protected function placeToDB($place)
    {
    	$place = Arr::decode($place);

    	if($address_components = $place['address_components']){
	    	$result = [];
	    	foreach ($address_components as $value) {
	    		if(in_array('street_number', $value['types']))
	    			$result[$this->street_numberKey] = $value['long_name'];
	    		if(in_array('route', $value['types']))
	    			$result[$this->streetKey] = $value['long_name'];
	    		if(in_array('locality', $value['types']))
	    			$result[$this->cityKey] = $value['long_name'];
	    		if(in_array('administrative_area_level_1', $value['types']))
	    			$result[$this->stateKey] = $value['long_name'];
	    		if(in_array('country', $value['types']))
	    			$result[$this->countryKey] = $value['long_name'];
	    		if(in_array('postal_code', $value['types']))
	    			$result[$this->postal_codeKey] = $value['long_name'];
	    	}
	        return array_merge($result, [
	            $this->addressKey => $place['formatted_address'],
	            $this->latKey => $place['geometry']['location']['lat'],
	            $this->lngKey => $place['geometry']['location']['lng'],
	            $this->external_idKey => $place['id']
	        ]);
	    }else{
	    	return $place;
	    }
    }
}
