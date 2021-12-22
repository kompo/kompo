<?php

namespace Kompo\Core;

class MetaAnalysis
{
    public static function getAllOfType($type)
    {
        $dir = __DIR__.'/../Usable';

        $files = array_diff(scandir($dir), ['.', '..']);

        return collect($files)->map(function ($element) use ($type) {
            $element = str_replace('.php', '', $element);
            $elementClass = 'Kompo\\'.$element;

            if (is_a($elementClass, $type, true)) {
                return $elementClass;
            }
        })->filter()->values()->all();
    }
}
