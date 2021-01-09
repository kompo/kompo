<?php

namespace Kompo;

use Kompo\Komponents\Traits\HasHref;
use Kompo\Komponents\Traits\HasSubmenu;
use Kompo\Komponents\Trigger;

class Dropdown extends Trigger
{
	use HasHref, HasSubmenu;

	public $vueComponent = 'Dropdown';
    public $bladeComponent = 'Dropdown';

    /**
     * The dropdown menu will align to the right instead of the default left alignment.
     *
     * @return     self 
     */
    public function alignRight()
    {
        return $this->config([ 'dropdownPosition' => 'vlDropdownMenuRight' ]);
    }

    public function alignUpRight()
    {
    	return $this->config([
    		'dropdownPosition' => 'vlDropdownMenuUpRight'
    	]);
    }
}
