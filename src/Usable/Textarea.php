<?php

namespace Kompo;

use Kompo\Elements\Field;
use Kompo\Elements\Traits\HasRows;

class Textarea extends Field
{
    use HasRows;

    public $vueComponent = 'Textarea';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->rows(3);
    }
}
