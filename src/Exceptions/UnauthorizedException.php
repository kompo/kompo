<?php

namespace Kompo\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;

class UnauthorizedException extends AuthorizationException
{
    public function __construct($komponentClass, $stage = null)
	{
        parent::__construct("The ".($stage ?: 'called')." functionality is unauthorized on [{$komponentClass}].");
    }
}
