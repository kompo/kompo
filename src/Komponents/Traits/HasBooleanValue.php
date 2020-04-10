<?php 

namespace Kompo\Komponents\Traits;

trait HasBooleanValue
{
    public function value($value)
    {
        $this->value = $value ? 1 : 0;
        return $this;
    }
}