<?php

namespace Kompo;

use Kompo\Core\KompoId;
use Kompo\Komponents\Traits\HasAddLabel;
use Kompo\Routing\RouteFinder;

class SelectUpdatable extends Select
{
    use HasAddLabel;

    public $vueComponent = 'SelectUpdatable';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->addLabel('Add a new option');
    }

    public function mounted($form)
    {
        $this->komponents = [clone $this];
        $this->komponents[0]->vueComponent = 'Select';
        KompoId::appendToElement($this->komponents[0], '-select');

        $this->config([
            'optionsKey'   => $this->optionsKey,
            'optionsLabel' => $this->optionsLabel,
        ]); //for updating value from options
    }

    /**
     * Specifies which form to open in the modal. In the first parameter:
     * - You may either call a Form class directly. Ex: App\Http\Komposers\MyForm::class
     * - Or call a Route::get() that points to the Form Class. Ex: route('my-form').
     * After submit, the object will be added to the select options (and selected).
     *
     * @param string     $formClassOrRoute The fully qualified form class or kompo route url.
     * @param array|null $ajaxPayload      Additional custom data to add to the request (optional).
     *
     * @return self
     */
    public function addsRelatedOption(
        $formClassOrRoute,
        $ajaxPayload = null
    ) {
        //it has to be POST... automatic payload contains id for reopening Form
        return RouteFinder::setUpKomposerRoute($this, $formClassOrRoute, 'POST')->config([
            'ajaxPayload'           => $ajaxPayload,
        ]);
    }
}
