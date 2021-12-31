<?php

namespace Kompo;

use Illuminate\Support\Arr;
use Kompo\Core\RequestData;
use Kompo\Core\Util;
use Kompo\Database\ModelManager;
use Kompo\Elements\Field;
use Kompo\Elements\Managers\FormField;

class Place extends Field
{
    public $vueComponent = 'Place';

    /**
     * Adds a cast to array to the attribute if no cast is present.
     *
     * @var bool
     */
    protected $castsToArray = true;

    /**
     * Boolean flag to indicate whether to store the place attributes in separate columns.
     *
     * @var array
     */
    protected $attributesToColumns = false;

    protected static $allKeys;
    protected static $addressKey;
    protected static $street_numberKey;
    protected static $streetKey;
    protected static $cityKey;
    protected static $stateKey;
    protected static $countryKey;
    protected static $postal_codeKey;
    protected static $latKey;
    protected static $lngKey;
    protected static $external_idKey;

    /**
     * Assign the config columns.
     *
     * @param string $label The label
     */
    protected function initialize($label)
    {
        parent::initialize($label);

        collect(static::$allKeys = config('kompo.places_attributes'))->each(function ($column, $key) {
            $internalKeyName = $key.'Key';
            static::$$internalKeyName = $column;
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
     * Sets the starting center point of the map.
     *
     * @param <type> $lat The lat
     * @param <type> $lng The lng
     *
     * @return <type> ( description_of_the_return_value )
     */
    public function defaultCenter($lat, $lng, $zoom = 10)
    {
        return $this->config([
            'defaultCenter' => [
                'lat' => $lat,
                'lng' => $lng,
            ],
            'defaultZoom' => $zoom,
        ]);
    }

    //TODO document
    public function noDefaultUi()
    {
        return $this->config(['noDefaultUi' => true]);
    }

    //TODO document
    //You have to add this in your js
    //window.GoogleMapsStyle = require('kompo-googlemaps/styles/silver').default
    public function customMapStyle()
    {
        return $this->config(['customMapStyle' => true]);
    }

    //TODO document
    public function addMarkers($markers)
    {
        return $this->config(['addMarkers' => Arr::wrap($markers)]);
    }

    //TODO document
    public function componentRestrictions($componentRestrictions)
    {
        return $this->config(['componentRestrictions' => $componentRestrictions]);
    }

    public function getValueFromModel($model, $name)
    {
        if (!$this->attributesToColumns) {
            return ModelManager::getValueFromDb($model, $name);
        }

        $value = collect(static::$allKeys)->map(fn ($key) => $model->{$key});

        return $value->filter()->count() ? $value->all() : null;
    }

    public function setAttributeFromRequest($requestName, $name, $model, $key = null)
    {
        $oldPlace = $this->attributesToColumns ? $model : ModelManager::getValueFromDb($model, $name);

        if ($newPlace = RequestData::get($requestName)) {
            $newPlace = static::placeToDB($newPlace[0]);

            if (!$this->attributesToColumns) {
                return $newPlace;
            }

            collect($newPlace)->each(function ($attribute, $column) use ($name) {
                if ($column !== $name) {
                    FormField::setExtraAttributes($this, [$column => $attribute]);
                }
            });

            return $newPlace[$name];
        } else {
            if (!$this->attributesToColumns) {
                return null;
            }

            if ($oldPlace->exists) {
                collect(static::$allKeys)->each(function ($key) {
                    FormField::setExtraAttributes($this, [$key => null]);
                });
            }

            return null;
        }
    }

    public function setRelationFromRequest($requestName, $name, $model, $key = null)
    {
        $oldPlace = ModelManager::getValueFromDb($model, $name);

        if ($place = RequestData::get($requestName)) {
            $place = static::placeToDB($place[0]);

            if ($oldPlace) {
                if ($oldPlace->{static::$external_idKey} == $place[static::$external_idKey]) {
                    return null;
                } else {
                    $oldPlace->delete();

                    return $place;
                }
            } else {
                return $place;
            }
        } else {
            $oldPlace->delete();

            return null;
        }
    }

    public static function placeToDB($place)
    {
        $place = Util::decode($place);

        if ($address_components = ($place['address_components'] ?? null)) {
            $result = [];
            foreach ($address_components as $value) {
                if (in_array('street_number', $value['types'])) {
                    $result[static::$street_numberKey] = $value['long_name'];
                }
                if (in_array('route', $value['types'])) {
                    $result[static::$streetKey] = $value['long_name'];
                }
                if (in_array('locality', $value['types'])) {
                    $result[static::$cityKey] = $value['long_name'];
                }
                if (in_array('administrative_area_level_1', $value['types'])) {
                    $result[static::$stateKey] = $value['long_name'];
                }
                if (in_array('country', $value['types'])) {
                    $result[static::$countryKey] = $value['long_name'];
                }
                if (in_array('postal_code', $value['types'])) {
                    $result[static::$postal_codeKey] = $value['long_name'];
                }
            }

            return array_merge($result, [
                //static::$addressKey => $place['formatted_address'],
                static::$addressKey     => $place['name'],
                static::$latKey         => $place['geometry']['location']['lat'],
                static::$lngKey         => $place['geometry']['location']['lng'],
                static::$external_idKey => $place['place_id'],
            ]);
        } else {
            return collect(static::$allKeys)->mapWithKeys(fn ($dbKey, $internalKey) => [
                $dbKey => $place[$internalKey],
            ])->all();
        }
    }
}
