<?php

namespace Kompo\Tests\Feature\Place;

use Kompo\Tests\EnvironmentBoot;
use Kompo\Tests\Models\Place;
use Faker\Factory;

class PlaceEnvironmentBoot extends EnvironmentBoot
{
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

    protected function getEnvironmentSetUp($app)
    {
    	parent::getEnvironmentSetUp($app);
    	collect(config('kompo.places_attributes'))->each(function($column, $key){
            $this->{$key.'Key'} = $column;
        });
    }

	protected function createPlace($address = null)
	{
		$faker = Factory::create();
		return ["address_components" => [
		    [
		      "long_name" => $faker->buildingNumber,
		      "types" => ["street_number"]
		    ],
		    [
		      "long_name" => $faker->streetName,
		      "types" => ["route"]
		    ],
		    [
		      "long_name" => $faker->city,
		      "types" => ["locality","political"]
		    ],
		    [
		      "long_name" => "Orange County", //not used in Kompo
		      "types" => ["administrative_area_level_2","political"]
		    ],
		    [
		      "long_name" => $faker->state,
		      "types" => ["administrative_area_level_1","political"]
		    ],
		    [
		      "long_name" => $faker->country,
		      "types" => ["country","political"]
		    ],
		    [
		      "long_name" => $faker->postcode,
		      "types" => ["postal_code"]
		    ]
		  ],
		  "name" => $address ?: $faker->streetAddress,
		  "geometry" =>[
		    "location" => [
		      "lat" => $faker->latitude,
		      "lng" => $faker->longitude
		    ]
		  ],
		  "place_id" => $faker->uuid
		];
	}

	protected function place_to_array($place)
	{
		return [
            $this->street_numberKey => $place['address_components'][0]["long_name"],
            $this->streetKey => $place['address_components'][1]["long_name"],
            $this->cityKey => $place['address_components'][2]["long_name"],
            $this->stateKey => $place['address_components'][4]["long_name"],//skip 1 for administrative_area_level_2
            $this->countryKey => $place['address_components'][5]["long_name"],
            $this->postal_codeKey => $place['address_components'][6]["long_name"],
            $this->addressKey => $place['name'],
            $this->latKey => $place['geometry']['location']['lat'],
            $this->lngKey => $place['geometry']['location']['lng'],
            $this->external_idKey => $place['place_id']
		];
	}


	protected function place_to_json($place)
	{
		return json_encode($this->place_to_array($place));
	}

	protected function places_to_json($places)
	{
		return json_encode(collect($places)->map(function($place){
			return $this->place_to_array($place);
		}));
	}
}