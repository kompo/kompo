<?php

namespace Kompo;

use Kompo\Select;
use Kompo\Core\Util;

class MultiSelect extends Select
{
    /**
     * Adds a cast to array to the attribute if no cast is present.
     *
     * @var boolean
     */
    protected $castsToArray = true;

    public $multiple = true;

    protected function setValueForFront($value)
    {
        $this->value = !Util::count($value) ? null : (($key = $this->valueKeyName($value)) ? $value->pluck($key) : Util::decode($value));
    }
}
