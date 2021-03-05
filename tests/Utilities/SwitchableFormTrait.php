<?php

namespace Kompo\Tests\Utilities;

trait SwitchableFormTrait
{
    public function filter($komponents)
    {
        return collect($komponents)->filter(function ($komponent) {
            if ($komponent->name == $this->store('komponent')) {
                return $komponent;
            }
        });
    }
}
