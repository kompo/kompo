<?php

namespace Kompo\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;

class KompoBootInfoNotFoundException extends AuthorizationException
{
    public function __construct()
    {
        parent::__construct(' The request does not have the information required to boot a Komponent.');

        logKompoRequest('Debugging boot info not found in');
    }
}
