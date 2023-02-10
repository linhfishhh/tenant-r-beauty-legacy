<?php

namespace Modules\ModHairWorld\Entities\ThemeConfigPages;


use App\Classes\BackendSettingPageWithFieldInput;
use App\Classes\FieldGroup;
use App\Classes\FieldInput\FieldInputFile;
use App\Classes\FieldInput\FieldInputFontAwesome;
use App\Classes\FieldInput\FieldInputPost;
use App\Classes\FieldInput\FieldInputRepeater;
use App\Classes\FieldInput\FieldInputText;
use App\Classes\FieldInput\FieldInputTouchSpin;
use Modules\ModHairWorld\Entities\PostTypes\News;

class Salon extends BackendSettingPageWithFieldInput
{

    protected function slug(): string
    {
        return 'theme_config_salon';
    }

    protected function menuTitle(): string
    {
        return 'Chi tiết salon';
    }

    protected function menuIcon(): string
    {
        return 'icon-home';
    }

    protected function menuOrder(): int
    {
        return 0;
    }

    protected function permissionTitle(): string
    {
        return 'Cấu hình giao diện trang chi tiết salon';
    }

    protected function permissionOrder(): int
    {
        return 99;
    }

    public function getParentMenuSlug()
    {
        return 'theme_config';
    }

    protected function pageTitle(): string
    {
        return 'Cấu hình giao diện trang chi tiết salon';
    }

    /**
     * @return array|FieldGroup[]
     */
    protected function fieldGroups(): array
    {
        $settings = [
            'theme_salon_service_limit' => 10,
        ];
        $settings = getSettings($settings);
        return [
            new FieldGroup(
                'Page headline',
                [
                    new FieldInputTouchSpin(
                        'theme_salon_service_limit',
                        $settings['theme_salon_service_limit'],
                        'Giới hạn dịch vụ hiển thị',
                        '',
                        true,
                        [
                            'min' => 1,
                            'max' => 9999,
                            'step' => 1,
                            'decimals' => 0,
                            'postfix' => 'Dịch vụ'
                        ]
                    ),
                ]
            ),
        ];
    }
}