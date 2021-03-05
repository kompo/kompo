<?php

namespace Kompo;

use Kompo\Forms\AuthLogoutForm;

class AuthMenu extends Dropdown
{
    protected function vlInitialize($label)
    {
        parent::vlInitialize($label ?: auth()->user()->name);

        $this->submenu([
            new AuthLogoutForm(),
        ])
        ->alignRight(); //Auth should align right by default. TODO: add alignLeft() to override in rare cases
    }
}
