<?php

namespace Kompo\Interactions\Actions;

trait FillModalActions
{
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
        return $this->prepareAction('fillModal', [
            'modalName' => $modalName,
            'panelId' => $modalName,
            'warnBeforeClose' => $warnBeforeClose == false ? false : 
                ($warnBeforeClose == true ? __('warnBeforeClose') : __($warnBeforeClose))
        ]);
    }
    
}