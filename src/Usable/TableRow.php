<?php

namespace Kompo;

use Kompo\Card;
use Kompo\Interactions\Traits\ForwardsInteraction;
use Kompo\Interactions\Traits\HasInteractions;
use Kompo\Komponents\Traits\AjaxConfigurations;

class TableRow extends Card
{
    use HasInteractions, ForwardsInteraction, AjaxConfigurations;
    
    public $vueComponent = 'TableRow';

}