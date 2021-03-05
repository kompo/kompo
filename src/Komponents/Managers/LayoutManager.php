<?php

namespace Kompo\Komponents\Managers;

use Closure;
use Illuminate\Support\Collection;
use Kompo\Core\Util;
use Kompo\Komponents\Layout;

class LayoutManager extends Layout
{
    public static function collectFilteredKomponents($args, $layout)
    {
        if (static::argsIsString($args)) {
            return collect([]);
        }

        if (static::argsIsClosure($args)) {
            $args = call_user_func($args[0], $layout);
        } //You can pass $layout optionnally as a param to the closure

        $args = static::wrapIfNotArray($args); //after closure, in case user returns wrong things

        $args = static::flattenIfNestedArray($args); //if instantiation argument was an array or Collection

        return Util::collect($args)->filter();
    }

    public static function getNormalizedLabel($args, $layout)
    {
        return static::argsIsString($args) ? $args[0] : class_basename($layout);
    }

    /********** PRIVATE ************/

    //PlainSpan for ex. //TODO: document when that is needed
    private static function argsIsString($args)
    {
        return is_array($args) && count($args) == 1 && is_string($args[0]);
    }

    private static function argsIsClosure($args)
    {
        return is_array($args) && count($args) == 1 && is_callable($args[0]) && $args[0] instanceof Closure;
    }

    private static function wrapIfNotArray($args)
    {
        return is_array($args) || $args instanceof Collection ? $args : [$args];
    }

    private static function flattenIfNestedArray($args)
    {
        return is_array($args) && count($args) == 1 && (is_array($args[0]) || $args[0] instanceof Collection) ? $args[0] : $args;
    }
}
