<?php

namespace Kompo;

class EditButton extends EditLink
{
    public $linkTag = 'vlButton';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);
        $this->outlined();
    }
}
