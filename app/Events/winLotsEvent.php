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
    // public $customerId;
    // public $lotId;
    public $detail;
    public $customer;
    public $success;
    public $autoBid;
    public $customerData;

    public function __construct($message, $detail,$customer,$success, $autoBid)
    {
        $this->message = $message;
        $this->detail = $detail;
        $this->customer=$customer;
        $this->success=$success;
        $this->autoBid = $autoBid;
        $this->customerData = $customer;
    }

    public function broadcastWith()
    {
        return [
            'autoBid' => $this->autoBid,
            'customerData' => $this->customerData,
        ];
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
