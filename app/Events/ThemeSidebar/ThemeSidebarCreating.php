<?php

namespace App\Events\ThemeSidebar;

use App\ThemeSidebar;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ThemeSidebarCreating
{
    use Dispatchable, SerializesModels;
    /** @var ThemeSidebar $model */
    public $model;
    public function __construct(ThemeSidebar $model)
    {
        $this->model = $model;
    }
}
