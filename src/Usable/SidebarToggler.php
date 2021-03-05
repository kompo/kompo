<?php

namespace Kompo;

use Kompo\Komponents\Trigger;

class SidebarToggler extends Trigger
{
    public $bladeComponent = 'SidebarToggler';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label ?: '&#9776;');

        $this->toggleSidebar();
    }

    //TODO : DOCUMENT
    public function toggleSidebar($side = 'left')
    {
        return $this->config([
            'toggleSidebar' => $side,
        ]);
    }

    //TODO : DOCUMENT
    public function toggleOnHover()
    {
        return $this->config([
            'toggleOnHover' => true,
        ]);
    }

    //TODO : DOCUMENT
    public function openClass($openClass)
    {
        return $this->config([
            'openClass' => $openClass,
        ]);
    }
}
