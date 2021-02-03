<?php

namespace Kompo\Interactions\Actions;

trait AddSlidingPanelActions
{

	//TODO: rename trait

    /** TODO: DOCUMENT
     * Displays HTML in a sliding panel after an AJAX request using the response from the request.
     *      *
     * @return     self  
     */
    public function inSlidingPanel()
    {
        return $this->prepareAction('fillSlidingPanel');
    }

    /** TODO: DOCUMENT
     * Displays HTML in a sliding panel after an AJAX request using the response from the request.
     *      *
     * @return     self  
     */
    public function inPopup()
    {
        return $this->prepareAction('fillPopup');
    }
}