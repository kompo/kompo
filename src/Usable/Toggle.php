<?php

namespace Kompo;

use Kompo\Komponents\Field;

class Toggle extends Field
{
    public $component = 'Toggle';

    protected function setValue($value)
    {
        $this->value = $value ? 1 : 0;
    }
}
