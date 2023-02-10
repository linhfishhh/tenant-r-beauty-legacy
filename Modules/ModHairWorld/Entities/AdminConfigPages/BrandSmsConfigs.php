<?php

namespace Modules\ModHairWorld\Entities\AdminConfigPages;


use App\Classes\BackendSettingPageWithFieldInput;
use App\Classes\FieldGroup;
use App\Classes\FieldInput\FieldInputBoolean;
use App\Classes\FieldInput\FieldInputSelect;
use App\Classes\FieldInput\FieldInputText;

class BrandSmsConfigs extends BackendSettingPageWithFieldInput
{

    protected function slug(): string
    {
        return 'brandsms_configs';
    }


    protected function menuTitle(): string
    {
        return 'Cấu hình Brandsms';
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
        return __('Quản lý cấu hình brandsms.vn api');
    }

    protected function permissionOrder(): int
    {
        return 99;
    }

    protected function pageTitle(): string
    {
        return __('Cấu hình brandsms.vn api');
    }

    /**
     * @return array|FieldGroup[]
     */
    protected function fieldGroups(): array
    {
        $setting = [
            'brandsms_disable' => true,
            'brandsms_token' => '',
            'brandsms_brandname' => 'iSalon.vn',
//            'brandsms_endpoint' => 'https://secure.brandsms.vn/vmgapi.asmx'
            'brandsms_endpoint' => 'https://api.brandsms.vn'
        ];
        $setting = getSettings($setting);
        return [
            new FieldGroup(__('Cấu hình tích hợp'), [
                new FieldInputBoolean(
                    'brandsms_disable',
                    $setting['brandsms_disable'],
                    'Bỏ qua xác thực SMS',
                    '',
                    false
                ),
                new FieldInputSelect(
                    'brandsms_endpoint',
                    $setting['brandsms_endpoint'],
                    __('API Endpoint'),
                    '',
                    true,
                    FieldInputSelect::buildConfigs([
                        'https://api.brandsms.vn' => 'Secure with ssl',
                        'http://api.brandsms.vn' => 'Normal without ssl'
                    ], false)
                ),
                new FieldInputText(
                    'brandsms_token',
                    $setting['brandsms_token'],
                    __('Token'),
                    '',
                    true,
                    FieldInputText::buildConfigs(__('Nhập token'))
                ),
                new FieldInputText(
                    'brandsms_brandname',
                    $setting['brandsms_brandname'],
                    __('Brand Name'),
                    '',
                    true,
                    FieldInputText::buildConfigs(__('Nhập brand name đã được đăng ký với các nhà mạng'))
                )
            ]),
        ];
    }
}