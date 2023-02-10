<?php


namespace Modules\ModHairWorld\Entities\ThemeConfigPages;
use App\Classes\BackendSettingPageWithFieldInput;
use App\Classes\FieldGroup;
use App\Classes\FieldInput\FieldInputBoolean;
use App\Classes\FieldInput\FieldInputFile;
use App\Classes\FieldInput\FieldInputFileCategory;
use App\Classes\FieldInput\FieldInputRepeater;
use App\Classes\FieldInput\FieldInputText;
use App\Classes\FieldInput\FieldInputTinyMCE;
use App\Classes\FieldInput\FieldInputTouchSpin;
use Modules\ModHairWorld\Entities\FieldInput\FieldInputMapLocationLevelOne;

class HomeSliderMobile extends BackendSettingPageWithFieldInput
{

    protected function slug(): string
    {
        return 'theme_config_home_slider_mobile';
    }

    protected function menuTitle(): string
    {
        return __('Home sliders mobile');
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
        return __('Chỉnh sửa home slider mobile');
    }

    protected function permissionOrder(): int
    {
        return 99;
    }

    protected function pageTitle(): string
    {
        return __('Home slider mobile');
    }

    /**
     * @return array|FieldGroup[]
     */
    protected function fieldGroups(): array
    {

        $settings = [
            'theme_home_slider_mobile' => [],
            // 'theme_home_slider_speed_mobile' => 2500,
            // 'theme_home_slider_nav_speed_mobile' => 500,
            // 'theme_home_slider_multi_display_mobile' => false,
        ];
        $settings = getSettings($settings);

        return [
            new FieldGroup(__('Home Slider Mobile'),[
                
                new FieldInputRepeater(
                    'theme_home_slider_mobile',
                    $settings['theme_home_slider_mobile'],
                    'Danh sách Slide mobile',
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
                                'Chon ảnh cho slider mobile',
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
                        )
                    ])
                ),
            ]),
        ];
    }
}