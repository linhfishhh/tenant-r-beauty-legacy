<?php

namespace App\Events\Menu;

use App\Menu;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MenuSaved
{
    use Dispatchable, SerializesModels;

    /** @var Menu $model */
    public $model;
    public function __construct(Menu $model)
    {
        $this->model = $model;
    }
}
