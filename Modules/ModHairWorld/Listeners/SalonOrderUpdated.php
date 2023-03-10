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
            "B???n c?? d???ch v??? s??? th???c hi???n v??o l??c {$order->service_time->format('H:i')} h??m nay t???i {$salon->name}. Vui l??ng c?? m???t ????ng gi??? ????? salon ph???c v??? b???n nh??",
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
            "{$salon->name} th??ng b??o r???ng b???n ???? kh??ng ?????n th???c hi???n d???ch v??? ????ng ng??y gi??? ???? ?????t nh?? trong ????n ?????t ch??? #{$order->id}.",
            route('frontend.account.history'),
            '#FF5C39',
            true,
            'customer_not_Come',
            'Kh??ng th??? th???c hi???n',
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
            $note = 'L?? do ri??ng t??';
        }
        $user->notify(new CommonNotify(
            $salon->cover_id?$salon->cover_id:false,
            "{$salon->name} kh??ng th??? ti???p nh???n ????n ?????t ch??? m?? s??? #{$order->id} c???a b???n v???i th??ng ??i???p: \"{$note}\"",
            route('frontend.account.history'),
            '#FF5C39',
            true,
            'order_rejected',
            '????n ?????t ch??? b??? t??? ch???i',
            false,
            '',
            [
                'salon_id' => $salon->id,
                'order_id' => $order->id,
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => "Salon ???? kh??ng th??? ti???p nh???n y??u c???u ?????t ch??? c???a b???n v???i th??ng ??i???p: \"{$note}\"",
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
            "????n ?????t ch??? m?? s??? #{$order->id} c???a b???n t???i {$salon->name} ???? ???????c th???c hi???n ho??n th??nh. C??m ??n b???n ???? s??? d???ng d???ch v??? c???a ch??ng t??i!",
            route('frontend.account.history'),
            '#FF5C39',
            true,
            'order_finished',
            '????n ?????t ch??? ho??n t???t',
            false,
            '',
            [
                'salon_id' => $salon->id,
                'order_id' => $order->id,
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => 'Salon ???? ho??n th??nh ????n ?????t ch??? n??y. C??m ??n b???n ???? s??? d???ch v??? c???a ch??ng t??i!',
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
            "B???n ???? hu??? ????n ?????t ch??? m?? s??? #{$order->id} t???i {$salon->name} th??nh c??ng!",
            route('frontend.account.history'),
            '#FF5C39',
            true,
            'order_canceled',
            '????n ?????t ch??? b??? hu???',
            false,
            '',
            [
                'salon_id' => $salon->id,
                'order_id' => $order->id,
                'note' => 'B???n ???? hu??? ????n ?????t ch??? n??y!',
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => 'B???n ???? hu??? ????n ?????t ch??? n??y!',
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
            "Kh??ch h??ng {$user->name} ???? hu??? b??? m???t ????n ?????t ch??? mang m?? s??? #{$order->id}!",
            '',
            '#FF5C39',
            true,
            'order_canceled',
            '????n ?????t ch??? b??? hu???',
            false,
            '',
            [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => "????n ?????t h??ng ???? b??? hu??? b???i kh??ch h??ng!",
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
            "????n ?????t ch??? m?? s??? #{$order->id} t???i {$salon->name} ???? ???????c thanh to??n th??nh c??ng, vui l??ng c?? m???t l??c {$order->service_time->format('H:i d/m/Y')} ????? salon ph???c v??? cho b???n nh??!",
            route('frontend.account.history'),
            '#FF5C39',
            true,
            'order_accept_paid',
            '????n ?????t ch??? ???? ???????c thanh to??n',
            false,
            '',
            [
                'salon_id' => $salon->id,
                'order_id' => $order->id,
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => '????n ?????t ch??? c???a qu?? kh??ch ???? ???????c thanh to??n, vui l??ng ?????n ????ng ng??y gi??? ???? ?????t ????? salon ph???c v??? cho b???n nh??!',
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
            "Kh??ch h??ng {$user->name} ???? thanh to??n ????n ?????t ch??? m?? s??? #{$order->id}, vui l??ng chu???n b??? ph???c v??? kh??ch h??ng c???a b???n l??c {$order->service_time->format('H:i d/m/Y')} nh??!",
            route('frontend.account.history'),
            '#FF5C39',
            true,
            'order_accept_paid',
            '????n ?????t ch??? ???? ???????c thanh to??n',
            false,
            '',
            [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => 'Kh??ch ???? thanh to??n, vui l??ng chu???n b??? v??o ng??y gi??? kh??ch ???? ?????t ????? ph???c v??? th???t t???t nh??!',
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
            "????n ?????t ch??? #{$order->id} t???i {$salon->name} ???? qu?? h???n m?? kh??ng ???????c salon duy???t v?? ???? b??? hu??? b???i h??? th???ng.",
            route('frontend.account.history'),
            '#FF5C39',
            true,
            'order_expired',
            '????n ?????t ch??? b??? hu??? b???i h??? th???ng',
            false,
            '',
            [
                'salon_id' => $salon->id,
                'order_id' => $order->id,
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => '????n ?????t ch??? ???? qu?? h???n x??? l?? cho ph??p m?? salon v???n ch??a x??? l?? v?? ???? b??? hu??? t??? ?????ng b???i h??? th???ng.',
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
            "????n ?????t ch??? m?? s??? #{$order->id} t???i {$salon->name} ???? ???????c ch???p nh???n, vui l??ng c?? m???t l??c {$order->service_time->format('H:i d/m/Y')} ????? salon ph???c v??? cho b???n nh??!",
            route('frontend.account.history'),
            '#FF5C39',
            true,
            'order_accept_pay_at_salon',
            '????n ?????t ch??? ???????c ch???p nh???n',
            false,
            '',
            [
                'salon_id' => $salon->id,
                'order_id' => $order->id,
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => 'Y??u c???u ?????t ch??? c???a b???n ???? ???????c ch???p thu???n, vui l??ng c?? m???t t???i salon ????ng ng??y gi??? ???? ?????t ????? salon ph???c v??? cho b???n nh??!',
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
            "????n ?????t ch??? m?? s??? #{$order->id} t???i {$salon->name} ???? ???????c ch???p nh???n, vui l??ng thanh to??n ????? ti???p t???c nh??.",
            route('frontend.account.history'),
            '#FF5C39',
            true,
            'order_accept_pay_online',
            '????n ?????t ch??? ch??? thanh to??n',
            false,
            '',
            [
                'salon_id' => $salon->id,
                'order_id' => $order->id,
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => 'Y??u c???u ?????t ch??? c???a b???n ???? ???????c ch???p nh???n, vui l??ng ti???n h??nh thanh to??n b???ng c??ch nh???n n??t "Thanh to??n" b??n d?????i nh??',
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
            "B???n v???a t???o m???t ????n ?????t ch??? mang m?? s??? #{$order->id} t???i {$salon->name}. Vui l??ng ch??? salon duy???t ????n ?????t ch??? c???a b???n nh??!",
            route('frontend.account.history'),
            '#FF5C39',
            true,
            'order_created',
            '????n ?????t ch??? m???i ???????c t???o',
            false,
            '',
            [
                'salon_id' => $salon->id,
                'order_id' => $order->id,
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => 'Y??u c???u ?????t ch??? c???a b???n ???? ???????c chuy???n t???i salon, vui l??ng ch??? salon duy???t y??u c???u c???a b???n nh??!',
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
            "Kh??ch h??ng {$user->name} v???a t???o m???t ????n ?????t ch??? mang m?? s??? #{$order->id}. Vui l??ng x??t duy???t ????n ?????t ch???. H???n ch??t: {$order->created_at->addMinute(SalonOrder::getProcessTimeOut())->format('H:i d/m/Y')}",
            '',
            '#FF5C39',
            true,
            'order_created',
            '????n ?????t ch??? m???i ch??? duy???t',
            false,
            '',
            [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => "Vui l??ng s??? l?? y??u c???u ?????t ch??? n??y tr?????c {$order->created_at->addMinute(SalonOrder::getProcessTimeOut())->format('H:i d/m/Y')}",
                    ]
                ],
            ]
        ));
    }
}