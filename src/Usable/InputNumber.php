<?php

namespace Kompo;

class InputNumber extends Input
{
	protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->inputType('number');
    }
}
