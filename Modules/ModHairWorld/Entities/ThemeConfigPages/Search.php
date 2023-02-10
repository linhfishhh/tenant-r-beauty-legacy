<?php

namespace Modules\ModHairWorld\Entities\ThemeConfigPages;


use App\Classes\BackendSettingPageWithFieldInput;
use App\Classes\FieldGroup;
use App\Classes\FieldInput\FieldInputFile;
use App\Classes\FieldInput\FieldInputFontAwesome;
use App\Classes\FieldInput\FieldInputPost;
use App\Classes\FieldInput\FieldInputRepeater;
use App\Classes\FieldInput\FieldInputText;
use Modules\ModHairWorld\Entities\PostTypes\News;

class Search extends BackendSettingPageWithFieldInput
{

    protected function slug(): string
    {
        return 'theme_config_search';
    }

    protected function menuTitle(): string
    {
        return 'Tiếm kiếm';
    }

    protected function menuIcon(): string
    {
        return 'icon-search4';
    }

    protected function menuOrder(): int
    {
        return 0;
    }

    protected function permissionTitle(): string
    {
        return 'Cấu hình giao diện trang tìm kiếm';
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
        return 'Cấu hình giao diện trang tìm kiếm';
    }

    /**
     * @return array|FieldGroup[]
     */
    protected function fieldGroups(): array
    {
        $settings = [
            'theme_search_headline' => 'Hơn 2500 salon trên toàn quốc',
            'theme_search_headline_bg' => false,
            'theme_search_headline_bg_mb' => false,
            'theme_search_link' => '#',
        ];
        $settings = getSettings($settings);
        return [
            new FieldGroup(
                'Page headline',
                [
                    new FieldInputText(
                        'theme_search_headline',
                        $settings['theme_search_headline'],
                        'Tiêu đề headline',
                        '',
                        false,
                        FieldInputText::buildConfigs()
                    ),
                    new FieldInputFile(
                        'theme_search_headline_bg',
                        $settings['theme_search_headline_bg'],
                        'Ảnh nền headline',
                        false,
                        false,
                        FieldInputFile::buildConfigs('Chọn ảnh', 'Chọn ảnh',['theme_files'], ['image'])
                    ),
                    new FieldInputFile(
                        'theme_search_headline_bg_mb',
                        $settings['theme_search_headline_bg_mb'],
                        'Ảnh nền headline - mobile',
                        false,
                        false,
                        FieldInputFile::buildConfigs('Chọn ảnh', 'Chọn ảnh',['theme_files'], ['image'])
                    ),
                    new FieldInputText(
                        'theme_search_link',
                        $settings['theme_search_link'],
                        'Liên kết',
                        '',
                        false,
                        FieldInputText::buildConfigs()
                    ),
                ]
            ),
        ];
    }
}