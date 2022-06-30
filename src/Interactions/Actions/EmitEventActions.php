<?php

namespace Kompo\Interactions\Actions;

trait EmitEventActions
{
    /**
     * Emits a Vue event to the parent <b>Komponent</b>.
     * You may add an optional payload as the event's first parameter.
     *
     * @param string     $event The event name
     * @param array|null $data  The optional additional data
     *
     * @return self
     */
    public function emit($event, $data = null)
    {
        return $this->prepareAction('emitFrom', [
            'event'       => $event,
            'emitPayload' => $data,
        ]);
    }

    /**
     * Emits a regular Vue event to it's parent <b>Element</b>. This is useful for custom Elements.
     * You may add an optional payload as the event's first parameter.
     *
     * @param string     $event The event name
     * @param array|null $data  The optional additional data
     *
     * @return self
     */
    public function emitDirect($event, $data = null)
    {
        return $this->prepareAction('emitDirect', [
            'event'       => $event,
            'emitPayload' => $data,
        ]);
    }

    /**
     * Emits a root Vue event. This is useful for custom Elements.
     * You may add an optional payload as the event's first parameter.
     *
     * @param string     $event The event name
     * @param array|null $data  The optional additional data
     *
     * @return self
     */
    public function emitRoot($event, $data = null)
    {
        return $this->prepareAction('emitRoot', [
            'event'       => $event,
            'emitPayload' => $data,
        ]);
    }

    /**
     * Closes a modal.
     * //TODO DOCUMENT.
     *
     * @return self
     */
    public function closeModal($modalName = null)
    {
        //TODO refactor and consolidate for Vue3
        if ($modalName) {
            return $this->prepareAction('closeModal', [
                'closeModalName' => $modalName,
            ]);
        }

        return $this->emit('closeModal');
    }

    /**
     * Closes a panel.
     * //TODO DOCUMENT.
     *
     * @return self
     */
    public function closePanel()
    {
        return $this->emit('closePanel');
    }

    /**
     * Closes a sidebar.
     * //TODO DOCUMENT.
     *
     * @return self
     */
    public function closeSidebar()
    {
        return $this->emit('closeSidebar');
    }

    /**
     * Confirms a modal dialog.
     * //TODO DOCUMENT.
     *
     * @return self
     */
    public function confirmSubmit()
    {
        return $this->emit('confirmSubmit');
    }
}
