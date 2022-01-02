<?php

namespace Kompo;

use Kompo\Core\ImageHandler;
use Kompo\Elements\Traits\UploadsImages;

class MultiImage extends MultiFile
{
    use UploadsImages;

    public $vueComponent = 'Image';

    protected function setupFileHandler()
    {
        $this->disk = config('kompo.default_storage_disk.image');

        $this->fileHandler = new ImageHandler();
    }

    public function prepareForFront($komponent)
    {
        $this->value = collect($this->value)->map(function ($image) {
            return $this->transformFromDB($image);
        });
    }
}
