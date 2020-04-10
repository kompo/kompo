<?php

namespace Kompo;

use Kompo\Komponents\Traits\UploadsImages;

class Image extends File
{    
    use UploadsImages;
    
    public $component = 'Image';
    
    public function prepareForFront($komposer)
    {
        $this->value = $this->value ? $this->transformFromDB($this->value) : null;
    }

}
