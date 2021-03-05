<?php

namespace Kompo\Tests;

trait KompoUtilitiesTrait
{
    protected function assertSubset($subset, $array)
    {
        $array = is_array($array) ? $array : $array->toArray();
        $subset = is_array($subset) ? $subset : $subset->toArray();
        foreach ($subset as $key => $value) {
            $this->assertEquals($value, $array[$key]);
        }
    }
}
