<?php

namespace Kompo;

use Kompo\Elements\Traits\ModalLinks;

class AddLink extends Link
{
    use ModalLinks;

    public $vueComponent = 'EditLink';
    public $linkTag = 'vlLink';

    protected function initialize($label)
    {
        parent::initialize($label);

        $this->setDefaultSuccessAction();

        $this->setDefaultIcon();
    }

    protected function setDefaultIcon()
    {
        $this->icon('icon-plus');
    }
}
