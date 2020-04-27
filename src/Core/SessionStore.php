<?php

namespace Kompo\Core;

use Illuminate\Support\Facades\Crypt;
use Kompo\Exceptions\AuthorizationUnavailableException;
use Kompo\Exceptions\KompoBootInfoNotFoundException;

class SessionStore
{
    public static function saveKomposer($komposer, $customAttributes = [])
    {
        $bootInfo = array_merge(
            $customAttributes, 
            [
                'kompoClass' => get_class($komposer),
                'store' => $komposer->store(),
                'parameters' => $komposer->parameter(),
                'uri' => optional(request()->route())->uri(),
                'method' => optional(request()->route())->methods()[0]
            ]
        );
        KompoId::setOnElement( $komposer, Crypt::encrypt($bootInfo));

        //old way
        //KompoId::setForKomposer($komposer);
        //session()->put(static::sessionKey($komposer), $bootInfo);
    }

    public static function getKompo()
    {
        if(! ($bootInfo = request()->header('X-Kompo-Id')))
            throw new KompoBootInfoNotFoundException();

        return Crypt::decrypt($bootInfo);


        /* old way 
        if(!array_key_exists($kompoId = request()->header('X-Kompo-Id'), session('bootedElements')))
            throw new AuthorizationUnavailableException($kompoId);

        return session('bootedElements')[$kompoId];*/
    }

    private static function sessionKey($komposer)
    {
        return 'bootedElements.'.KompoId::get($komposer);
    }
}