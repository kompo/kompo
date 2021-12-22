<?php

namespace Kompo;

use Kompo\Elements\Field;
use Kompo\Elements\Traits\HasSelectedClass;

class HtmlField extends Field
{
    use HasSelectedClass;

    public $vueComponent = 'HtmlField';

    //TODO DOCUMENT
    public function selectedValue($value)
    {
        return $this->config([
            'selectedValue' => $value,
        ]);
    }
}
