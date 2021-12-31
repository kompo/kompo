<?php

namespace Kompo;

use Kompo\Elements\Field;
use Kompo\Elements\Traits\HasRows;

class Textarea extends Field
{
    use HasRows;

    public $vueComponent = 'Textarea';

    protected function initialize($label)
    {
        parent::initialize($label);

        $this->rows(3);
    }
}
