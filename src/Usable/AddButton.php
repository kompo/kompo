<?php

namespace Kompo;

class AddButton extends AddLink
{
    public $linkTag = 'vlButton';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);
        $this->outlined();
    }
}
