<?php

namespace Kompo;

use Illuminate\Support\Facades\Hash;

class Password extends Input
{
    protected function initialize($label)
    {
        parent::initialize($label);

        $this->inputType('password');
    }

    public function setOutput($value, $key)
    {
        $this->value = ''; //empty string on output (display phase)

        return $this;
    }

    public function setInput($value, $key)
    {
        return Hash::make($value);
    }
}
