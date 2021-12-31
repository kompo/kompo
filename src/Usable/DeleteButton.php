<?php

namespace Kompo;

class DeleteButton extends DeleteLink
{
    protected function initialize($label)
    {
        parent::initialize($label);

        $this->button();
    }
}
