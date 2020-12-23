<?php 

if (! function_exists('phone')) {
    function phone($phone)
    {
        return $phone ? 
            preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $phone) : '';
    }
}