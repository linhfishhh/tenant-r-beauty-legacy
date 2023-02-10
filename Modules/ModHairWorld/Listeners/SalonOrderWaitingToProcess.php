<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/21/18
 * Time: 16:12
 */

namespace Modules\ModHairWorld\Listeners;


use Modules\ModHairWorld\Notifications\CommonNotify;

class SalonOrderWaitingToProcess
{
    function handle(\Modules\ModHairWorld\Events\SalonOrderWaitingToProcess $event){
        $booking = $event->model;
        $booking->load(['user', 'salon']);
        $user = $booking->user;
        $salon = $booking->salon;
        $end = $booking->updated_at->addHour(2);
        $salon->notify(new CommonNotify(
            $user->avatar_id?$user->avatar_id:false,
            "<strong>Đơn đặt chỗ #{$booking->id}</strong> đang chờ salon bạn xử lý. Bạn có 2 giờ để xử lý đơn đặt chỗ này (hạn chót: {$end->format('H:i d/m/Y')})",
            false,
            '#FF5C39',
            true,
            'new_order',
            'Đơn đặt chỗ chờ xử lý',
            false,
            '',
            [
                'user_id' => $user->id,
                'order_id' => $booking->id
            ]
        ));
    }
}