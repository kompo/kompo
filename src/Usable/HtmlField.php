<?php

namespace Kompo;

use Kompo\Komponents\Field;
use Kompo\Komponents\Traits\HasSelectedClass;

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
