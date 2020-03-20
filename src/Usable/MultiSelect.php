<?php

namespace Kompo;

use Kompo\Utilities\Arr;

class MultiSelect extends Select
{
    /**
     * Adds a cast to array to the attribute if no cast is present.
     *
     * @var boolean
     */
    protected $castsToArray = true;

    public $multiple = true;

    public $value = [];

    protected function setValueForFront($value)
    {
        if(!$value)
            return;

        $this->value = ($key = $this->valueKeyName($value)) ? $value->pluck($key) : Arr::decode($value);
    }
}
