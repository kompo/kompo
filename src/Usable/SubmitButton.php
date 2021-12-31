<?php

namespace Kompo;

class SubmitButton extends Button
{
    protected function initialize($label)
    {
        parent::initialize($label ?: 'Save');

        $this->submit();
    }
}
