<?php

namespace Kompo;

use Kompo\Elements\Traits\CKEditorTrait;

class TranslatableEditor extends Translatable
{
    use CKEditorTrait;

    public $vueComponent = 'TranslatableEditor';

    protected function initialize($label)
    {
        parent::initialize($label);

        $this->setDefaultToolbar();

        $this->passLocale();
    }
}
