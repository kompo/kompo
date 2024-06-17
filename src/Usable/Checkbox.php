<?php

namespace Kompo;

use Kompo\Elements\Field;
use Kompo\Elements\Traits\HasBooleanValue;

class Checkbox extends Field
{
    use HasBooleanValue;

    public $vueComponent = 'Checkbox';
}
