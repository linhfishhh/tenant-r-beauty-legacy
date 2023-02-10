<?php

namespace Modules\ModHairWorld\Listeners;


use Modules\ModHairWorld\Notifications\CommonNotify;

class ReviewCreated
{
    function handle(\Modules\ModHairWorld\Events\ReviewCreated $event){
        $model = $event->model;
        $model->load(['user', 'service', 'service.salon']);
        $salon = $model->service->salon;
        $service = $model->service;
        $user = $model->user;
        $salon->notify(new CommonNotify(
            $user->avatar_id?$user->avatar_id:false,
            "<strong>{$user->name}</strong> đã viết một nhận xét đánh giá cho dịch vụ \"{$service->name}\" của salon bạn",
            false,
            '#FF5C39',
            true,
            'new_review',
            'Khách viết nhận xét đánh giá',
            false,
            '',
            [
                'user_id' => $user->id,
                'review_id' => $model->id,
                'route' => [
                    'review_list',
                    [
                    ]
                ],
            ]
        ));
    }
}