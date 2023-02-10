<?php

namespace Modules\ModHairWorld\Entities\AdminConfigPages;


use App\Classes\BackendSettingPageWithFieldInput;
use App\Classes\FieldGroup;
use App\Classes\FieldInput\FieldInputSelect;
use App\Classes\FieldInput\FieldInputText;

class NganLuongConfigs extends BackendSettingPageWithFieldInput
{

    protected function slug(): string
    {
        return 'nganluong_configs';
    }

    protected function menuTitle(): string
    {
        return 'Cấu hình nganluong.vn';
    }

    protected function menuIcon(): string
    {
        return 'icon-coins';
    }

    protected function menuOrder(): int
    {
       return 0;
    }

    protected function permissionTitle(): string
    {
        return __('Quản lý cấu hình ngân lượng');
    }

    protected function permissionOrder(): int
    {
        return 99;
    }

    protected function pageTitle(): string
    {
        return __('Cấu hình nganluong.vn');
    }

    /**
     * @return array|FieldGroup[]
     */
    protected function fieldGroups(): array
    {
        $setting = [
            'nl_user' => '',
            'nl_pass' => '',
            'nl_email' => '',
            'nl_link' => 'https://sandbox.nganluong.vn:8088/nl35/checkout.php'
        ];
        $setting = getSettings($setting);
        return [
            new FieldGroup(__('Cấu hình tích hợp'), [
                new FieldInputSelect(
                    'nl_link',
                    $setting['nl_link'],
                    __('Môi trường hoạt động'),
                    '',
                    false,
                    FieldInputSelect::buildConfigs([
                        'https://www.nganluong.vn/checkout.php' => 'LIVE',
                        'https://sandbox.nganluong.vn:8088/nl30/checkout.php' => 'SANDBOX'
                    ], false)
                ),
                new FieldInputText(
                    'nl_user',
                    $setting['nl_user'],
                    __('ID Kết nối'),
                    '',
                    false,
                    FieldInputText::buildConfigs(__('Nhập ID Kết nối'))
                ),
                new FieldInputText(
                    'nl_pass',
                    $setting['nl_pass'],
                    __('Mật khẩu Kết nối'),
                    '',
                    false,
                    FieldInputText::buildConfigs(__('Nhập Mật khẩu Kết nối'))
                ),
                new FieldInputText(
                    'nl_email',
                    $setting['nl_email'],
                    __('Tài khoản hưởng tiền'),
                    '',
                    false,
                    FieldInputText::buildConfigs(__('Nhập tài khoản hưởng tiền'))
                )
            ]),
        ];
    }
}