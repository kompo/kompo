<?php

namespace Kompo;

class AddButton extends AddLink
{
    public $linkTag = 'vlButton';

    protected function initialize($label)
    {
        parent::initialize($label);
        $this->outlined();
    }
}
