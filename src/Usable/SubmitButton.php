<?php

namespace Kompo;

use Kompo\Button;

class SubmitButton extends Button
{ 	
    protected function vlInitialize($label)
    {
        parent::vlInitialize($label ?: 'Save');

        $this->submit();
    }
}
