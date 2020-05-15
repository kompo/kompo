<?php

namespace Kompo\Interactions\Actions;

trait FillModalActions
{
    /**
     * Whatever is loaded by AJAX will be displayed in a modal. 
     * If the modalName is left blank, the default modal will be used. 
     * Otherwise, you have to declare a `&lt;vl-modal>` with the desired name.
     *
     * @param      string|null  $modalName  The modal name (optional)
     *
     * @return     self    
     */
    public function inModal($modalName = null)
    {
        return $this->prepareAction('fillModal', [
            'modalName' => $modalName,
            'panelId' => $modalName
        ]);
    }
    
}