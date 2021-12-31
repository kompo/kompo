<?php

namespace Kompo;

class Search extends Select
{
    protected function initialize($label)
    {
        parent::initialize($label);

        $this->config([
            'searchInput' => true,
        ]);

        $this->icon('icon-search');
    }
}
