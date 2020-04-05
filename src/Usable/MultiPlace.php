<?php

namespace Kompo;

use Kompo\Database\ModelManager;
use Kompo\Place;
use LogicException;

class MultiPlace extends Place
{
    public $multiple = true;

    public function attributesToColumns()
    {
        throw new LogicException("Only Kompo\Place accepts the attributesToColumns() method.");
    }

    protected function setAttributeFromRequest($requestName, $name, $model)
    {
		$value = collect(request()->__get($requestName))->map(function($place){

            return $this->placeToDB($place);

        });

        return $value->count() ? $value : null;
    }

    protected function setRelationFromRequest($requestName, $name, $model)
    {
        $oldPlaces = ModelManager::getValueFromDb($model, $name);

        $newPlaces = request()->__get($requestName);

        $keepExtIds = [];

    	if($oldPlaces && $oldPlaces->count()){

            $newExtIds = collect($newPlaces)->pluck('id')->filter()->all(); //id is the key from the gmaps address_components
            
            $keepExtIds = $oldPlaces->map(function($place) use($newExtIds) { 
                if(!in_array($place->{$this->external_idKey},$newExtIds)){
                    $place->delete(); //No detach, onDelete('cascade') should give the choice.
                }else{
                    return $place->{$this->external_idKey} ?? '';
                }
            })->all();
        }
        if($newPlaces)
        	return collect($newPlaces)->map(function($newPlace) use($keepExtIds){
                    if(!in_array($newPlace['id'] ?? 'not-found', $keepExtIds))  //id is the key from the gmaps address_components
                        return $this->placeToDB($newPlace);
    	        })->filter();

        return null;
    }

}
