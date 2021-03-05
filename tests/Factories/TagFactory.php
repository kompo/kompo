<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Kompo\Tests\Models\Tag;

$factory->define(Tag::class, function (Faker $faker) {
    return [
        'name' => Str::random(40), //to ensure none are the same
    ];
});
