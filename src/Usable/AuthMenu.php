<?php

namespace Kompo;

use Kompo\Dropdown;
use Kompo\Forms\AuthLogoutForm;

class AuthMenu extends Dropdown
{
    protected function vlInitialize($label)
    {
        parent::vlInitialize($label ?: auth()->user()->name);
        
    	$this->submenu([
            new AuthLogoutForm()
        ]);
    }
}
