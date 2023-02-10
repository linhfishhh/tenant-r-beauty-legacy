<?php

namespace App\Events;

use App\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class SocialRegisterSuccess
{
    use Dispatchable, SerializesModels;

    public $request;
    public $social_user;
    public $user;

    public function __construct(Request $request, \Laravel\Socialite\Contracts\User $social_user, User $user)
    {
        $this->request = $request;
        $this->social_user = $social_user;
        $this->user = $user;
    }
}
