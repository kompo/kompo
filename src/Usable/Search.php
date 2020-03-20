<?php

namespace Kompo;

class Search extends Select
{
    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->data([
	        'searchInput' => true
	    ]);
    }
}
