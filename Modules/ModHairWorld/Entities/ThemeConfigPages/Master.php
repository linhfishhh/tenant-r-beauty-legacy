<?php

namespace Modules\ModHairWorld\Entities\ThemeConfigPages;


use App\Classes\BackendSettingPageWithFieldInput;
use App\Classes\FieldGroup;
use App\Classes\FieldInput\FieldInputFontAwesome;
use App\Classes\FieldInput\FieldInputPost;
use App\Classes\FieldInput\FieldInputRepeater;
use App\Classes\FieldInput\FieldInputText;
use Modules\ModHairWorld\Entities\PostTypes\News;

class Master extends BackendSettingPageWithFieldInput
{

    protected function slug(): string
    {
        return 'theme_config_master';
    }

    protected function menuTitle(): string
    {
        return 'Toàn cục';
    }

    protected function menuIcon(): string
    {
        return 'icon-cogs';
    }

    protected function menuOrder(): int
    {
        return 0;
    }

    protected function permissionTitle(): string
    {
        return 'Cấu hình giao diện toàn cục';
    }

    protected function permissionOrder(): int
    {
        return 98;
    }

    public function getParentMenuSlug()
    {
        return 'theme_config';
    }

    protected function pageTitle(): string
    {
        return 'Cấu hình giao diện toàn cục';
    }

    /**
     * @return array|FieldGroup[]
     */
    protected function fieldGroups(): array
    {
        $settings = [
            'theme_master_site_title' => 'Thế Giới Tóc',
            'theme_master_site_desc' => 'Thế Giới Tóc',
            'theme_master_quy_dinh' => false,
            'theme_master_chinh_sach' => false,
            'theme_master_copyright' => '© 2018 isalon.vn',
            'theme_master_mobile_app_android' => '#',
            'theme_master_mobile_app_ios' => '#',
            'theme_master_social_links' => []
        ];
        $settings = getSettings($settings);
        return [
            new FieldGroup(
                'Cấu hình chung',
                [
                    new FieldInputText(
                        'theme_master_site_title',
                        $settings['theme_master_site_title'],
                        'Tiêu đề website',
                        '',
                        true,
                        FieldInputText::buildConfigs()
                    ),
                    new FieldInputText(
                        'theme_master_site_desc',
                        $settings['theme_master_site_desc'],
                        'Mô tả website',
                        '',
                        true,
                        FieldInputText::buildConfigs()
                    ),
                    new FieldInputPost(
                        'theme_master_quy_dinh',
                        $settings['theme_master_quy_dinh'],
                        'Trang điều khoản sử dụng',
                        false,
                        false,
                        FieldInputPost::buildConfigs(false,false,News::class)
                        )
                    ,
                    new FieldInputPost(
                        'theme_master_chinh_sach',
                        $settings['theme_master_chinh_sach'],
                        'Trang chính sách bảo mật',
                        false,
                        false,
                        FieldInputPost::buildConfigs(false,false,News::class)
                    ),
                    new FieldInputText(
                        'theme_master_copyright',
                        $settings['theme_master_copyright'],
                        'Dòng bản quyền',
                        '',
                        false,
                        FieldInputText::buildConfigs()
                    ),
                ]
            ),
            new FieldGroup('Mạng xã hội', [
                new FieldInputRepeater(
                    'theme_master_social_links',
                    $settings['theme_master_social_links'],
                    'Các link mạng xã hội',
                    '',
                    false,
                    FieldInputRepeater::buildConfigs([
                        new FieldInputFontAwesome(
                            'icon',
                            '',
                            'Icon',
                            '',
                            true,
                            FieldInputFontAwesome::buildConfigs()
                        ),
                        new FieldInputText(
                            'link',
                            '',
                            'Link',
                            '',
                            true,
                            FieldInputText::buildConfigs()
                        ),
                    ])
                )
            ]),
            new FieldGroup(
                'Mobile Apps',
                [
                    new FieldInputText(
                        'theme_master_mobile_app_android',
                        $settings['theme_master_mobile_app_android'],
                        'Link app Android',
                        '',
                        true,
                        FieldInputText::buildConfigs()
                    ),
                    new FieldInputText(
                        'theme_master_mobile_app_ios',
                        $settings['theme_master_mobile_app_ios'],
                        'Link app IOS',
                        '',
                        true,
                        FieldInputText::buildConfigs()
                    ),
                ]),
        ];
    }
}