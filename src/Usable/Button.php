<?php

namespace Kompo;

use Kompo\Komponents\Traits\TriggerStyles;
use Kompo\Komponents\Trigger;

class Button extends Trigger
{ 	
	use TriggerStyles;
	
    public $vueComponent = 'Button';
    public $bladeComponent = 'Button';

    public $vuetifyComponent = 'btn';
}
