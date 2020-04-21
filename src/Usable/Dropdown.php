<?php

namespace Kompo;

use Kompo\Komponents\Traits\HasHref;
use Kompo\Komponents\Traits\HasSubmenu;
use Kompo\Komponents\Trigger;

class Dropdown extends Trigger
{
	use HasHref, HasSubmenu;

    public $bladeComponent = 'Dropdown';
}
