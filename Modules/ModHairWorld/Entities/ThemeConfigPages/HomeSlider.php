<?php


namespace Modules\ModHairWorld\Entities\ThemeConfigPages;


use App\Classes\BackendSettingPageWithFieldInput;
use App\Classes\FieldGroup;
use App\Classes\FieldInput\FieldInputBoolean;
use App\Classes\FieldInput\FieldInputFile;
use App\Classes\FieldInput\FieldInputFileCategory;
use App\Classes\FieldInput\FieldInputRepeater;
use App\Classes\FieldInput\FieldInputText;
use App\Classes\FieldInput\FieldInputSelect;
use App\Classes\FieldInput\FieldInputTouchSpin;
use Modules\ModHairWorld\Entities\FieldInput\FieldInputMapLocationLevelOne;

class HomeSlider extends BackendSettingPageWithFieldInput
{

    protected function slug(): string
    {
        return 'theme_config_home_slider';
    }

    protected function menuTitle(): string
    {
        return __('Home sliders');
    }

    public function getParentMenuSlug()
    {
        return 'theme_config';
    }

    protected function menuIcon(): string
    {
        return 'icon-home';
    }

    protected function menuOrder(): int
    {
        return 1;
    }

    protected function permissionTitle(): string
    {
        return __('Chỉnh sửa home slider');
    }

    protected function permissionOrder(): int
    {
        return 99;
    }

    protected function pageTitle(): string
    {
        return __('Home slider');
    }

    /**
     * @return array|FieldGroup[]
     */
    protected function fieldGroups(): array
    {

        $settings = [
            'theme_home_slider' => [],
            'theme_home_slider_speed' => 2500,
            'theme_home_slider_nav_speed' => 500,
            'theme_home_slider_multi_display' => false,
        ];
        $settings = getSettings($settings);

        return [
            new FieldGroup(__('Home Slider'),[
                new FieldInputTouchSpin(
                    'theme_home_slider_speed',
                    $settings['theme_home_slider_speed'],
                    'Thời gian hiển thị mỗi slide',
                    '',
                    true,
                    [
                        'min' => 500,
                        'max' => 9999999999,
                        'postfix' => 'ms'
                    ]
                ),
                new FieldInputTouchSpin(
                    'theme_home_slider_nav_speed',
                    $settings['theme_home_slider_nav_speed'],
                    'Tốc độ chuyển slide',
                    '',
                    true,
                    [
                        'min' => 100,
                        'max' => 9999999999,
                        'postfix' => 'ms'
                    ]
                ),
                new FieldInputBoolean(
                    'theme_home_slider_multi_display',
                    $settings['theme_home_slider_multi_display'],
                    'Hiển thị 3 banner cùng lúc',
                    '',
                    false
                ),
                new FieldInputRepeater(
                    'theme_home_slider',
                    $settings['theme_home_slider'],
                    'Danh sách Slide',
                    '',
                    true,
                    FieldInputRepeater::buildConfigs([
                        new FieldInputFile(
                            'image',
                            null,
                            'Ảnh slider',
                            '',
                            true,
                            FieldInputFile::buildConfigs(
                                'Chon ảnh cho slider',
                                'Chọn ảnh',
                                ['theme_files'],
                                ['image']
                            )
                        ),
                        new FieldInputText(
                            'link',
                            '#',
                            'Liên kết',
                            '',
                            true,
                            FieldInputText::buildConfigs()
                        ),
                        new FieldInputSelect(
                            'banner_type',
                            null,
                            'Loại',
                            '',
                            false,
                            FieldInputSelect::buildConfigs(['booking', 'shop'],  false)
                        )
                    ])
                ),
            ]),
        ];
    }
}