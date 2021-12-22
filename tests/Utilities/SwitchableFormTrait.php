<?php

namespace Kompo\Tests\Utilities;

trait SwitchableFormTrait
{
    public function filter($elements)
    {
        return collect($elements)->filter(function ($element) {
            if ($element->name == $this->store('element')) {
                return $element;
            }
        });
    }
}
