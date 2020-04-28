<?php

namespace Kompo\Core;

class KompoData
{
    public static function getFromElement($el)
    {
        return $el->data( static::$kompoDataKey );
    }

    public static function setOnElement($el, $kompoData)
    {
        return $el->data([ static::$kompoDataKey => $kompoData ]);
    }

    public static function getAsArray($el)
    {
        return [
            static::$kompoDataKey => $el->data( static::$kompoDataKey )
        ];
    }
}