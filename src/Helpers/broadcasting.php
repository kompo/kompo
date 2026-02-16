<?php

use Kompo\Broadcasting\KompoEvent;

if (!function_exists('kompo_broadcast')) {
    /**
     * Broadcast a Kompo event to a channel.
     *
     * @param string       $channel     Channel name (e.g., "order.123")
     * @param string|array $eventOrData Event name or data array
     * @param array        $data        Event payload data
     *
     * @return \Kompo\Broadcasting\KompoEvent
     */
    function kompo_broadcast(string $channel, $eventOrData = [], array $data = [])
    {
        if (is_array($eventOrData)) {
            $event = new KompoEvent($channel, $eventOrData);
        } else {
            $event = new KompoEvent($channel, $data, $eventOrData);
        }

        broadcast($event);

        return $event;
    }
}

if (!function_exists('kompo_broadcast_public')) {
    /**
     * Broadcast a Kompo event to a public channel.
     *
     * @param string       $channel     Channel name
     * @param string|array $eventOrData Event name or data array
     * @param array        $data        Event payload data
     *
     * @return \Kompo\Broadcasting\KompoEvent
     */
    function kompo_broadcast_public(string $channel, $eventOrData = [], array $data = [])
    {
        if (is_array($eventOrData)) {
            $event = new KompoEvent($channel, $eventOrData);
        } else {
            $event = new KompoEvent($channel, $data, $eventOrData);
        }

        $event->asPublic()->send();

        return $event;
    }
}
