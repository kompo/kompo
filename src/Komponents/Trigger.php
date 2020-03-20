<?php

namespace Kompo\Komponents;

use Kompo\Komponents\Traits\DoesFormSubmits;

abstract class Trigger extends Komponent
{
    use DoesFormSubmits;
	/**
     * Passes Komposer information to the component.
     *
     * @return void
     */
    public function prepareForDisplay($komposer)
    {
        if(config('vuravel.smart_readonly_fields') && $this->data('submitsForm') && method_exists($komposer, 'authorize') && !$komposer->authorize())
            $this->displayNone();
    }

    /**
     * Passes Komposer information to the component.
     *
     * @return void
     */
    public function prepareForSave($komposer)
    {
        parent::prepareForSave($komposer);        
    }
    
}