<?php

namespace Kompo\Interactions\Actions;

trait AddDrawerActions
{
    //TODO: rename trait

    /** TODO: DOCUMENT
     * Displays HTML in a sliding panel after an AJAX request using the response from the request.
     *      *
     * @return self
     */
    public function inDrawer()
    {
        return $this->prepareAction('fillDrawer');
    }

    /** TODO: DOCUMENT
     * Close the sliding panel.
     *      *
     * @return self
     */
    public function closeDrawer()
    {
        return $this->prepareAction('closeDrawer');
    }

    //alias deprecated in v4
    public function inSlidingPanel()
    {
        return $this->inDrawer();
    }

    //alias deprecated in v4
    public function closeSlidingPanel()
    {
        return $this->closeDrawer();
    }

    /** TODO: DOCUMENT
     * Displays HTML in a sliding panel after an AJAX request using the response from the request.
     *      *
     * @return self
     */
    public function inPopup()
    {
        return $this->prepareAction('fillPopup');
    }

    public function closePopup()
    {
        return $this->prepareAction('closePopup');
    }
}
