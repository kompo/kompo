<?php

namespace Kompo\Core;

class LaravelApp
{
    public static function isVersion7orHigher()
    {
        return version_compare(app()->version(), '7.0', '>=');
    }
}
