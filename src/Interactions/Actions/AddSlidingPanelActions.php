<?php

namespace Kompo\Interactions\Actions;

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