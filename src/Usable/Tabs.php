<?php

namespace Kompo;

use Kompo\Komponents\Layout;

class Tabs extends Layout
{
    public $vueComponent = 'FormTabs';

    public function activeTab($index = null)
    {
        return $this->config([
            'activeTab' => $index ?: 0,
        ]);
    }
}
