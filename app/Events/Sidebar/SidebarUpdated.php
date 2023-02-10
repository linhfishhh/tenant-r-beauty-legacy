<?php

namespace App\Events\Sidebar;

use App\Sidebar;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SidebarUpdated
{
    use Dispatchable, SerializesModels;
    /** @var Sidebar $model */
    public $model;
    public function __construct(Sidebar $model)
    {
        $this->model = $model;
    }
}
