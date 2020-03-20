<?php

namespace Kompo;

use Kompo\Komposers\Menu\MenuBooter;
use Kompo\Komposers\Komposer;

abstract class Menu extends Komposer
{
	/**
     * Constructs a Menu
     * 
     * @param null|array $store (optional) Additional data passed to the komponent.
     *
     * @return self
     */
	public function __construct($store = [], $dontBoot = false)
	{
        if(!$dontBoot)
            MenuBooter::bootForDisplay($this, $store);
	}

}
