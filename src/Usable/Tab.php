<?php

namespace Kompo;

use Kompo\Elements\Layout;

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
