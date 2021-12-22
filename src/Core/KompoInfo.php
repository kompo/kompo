<?php

namespace Kompo\Core;

use Illuminate\Support\Facades\Crypt;
use Kompo\Exceptions\KompoBootInfoNotFoundException;

class KompoInfo extends KompoAjax
{
    public static $key = 'X-Kompo-Info';

    public static function saveKomponent($komponent, $customAttributes = [])
    {
        $bootInfo = array_merge(
            $customAttributes,
            KompoId::arrayFromElement($komponent),
            [
                'kompoClass' => get_class($komponent),
                'store'      => $komponent->store(),
                'parameters' => $komponent->parameter(),
                //'uri' => optional(request()->route())->uri(),
                //'method' => optional(request()->route())->methods()[0]
            ]
        );

        static::setForBaseElement($komponent, Crypt::encrypt($bootInfo));
    }

    public static function getKompo()
    {
        if (!($bootInfo = static::header())) {
            throw new KompoBootInfoNotFoundException();
        }

        return Crypt::decrypt($bootInfo);
    }

    public static function isKomponent($komponent)
    {
        return static::getKompo()[KompoId::$key] == KompoId::getFromElement($komponent);
    }
}
