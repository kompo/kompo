<?php

namespace Kompo\Core;

class MetaAnalysis
{
    public static function getAllOfType($type)
    {
        $dir = __DIR__.'/../Usable';

        $files = array_diff(scandir($dir), ['.', '..']);

        return collect($files)->map(function ($komponent) use ($type) {
            $komponent = str_replace('.php', '', $komponent);
            $komponentClass = 'Kompo\\'.$komponent;

            if (is_a($komponentClass, $type, true)) {
                return $komponentClass;
            }
        })->filter()->values()->all();
    }
}
