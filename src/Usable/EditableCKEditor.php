<?php

namespace Kompo;

use Kompo\CKEditor;

class EditableCKEditor extends CKEditor
{
    public $component = 'EditableCKEditor';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->noLabel();
    }

}
