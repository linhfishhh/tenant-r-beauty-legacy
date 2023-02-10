<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BackendMenuItemCheckActive
{
    use Dispatchable, SerializesModels;
    
    public $item_id;
    public $include_routes;
    /**
     * Create a new event instance.
     *
     * @param string $item_id
     */
    public function __construct($item_id)
    {
        $this->include_routes = [];
        $this->item_id = $item_id;
    }
}
