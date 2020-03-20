<?php

namespace Kompo;

use Illuminate\Support\Facades\Hash;

class Password extends Input
{
    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->inputType('password');
    }

    protected function setAttributeFromRequest($name, $record)
    {
        return Hash::make(request()->input($name));
    }

}
