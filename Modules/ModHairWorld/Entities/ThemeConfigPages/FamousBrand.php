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

class FamousBrand extends BackendSettingPageWithFieldInput
{

    protected function slug(): string
    {
        return 'theme_config_famous_brand';
    }

    protected function menuTitle(): string
    {
        return __('Thương hiệu nổi tiếng');
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
        return __('Chỉnh sửa thương hiệu nổi tiếng');
    }

    protected function permissionOrder(): int
    {
        return 99;
    }

    protected function pageTitle(): string
    {
        return __('Thông tin thương hiệu nổi tiếng');
    }

    /**
     * @return array|FieldGroup[]
     */
    protected function fieldGroups(): array
    {

        $settings = [
            'theme_config_famous_brand' => [],
        ];
        $settings = getSettings($settings);

        return [
            new FieldGroup(__('Thương hiệu nổi tiếng'),[
                new FieldInputRepeater(
                    'theme_config_famous_brand',
                    $settings['theme_config_famous_brand'],
                    'Danh sách thương hiệu nổi tiếng',
                    '',
                    true,
                    FieldInputRepeater::buildConfigs([
                        new FieldInputFile(
                            'image',
                            null,
                            'Ảnh đại diện',
                            '',
                            true,
                            FieldInputFile::buildConfigs(
                                'Chon ảnh thương hiệu',
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
                        new FieldInputText(
                            'title',
                            '#',
                            'Mô tả',
                            '',
                            false,
                            FieldInputText::buildConfigs()
                        ),
                    ])
                ),
            ]),
        ];
    }
}