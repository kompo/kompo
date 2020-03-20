<?php 

use Faker\Generator as Faker;
use Kompo\Tests\Models\Place;

$factory->define(Place::class, function (Faker $faker) {
    return [
        'address' => $faker->streetAddress,
        'street_number' => $faker->buildingNumber,
        'street' => $faker->streetName,
        'city' => $faker->city,
        'state' => $faker->state,
        'country' => $faker->country,
        'postal_code' => $faker->postcode,
        'lat' => $faker->latitude,
        'lng' => $faker->longitude,
        'external_id' => $faker->uuid, 
        'user_id' => 1
    ];
});