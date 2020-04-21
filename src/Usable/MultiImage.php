<?php

namespace Kompo;

use Kompo\Komponents\Traits\UploadsImages;
use Kompo\MultiFile;

class MultiImage extends MultiFile
{
    use UploadsImages;
    
    public $vueComponent = 'Image';

    public function prepareForFront($komposer)
    {
        $this->value = collect($this->value)->map(function($image){

        	return $this->transformFromDB($image);

        });
    }

}
