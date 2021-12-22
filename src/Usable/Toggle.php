<?php

namespace Kompo;

use Kompo\Elements\Field;
use Kompo\Elements\Traits\HasBooleanValue;

class Toggle extends Field
{
    use HasBooleanValue;

    public $vueComponent = 'Toggle';
}
