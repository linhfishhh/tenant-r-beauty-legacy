<?php
namespace Modules\ModHairWorld\Listeners;


use Modules\ModHairWorld\Entities\SalonOrder;
use Modules\ModHairWorld\Entities\SalonOrderItem;
use Modules\ModHairWorld\Http\Controllers\OneSignalController;
use Modules\ModHairWorld\Notifications\CommonNotify;

class SalonOrderUpdated
{
    function handle(\Modules\ModHairWorld\Events\SalonOrderUpdated $event){

        $order = $event->model;
        if($order->isDirty('status')){
            $from_status = $order->getOriginal('status');
            $order->loadMissing(['salon', 'user']);
            $user = $order->user;
            $salon = $order->salon;
            if(!$salon || !$user){
                return;
            }
            switch ($order->status){
                case SalonOrder::_CHO_XU_LY_:
                    $this->notifySalonNewOrder($order);
                    $this->notifyUserNewOrder($order);
                    break;
                case SalonOrder::_CHO_THANH_TOAN_:
                    $this->notifyUserOrderAcceptedPayOnline($order);
                    break;
                case SalonOrder::_CHO_THUC_HIEN_:
                    if($from_status == 1){
                        $this->notifyUserOrderPaid($order);
                        $this->notifySalonOrderPaid($order);
                    }
                    else{
                        $this->notifyUserOrderAcceptedPayAtSalon($order);
                    }
                    //$this->remindUser($order);
                    break;
                case SalonOrder::_DA_HOAN_THANH_:
                    $this->notifyUserOrderFinished($order);
                    break;
                case SalonOrder::_KHACH_KHONG_DEN_:
                    $this->notifyUserNotCome($order);
                    break;
                case SalonOrder::_HUY_BOI_KHACH_:
                    $this->notifySalonOrderCanceled($order);
                    $this->notifyUserOrderCanceled($order);
                    break;
                case SalonOrder::_HUY_BOI_SALON_:
                    $this->notifyUserOrderRejected($order);
                    break;
                case SalonOrder::_HUY_DO_QUA_HAN_XU_LY:
                    $this->notifyUserOrderExprired($order);
                    break;
            }

            /** @var SalonOrderItem[] $items */
            $items = $order->items;
            foreach ($items as $item){
                $service = $item->service;
                if($service){
                    $service->cacheBookingCount();
                }
            }
            $salon->cacheBookingCount();
        }

    }

    private function remindUser(SalonOrder $order){
        $user = $order->user;
        $salon = $order->salon;
        $old_id = $order->reminder_id;
        if($old_id){
            $client = OneSignalController::getCustomerClient();
            $client->deleteNotification($old_id);
        }
        $user->notify(new CommonNotify(
            $salon->cover_id?$salon->cover_id:false,
            "",
            route('frontend.account.history'),
            '#FF5C39',
            true,
            'remind',
            "Bạn có dịch vụ sẽ thực hiện vào lúc {$order->service_time->format('H:i')} hôm nay tại {$salon->name}. Vui lòng có mặt đúng giờ để salon phục vụ bạn nhé",
            false,
            '',
            [
                'salon_id' => $salon->id,
                'order_id' => $order->id,
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => "",
                    ]
                ],
            ]
        ));
    }

    private function notifyUserNotCome(SalonOrder $order){
        $user = $order->user;
        $salon = $order->salon;
        $user->notify(new CommonNotify(
            $salon->cover_id?$salon->cover_id:false,
            "{$salon->name} thông báo rằng bạn đã không đến thực hiện dịch vụ đúng ngày giờ đã đặt như trong đơn đặt chỗ #{$order->id}.",
            route('frontend.account.history'),
            '#FF5C39',
            true,
            'customer_not_Come',
            'Không thể thực hiện',
            false,
            '',
            [
                'salon_id' => $salon->id,
                'order_id' => $order->id,
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => "",
                    ]
                ],
            ]
        ));
    }

    private function notifyUserOrderRejected(SalonOrder $order){
        $user = $order->user;
        $salon = $order->salon;
        $note = $order->note;
        if(!$note){
            $note = 'Lý do riêng tư';
        }
        $user->notify(new CommonNotify(
            $salon->cover_id?$salon->cover_id:false,
            "{$salon->name} không thể tiếp nhận đơn đặt chỗ mã số #{$order->id} của bạn với thông điệp: \"{$note}\"",
            route('frontend.account.history'),
            '#FF5C39',
            true,
            'order_rejected',
            'Đơn đặt chỗ bị từ chối',
            false,
            '',
            [
                'salon_id' => $salon->id,
                'order_id' => $order->id,
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => "Salon đã không thể tiếp nhận yêu cầu đặt chỗ của bạn với thông điệp: \"{$note}\"",
                    ]
                ],
            ]
        ));
    }

    private function notifyUserOrderFinished(SalonOrder $order){
        $user = $order->user;
        $salon = $order->salon;
        $user->notify(new CommonNotify(
            $salon->cover_id?$salon->cover_id:false,
            "Đơn đặt chỗ mã số #{$order->id} của bạn tại {$salon->name} đã được thực hiện hoàn thành. Cám ơn bạn đã sử dụng dịch vụ của chúng tôi!",
            route('frontend.account.history'),
            '#FF5C39',
            true,
            'order_finished',
            'Đơn đặt chỗ hoàn tất',
            false,
            '',
            [
                'salon_id' => $salon->id,
                'order_id' => $order->id,
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => 'Salon đã hoàn thành đơn đặt chỗ này. Cám ơn bạn đã sử dịch vụ của chúng tôi!',
                    ]
                ],
            ]
        ));
    }

    private function notifyUserOrderCanceled(SalonOrder $order){
        $user = $order->user;
        $salon = $order->salon;
        $user->notify(new CommonNotify(
            $salon->cover_id?$salon->cover_id:false,
            "Bạn đã huỷ đơn đặt chỗ mã số #{$order->id} tại {$salon->name} thành công!",
            route('frontend.account.history'),
            '#FF5C39',
            true,
            'order_canceled',
            'Đơn đặt chỗ bị huỷ',
            false,
            '',
            [
                'salon_id' => $salon->id,
                'order_id' => $order->id,
                'note' => 'Bạn đã huỷ đơn đặt chỗ này!',
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => 'Bạn đã huỷ đơn đặt chỗ này!',
                    ]
                ],
            ]
        ));
    }

    private function notifySalonOrderCanceled(SalonOrder $order){
        $user = $order->user;
        $salon = $order->salon;
        $salon->notify(new CommonNotify(
            $user->avatar_id?$user->avatar_id:false,
            "Khách hàng {$user->name} đã huỷ bỏ một đơn đặt chỗ mang mã số #{$order->id}!",
            '',
            '#FF5C39',
            true,
            'order_canceled',
            'Đơn đặt chỗ bị huỷ',
            false,
            '',
            [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => "Đơn đặt hàng đã bị huỷ bởi khách hàng!",
                    ]
                ],
            ]
        ));
    }

    private function notifyUserOrderPaid(SalonOrder $order){
        $user = $order->user;
        $salon = $order->salon;
        $user->notify(new CommonNotify(
            $salon->cover_id?$salon->cover_id:false,
            "Đơn đặt chỗ mã số #{$order->id} tại {$salon->name} đã được thanh toán thành công, vui lòng có mặt lúc {$order->service_time->format('H:i d/m/Y')} để salon phục vụ cho bạn nhé!",
            route('frontend.account.history'),
            '#FF5C39',
            true,
            'order_accept_paid',
            'Đơn đặt chỗ đã được thanh toán',
            false,
            '',
            [
                'salon_id' => $salon->id,
                'order_id' => $order->id,
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => 'Đơn đặt chỗ của quý khách đã được thanh toán, vui lòng đến đúng ngày giờ đã đặt để salon phục vụ cho bạn nhé!',
                    ]
                ],
            ]
        ));
    }

    private function notifySalonOrderPaid(SalonOrder $order){
        $user = $order->user;
        $salon = $order->salon;
        $salon->notify(new CommonNotify(
            $user->avatar_id?$user->avatar_id:false,
            "Khách hàng {$user->name} đã thanh toán đơn đặt chỗ mã số #{$order->id}, vui lòng chuẩn bị phục vụ khách hàng của bạn lúc {$order->service_time->format('H:i d/m/Y')} nhé!",
            route('frontend.account.history'),
            '#FF5C39',
            true,
            'order_accept_paid',
            'Đơn đặt chỗ đã được thanh toán',
            false,
            '',
            [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => 'Khách đã thanh toán, vui lòng chuẩn bị vào ngày giờ khách đã đặt để phục vụ thật tốt nhé!',
                    ]
                ],
            ]
        ));
    }

    private function notifyUserOrderExprired(SalonOrder $order){
        $user = $order->user;
        $salon = $order->salon;
        $user->notify(new CommonNotify(
            $salon->cover_id?$salon->cover_id:false,
            "Đơn đặt chỗ #{$order->id} tại {$salon->name} đã quá hạn mà không được salon duyệt và đã bị huỷ bởi hệ thống.",
            route('frontend.account.history'),
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

    private function notifyUserOrderAcceptedPayAtSalon(SalonOrder $order){
        $user = $order->user;
        $salon = $order->salon;
        $user->notify(new CommonNotify(
            $salon->cover_id?$salon->cover_id:false,
            "Đơn đặt chỗ mã số #{$order->id} tại {$salon->name} đã được chấp nhận, vui lòng có mặt lúc {$order->service_time->format('H:i d/m/Y')} để salon phục vụ cho bạn nhé!",
            route('frontend.account.history'),
            '#FF5C39',
            true,
            'order_accept_pay_at_salon',
            'Đơn đặt chỗ được chấp nhận',
            false,
            '',
            [
                'salon_id' => $salon->id,
                'order_id' => $order->id,
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => 'Yêu cầu đặt chỗ của bạn đã được chấp thuận, vui lòng có mặt tại salon đúng ngày giờ đã đặt để salon phục vụ cho bạn nhé!',
                    ]
                ],
            ]
        ));
    }

    private function notifyUserOrderAcceptedPayOnline(SalonOrder $order){
        $user = $order->user;
        $salon = $order->salon;
        $user->notify(new CommonNotify(
            $salon->cover_id?$salon->cover_id:false,
            "Đơn đặt chỗ mã số #{$order->id} tại {$salon->name} đã được chấp nhận, vui lòng thanh toán để tiếp tục nhé.",
            route('frontend.account.history'),
            '#FF5C39',
            true,
            'order_accept_pay_online',
            'Đơn đặt chỗ chờ thanh toán',
            false,
            '',
            [
                'salon_id' => $salon->id,
                'order_id' => $order->id,
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => 'Yêu cầu đặt chỗ của bạn đã được chấp nhận, vui lòng tiến hành thanh toán bằng cách nhấn nút "Thanh toán" bên dưới nhé',
                    ]
                ],
            ]
        ));
    }

    private function notifyUserNewOrder(SalonOrder $order){
        $color = [
            '#00A69C',
            '#FF5C39'
        ];
        $user = $order->user;
        $salon = $order->salon;
        $user->notify(new CommonNotify(
            $salon->cover_id?$salon->cover_id:false,
            "Bạn vừa tạo một đơn đặt chỗ mang mã số #{$order->id} tại {$salon->name}. Vui lòng chờ salon duyệt đơn đặt chỗ của bạn nhé!",
            route('frontend.account.history'),
            '#FF5C39',
            true,
            'order_created',
            'Đơn đặt chỗ mới được tạo',
            false,
            '',
            [
                'salon_id' => $salon->id,
                'order_id' => $order->id,
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => 'Yêu cầu đặt chỗ của bạn đã được chuyển tới salon, vui lòng chờ salon duyệt yêu cầu của bạn nhé!',
                    ]
                ],
            ]
        ));
    }

    private function notifySalonNewOrder(SalonOrder $order){
        $color = [
            '#00A69C',
            '#FF5C39'
        ];
        $user = $order->user;
        $salon = $order->salon;

        $salon->notify(new CommonNotify(
            $user->avatar_id?$user->avatar_id:false,
            "Khách hàng {$user->name} vừa tạo một đơn đặt chỗ mang mã số #{$order->id}. Vui lòng xét duyệt đơn đặt chỗ. Hạn chót: {$order->created_at->addMinute(SalonOrder::getProcessTimeOut())->format('H:i d/m/Y')}",
            '',
            '#FF5C39',
            true,
            'order_created',
            'Đơn đặt chỗ mới chờ duyệt',
            false,
            '',
            [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => "Vui lòng sử lý yêu cầu đặt chỗ này trước {$order->created_at->addMinute(SalonOrder::getProcessTimeOut())->format('H:i d/m/Y')}",
                    ]
                ],
            ]
        ));
    }
}