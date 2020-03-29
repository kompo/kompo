<?php 

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Kompo\Tests\Models\File;
use Illuminate\Http\UploadedFile;

$factory->define(File::class, function (Faker $faker) {
    
	static $order = 1;

	$file = UploadedFile::fake()->create($faker->word.'.'.$faker->fileExtension);

    return [
        'path' => $file->path(),
        'name' => $file->hashName(),
        'mime_type' => $faker->mimeType,
        'size' => $faker->randomNumber(6),
        'order' => $order++,
        'user_id' => 1
    ];
});