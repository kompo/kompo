<?php

namespace Kompo;

use Kompo\Komponents\Layout;
use Kompo\Komponents\Traits\HasHref;

class Rows extends Layout
{
    use HasHref;
    
    public $vueComponent = 'Rows';
    public $bladeComponent = 'Rows';
}
