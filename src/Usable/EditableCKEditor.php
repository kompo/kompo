<?php

namespace Kompo;

class EditableCKEditor extends CKEditor
{
    public $vueComponent = 'EditableCKEditor';

    protected function initialize($label)
    {
        parent::initialize($label);

        $this->noLabel();
    }
}
