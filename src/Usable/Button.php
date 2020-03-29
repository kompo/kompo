<?php

namespace Kompo;

use Kompo\Komponents\Traits\TriggerStyles;
use Kompo\Komponents\Trigger;

class Button extends Trigger
{ 	
	use TriggerStyles;
	
    public $component = 'FormButton';
    public $menuComponent = 'Button';
}
