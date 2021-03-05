<?php

namespace Kompo\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;

class AuthorizationUnavailableException extends AuthorizationException
{
    public function __construct($kompoId)
    {
        parent::__construct("The kompo Id [{$kompoId}] was not found in the session.");
    }
}
