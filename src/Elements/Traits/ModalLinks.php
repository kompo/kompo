<?php

namespace Kompo\Elements\Traits;

use Kompo\Interactions\Action;
use Kompo\Interactions\Interaction;

trait ModalLinks
{
    public $defaultSuccessInteraction;

    protected function setDefaultSuccessAction()
    {
        $action = with(new Action())->emitDirect('insertForm');
        $interaction = new Interaction($action, 'success');
        $interaction->action = $action;

        $this->defaultSuccessInteraction = $interaction;
    }
}
