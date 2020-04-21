<?php

namespace Kompo;

use Kompo\Routing\RouteFinder;
use Kompo\Select;

class SelectUpdatable extends Select
{
    public $vueComponent = 'SelectUpdatable';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->addLabel('Add a new option');
    }

    public function mounted($form)
    {
        $this->komponents = [ clone $this ];
        $this->komponents[0]->vueComponent = 'Select';

        $this->data([
            'optionsKey' => $this->optionsKey,
            'optionsLabel' => $this->optionsLabel
        ]); //for updating value from options
    }

    /**
     * Specifies which form to open in the modal. In the first parameter:
     * - You may either call a Form class directly. Ex: App\Http\Komposers\MyForm::class
     * - Or call a Route::kompo() that points to the Form Class. Ex: route('my-form').
     * After submit, the object will be added to the select options (and selected).
     *
     * @param string       $formClassOrRoute  The fully qualified form class or kompo route url. 
     * @param string|null  $routeMethod       The desired method for the request. Default is a GET request.
     * @param array|null   $ajaxPayload       Additional custom data to add to the request (optional).
     *
     * @return self 
     */
    public function addsRelatedOption(
        $formClassOrRoute, 
        $routeMethod = 'GET',
        $ajaxPayload = null
    )
    {
        return RouteFinder::setUpKomposerRoute($this, $formClassOrRoute, $routeMethod)->data([
            'ajaxPayload' => $ajaxPayload,
            'sessionTimeoutMessage' => __('sessionTimeoutMessage')
        ]);
    }

    /**
     * Specifies the label of the link that will load the form. Default is 'Add a new option'.
     *
     * @param string  $label  The label
     */
    public function addLabel($label)
    {
        return $this->data([
            'updateOptionsLabel' => __($label)
        ]);
    }
    
}
