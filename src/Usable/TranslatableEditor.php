<?php

namespace Kompo;

use Kompo\Elements\Traits\CKEditorTrait;

class TranslatableEditor extends Translatable
{
    use CKEditorTrait;

    public $vueComponent = 'TranslatableEditor';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->setDefaultToolbar();
    }
}
