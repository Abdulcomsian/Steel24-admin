<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class NewBidPlaced implements ShouldBroadcast
{
    use SerializesModels;

    public $bid;
    public $customer;

    public function __construct($bid , $customer)
    {
        $this->bid = $bid;
        $this->customer = $customer;
    }

    public function broadcastOn()
    {
        return new Channel('bid-placed');
    }

    public function broadcastAs()
    {
        return 'bid.placed';
    }
}