<?php 
namespace Kompo\Komponents\Traits;

use Kompo\Triggers\TriggerHandler;

trait AjaxConfigurations 
{

    /**
     * Shows a warning message that the user needs to confirm before closing the editing modal.
     * If message is left empty, it defaults to "Any unsaved changes will be lost. Are you sure you want to close this window?". 
     * 
     * @param  string|null $message 
     * @return self
     */
    public function warnBeforeClose($message = null)
    {
        //for EditLink -- to remove DRY
        return $this->data(['warnBeforeClose' => $message ? __($message) : __('warnBeforeClose')]);
    }


    /**
     * Cancels or reverts the closest parent Panel (hides it and shows previous state).
     *
     * @return Vuravel\Elements\Element
     */
    public function revertsPanel()
    {
        $this->data([ 'revertsPanel' => true ]);
        return $this;
    }

    /**
     * Removes the form Row of the closes parent MultiForm.
     *
     * @return Vuravel\Elements\Element
     */
    public function revertsFormRow()
    {
        $this->data([ 'revertsFormRow' => true ]);
        return $this;
    }

    /**
     * Keeps the modal open after a form submit.
     *
     * @return Vuravel\Elements\Element
     */
    public function keepModalOpen()
    {
        return $this->data(['keepModalOpen' => true]);
    }

}