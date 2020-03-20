<?php

namespace Kompo;

use Kompo\Komponents\Traits\UploadsImages;

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
