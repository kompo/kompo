<?php

namespace Kompo;

use Kompo\Elements\Field;
use Kompo\Elements\Traits\HasInputAttributes;

class Input extends Field
{
    use HasInputAttributes;

    public $vueComponent = 'Input';

    public $vuetifyComponent = 'text-field';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        if (strtolower($label) == 'email') {
            $this->inputType('email');
        } else {
            $this->inputType('text');
        }

        $this->debounce();
    }

    public function mounted($komponent)
    {
        $this->onEnter->submit();
    }

    //TODO: document
    public function clearable()
    {
        return $this->type('search');
    }
}
