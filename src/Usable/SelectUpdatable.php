<?php

namespace Kompo;

use Kompo\Select;
use Closure;

class SelectUpdatable extends Select
{
    public $component = 'SelectUpdatable';

    const DB_UPDATE_OPTIONS_ROUTE = 'vuravel-form.select-updated-options';

    public function mounted($form)
    {
        parent::mounted($form);
        $this->components = [ clone $this ];
        $this->components[0]->component = 'Select';

        $this->data([
            'optionsKey' => $this->optionsKey,
            'optionsLabel' => $this->optionsLabel
        ]); //for updating value from options
    }

    /**
     * Specifies which form class to open in the modal. After submit, the object will be added to the select options (and selected).
     *
     * @param      string  $route    The route name or uri.
     * @param      array|null  $parameters   The route parameters (optional).
     * @param      array|null  $ajaxPayload  Additional custom data to add to the request (optional).
     * @param      string|null  $label      The label of the link that loads the new form. Default is 'Add a new option'.
     *
     * @return     self 
     */
    public function addsRelatedOption(
        $route, 
        $parameters = null, 
        $ajaxPayload = null, 
        $label = 'Add a new option'
    )
    {
        $this->setRoute($route, $parameters);
        $this->setRouteMethod('POST');

        if($ajaxPayload)
            $this->data(['ajaxPayload' => $ajaxPayload]);

        return $this->data([
            'sessionTimeoutMessage' => __('sessionTimeoutMessage'),
            'updateOptionsLabel' => $label,
            'updateOptionsRoute' => route(self::DB_UPDATE_OPTIONS_ROUTE)
        ]);
    }
    
}
