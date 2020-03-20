<?php 

use Kompo\Tests\Utilities\_Catalog;
use Kompo\Tests\Utilities\_Form;
use Kompo\Tests\Utilities\_Menu;

function _Catalog($store = [])
{
    return new _Catalog($store);
}

function _Form($modelKey = null, $store = [])
{
    return new _Form($modelKey, $store);
}

function _Menu($store = [])
{
    return new _Menu($store);
}