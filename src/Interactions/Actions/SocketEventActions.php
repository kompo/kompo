<?php

namespace Kompo\Interactions\Actions;

use Kompo\Interactions\HigherOrderInteraction;

trait SocketEventActions
{
    /**
     * React to a socket event on a private channel. Returns a HigherOrderInteraction proxy for chaining actions.
     *
     * @param string      $channel The channel name (e.g., "order.123")
     * @param string|null $event   The event name (e.g., "StatusChanged")
     *
     * @return \Kompo\Interactions\HigherOrderInteraction
     */
    public function onSocketEvent($channel, $event = null)
    {
        $socketConfig = ['channel' => $channel, 'type' => 'private'];
        if ($event) {
            $socketConfig['event'] = $event;
        }
        $this->config(['socketEvent' => $socketConfig]);

        return new HigherOrderInteraction($this, 'onSocketEventAction');
    }

    /**
     * React to a socket event on a public channel.
     *
     * @param string      $channel The channel name
     * @param string|null $event   The event name
     *
     * @return \Kompo\Interactions\HigherOrderInteraction
     */
    public function onPublicEvent($channel, $event = null)
    {
        $socketConfig = ['channel' => $channel, 'type' => 'public'];
        if ($event) {
            $socketConfig['event'] = $event;
        }
        $this->config(['socketEvent' => $socketConfig]);

        return new HigherOrderInteraction($this, 'onSocketEventAction');
    }

    /**
     * React to a socket event on a presence channel.
     *
     * @param string      $channel The channel name
     * @param string|null $event   The event name
     *
     * @return \Kompo\Interactions\HigherOrderInteraction
     */
    public function onPresenceEvent($channel, $event = null)
    {
        $socketConfig = ['channel' => $channel, 'type' => 'presence'];
        if ($event) {
            $socketConfig['event'] = $event;
        }
        $this->config(['socketEvent' => $socketConfig]);

        return new HigherOrderInteraction($this, 'onSocketEventAction');
    }

    /**
     * Listen to multiple events on the same channel.
     *
     * @param string $channel The channel name
     * @param array  $events  Array of event names
     *
     * @return \Kompo\Interactions\HigherOrderInteraction
     */
    public function onSocketEvents($channel, array $events)
    {
        $this->config(['socketEvent' => [
            'channel' => $channel,
            'events' => $events,
            'type' => 'private',
        ]]);

        return new HigherOrderInteraction($this, 'onSocketEventAction');
    }

    /**
     * Bind event data directly to element content or field value.
     *
     * @param string      $channel The channel name
     * @param string      $event   The event name
     * @param string      $dataKey The key in the event payload to bind
     * @param string|null $format  Optional format (e.g., 'currency')
     *
     * @return self
     */
    public function bindToEvent($channel, $event, $dataKey, $format = null)
    {
        return $this->config([
            'socketBinding' => [
                'channel' => $channel,
                'event' => $event,
                'dataKey' => $dataKey,
                'format' => $format,
            ],
        ]);
    }

    /**
     * Append event data to this panel/element when events fire.
     *
     * @param string $channel The channel name
     * @param string $event   The event name
     * @param string $dataKey The key in the event payload containing HTML
     *
     * @return self
     */
    public function appendOnEvent($channel, $event, $dataKey = 'html')
    {
        return $this->config([
            'socketStream' => [
                'channel' => $channel,
                'event' => $event,
                'dataKey' => $dataKey,
                'mode' => 'append',
            ],
        ]);
    }

    /**
     * Prepend event data to this panel/element when events fire.
     *
     * @param string $channel The channel name
     * @param string $event   The event name
     * @param string $dataKey The key in the event payload containing HTML
     *
     * @return self
     */
    public function prependOnEvent($channel, $event, $dataKey = 'html')
    {
        return $this->config([
            'socketStream' => [
                'channel' => $channel,
                'event' => $event,
                'dataKey' => $dataKey,
                'mode' => 'prepend',
            ],
        ]);
    }

    /**
     * Show this element when a socket event fires.
     *
     * @param string $channel The channel name
     * @param string $event   The event name
     *
     * @return self
     */
    public function showOnEvent($channel, $event)
    {
        return $this->class('hidden')->config([
            'socketVisibility' => [
                'channel' => $channel,
                'event' => $event,
                'action' => 'show',
            ],
        ]);
    }

    /**
     * Hide this element when a socket event fires.
     *
     * @param string $channel The channel name
     * @param string $event   The event name
     *
     * @return self
     */
    public function hideOnEvent($channel, $event)
    {
        return $this->config([
            'socketVisibility' => [
                'channel' => $channel,
                'event' => $event,
                'action' => 'hide',
            ],
        ]);
    }

    /**
     * Toggle visibility of this element when a socket event fires.
     *
     * @param string $channel The channel name
     * @param string $event   The event name
     *
     * @return self
     */
    public function toggleOnEvent($channel, $event)
    {
        return $this->config([
            'socketVisibility' => [
                'channel' => $channel,
                'event' => $event,
                'action' => 'toggle',
            ],
        ]);
    }

    /**
     * Auto-increment a counter when events fire.
     *
     * @param string      $channel The channel name
     * @param string      $event   The event name
     * @param string|null $dataKey If set, use the event payload value; otherwise increment by 1
     *
     * @return self
     */
    public function liveCount($channel, $event, $dataKey = null)
    {
        return $this->config([
            'socketCounter' => [
                'channel' => $channel,
                'event' => $event,
                'dataKey' => $dataKey,
                'mode' => 'increment',
            ],
        ]);
    }

    /**
     * Auto-decrement a counter when events fire.
     *
     * @param string      $channel The channel name
     * @param string      $event   The event name
     * @param string|null $dataKey If set, use the event payload value; otherwise decrement by 1
     *
     * @return self
     */
    public function liveCountDown($channel, $event, $dataKey = null)
    {
        return $this->config([
            'socketCounterDown' => [
                'channel' => $channel,
                'event' => $event,
                'dataKey' => $dataKey,
                'mode' => 'decrement',
            ],
        ]);
    }

    /**
     * Track presence on a channel (list of online users).
     *
     * @param string $channel The presence channel name
     *
     * @return self
     */
    public function withPresence($channel)
    {
        return $this->config([
            'socketPresence' => [
                'channel' => $channel,
                'mode' => 'list',
            ],
        ]);
    }

    /**
     * Track presence count on a channel.
     *
     * @param string $channel The presence channel name
     *
     * @return self
     */
    public function presenceCount($channel)
    {
        return $this->config([
            'socketPresence' => [
                'channel' => $channel,
                'mode' => 'count',
            ],
        ]);
    }

    /**
     * Send a whisper event on input (client-to-client, no server).
     *
     * @param string $channel   The channel name
     * @param string $eventName The whisper event name
     *
     * @return self
     */
    public function whisperOnInput($channel, $eventName)
    {
        return $this->config([
            'socketWhisper' => [
                'channel' => $channel,
                'event' => $eventName,
                'trigger' => 'input',
            ],
        ]);
    }

    /**
     * Show this element when a whisper event is received.
     *
     * @param string $channel   The channel name
     * @param string $eventName The whisper event name
     *
     * @return self
     */
    public function showOnWhisper($channel, $eventName)
    {
        return $this->class('hidden')->config([
            'socketWhisperListen' => [
                'channel' => $channel,
                'event' => $eventName,
                'action' => 'show',
            ],
        ]);
    }

    /**
     * Hide this element after a delay (useful with showOnWhisper).
     *
     * @param int $delayMs The delay in milliseconds before hiding
     *
     * @return self
     */
    public function hideAfter($delayMs)
    {
        return $this->config([
            'hideAfter' => $delayMs,
        ]);
    }
}
