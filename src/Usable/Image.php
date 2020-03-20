<?php

namespace Kompo;

use Kompo\Komponents\Traits\UploadsImages;

class Image extends File
{    
    use UploadsImages;
    
    public $component = 'Image';
    
    public function prepareValueForFront($name, $value, $model)
    {
        $this->value = $value ? $this->transformFromDB($value) : null;
    }

}
