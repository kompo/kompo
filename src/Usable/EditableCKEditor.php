<?php

namespace Kompo;

class EditableCKEditor extends CKEditor
{
    public $vueComponent = 'EditableCKEditor';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->noLabel();
    }
}
