<?php

namespace App\Events\User;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class UserUpdateRequestMessages
{
    use Dispatchable, SerializesModels;
    public $request;
    public $messages;
    public function __construct(Request $request, array $messages)
    {
        $this->request = $request;
        $this->messages = $messages;
    }
}
