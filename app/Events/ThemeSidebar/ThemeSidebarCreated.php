<?php

namespace App\Events\ThemeSidebar;

use App\ThemeSidebar;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ThemeSidebarCreated
{
    use Dispatchable, SerializesModels;
    /** @var ThemeSidebar $model */
    public $model;
    public function __construct(ThemeSidebar $model)
    {
        $this->model = $model;
    }
}
