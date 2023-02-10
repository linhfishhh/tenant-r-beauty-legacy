<?php

namespace Modules\ModHairWorld\Entities\AdminConfigPages;


use App\Classes\BackendSettingPageWithFieldInput;
use App\Classes\FieldGroup;
use App\Classes\FieldInput\FieldInputRepeater;
use App\Classes\FieldInput\FieldInputSelect;
use App\Classes\FieldInput\FieldInputText;
use App\Classes\FieldInput\FieldInputTouchSpin;

class BookingFlowConfigs extends BackendSettingPageWithFieldInput
{

    protected function slug(): string
    {
        return 'booking_flow';
    }

    public function getParentMenuSlug()
    {
        return 'salons';
    }

    protected function menuTitle(): string
    {
        return 'Cấu hình quy trình và sms';
    }

    protected function menuIcon(): string
    {
        return 'icon-coins';
    }

    protected function menuOrder(): int
    {
       return 99999;
    }

    protected function permissionTitle(): string
    {
        return __('Cấu hình quy trình và sms');
    }

    protected function permissionOrder(): int
    {
        return 99999;
    }

    protected function pageTitle(): string
    {
        return __('Cấu hình quy trình và sms');
    }

    /**
     * @return array|FieldGroup[]
     */
    protected function fieldGroups(): array
    {
        $setting = [
            'booking_accept_timeout' => 120,
            'booking_change_time_limit' => 12,
            'booking_change_limit' => 0,
            'booking_manager_cancel_reasons' => [],
            'notification_interval' => 0,
            'sms_interval' => 0,
            'verify_code_life' => 10,
            'booking_limit' => 3
        ];
        $setting = getSettings($setting);
        return [
            new FieldGroup(__('Quy trình - chủ salon'), [
                new FieldInputTouchSpin(
                    'booking_accept_timeout',
                    $setting['booking_accept_timeout']*1,
                    __('Thời gian chờ duyệt tối đa'),
                    'Quá thời gian này nếu chủ salon không duyệt hoặc từ chối thì đơn hàng sẽ tự huỷ.',
                    true,
                    [
                        'min' => 10,
                        'max' => 60*12,
                        'step' => 1,
                        'decimals' => 0,
                        'postfix' => 'PHÚT'
                    ]
                ),
                new FieldInputRepeater(
                    'booking_manager_cancel_reasons',
                    $setting['booking_manager_cancel_reasons'],
                    __('Các lý do huỷ đơn hàng'),
                    '',
                    true,
                    FieldInputRepeater::buildConfigs([
                        new FieldInputText(
                            'content',
                            '',
                            'Nội dung',
                            '',
                            true,
                            FieldInputText::buildConfigs('Nhập lý do huỷ')
                        )
                    ], 'Thêm', 'Lý do huỷ')
                ),
            ]),
            new FieldGroup(__('Quy trình - khách hàng'), [
                new FieldInputTouchSpin(
                    'booking_change_limit',
                    $setting['booking_change_limit']*1,
                    __('Số lần đổi giờ thực hiện cho phép'),
                    '',
                    true,
                    [
                        'min' => 1,
                        'max' => 9,
                        'step' => 1,
                        'decimals' => 0,
                        'postfix' => 'LẦN'
                    ]
                ),
                new FieldInputTouchSpin(
                    'booking_change_time_limit',
                    $setting['booking_change_time_limit']*1,
                    __('Chỉ cho phép đổi giờ trước khi thực hiện dịch vụ'),
                    '*Cho phép đổi giờ nhưng vẫn phải thông qua salon duyệt. Một khi salon duyệt thì yêu cầu đổi giờ mới được thông qua.',
                    true,
                    [
                        'min' => 1,
                        'max' => 24,
                        'step' => 1,
                        'decimals' => 0,
                        'postfix' => 'GIỜ'
                    ]
                ),
            ]),
            new FieldGroup(__('Spam Filter'), [
                new FieldInputTouchSpin(
                    'notification_interval',
                    $setting['notification_interval']*1.0,
                    __('Khoảng thời gian giữa các thông báo'),
                    'Dành cho các thông báo like tránh spam thông báo làm phiền người dùng',
                    true,
                    [
                        'min' => 0,
                        'max' => 60*24,
                        'step' => 1,
                        'decimals' => 0,
                        'postfix' => 'PHÚT'
                    ]
                ),
                new FieldInputTouchSpin(
                    'booking_limit',
                    $setting['booking_limit']*1,
                    __('Số đơn đặt chỗ tối đa'),
                    'Giới hạn số lượng booking mỗi khác một ngày, 0: tắt chức năng',
                    true,
                    [
                        'min' => 0,
                        'max' => 365,
                        'step' => 1,
                        'decimals' => 0,
                        'postfix' => 'ĐƠN/NGÀY'
                    ]
                ),
            ]),
            new FieldGroup('SMS Verify', [
                new FieldInputTouchSpin(
                    'sms_interval',
                    $setting['sms_interval']*1.0,
                    __('Giới hạn thời gian xác nhận'),
                    'Set giới hạn thời gian giữa những lần gửi tin nhắn xác nhận (set 0 là không giới hạn, -1 là tắt hẳn chức năng)',
                    true,
                    [
                        'min' => -1,
                        'max' => 60*24,
                        'step' => 1,
                        'decimals' => 0,
                        'postfix' => 'PHÚT'
                    ]
                ),
                new FieldInputTouchSpin(
                    'verify_code_life',
                    $setting['verify_code_life']*1.0,
                    __('Thời hạn sử dụng mã xác nhận'),
                    'Sau khoảng thời gian này mã xác nhận sẽ không còn có tác dụng',
                    true,
                    [
                        'min' => 5,
                        'max' => 60*24,
                        'step' => 1,
                        'decimals' => 0,
                        'postfix' => 'PHÚT'
                    ]
                ),
            ])
        ];
    }
}