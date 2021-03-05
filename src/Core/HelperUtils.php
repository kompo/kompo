<?php

if (!function_exists('phoneFormat')) {
    function phoneFormat($phone)
    {
        return $phone ?
            preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $phone) : '';
    }
}
