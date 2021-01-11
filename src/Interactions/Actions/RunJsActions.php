<?php

namespace Kompo\Interactions\Actions;

trait RunJsActions
{
    public function run($jsFunction)
    {
        return $this->prepareAction('runJs', [
            'jsFunction' => $jsFunction
        ]);
    }
}