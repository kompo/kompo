<?php

namespace Kompo\Core;

abstract class KompoAjax
{
    /***** From Element *************/

    public static function getFromElement($el)
    {
        return $el->config( static::$key );
    }

    public static function setOnElement($el, $kompoData)
    {
        return $el->config([ static::$key => $kompoData ]);
    }

    public static function arrayFromElement($el)
    {
        return [
            static::$key => static::getFromElement($el)
        ];
    }

    /***** From Header *******/

    public static function header()
    {
        return request()->header(static::$key);
    }

    public static function headerArray($data)
    {
        return [
            static::$key => $data
        ];
    }

    public static function is($action)
    {
        return is_array($action) ? 

            in_array(static::header(), $action) :

            (static::header() === $action);
    }
}