<?php

namespace Kompo;

use Kompo\Search;

class NavSearch extends Search
{
    public $menuComponent = 'NavSearch';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->noLabel()->placeholder($label);
    }

}
