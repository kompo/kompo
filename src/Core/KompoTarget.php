<?php

namespace Kompo\Core;

use Illuminate\Support\Facades\Crypt;

class KompoTarget
{
    protected static $kompoTargetKey = 'X-Kompo-Target';

    public static function getEncryptedArray($methodOrClass)
    {
        return [
            static::$kompoTargetKey => static::getEncrypted($methodOrClass)
        ];
    }

    public static function getEncrypted($methodOrClass)
    {
        return Crypt::encryptString($methodOrClass);
    }

    public static function getDecrypted($target = null)
    {
        if($target)
            return Crypt::decryptString($target);

        if($headerTarget = request()->header(static::$kompoTargetKey))
            return Crypt::decryptString($headerTarget);

        //else return null
    }
}