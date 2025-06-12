<?php

namespace Kompo;

use Kompo\Elements\Field;
use Kompo\Elements\Traits\CKEditorTrait;

class CKEditor extends Field
{
    use CKEditorTrait;

    public $vueComponent = 'CKEditor';

    protected function initialize($label)
    {
        parent::initialize($label);

        $this->setDefaultToolbar();

        $this->passLocale();
    }
}
