<?php

namespace Kompo;

use Kompo\Komponents\Traits\CKEditorTrait;
use Kompo\Translatable;

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
