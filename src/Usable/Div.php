<?php

namespace Kompo;

use Kompo\Interactions\Traits\ForwardsInteraction;
use Kompo\Interactions\Traits\HasInteractions;
use Kompo\Komponents\Layout;
use Kompo\Komponents\Traits\HasHref;

class Div extends Layout
{
    use HasInteractions, ForwardsInteraction;
    use HasHref;
    
    public $vueComponent = 'Rows';
    public $bladeComponent = 'Rows';
}
