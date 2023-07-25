<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class winLotsEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $customerId;
    public $lotId;

    public function __construct($message, $customerId, $lotId)
    {
        $this->message = $message;
        $this->customerId = $customerId;
        $this->lotId = $lotId;
    }

    public function broadcastOn()
    {
        return new Channel('steel24');
    }

    public function broadcastAs()
    {
        return 'win-lots checking';
    }
}
