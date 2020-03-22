<?php

namespace Kompo\Core;

use Kompo\Exceptions\AuthorizationUnavailableException;

class SessionStore
{
    public static function saveKomposer($komposer, $customAttributes = [])
    {
        KompoIdCreator::setForKomposer($komposer);

        session()->put(static::sessionKey($komposer), array_merge(
            $customAttributes, 
            [
                'kompoClass' => get_class($komposer),
                'store' => $komposer->store(),
                'parameters' => $komposer->parameter(),
                'uri' => optional(request()->route())->uri(),
                'method' => optional(request()->route())->methods()[0]
            ])
        );
    }

    public static function getKompo()
    {
        if(!array_key_exists($kompoId = request()->header('X-Kompo-Id'), session('bootedElements')))
            throw new AuthorizationUnavailableException($kompoId);

        return session('bootedElements')[$kompoId];
    }

    private static function sessionKey($komposer)
    {
        return 'bootedElements.'.$komposer->data('kompoId');
    }
}