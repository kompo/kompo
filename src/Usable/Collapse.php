<?php

namespace Kompo;

use Kompo\Elements\TriggerWithSubmenu;

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
