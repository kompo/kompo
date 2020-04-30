<?php

namespace Kompo\Core;

class LaravelApp
{
    public static function isVersion7orHigher()
    {
        return ((int) substr(app()->version(), 0, 1)) >= 7;
    }
}