<?php 

use Faker\Generator as Faker;
use Kompo\Tests\Models\Obj;
use Kompo\Tests\Models\User;

$factory->define(Obj::class, function (Faker $faker) {

	static $iterator = 1;

	$controlText = $iterator % 2 == 1 ? 'a$w3IRdTe#t1' : 'x^^y $mtH3lS*';
	$controlNumber = $iterator % 2 == 1 ? rand(11, 499) : rand(501, 999);
	$controlDate = $iterator % 2 == 1 ? $faker->dateTimeBetween('-1 year', '-1 day') : $faker->dateTimeBetween('+1 day', '+1 year');

    return [
        'user_id' => $iterator % 2 == 1 ? 1 : 2,
        'model_id' => $iterator % 2 == 1 ? 1 : 2,
        'model_type' => User::class,
        'post_id' => 1,
        'title' => $faker->sentence.$controlText,
        'equal' => $controlText,
        'greater' => $controlText, //text here
        'lower' => $controlNumber, //number here
        'like' => $faker->word.' '.$controlText.' '.$faker->word,
        'startswith' => $controlText.$faker->sentence,
        'endswith' => $faker->sentence.$controlText,
        'between' => $controlDate,
        'in' => $iterator++,
    ];
});