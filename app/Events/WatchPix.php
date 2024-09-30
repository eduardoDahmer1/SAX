<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
class WatchPix
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    private $order;
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
    public function getOrder()
    {
        return $this->order;
    }
}
