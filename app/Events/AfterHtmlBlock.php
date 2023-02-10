<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AfterHtmlBlock
{
    use Dispatchable, SerializesModels;

    public $block_id;
    public $route_name;

    /**
     * Create a new event instance.
     *
     * @param $block_id
     */
    public function __construct($block_id)
    {
        $this->block_id = $block_id;
        $this->route_name = \Route::currentRouteName();
    }
}
