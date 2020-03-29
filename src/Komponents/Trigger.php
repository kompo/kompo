<?php

namespace Kompo\Komponents;

use Kompo\Komponents\Traits\FormSubmitConfigurations;
use Kompo\Komponents\Traits\PerformsAjax;
use Kompo\Interactions\Traits\HasInteractions;
use Kompo\Interactions\Traits\NestsInteractions;

abstract class Trigger extends Komponent
{
    use HasInteractions, NestsInteractions, FormSubmitConfigurations;
    //use PerformsAjax;

	/**
     * Passes Komposer information to the component.
     *
     * @return void
     */
    public function prepareForDisplay($komposer)
    {
        if(config('kompo.smart_readonly_fields') && $this->data('submitsForm') && method_exists($komposer, 'authorize') && !$komposer->authorize())
            $this->displayNone();
    }

    /**
     * Passes Komposer information to the component.
     *
     * @return void
     */
    public function prepareForAction($komposer)
    {
        parent::prepareForAction($komposer);        
    }
    
}