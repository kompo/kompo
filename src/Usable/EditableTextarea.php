<?php

namespace Kompo;

class EditableTextarea extends Textarea
{
    public $vueComponent = 'EditableTextarea';

    protected function initialize($label)
    {
        parent::initialize($label);

        $this->noLabel();
    }
}
