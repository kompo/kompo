<?php

namespace Kompo\Core;

use Illuminate\Support\Facades\Crypt;

class KompoTarget extends KompoAjax
{
    public static $key = 'X-Kompo-Target';

    public static function getEncryptedArray($methodOrClass)
    {
        return [
            static::$key => static::getEncrypted($methodOrClass),
        ];
    }

    public static function getEncrypted($methodOrClass)
    {
        return Crypt::encryptString($methodOrClass);
    }

    public static function getDecrypted($target = null)
    {
        $targetValue = $target ?: static::header();

        if (!$targetValue) {
            return null;
        }

        return Crypt::decryptString($targetValue);
    }
}
