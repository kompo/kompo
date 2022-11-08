<?php

namespace Kompo;

use Kompo\Elements\Traits\TriggerStyles;
use Kompo\Elements\Trigger;

class Button extends Trigger
{
    use TriggerStyles;

    public $vueComponent = 'Button';
    public $bladeComponent = 'Button';
}
