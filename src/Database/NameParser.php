<?php

namespace Kompo\Database;

class NameParser
{
    public static $nester = '.';

    public static function isNested($fieldName)
    {
        return strpos($fieldName, static::$nester) !== false;
    }

    public static function explode($name, $limit = 3)
    {
        return explode(static::$nester, $name, $limit);
    }

    public static function firstTerm($name)
    {
        return static::explode($name)[0];
    }

    public static function secondTerm($name)
    {
        $limitedExplode = static::explode($name, 2);
        return count($limitedExplode) == 2 ? $limitedExplode[1] : null;
    }

}