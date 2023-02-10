<?php

namespace App\Events\ThemeMenu;

use App\ThemeMenu;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ThemeMenuCreated
{
    use Dispatchable, SerializesModels;

    /** @var ThemeMenu $model */
    public $model;
    public function __construct($model)
    {
        $this->model = $model;
    }
}
