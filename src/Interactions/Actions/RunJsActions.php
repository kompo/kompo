<?php

namespace Kompo\Interactions\Actions;

trait RunJsActions
{
    //TODO: document
    public function run($jsFunction)
    {
        return $this->prepareAction('runJs', [
            'jsFunction' => $jsFunction,
        ]);
    }
}
