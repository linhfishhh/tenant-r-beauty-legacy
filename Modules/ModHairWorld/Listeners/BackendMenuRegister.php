<?php

namespace Modules\ModHairWorld\Listeners;

use App\Classes\BackendMenuItem;
use App\Events\BackendMenuItemRegister;

class BackendMenuRegister
{

    public function handle(BackendMenuItemRegister $event)
    {
        $items = [
            new BackendMenuItem(
                'salons',
                __('Quản lý salon'),
                null,
                '#',
                'icon-store',[
                    'manage_salons',
                ],0,0),
            new BackendMenuItem(
                'salon_list',
                __('Danh sách salon'),
                'salons',
                'backend.salon.index',
                'icon-store2',[
                    'manage_salons',
                ],0,0),
            new BackendMenuItem(
                'booking_list',
                __('Danh sách đơn đặt chỗ'),
                'salons',
                'backend.bookings.index',
                'icon-store',[
                    'manage_salons',
                ],0,1),

            // new BackendMenuItem(
            //     'top_salon_list',
            //     __('Danh sách top salon nam'),
            //     'salons',
            //     'backend.top_salons.index',
            //     'icon-store2',[
            //     'manage_salons',
            // ],0,1), 
            new BackendMenuItem(
                'top_salons_list',
                __('Danh sách salon tuỳ chọn'),
                'salons',
                'backend.list_salons.indexcustom',
                'icon-store2',[
                    'manage_salons',
                ],0,1), 
            new BackendMenuItem(
                'theme_config',
                __('Cấu hình giao diện'),
                'frontend',
                '#12',
                'icon-magic-wand',
                [],
                false,
                99),
        ];
        $event->register(
            $items
        );

//        $event->register([
//            new BackendMenuItem(
//                'salon_register',
//                __('Quản lý đăng ký chủ salon'),
//                'salons',
//                'backend.salon_register.index',
//                'icon-profile',
//                [
//                    'manage_salon_register'
//                ],
//                false,
//                1
//            )
//        ]);

        $event->register([
            new BackendMenuItem(
                'topsalons',
                __('Test'),
                'salons',
                '#',
                'icon-profile',
                [
                    'manage_option_promo_configs'
                ],
                false,
                1
            )
        ]);

        $event->register([
            new BackendMenuItem(
                'promo',
                __('Promotion Campaign'),
                'salons',
                '#',
                'icon-profile',
                [
                    'manage_option_promo_configs'
                ],
                false,
                1
            )
        ]);

        $event->register([
            new BackendMenuItem(
                'promo_salon',
                __('Salon tham gia'),
                'promo',
                'backend.promo_salons.index',
                'icon-profile',
                [
                    'manage_option_promo_configs'
                ],
                false,
                2
            )
        ]);
        
        
        $event->register([
            new BackendMenuItem(
                'salon_tools',
                __('Công cụ hỗ trợ'),
                'salons',
                '#',
                'icon-hammer-wrench',
                [
                    'salon_import_tool'
                ],
                false,
                9999998
            )
        ]);

        $event->register([
            new BackendMenuItem(
                'salon_tool_import',
                __('Import thông tin salon'),
                'salon_tools',
                'salon_tools.import',
                'icon-import',
                [
                    'salon_import_tool'
                ],
                false,
                0
            )
        ]);
        $event->register([
            new BackendMenuItem(
                'salon_send_noti',
                __('Push notification'),
                'salon_tools',
                'backend.notification.index',
                'icon-paperplane',
                [
                    'manage_salons',
                ],
                false,
                0
            )
        ]);
    }
}
