<?php

namespace App\Events\User;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class UserStoreRequestRules
{
    use Dispatchable, SerializesModels;
    public $request;
    public $rules;
    public function __construct(Request $request, array $rules)
    {
        $this->request = $request;
        $this->rules = $rules;
    }
}
