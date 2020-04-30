<?php

namespace Kompo;

use Kompo\Komponents\Field;
use Kompo\Komponents\Traits\HasInputAttributes;

class Input extends Field
{
	use HasInputAttributes;
	
	public $vueComponent = 'Input';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->inputType('text');

        $this->debounce();
    }

    public function mounted($komposer)
    {
        $this->onEnter->submit();
    }
}
