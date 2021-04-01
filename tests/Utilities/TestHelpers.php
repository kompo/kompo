<?php

use Kompo\Tests\Utilities\_Form;
use Kompo\Tests\Utilities\_Menu;
use Kompo\Tests\Utilities\_Query;

function _Query($store = [])
{
    return _Query::boot($store);
}

function _Form($modelKey = null, $store = [])
{
    return _Form::boot($modelKey, $store);
}

function _Menu($store = [])
{
    return new _Menu($store);
}
