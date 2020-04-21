<?php

namespace Kompo;

use Kompo\Komponents\Field;
use Kompo\Komponents\Traits\HasBooleanValue;

class Toggle extends Field
{
	use HasBooleanValue;

    public $vueComponent = 'Toggle';
}
