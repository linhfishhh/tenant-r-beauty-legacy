<?php

namespace Modules\ModHairWorld\Listeners;


use Modules\ModHairWorld\Notifications\CommonNotify;

class SalonShowcaseLikeDeleted
{
    function handle(\Modules\ModHairWorld\Events\SalonShowcaseLikeDeleted $event){
//        $showcase = $event->model->showcase;
//        $salon = $showcase->salon;
//        $user = $event->model->user;
//        $color = [
//            '#00A69C',
//            '#FF5C39'
//        ];
//        $salon->notify(new CommonNotify(
//            $user->avatar_id?$user->avatar_id:false,
//            "<strong>{$user->name}</strong> không còn thích thích tác phẩm <strong>{$showcase->name}</strong> của salon bạn",
//            false,
//            '#00A69C'
//        ));
    }
}