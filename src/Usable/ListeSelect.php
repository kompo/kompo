<?php

namespace Kompo;

class ListeSelect extends Liste
{
    //Should be called SelectList

    public $vueComponent = 'ListeSelect';

    public function options($options = [])
    {
        $this->options = $options;

        return $this;
    }
}
