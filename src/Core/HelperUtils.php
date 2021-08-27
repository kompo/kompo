<?php

/* Image & Thumbnail URLs */
if (!function_exists('thumb')) {
    function thumb($path)
    {
        return substr($path, 0, strrpos($path, '.')).
               '_thumb.'.
               substr($path, strrpos($path, '.') + 1);
    }
}

if (!function_exists('assetThumb')) {
    function assetThumb($path)
    {
        return thumb(asset($path));
    }
}

/* Phone */
if (!function_exists('phoneFormat')) {
    function phoneFormat($phone)
    {
        return $phone ?
            preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $phone) : '';
    }
}

/* Color Shortcuts to override in your app*/
if (!function_exists('color')) {
    function color()
    {
        return 'yellow'; //override here for example
    }
}

if (!function_exists('hex')) {
    function hex()
    {
        return colors()[color()];
    }
}

if (!function_exists('colors')) {
    function colors()
    {
        return [
            'yellow' => 'rgb(180, 83, 9)',
            'cyan' => 'rgb(15, 116, 144)',
            'gray' => 'rgb(55, 65, 81)',
            'red' => 'rgb(185, 28, 28)',
        ];
    }
}
