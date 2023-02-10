<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserFilterQuery
{
    use Dispatchable, SerializesModels;

    public $query;
    public $request;

    public function __construct($query, $request)
    {
        $this->query = $query;
        $this->request = $request;
    }
}
