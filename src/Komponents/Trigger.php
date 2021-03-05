<?php

namespace Kompo\Komponents;

use Kompo\Interactions\Traits\ForwardsInteraction;
use Kompo\Interactions\Traits\HasInteractions;

abstract class Trigger extends Komponent
{
    use HasInteractions;
    use ForwardsInteraction;
    use Traits\FormSubmitConfigurations;
    use Traits\AjaxConfigurations;
    use Traits\LabelInfoComment;

    /**
     * Passes Komposer information to the component.
     *
     * @return void
     */
    public function prepareForDisplay($komposer)
    {
        if (config('kompo.smart_readonly_fields') && $this->config('submitsForm') && method_exists($komposer, 'authorize') && !$komposer->authorize()) {
            $this->displayNone();
        }
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
