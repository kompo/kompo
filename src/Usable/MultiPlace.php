<?php

namespace Kompo;

use Kompo\Core\RequestData;
use Kompo\Database\ModelManager;
use LogicException;

class MultiPlace extends Place
{
    public $multiple = true;

    public function attributesToColumns()
    {
        throw new LogicException("Only Kompo\Place accepts the attributesToColumns() method.");
    }

    public function setAttributeFromRequest($requestName, $name, $model, $key = null)
    {
        $value = collect(RequestData::get($requestName))->map(function ($place) {
            return static::placeToDB($place);
        });

        return $value->count() ? $value : null;
    }

    public function setRelationFromRequest($requestName, $name, $model, $key = null)
    {
        $oldPlaces = ModelManager::getValueFromDb($model, $name);

        $newPlaces = RequestData::get($requestName);

        $keepExtIds = [];

        if ($oldPlaces && $oldPlaces->count()) {
            $newExtIds = collect($newPlaces)->pluck('id')->filter()->all(); //id is the key from the gmaps address_components

            $keepExtIds = $oldPlaces->map(function ($place) use ($newExtIds) {
                if (!in_array($place->{static::$external_idKey}, $newExtIds)) {
                    $place->delete(); //No detach, onDelete('cascade') should give the choice.
                } else {
                    return $place->{static::$external_idKey} ?? '';
                }
            })->all();
        }
        if ($newPlaces) {
            return collect($newPlaces)->map(function ($newPlace) use ($keepExtIds) {
                if (!in_array($newPlace['id'] ?? 'not-found', $keepExtIds)) {  //id is the key from the gmaps address_components
                    return static::placeToDB($newPlace);
                }
            })->filter();
        }

        return null;
    }
}
