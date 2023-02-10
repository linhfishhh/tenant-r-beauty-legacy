<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BeforeHtmlBlock
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $block_id;
    public $route_name;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($block_id)
    {
        $this->block_id = $block_id;
        $this->route_name = \Route::currentRouteName();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
