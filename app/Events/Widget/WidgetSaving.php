<?php

namespace App\Events\Widget;

use App\Widget;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WidgetSaving
{
    use Dispatchable, SerializesModels;
    /** @var Widget $model */
    public $model;
    public function __construct(Widget $model)
    {
        $this->model = $model;
    }
}
