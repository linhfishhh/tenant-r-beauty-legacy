<?php

namespace Modules\ModHairWorld\Listeners;


use Modules\ModHairWorld\Entities\SalonReviewLikeSpamFilter;
use Modules\ModHairWorld\Notifications\CommonNotify;

class ReviewLikeCreated
{
    function handle(\Modules\ModHairWorld\Events\ReviewLikeCreated $event){
        $user =$event->model->user;
        $review = $event->model->review;
        $service = $review->service;
        $salon = $service->salon;
        $auth = $review->user;
        $color = [
            '#00A69C',
            '#FF5C39'
        ];
        $target_id = $review->id;
        $user_id = $user->id;
        $no_spam = SalonReviewLikeSpamFilter::createFilter($target_id, $user_id);
        if(!$no_spam){
            return;
        }
        if ($auth) {
            $auth->notify(new CommonNotify(
                $user->avatar_id ? $user->avatar_id : false,
                "<strong>{$user->name}</strong> cảm thấy đánh giá và nhận xét của bạn về dịch vụ <strong>\"{$service->name}\"</strong> của salon <strong>\"{$salon->name}\"</strong> hữu ích!",
                false,
                '#FF5C39',
                true,
                'like_review',
                'Đánh giá được yêu thích',
                false,
                '',
                [
                    'user_id' => $user->id,
                    'liked' => true,
                    'review_id' => $review->id
                ]
            ));
        }
    }
}