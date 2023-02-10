<?php

namespace App\Events;


use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class SocialRegisterSuccessNoAccount
{
    use Dispatchable, SerializesModels;

    public $request;
    public $social_user;

    public function __construct(Request $request, \Laravel\Socialite\Contracts\User $social_user)
    {
        $this->request = $request;
        $this->social_user = $social_user;
    }
}