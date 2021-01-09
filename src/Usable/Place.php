<?php

namespace Kompo;

use Kompo\Core\RequestData;
use Kompo\Core\Util;
use Kompo\Database\ModelManager;
use Kompo\Komponents\Field;
use Kompo\Komponents\Managers\FormField;

class Place extends Field
{
    public $vueComponent = 'Place';

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
     * @return self 
     */
    public function attributesToColumns()
    {
        $this->attributesToColumns = true;
        return $this;
    }

    /**
     * Sets the starting center point of the map
     *
     * @param      <type>  $lat    The lat
     * @param      <type>  $lng    The lng
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function defaultCenter($lat, $lng, $zoom = 10)
    {
        return $this->config([
            'defaultCenter' => [
                'lat' => $lat,
                'lng' => $lng,
            ],
            'defaultZoom' => $zoom
        ]);
    }

    public function getValueFromModel($model, $name)
    {
        return !$this->attributesToColumns ? ModelManager::getValueFromDb($model, $name) : collect($this->allKeys)->map(function($key) use ($model){
            return $model->{$key};
        })->filter()->all();      
    }

    public function setAttributeFromRequest($requestName, $name, $model, $key = null)
    {
        $oldPlace = $this->attributesToColumns ? $model : ModelManager::getValueFromDb($model, $name);

        if($newPlace = RequestData::get($requestName)){

        	$newPlace = $this->placeToDB($newPlace[0]);

        	if(!$this->attributesToColumns)
                return $newPlace;

            collect($newPlace)->each(function($attribute, $column) use($name){
                if($column !== $name)
                    FormField::setExtraAttributes($this, [$column => $attribute]);
            });

            return $newPlace[$name];
        }else{
        	if(!$this->attributesToColumns)
                return null;

            if($oldPlace->exists)
                collect($this->allKeys)->each(function($key){
                    FormField::setExtraAttributes($this, [$key => null]);
                });

            return null;
        }
    }

    public function setRelationFromRequest($requestName, $name, $model, $key = null)
    {
    	$oldPlace = ModelManager::getValueFromDb($model, $name);

        if($place = RequestData::get($requestName)){

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

    protected function placeToDB($place)
    {
    	$place = Util::decode($place);

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
	            $this->external_idKey => $place['place_id']
	        ]);
	    }else{
	    	return $place;
	    }
    }
}
