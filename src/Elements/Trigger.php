<?php

namespace Kompo\Elements;

use Kompo\Interactions\Traits\ForwardsInteraction;
use Kompo\Interactions\Traits\HasInteractions;

abstract class Trigger extends Element
{
    use HasInteractions;
    use ForwardsInteraction;
    use Traits\FormSubmitConfigurations;
    use Traits\LabelInfoComment;

    /**
     * Passes Komponent information to the component.
     *
     * @return void
     */
    public function prepareForDisplay($komponent)
    {
        if (config('kompo.smart_readonly_fields') && $this->config('submitsForm') && method_exists($komponent, 'authorize') && !$komponent->authorize()) {
            $this->displayNone();
        }

        if (method_exists($this, 'checkActive')) {
            $this->checkActive();
        }
    }

    /**
     * Passes Komponent information to the component.
     *
     * @return void
     */
    public function prepareForAction($komponent)
    {
        parent::prepareForAction($komponent);
    }
}
