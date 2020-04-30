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
     * @param      string|boolean|null  $iconClass  An optional icon class. Default is "icon-check".
     * @param      string|null          $alertClass The class for the alert box. Default is "vlAlertSuccess" (green background).
     *
     * @return     self    
     */
    public function alert($message, $iconClass = true, $alertClass = 'vlAlertSuccess')
    {
        return $this->prepareAction('addAlert', [
            'alert' => [
                'message' => $message,                
                'iconClass' => $iconClass === true ? 'icon-check' : $iconClass,
                'alertClass' => $alertClass
            ]
        ]);
    }

    /**
     * Displays a Bootstrap-style alert message after an AJAX request, using the response from the request as the message.
     * 
     * @param      string|boolean|null  $iconClass  An optional icon class. Default is "icon-check".
     * @param      string|null          $alertClass The class for the alert box. Default is "vlAlertSuccess" (green background).
     *
     * @return     self  
     */
    public function inAlert($iconClass = true, $alertClass = 'vlAlertSuccess')
    {
        return $this->prepareAction('fillAlert', [  
            'alert' => [              
                'iconClass' => $iconClass === true ? 'icon-check' : $iconClass,
                'alertClass' => $alertClass
            ]
        ]);
    }
}