<?php

namespace Kompo\Elements\Traits;

trait AjaxConfigurations
{
    /**
     * Shows a warning message that the user needs to confirm before closing the editing modal.
     * If message is left empty, it defaults to "Any unsaved changes will be lost. Are you sure you want to close this window?".
     *
     * @param string|null $message
     *
     * @return self
     */
    public function warnBeforeClose($message = null)
    {
        return $this->config(['warnBeforeClose' => $message ? __($message) : __('warnBeforeClose')]);
    }

    /** TODO document
     * Refreshes the parent Komponent when opened in a Modal, Panel, Drawer or Popup
     *
     * @return self
     */
    public function refreshParent()
    {
        return $this->config(['refreshParent' => true]);
    }

    /**
     * Cancels or reverts the closest parent Panel (hides it and shows previous state).
     *
     * @return Kompo\Elements\Element
     */
    public function revertsPanel()
    {
        $this->config(['revertsPanel' => true]);

        return $this;
    }

    /**
     * Removes the form Row of the closes parent MultiForm.
     *
     * @return Kompo\Elements\Element
     */
    public function revertsFormRow()
    {
        $this->config(['revertsFormRow' => true]);

        return $this;
    }

    /** Deprecated in v4 => keepOpen()
     * Keeps the modal open after a form submit.
     *
     * @return Kompo\Elements\Element
     */
    public function keepModalOpen()
    {
        return $this->keepOpen();
    }

    //TODO DOCUMENT
    public function keepOpen()
    {
        return $this->config(['keepOpen' => true]);
    }
}
