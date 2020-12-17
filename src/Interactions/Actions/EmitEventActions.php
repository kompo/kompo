<?php

namespace Kompo\Interactions\Actions;

trait EmitEventActions
{
    /**
     * Emits a Vue event to the parent <b>Komposer</b>.
     * You may add an optional payload as the event's first parameter.
     *
     * @param  string  $event  The event name
     * @param  array|null  $data   The optional additional data
     *
     * @return self  
     */
    public function emit($event, $data = null)
    {
        return $this->prepareAction('emitFrom', [
	        'event' => $event,
	        'emitPayload' => $data
	    ]);
    }

    /**
     * Emits a regular Vue event to it's parent <b>Komponent</b>. This is useful for custom Komponents.
     * You may add an optional payload as the event's first parameter.
     *
     * @param  string  $event  The event name
     * @param  array|null  $data   The optional additional data
     *
     * @return self  
     */
    public function emitDirect($event)
    {
        return $this->prepareAction('emitDirect', [
            'event' => $event
        ]);
    }

    /**
     * Closes a modal.
     *
     * @return self
     */
    public function closeModal()
    {
        return $this->emit('closeModal');
    }
    
}