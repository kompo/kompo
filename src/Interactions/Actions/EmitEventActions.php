<?php

namespace Kompo\Interactions\Actions;

use Kompo\Core\KompoAction;
use Kompo\Core\KompoTarget;
use Kompo\Routing\RouteFinder;

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

    /**
     * Broadcasts a socket event to all connected clients on the given channel.
     * The broadcast config is encrypted so it cannot be tampered with from the frontend.
     *
     * @param string      $channel The channel name (e.g., "order.123")
     * @param string|null $event   The event name (e.g., "StatusChanged")
     * @param array       $data    The event payload data
     *
     * @return self
     */
    public function emitSocketEvent($channel, $event = null, $data = [])
    {
        $broadcastConfig = json_encode([
            'channel' => $channel,
            'event' => $event,
            'data' => $data,
        ]);

        $this->applyToElement(function ($el) {
            $el->class('cursor-pointer');
        });

        return $this->prepareAction('axiosRequest', [
            'route' => RouteFinder::getKompoRoute('POST'),
            'routeMethod' => 'POST',
            KompoAction::$key => 'broadcast-event',
            KompoTarget::$key => KompoTarget::getEncrypted($broadcastConfig),
        ]);
    }

    /**
     * Broadcasts a socket event to a public channel (no auth required).
     *
     * @param string      $channel The channel name
     * @param string|null $event   The event name
     * @param array       $data    The event payload data
     *
     * @return self
     */
    public function emitPublicSocketEvent($channel, $event = null, $data = [])
    {
        $broadcastConfig = json_encode([
            'channel' => $channel,
            'event' => $event,
            'data' => $data,
            'type' => 'public',
        ]);

        $this->applyToElement(function ($el) {
            $el->class('cursor-pointer');
        });

        return $this->prepareAction('axiosRequest', [
            'route' => RouteFinder::getKompoRoute('POST'),
            'routeMethod' => 'POST',
            KompoAction::$key => 'broadcast-event',
            KompoTarget::$key => KompoTarget::getEncrypted($broadcastConfig),
        ]);
    }
}
