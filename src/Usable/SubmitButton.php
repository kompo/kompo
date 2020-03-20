<?php

namespace Kompo;

class SubmitButton extends Button
{ 	
    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->submitsForm();
    }
}
