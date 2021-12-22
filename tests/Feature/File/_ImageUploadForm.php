<?php

namespace Kompo\Tests\Feature\File;

use Kompo\Form;
use Kompo\Image;
use Kompo\MultiImage;
use Kompo\Tests\Models\Obj;

class _ImageUploadForm extends Form
{
    public $model = Obj::class;

    public function render()
    {
        return [

            Image::form()->name('file'),
            Image::form()->name('image')->noResize(),
            Image::form()->name('image_cast')->resize(1500)->withThumbnail(200),
            MultiImage::form()->name('images'),
        ];
    }

    public function rules()
    {
        return [
            'image'    => 'max:1', //max targets filesize in kb,
            'images.*' => 'max:1',
        ];
    }
}
