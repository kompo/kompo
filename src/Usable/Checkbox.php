<?php

namespace Kompo;

use Kompo\Komponents\Field;

class Checkbox extends Field
{
    public $component = 'Checkbox';

    protected function setValue($value)
    {
        $this->value = $value ? 1 : 0;
    }

}
