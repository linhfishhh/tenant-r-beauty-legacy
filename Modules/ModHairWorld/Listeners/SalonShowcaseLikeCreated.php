<?php

namespace Modules\ModHairWorld\Listeners;


use Modules\ModHairWorld\Entities\SalonShowcaseLikeSpamFilter;
use Modules\ModHairWorld\Notifications\CommonNotify;

class SalonShowcaseLikeCreated
{
    function handle(\Modules\ModHairWorld\Events\SalonShowcaseLikeCreated $event){
        $showcase = $event->model->showcase;
        $showcase->load(['items', 'items.image']);
        $salon = $showcase->salon;
        $user = $event->model->user;
        $color = [
            '#00A69C',
            '#FF5C39'
        ];
        $target_id = $showcase->id;
        $user_id = $user->id;
        $no_spam = SalonShowcaseLikeSpamFilter::createFilter($target_id, $user_id);
        if(!$no_spam){
            return;
        }
        $salon->notify(new CommonNotify(
            $user->avatar_id?$user->avatar_id:false,
            "<strong>{$user->name}</strong> đã thêm tác phẩm \"<strong>{$showcase->name}</strong>\" của salon bạn vào danh sách những tác phẩm yêu thích của mình!",
            false,
            '#FF5C39',
            true,
            'like_showcase',
            'Tác phẩm được yêu thích',
            false,
            '',
            [
                'user_id' => $user->id,
                'liked' => true,
                'showcase_id' => $showcase->id
            ]
        ));
    }
}