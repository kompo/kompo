<?php

namespace Kompo;

use Kompo\Elements\Field;
use Kompo\Elements\Traits\CKEditorTrait;

class CKEditor extends Field
{
    use CKEditorTrait;

    public $vueComponent = 'CKEditor';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->setDefaultToolbar();
    }
}
