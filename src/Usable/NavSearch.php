<?php

namespace Kompo;

use Kompo\Search;

class NavSearch extends Search
{
    public $bladeComponent = 'NavSearch';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->noLabel()->placeholder($label)->noMargins();
    }

}
