<?php

namespace Kompo\Core;

use Illuminate\Support\Facades\Crypt;
use Kompo\Exceptions\KompoBootInfoNotFoundException;

class KompoInfo extends KompoAjax
{
    public static $key = 'X-Kompo-Info';

    public static function saveKomposer($komposer, $customAttributes = [])
    {
        $bootInfo = array_merge(
            $customAttributes,
            KompoId::arrayFromElement($komposer),
            [
                'kompoClass' => get_class($komposer),
                'store'      => $komposer->store(),
                'parameters' => $komposer->parameter(),
                //'uri' => optional(request()->route())->uri(),
                //'method' => optional(request()->route())->methods()[0]
            ]
        );

        static::setOnElement($komposer, Crypt::encrypt($bootInfo));
    }

    public static function getKompo()
    {
        if (!($bootInfo = static::header())) {
            throw new KompoBootInfoNotFoundException();
        }

        return Crypt::decrypt($bootInfo);
    }

    public static function isKomposer($komposer)
    {
        return static::getKompo()[KompoId::$key] == KompoId::getFromElement($komposer);
    }
}
