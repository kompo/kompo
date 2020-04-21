<?php

namespace Kompo;

use Kompo\Komponents\Field;
use Kompo\Komponents\Traits\CKEditorTrait;

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
