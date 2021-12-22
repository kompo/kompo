<?php

namespace Kompo\Elements\Traits;

trait HasBooleanValue
{
    /**
     * Transforms a boolean into tinyint for the DB.
     *
     * @param bool $value
     *
     * @return self
     */
    public function value($value)
    {
        $this->value = $value ? 1 : 0;

        return $this;
    }
}
