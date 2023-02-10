<?php

namespace Modules\ModHairWorld\Listeners;


use Modules\ModHairWorld\Notifications\CommonNotify;

class SalonLikeDeleted
{
    function handle(\Modules\ModHairWorld\Events\SalonLikeDeleted $event){
//        $salon = $event->model->salon;
//        $user = $event->model->user;
//        $color = [
//            '#00A69C',
//            '#FF5C39'
//        ];
//        $salon->notify(new CommonNotify(
//            $user->avatar_id?$user->avatar_id:false,
//            "<strong>{$user->name}</strong> đã không còn thích salon bạn nữa",
//            false,
//            '#00A69C',
//            false
//        ));
    }
}