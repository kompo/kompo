<?php

namespace Kompo;

use Kompo\Interactions\Traits\ForwardsInteraction;
use Kompo\Interactions\Traits\HasInteractions;
use Kompo\Elements\Layout;
use Kompo\Elements\Traits\HasHref;

class Div extends Layout
{
    use HasInteractions;
    use ForwardsInteraction;
    use HasHref;

    public $vueComponent = 'Div';
    public $bladeComponent = 'Div';
}
