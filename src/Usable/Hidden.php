<?php

namespace Kompo;

use Kompo\Komponents\Field;

class Hidden extends Field
{	
    public $component = 'Hidden';

    public $showHidden = false;

    protected function vlInitialize($label)
    {
    	parent::vlInitialize($label);

        $this->name = $label; //not snakecase...
    }

    // Show for Form Demo Builder Purposes
    public function show()
    {
    	$this->showHidden = true;
    	return $this;
    }
}
