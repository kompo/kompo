<?php

namespace Kompo;

class InputNumber extends Input
{
    protected function initialize($label)
    {
        parent::initialize($label);

        $this->inputType('number');
    }
}
