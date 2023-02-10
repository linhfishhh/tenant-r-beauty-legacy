<?php

namespace App\Events\User;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class UserStoreRequestAuthorize
{
    use Dispatchable, SerializesModels;
    public $request;
    public $authorize;
    public function __construct(Request $request, $authorize)
    {
        $this->request = $request;
        $this->authorize = $authorize;
    }
}
