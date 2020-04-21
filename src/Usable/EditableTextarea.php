<?php

namespace Kompo;

use Kompo\Textarea;

class EditableTextarea extends Textarea
{
    public $vueComponent = 'EditableTextarea';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->noLabel();
    }

}
