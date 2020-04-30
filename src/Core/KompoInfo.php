<?php

namespace Kompo\Core;

use Illuminate\Support\Facades\Crypt;
use Kompo\Exceptions\AuthorizationUnavailableException;
use Kompo\Exceptions\KompoBootInfoNotFoundException;

use App\Intro\EloquentForm;

class KompoInfo extends KompoData
{
    protected static $kompoDataKey = 'kompoInfo';

    public static function saveKomposer($komposer, $customAttributes = [])
    {
        KompoId::setForKomposer($komposer);

        $bootInfo = array_merge(
            $customAttributes, 
            [
                'kompoId' => KompoId::getFromElement($komposer),
                'kompoClass' => get_class($komposer),
                'store' => $komposer->store(),
                'parameters' => $komposer->parameter(),
                'uri' => optional(request()->route())->uri(),
                'method' => optional(request()->route())->methods()[0]
            ]
        );

        static::setOnElement( $komposer, Crypt::encrypt($bootInfo));

        //old way
        //session()->put(static::sessionKey($komposer), $bootInfo);
    }

    public static function getKompo()
    {
        if(! ($bootInfo = request()->header('X-Kompo-Info')))
            throw new KompoBootInfoNotFoundException();

        return Crypt::decrypt($bootInfo);


        /* old way 
        if(!array_key_exists($kompoId = request()->header('X-Kompo-Id'), session('bootedElements')))
            throw new AuthorizationUnavailableException($kompoId);

        return session('bootedElements')[$kompoId];*/
    }

    /*
    private static function sessionKey($komposer)
    {
        return 'bootedElements.'.KompoId::getFromElement($komposer);
    }*/
}