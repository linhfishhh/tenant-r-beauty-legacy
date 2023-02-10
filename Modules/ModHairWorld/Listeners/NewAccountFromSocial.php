<?php

namespace Modules\ModHairWorld\Listeners;


use App\Events\SocialRegisterSuccessNoAccount;
use Modules\ModHairWorld\Entities\UserSocialTemp;

class NewAccountFromSocial
{
    function handle(SocialRegisterSuccessNoAccount $event){
        \Session::flash('social_new_account', $event->social_user);
        $user = $event->social_user;
        UserSocialTemp::getQuery()->where('email', $user->email)->delete();
        $temp = new UserSocialTemp();
        $temp->email = $user->email;
        $temp->name = $user->name;
        $temp->token = $user->token;
        $temp->save();
    }
}