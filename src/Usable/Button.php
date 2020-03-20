<?php

namespace Kompo;

use Kompo\Komponents\Trigger;
use Vuravel\Menu\MenuItems\Traits\Clickable;

class Button extends Trigger
{ 	
	use Clickable; //When in Menus

    public $component = 'FormButton';
    public $menuComponent = 'Button';

}
