<?php

namespace Kompo;

use Kompo\Komponents\Trigger;
use Vuravel\Menu\MenuItems\Traits\Clickable;

class Link extends Trigger
{
	use Clickable;

    public $component = 'FormLink';
    public $menuComponent = 'Link';
    
}
