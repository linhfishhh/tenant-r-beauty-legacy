<?php

namespace Modules\ModHairWorld\Listeners;


use Modules\ModHairWorld\Notifications\CommonNotify;

class ChangeTimeRequestCreated
{
    function handle(\Modules\ModHairWorld\Events\ChangeTimeRequestCreated $event){
        $change = $event->model;
        $booking = $change->order;
        $salon = $booking->salon;
        $user = $booking->user;
        $salon->notify(new CommonNotify(
            $user->avatar_id?$user->avatar_id:false,
            "<strong>{$user->name}</strong> đã yêu cầu đổi thời gian thực hiện đơn đặt chỗ #{$booking->id}.",
            false,
            '#FF5C39',
            true,
            'change_request',
            'Yêu cầu đổi thời gian thực hiện',
            false,
            '',
            [
                'user_id' => $user->id,
                'order_id' => $booking->id,
                'route' => [
                    'approve_change',
                    [
                        'id' => $change->id,
                        'order_id' => $booking->id
                    ]
                ]
            ]
        ));
    }
}