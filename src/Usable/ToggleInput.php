<?php

namespace Kompo;

use Kompo\Elements\Field;

class ToggleInput extends Field
{
    public $vueComponent = 'ToggleInput';

    protected function initialize($label)
    {
        parent::initialize($label);

        $this->noInputWrapper();
    }
}
