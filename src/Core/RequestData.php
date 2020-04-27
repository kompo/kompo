<?php 

namespace Kompo\Core;

use Kompo\Database\NameParser;

class RequestData
{
    protected static $delimiter = '`'; //PHP limitation dot notations are converted to underscore

    public static function get($fieldName)
    {
        return request()->__get($fieldName) ?: request()->__get(static::convert($fieldName)); //->input() does tranformations for dot notations...
    }

    public static function has($fieldName)
    {
        return request()->has($fieldName) || request()->has(static::convert($fieldName));
    }

    public static function convert($name)
    {
        return str_replace(NameParser::$nester, static::$delimiter, $name);
    }

    public static function convertBack($name)
    {
        return str_replace(static::$delimiter, NameParser::$nester, $name);
    }
}