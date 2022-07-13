<?php

namespace Kompo;

use Kompo\Elements\Field;

class Input extends Field
{
    use \Kompo\Elements\Traits\HasInputAttributes;
    use \Kompo\Elements\Traits\FocusBlur;

    public $vueComponent = 'Input';

    public $vuetifyComponent = 'text-field';

    protected function initialize($label)
    {
        parent::initialize($label);

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
