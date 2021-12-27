<?php

namespace Kompo;

class DeleteButton extends DeleteLink
{
    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->button();
    }
}
