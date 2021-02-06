<?php

namespace Kompo;

use Kompo\Select;

class Search extends Select
{
    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->config([
	        'searchInput' => true
	    ]);

	    $this->icon('icon-search');
    }
}
