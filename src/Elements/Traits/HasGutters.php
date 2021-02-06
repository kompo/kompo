<?php 

namespace Kompo\Elements\Traits;

trait HasGutters 
{
    /**
     * Removes the gutters in the columns grid (no padding between the columns).
     *
     * @return self 
     */
    public function noGutters()
    {
        $this->config(['guttersClass' => 'no-gutters']);
        return $this;
    }
}