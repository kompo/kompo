<?php

namespace Kompo;

use Kompo\Komponents\Block;

class Img extends Block
{
    public $vueComponent = 'Img';
    public $bladeComponent = 'Img';

    protected function vlInitialize($label)
    {
    	$label = filter_var($label, FILTER_VALIDATE_URL) ? $label : asset($label);
    	
        parent::vlInitialize($label);
    }

    public function bgCover()
    {
    	return $this->data([
    		'backgroundCover' => true
    	]);
    }
    
}
