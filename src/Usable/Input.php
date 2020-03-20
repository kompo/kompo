<?php

namespace Kompo;

use Kompo\Komponents\Field;
use Kompo\Komponents\Traits\HasInputAttributes;

class Input extends Field
{
	use HasInputAttributes;
	
	public $component = 'Input';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->inputType('text');

        $this->submitsOnEnter();
    }
}
