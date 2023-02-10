<?php

namespace App\Events\Role;

use App\Role;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoleDeleted
{
    use Dispatchable, SerializesModels;

    /** @var Role $model */
    public $model;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Role $model)
    {
        $this->model = $model;
    }
}
