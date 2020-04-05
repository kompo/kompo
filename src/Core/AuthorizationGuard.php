<?php

namespace Kompo\Core;

use Kompo\Exceptions\UnauthorizedException;
use Kompo\Komposers\KomposerManager;

class AuthorizationGuard
{
    public static function checkBoot($komposer)
    {
        if(!$komposer->bootAuthorization())
            throw new UnauthorizedException( get_class($komposer), 'boot' );

        if(in_array(request()->header('X-Kompo-Action'),['eloquent-submit', 'handle-submit']))
            static::checkPreventSubmit($komposer);

        KomposerManager::created($komposer);
    }

    public static function mainGate($komposer)
    {
    	if(method_exists($komposer, 'authorize') && !$komposer->authorize())
    		throw new UnauthorizedException( get_class($komposer), 'main' );
    }

    public static function checkIfAllowedToSeachOptions($komposer)
    {
        //todo
    }

    public static function checkIfAllowedToPost($komposer)
    {
        //todo
    }

    /**** PRIVATE / PROTECTED ****/

    protected static function checkPreventSubmit($komposer)
    {
        if($komposer->_kompo('options')['preventSubmit'])
            throw new UnauthorizedException( get_class($komposer), 'submit' );
    }
}