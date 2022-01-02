<?php

namespace Kompo;

use Kompo\Core\ImageHandler;
use Kompo\Elements\Traits\UploadsImages;

class Image extends File
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
        $this->value = $this->value ? $this->transformFromDB($this->value) : null;
    }
}
