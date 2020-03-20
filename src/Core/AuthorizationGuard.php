<?php

namespace Kompo\Core;

use Kompo\Exceptions\UnauthorizedException;

class AuthorizationGuard
{
    public static function checkBoot($komposer)
    {
        if(!$komposer->bootAuthorization())
            throw new UnauthorizedException( get_class($komposer), 'boot' );
    }

    public static function mainGate($komposer)
    {
    	if(method_exists($komposer, 'authorize') && !$komposer->authorize())
    		throw new UnauthorizedException( get_class($komposer), 'main' );
    }

    /**** PRIVATE ****/
}