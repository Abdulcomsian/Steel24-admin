<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LotStatusUpdate
{
    use Dispatchable, InteractsWithSockets, SerializesModels , ShouldBroadcast;
    public $lot;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($lot)
    {
        $this->lot = $lot;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('lot-updated');
    }
}
