<?php

namespace Kompo\Interactions\Actions;

trait EmitEventActions
{
    //Internal use only for now. To emit from vl-button, vl-link
    public function emitDirect($event)
    {
        return $this->prepareAction('emitDirect', [
            'event' => $event
        ]);
    }
    
}