<?php

namespace App\Events\Role;

use App\Role;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoleSaved
{
    use Dispatchable, SerializesModels;

    /** @var Role $model */
    public $model;
    public function __construct($model)
    {
        $this->model = $model;
    }
}
