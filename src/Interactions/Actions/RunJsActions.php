<?php

namespace Kompo\Interactions\Actions;

use Kompo\Core\IconGenerator;

trait RunJsActions
{
    public function run($jsFunction)
    {
        return $this->prepareAction('runJs', [
            'jsFunction' => $jsFunction
        ]);
    }
}