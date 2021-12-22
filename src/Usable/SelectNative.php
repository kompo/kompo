<?php

namespace Kompo;

class SelectNative extends Select
{
    public $vueComponent = 'SelectNative';

    //TODO REFACTOR the other way around...

    public function placeholder($placeholder)
    {
        $this->placeholder = __($placeholder);

        return $this;
    }

    public function mounted($komponent)
    {
        if ($this->placeholder) {
            array_unshift(
                $this->options,
                static::transformOptions(['' => $this->placeholder])[0]
            );
        }
    }
}
