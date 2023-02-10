<?php

namespace Modules\ModHairWorld\Listeners;


use Modules\ModHairWorld\Notifications\CommonNotify;

class ReviewLikeDeleted
{
    function handle(\Modules\ModHairWorld\Events\ReviewLikeDeleted $event){
//        $user =$event->model->user;
//        $review = $event->model->review;
//        $service = $review->service;
//        $salon = $service->salon;
//        $auth = $review->user;
//        $color = [
//            '#00A69C',
//            '#FF5C39'
//        ];
//        $auth->notify(new CommonNotify(
//            $user->avatar_id?$user->avatar_id:false,
//            "<strong>{$user->name}</strong> không còn cảm thấy đánh giá của bạn về dịch vụ <strong>{$service->name}</strong> của salon <strong>{$salon->name}</strong> hữu ích",
//            false,
//            '#00A69C'
//        ));
    }
}