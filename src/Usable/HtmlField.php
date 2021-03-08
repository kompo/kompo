<?php

namespace Kompo;

use Kompo\Komponents\Field;

class HtmlField extends Field
{
    public $vueComponent = 'HtmlField';

    //TODO DOCUMENT
    public function selectedValue($value)
    {
        return $this->config([
            'selectedValue' => $value,
        ]);
    }

    //TODO DOCUMENT
    public function selectedClass($selectedClass)
    {
        return $this->config([
            'selectedClass' => $selectedClass,
        ]);
    }
}
