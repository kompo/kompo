<?php

namespace Kompo;

use Kompo\Komponents\Field;
use Kompo\Komponents\Traits\HasBooleanValue;

class Checkbox extends Field
{
    use HasBooleanValue;

    public $vueComponent = 'Checkbox';
}
