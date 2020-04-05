<?php

namespace Kompo;

use Illuminate\Support\Facades\Hash;
use Kompo\Input;

class Password extends Input
{
    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->inputType('password');
    }

    protected function setAttributeFromRequest($requestName, $name, $model)
    {
        return Hash::make(request()->__get($requestName));
    }

}
