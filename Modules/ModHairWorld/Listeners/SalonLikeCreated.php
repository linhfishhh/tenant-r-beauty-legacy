<?php

namespace Modules\ModHairWorld\Listeners;


use Modules\ModHairWorld\Entities\SalonLikeSpamFilter;
use Modules\ModHairWorld\Notifications\CommonNotify;

class SalonLikeCreated
{
    function handle(\Modules\ModHairWorld\Events\SalonLikeCreated $event){
        $salon = $event->model->salon;
        $user = $event->model->user;
        $color = [
            '#00A69C',
            '#FF5C39'
        ];
        $target_id = $salon->id;
        $user_id = $user->id;
        $no_spam = SalonLikeSpamFilter::createFilter($target_id, $user_id);
        if(!$no_spam){
            return;
        }
        $salon->notify(new CommonNotify(
            $user->avatar_id?$user->avatar_id:false,
            "<strong>{$user->name}</strong> đã thêm salon của bạn vào danh sách yêu thích của mình!",
            false,
            '#FF5C39',
            true,
            'like_salon',
            'Salon được yêu thích',
            false,
            '',
            [
                'user_id' => $user->id,
                'liked' => true
            ]
        ));
    }
}