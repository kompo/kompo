<?php

namespace Kompo;

use Kompo\Komponents\Layout;

class Tab extends Layout
{
    public $vueComponent = 'FormTab';

    public function disabled()
    {
        return $this->config([
            'tabDisabled' => true,
        ]);
    }
}
