<?php

namespace Kompo\Broadcasting;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class KompoEvent implements ShouldBroadcast
{
    public $data;

    protected $channelName;
    protected $channelType;
    protected $eventName;

    public function __construct(string $channel, array $data = [], string $eventName = null)
    {
        $this->channelName = $channel;
        $this->data = $data;
        $this->eventName = $eventName;
        $this->channelType = 'private';
    }

    public static function on(string $channel): static
    {
        return new static($channel);
    }

    public function asPublic(): static
    {
        $this->channelType = 'public';

        return $this;
    }

    public function asPresence(): static
    {
        $this->channelType = 'presence';

        return $this;
    }

    public function with(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function as(string $eventName): static
    {
        $this->eventName = $eventName;

        return $this;
    }

    public function broadcastOn(): Channel
    {
        return match ($this->channelType) {
            'public' => new Channel($this->channelName),
            'presence' => new PresenceChannel($this->channelName),
            default => new PrivateChannel($this->channelName),
        };
    }

    public function broadcastAs(): string
    {
        return $this->eventName ?? 'KompoEvent';
    }

    public function broadcastWith(): array
    {
        return $this->data;
    }

    public function send(): void
    {
        broadcast($this);
    }

    public function sendNow(): void
    {
        broadcast($this)->toOthers();
    }
}
