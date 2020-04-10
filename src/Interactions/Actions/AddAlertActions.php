<?php

namespace Kompo\Interactions\Actions;

trait AddAlertActions
{
    /**
     * Displays a Bootstrap-style alert message. 
     * By default, a "check" icon is shown but you may override it by setting your own icon class, or disable it by setting it to false. 
     * You may also add an class to the whole box with the alertClass parameter.
     *
     * @param      string               $message    The message to be displayed
     * @param      string|null          $alertClass The class for the alert box. Default is "vlAlertSuccess" (green background).
     * @param      string|boolean|null  $iconClass  An optional icon class. Default is "icon-check".
     *
     * @return     self    
     */
    public function alert($message, $iconClass = true, $alertClass = 'vlAlertSuccess')
    {
        $message = !$iconClass ? $message : 
            ('<i class="'.($iconClass == true ? 'icon-check' : $iconClass).'"> '.$message);

        return $this->prepareAction('addAlert', [
            'message' => $message,
            'alertClass' => $alertClass
        ]);
    }
}