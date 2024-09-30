<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;
use App\Models\Generalsetting;
class BackInStock
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    private $product;
    private $storeSettings;
    public function __construct(Product $product, Generalsetting $storeSettings)
    {
        $this->product = $product; 
        $this->storeSettings = $storeSettings;
    }
    public function product()
    {
        return $this->product;
    }
    public function storeSettings()
    {
        return $this->storeSettings;
    }
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
