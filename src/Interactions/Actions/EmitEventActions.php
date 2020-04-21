<?php

namespace Kompo\Interactions\Actions;

trait EmitEventActions
{
    /**
     * Emits a Vue event when clicked with an optional payload as the event's first parameter.
     *
     * @param      string  $event  The event name
     * @param      array|null  $data   The optional additional data
     *
     * @return     self  
     */
    public function emit($event, $data = null)
    {
        return $this->prepareAction('emitFrom', [
	        'event' => $event,
	        'emitPayload' => $data
	    ]);
    }

    /********** INTERNAL USE *********************/
    //Internal use only for now. To emit from vl-button, vl-link
    public function emitDirect($event)
    {
        return $this->prepareAction('emitDirect', [
            'event' => $event
        ]);
    }
    
}