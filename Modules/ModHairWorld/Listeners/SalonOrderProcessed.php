<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/21/18
 * Time: 16:12
 */

namespace Modules\ModHairWorld\Listeners;


use Modules\ModHairWorld\Notifications\CommonNotify;

class SalonOrderProcessed
{
    function handle(\Modules\ModHairWorld\Events\SalonOrderProcessed $event){
        $booking = $event->model;
        $booking->load(['user', 'salon']);
        $user = $booking->user;
        $salon = $booking->salon;
        $user->notify(new CommonNotify(
            $salon->cover_id?$salon->cover_id:false,
            "<strong>Đơn đặt chỗ #{$booking->id}</strong> của bạn đã được salon \"{$salon->name}\" chấp nhận. Xin bạn vui lòng đến salon vào lúc {$booking->service_time->format('H:i d/m/Y')} để salon phục vụ cho bạn!",
            false,
            '#FF5C39',
            true,
            'order_accepted',
            'Đơn đặt chỗ được chấp nhận',
            false,
            '',
            [
                'order_id' => $booking->id,
                'salon_id' => $salon->id
            ]
        ));
    }
}