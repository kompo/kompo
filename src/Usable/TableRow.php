<?php

namespace Kompo;

use Kompo\Card;
use Kompo\Interactions\Traits\ForwardsInteraction;
use Kompo\Interactions\Traits\HasInteractions;
use Kompo\Komponents\Traits\AjaxConfigurations;
use Kompo\Komponents\Traits\HasHref;

class TableRow extends Card
{
    use HasInteractions, ForwardsInteraction, AjaxConfigurations;
    use HasHref;
    
    public $vueComponent = 'TableRow';

}