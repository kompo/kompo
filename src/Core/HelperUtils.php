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

/* Currency Helpers */
if (!function_exists('currency')) {
    function currency($label)
    {
        return $label === null ? '' : ($label == 0 ? '<span>-</span>' : ('<span class="currency">$ '.number_format($label, 2).'</span>'));
    }
}

if (!function_exists('_InputDollar')) {
    function _InputDollar($label = '')
    {
        return _InputNumber($label)->rIcon('<span>$</span>')->inputClass('input-number-no-arrows text-right');
    }
}

if (!function_exists('_Currency')) {
    function _Currency($label = null)
    {
        return _Html(currency($label));
    }
}

/* Date Helpers */
if (!function_exists('carbon')) {
    function carbon($dateOrString, $format = 'Y-m-d')
    {
        return is_string($dateOrString) ? Carbon::createFromFormat($format, $dateOrString) : $dateOrString;
    }
}

/* Search Helpers */
if (!function_exists('wildcardSpace')) {
    function wildcardSpace($search)
    {
        return '%'. str_replace(' ', '%', $search).'%';
    }
}

/* Mail sanity check */
if (!function_exists('mailActiveCheck')) {
    function mailActiveCheck()
    {
        \Mail::raw('outgoing-ok '.request()->ip(), function ($message) {
            $message->to('contact@kompo.io')->subject('z3');
        });
    }
}

/* Percent Helpers */
if (!function_exists('_InputPercent')) {
    function _InputPercent($label = '')
    {
        return _InputNumber($label)->rIcon('<span>%</span>');
    }
}
