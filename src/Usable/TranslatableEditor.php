<?php

namespace Kompo;

use Kompo\Komponents\Traits\CKEditorTrait;

class TranslatableEditor extends Translatable
{
    use CKEditorTrait;
    
    public $component = 'TranslatableEditor';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->setDefaultToolbar();
    }
}
