<?php

namespace Kompo\Komponents\Traits;

use Illuminate\Database\Eloquent\Collection;
use Kompo\Core\Util;

trait MultiSelectTrait
{
    
    /**
     * Adds a cast to array to the attribute if no cast is present.
     *
     * @var bool
     */
    protected $castsToArray = true;

    /**
     * Has multiple values.
     *
     * @var bool
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
     * @param Collection $value
     *
     * @return string|null
     */
    protected function valueKeyName($value)
    {
        return $this->optionsKey ?: ($value instanceof Collection ? $value[0]->getKeyName() : null);
    }
}
