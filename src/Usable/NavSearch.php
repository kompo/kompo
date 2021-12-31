<?php

namespace Kompo;

class NavSearch extends Search
{
    public $bladeComponent = 'NavSearch';

    protected function initialize($label)
    {
        parent::initialize($label);

        $this->noLabel()->placeholder($label)->noMargins();
    }
}
