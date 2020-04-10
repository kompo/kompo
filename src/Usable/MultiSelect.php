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

    protected function setValueForFront()
    {
        $this->value = !Util::count($this->value) ? null : (($key = $this->valueKeyName($this->value)) ? $this->value->pluck($key) : Util::decode($this->value));
    }
}
