<?php

namespace Kompo;

use Kompo\Komponents\Traits\UploadsImages;
use Kompo\MultiFile;

class MultiImage extends MultiFile
{
    use UploadsImages;
    
    public $component = 'Image';

    public function prepareValueForFront($name, $value, $model)
    {
        $this->value = collect($value)->map(function($image){
				        		return $this->transformFromDB($image);
				        	});
    }

}
