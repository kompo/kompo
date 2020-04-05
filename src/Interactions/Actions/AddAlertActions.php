<?php

namespace Kompo\Interactions\Actions;

trait AddAlertActions
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
        $message = !$iconClass ? $message : 
            ('<i class="'.($iconClass == true ? 'icon-check' : $iconClass).'"> '.$message);

        return $this->prepareAction('addAlert', [
            'message' => $message,
            'alertClass' => 'vlAlertSuccess'
        ]);
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
        $message = !$iconClass ? $message : 
            ('<i class="'.($iconClass == true ? 'icon-times' : $iconClass).'"> '.$message);

        return $this->prepareAction('addAlert', [
            'message' => $message,
            'alertClass' => 'vlAlertError'
        ]);
    }
    
}