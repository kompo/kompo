<?php

namespace Kompo;

use Kompo\Select;
use Kompo\Core\Util;

use Illuminate\Database\Eloquent\Collection;

class MultiSelect extends Select
{
    /**
     * Adds a cast to array to the attribute if no cast is present.
     *
     * @var boolean
     */
    protected $castsToArray = true;

    /**
     * Has multiple values.
     *
     * @var        boolean
     */
    public $multiple = true;

    /**
     * Sets the value for front.
     */
    protected function setValueForFront()
    {
        $this->value = !Util::count($this->value) ? null : (($key = $this->valueKeyName($this->value)) ? $this->value->pluck($key) : Util::decode($this->value));
    }

    /**
     * Returns the primary key of the plucked values.
     *
     * @param Collection  $value
     *
     * @return string|null
     */
    protected function valueKeyName($value)
    {
        return $this->optionsKey ?: ($value instanceOf Collection ? $value[0]->getKeyName() : null);
    }
}
