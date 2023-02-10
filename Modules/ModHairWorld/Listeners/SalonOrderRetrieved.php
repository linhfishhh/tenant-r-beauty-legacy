<?php
namespace Modules\ModHairWorld\Listeners;

use Carbon\Carbon;
use Modules\ModHairWorld\Entities\SalonOrder;
use Modules\ModHairWorld\Http\Controllers\OneSignalController;
use Modules\ModHairWorld\Notifications\CommonNotify;

class SalonOrderRetrieved
{
    function handle(\Modules\ModHairWorld\Events\SalonOrderRetrieved $event){
        $salon_order = $event->model;
        if($salon_order->status == SalonOrder::_CHO_XU_LY_){
            try{
                $timeout = getSetting('booking_accept_timeout', 120);
                if(!is_numeric($timeout)){
                    $timeout = 120;
                }
                $timeout = round($timeout);
                $limit = $timeout;
                $timeout = $salon_order->created_at->diffInMinutes(Carbon::now(), false);
                if($timeout > $limit){
                    $salon_order->status = SalonOrder::_HUY_DO_QUA_HAN_XU_LY;
                    $salon_order->save();

                    $this->notifySalonOrderExprired($salon_order);
                }
            }
            catch (\Exception $exception){

            }
        }
    }

    private function notifySalonOrderExprired(SalonOrder $order){
        $salon = $order->salon;
        $salon->notify(new CommonNotify(
            $salon->cover_id?$salon->cover_id:false,
            "Đơn đặt chỗ #{$order->id} tại {$salon->name} đã quá hạn mà không được salon duyệt và đã bị huỷ bởi hệ thống.",
            '',
            '#FF5C39',
            true,
            'order_expired',
            'Đơn đặt chỗ bị huỷ bởi hệ thống',
            false,
            '',
            [
                'salon_id' => $salon->id,
                'order_id' => $order->id,
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => 'Đơn đặt chỗ đã quá hạn xử lý cho phép mà salon vẫn chưa xử lý và đã bị huỷ tự động bởi hệ thống.',
                    ]
                ],
            ]
        ));
    }
}