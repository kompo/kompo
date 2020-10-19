<?php

namespace Kompo;

use Kompo\Interactions\Traits\ForwardsInteraction;
use Kompo\Interactions\Traits\HasInteractions;
use Kompo\Komponents\Layout;
use Kompo\Komponents\Traits\HasHref;

class Rows extends Layout
{
    use HasInteractions, ForwardsInteraction;
    use HasHref;
    
    public $vueComponent = 'Rows';
    public $bladeComponent = 'Rows';
}
