<?php

namespace Kompo;

use Kompo\Interactions\Traits\ForwardsInteraction;
use Kompo\Interactions\Traits\HasInteractions;
use Kompo\Elements\Traits\AjaxConfigurations;
use Kompo\Elements\Traits\HasHref;

class TableRow extends Card
{
    use HasInteractions;
    use ForwardsInteraction;
    use AjaxConfigurations;
    use HasHref;

    public $vueComponent = 'TableRow';
}
