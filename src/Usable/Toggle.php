<?php

namespace Kompo;

use Kompo\Elements\Field;
use Kompo\Elements\Traits\HasBooleanValue;

class Toggle extends Field
{
    use HasBooleanValue;

    public $vueComponent = 'Toggle';

    protected function initialize($label)
    {
        parent::initialize($label);

        $this->noInputWrapper();
    }
}
