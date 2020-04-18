<?php 

use Kompo\Tests\Utilities\_Query;
use Kompo\Tests\Utilities\_Form;
use Kompo\Tests\Utilities\_Menu;

function _Query($store = [])
{
    return new _Query($store);
}

function _Form($modelKey = null, $store = [])
{
    return new _Form($modelKey, $store);
}

function _Menu($store = [])
{
    return new _Menu($store);
}