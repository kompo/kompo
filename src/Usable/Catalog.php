<?php

namespace Kompo;

use Kompo\Komposers\Catalog\CatalogBooter;
use Kompo\Komposers\Komposer;

abstract class Catalog extends Komposer
{
	/**
     * Constructs a Catalog
     * 
     * @param null|array $store (optional) Additional data passed to the komponent.
     *
     * @return self
     */
	public function __construct($store = [], $dontBoot = false)
	{
        if(!$dontBoot)
            CatalogBooter::bootForDisplay($this, $store);
	}

}
