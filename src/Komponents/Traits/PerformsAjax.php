<?php 
namespace Kompo\Komponents\Traits;

use Kompo\Triggers\TriggerHandler;

trait PerformsAjax 
{

    /**
     * Displays a Bootstrap-style alert message on success. By default, a "check" icon is shown but you may override it by setting your own icon class, or disable it by setting it to false.
     *
     * @param      string  $message  The message to be displayed
     * @param      string|boolean|null  $iconClass  An optional icon class. Default is "icon-check".
     *
     * @return     self    
     */
    public function onSuccessAlert($message, $iconClass = true)
    {
        TriggerHandler::defaultTrigger($this)->onSuccessAlert($message, $iconClass);
        return $this;
    }

    /**
     * Displays a Bootstrap-style alert message on error. By default, an "x" icon is shown but you may override it by setting your own icon class, or disable it by setting it to false.
     *
     * @param      string  $message  The message to be displayed
     * @param      string|boolean|null  $iconClass  An optional icon class. Default is "icon-times".
     *
     * @return     self    
     */
    public function onErrorAlert($message, $iconClass = true)
    {
        TriggerHandler::defaultTrigger($this)->onErrorAlert($message, $iconClass);
        return $this;
    }

    /**
     * Whatever is loaded by AJAX will be displayed in a modal. 
     * If the modalName is left blank, the default modal will be used. 
     * Otherwise, you have to declare a `&lt;vl-modal>` with the desired name.
     * You may also add as a second parameter a warning message that the user needs to confirm before closing the editing modal. If set to true, the default message is "Any unsaved changes will be lost. Are you sure you want to close this window?". 
     *
     * @param      string|null  $modalName  The modal name (optional)
     * @param      boolean|null  $warnBeforeClose  An optional warning message if the user attempts to close the modal
     *
     * @return     self    
     */
    public function inModal($modalName = null, $warnBeforeClose = false)
    {
        TriggerHandler::defaultTrigger($this)->inModal($modalName, $warnBeforeClose);
        return $this;
    }

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
     * Whatever is loaded by AJAX will be displayed in the panel with the specified id. 
     * You need to add a `Panel` component to your Form or Catalog. For example:
     * <php>Button::form('Click')->loadsView('view-route')->inPanel('panel-id'),
     * Panel::form('panel-id')</php>
     * You may also add an optional quick HTML response as a second parameter.
     * 
     *
     * @param      string  $panelId  The panel id attribute
     * @param      string|null  $response  An quick html response (optional)
     *
     * @return     self  
     */
    public function inPanel($panelId, $response = null)
    {
        TriggerHandler::defaultTrigger($this)->inPanel($panelId, $response);
        return $this;
    }
    public function inPanel1(){ return $this->inPanel('VlPanel1'); }
    public function inPanel2(){ return $this->inPanel('VlPanel2'); }
    public function inPanel3(){ return $this->inPanel('VlPanel3'); }
    public function inPanel4(){ return $this->inPanel('VlPanel4'); }
    public function inPanel5(){ return $this->inPanel('VlPanel5'); }

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