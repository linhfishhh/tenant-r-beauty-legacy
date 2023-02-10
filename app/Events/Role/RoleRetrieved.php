<?php

namespace App\Events\Role;

use App\Role;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoleRetrieved
{
    use Dispatchable, SerializesModels;

    /** @var Role $model */
    public $model;
    public function __construct($model)
    {
        $this->model = $model;
    }
}
