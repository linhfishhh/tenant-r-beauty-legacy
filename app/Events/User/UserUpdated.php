<?php

namespace App\Events\User;

use App\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var User $model */
    public $model;
    public function __construct($model)
    {
        $this->model = $model;
    }
}
