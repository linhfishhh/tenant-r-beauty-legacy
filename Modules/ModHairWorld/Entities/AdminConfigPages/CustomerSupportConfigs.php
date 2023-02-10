<?php

namespace Modules\ModHairWorld\Entities\AdminConfigPages;


use App\Classes\BackendSettingPageWithFieldInput;
use App\Classes\FieldGroup;
use App\Classes\FieldInput\FieldInputBoolean;
use App\Classes\FieldInput\FieldInputSelect;
use App\Classes\FieldInput\FieldInputText;

class CustomerSupportConfigs extends BackendSettingPageWithFieldInput
{

    protected function slug(): string
    {
        return 'customer_support_configs';
    }


    protected function menuTitle(): string
    {
        return 'Cấu hình CSKH';
    }

    protected function menuIcon(): string
    {
        return 'icon-hammer-wrench';
    }

    protected function menuOrder(): int
    {
       return 999;
    }

    protected function permissionTitle(): string
    {
        return __('Quản lý cấu hình CSKH');
    }

    protected function permissionOrder(): int
    {
        return 99;
    }

    protected function pageTitle(): string
    {
        return __('Cấu hình CSKH');
    }

    /**
     * @return array|FieldGroup[]
     */
    protected function fieldGroups(): array
    {
        $setting = [
            'send_mail_new_booking_order' => true,
            'booking_order_recipients' => '',
            'send_mail_new_shop_order' => true,
            'shop_order_recipients' => '',
        ];
        $setting = getSettings($setting);
        return [
            new FieldGroup(__('Cấu hình gửi mail thông báo đặt lịch'), [
                new FieldInputBoolean(
                    'send_mail_new_booking_order',
                    $setting['send_mail_new_booking_order'],
                    'Gửi mail khi có lịch đặt mới',
                    '',
                    false
                ),
                new FieldInputText(
                    'booking_order_recipients',
                    $setting['booking_order_recipients'],
                    __('Danh sách mail nhận'),
                    '',
                    false,
                    FieldInputText::buildConfigs(__('Nhập email, ngăn cách bằng dấu ;'))
                ),
            ]),
            new FieldGroup(__('Cấu hình gửi mail thông báo đơn mua hàng mới'), [
                new FieldInputBoolean(
                    'send_mail_new_shop_order',
                    $setting['send_mail_new_shop_order'],
                    'Gửi mail khi có đơn mua hàng mới',
                    '',
                    false
                ),
                new FieldInputText(
                    'shop_order_recipients',
                    $setting['shop_order_recipients'],
                    __('Danh sách mail nhận'),
                    '',
                    false,
                    FieldInputText::buildConfigs(__('Nhập email, ngăn cách bằng dấu ;'))
                ),
            ]),
        ];
    }
}