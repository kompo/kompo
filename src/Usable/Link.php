<?php

namespace Kompo;

use Kompo\Komponents\Traits\HasHref;
use Kompo\Komponents\Traits\TriggerStyles;
use Kompo\Komponents\Trigger;

class Link extends Trigger
{
	use HasHref;
	use TriggerStyles;

    public $component = 'FormLink';
    public $menuComponent = 'Link';
    
}
