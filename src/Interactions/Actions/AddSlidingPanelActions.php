<?php

namespace Kompo\Interactions\Actions;

use Kompo\Core\IconGenerator;

trait AddSlidingPanelActions
{
    /**
     * Displays HTML in a sliding panel after an AJAX request using the response from the request.
     *      *
     * @return     self  
     */
    public function inSlidingPanel()
    {
        return $this->prepareAction('fillSlidingPanel');
    }
}