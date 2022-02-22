<?php

use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Kompo\Core\FileHandler;
use Kompo\Tests\Models\File;
use Kompo\Tests\Models\Obj;

$factory->define(File::class, function (Faker $faker) {
    static $iterator = 1;

    $file = UploadedFile::fake()->create($faker->word.'.'.$faker->fileExtension);

    return [
        'path'       => $file->path(),
        'name'       => FileHandler::getHashname($file),
        'mime_type'  => $faker->mimeType,
        'size'       => $faker->randomNumber(6),
        'user_id'    => 1,
        'obj_id'     => $iterator % 2 == 1 ? 1 : 2,
        'model_id'   => $iterator % 2 == 1 ? 1 : 2,
        'model_type' => Obj::class,
        'order'      => $iterator++,
    ];
});
