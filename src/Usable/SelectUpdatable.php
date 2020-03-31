<?php

namespace Kompo;

use Kompo\Routing\RouteFinder;
use Kompo\Select;

class SelectUpdatable extends Select
{
    public $component = 'SelectUpdatable';

    public function mounted($form)
    {
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
     * @param string  $formClass  The fully qualified form class. Ex: App\Http\Komposers\MyForm::class
     * @param array|null  $ajaxPayload  Additional custom data to add to the request (optional).
     * @param string|null  $label      The label of the link that loads the new form. Default is 'Add a new option'.
     *
     * @return self 
     */
    public function addsRelatedOption(
        $formClass, 
        $ajaxPayload = null, 
        $label = 'Add a new option'
    )
    {
        return $this->data([
            'route' => RouteFinder::getKompoRoute(),
            'routeMethod' => 'GET',
            'formClass' => $formClass,
            'ajaxPayload' => $ajaxPayload,
            'sessionTimeoutMessage' => __('sessionTimeoutMessage'),
            'updateOptionsLabel' => $label
        ]);
    }
    
}
