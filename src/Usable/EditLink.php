<?php

namespace Kompo;

class EditLink extends AddLink
{
    protected function setDefaultIcon()
    {
        if (!$this->label) { //just an icon
            $this->icon('icon-edit');
        }
    }
}
