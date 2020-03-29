<?php

namespace Kompo\Interactions\Actions;

trait DebounceActions
{
	/**
     * Sets the debounce interval for an action. Otherwise, it is defaulted to 500ms
     *
     * @param integer|null $debounce The number of milliseconds between requests.
     *
     * @return     self 
     */
    public function debounce($debounce = 500)
    {
        $this->debounce = $debounce; //TODO to review because different from other methods
        return $this;
    }
    
}