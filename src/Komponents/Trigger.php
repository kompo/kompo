<?php

namespace Kompo\Komponents;

use Kompo\Interactions\Traits\HasInteractions;
use Kompo\Interactions\Traits\ForwardsInteraction;

abstract class Trigger extends Komponent
{
    use HasInteractions, ForwardsInteraction;

    use Traits\FormSubmitConfigurations,
        Traits\AjaxConfigurations,
        Traits\LabelInfoComment;

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