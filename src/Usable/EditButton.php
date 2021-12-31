<?php

namespace Kompo;

class EditButton extends EditLink
{
    public $linkTag = 'vlButton';

    protected function initialize($label)
    {
        parent::initialize($label);
        $this->outlined();
    }
}
