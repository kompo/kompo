<?php

namespace Kompo;

class EditableTextarea extends Textarea
{
    public $vueComponent = 'EditableTextarea';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->noLabel();
    }
}
