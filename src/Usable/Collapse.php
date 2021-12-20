<?php

namespace Kompo;

use Kompo\Komponents\TriggerWithSubmenu;

class Collapse extends TriggerWithSubmenu
{
    public $vueComponent = 'Collapse';
    public $bladeComponent = 'Collapse';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->expandIfActive();
    }
}
