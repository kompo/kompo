<?php

namespace Kompo;

use Kompo\Elements\Field;
use Kompo\Elements\Traits\HasInputAttributes;

class Range extends Field
{
    use HasInputAttributes;
    
    public $vueComponent = 'Range';

    protected function initialize($label)
    {
        parent::initialize($label);

        $this->noInputWrapper();
    }
}
