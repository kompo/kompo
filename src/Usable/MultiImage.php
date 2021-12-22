<?php

namespace Kompo;

use Kompo\Core\ImageHandler;
use Kompo\Elements\Traits\UploadsImages;

class MultiImage extends MultiFile
{
    use UploadsImages;

    public $vueComponent = 'Image';

    /**
     * The file's handler class.
     */
    protected $fileHandler = ImageHandler::class;

    public function prepareForFront($komponent)
    {
        $this->value = collect($this->value)->map(function ($image) {
            return $this->transformFromDB($image);
        });
    }
}
