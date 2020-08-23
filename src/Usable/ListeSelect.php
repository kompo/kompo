<?php

namespace Kompo;

use Kompo\Liste;

class ListeSelect extends Liste
{	
    public $vueComponent = 'ListeSelect';

    public function options($options = [])
    {
        $this->options = $options;

        return $this;
    }
}
