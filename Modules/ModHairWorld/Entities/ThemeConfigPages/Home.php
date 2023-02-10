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

class Home extends BackendSettingPageWithFieldInput
{

    protected function slug(): string
    {
        return 'theme_config_home';
    }

    protected function menuTitle(): string
    {
        return __('Trang chủ');
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
        return __('Chỉnh sửa giao diện trang chủ');
    }

    protected function permissionOrder(): int
    {
        return 99;
    }

    protected function pageTitle(): string
    {
        return __('Giao diện trang chủ');
    }

    /**
     * @return array|FieldGroup[]
     */
    protected function fieldGroups(): array
    {
        $tiny_cfg = FieldInputTinyMCE::buildConfigs(
            [
                'height' => 400,
            ],
            'post_type_news_content'
        );
        $tiny_cfg['wa_image_insert']['categories'] = ['theme_files'] ;
        $tiny_cfg['wa_link_insert']['categories'] = ['theme_files'] ;
        $tiny_cfg['wa_media_insert']['categories'] = ['theme_files'] ;

        $settings = [
            'theme_home_header_banner_title' => 'Làm đẹp theo cách của bạn',
            'theme_home_header_feature_text' => [],
            'theme_home_banner_grid_1' => '',
            'theme_home_banner_grid_2' => '',
            'theme_home_banner_grid_3' => '',
            'theme_home_banner_grid_4' => '',
            'theme_home_banner_grid_1_link' => '#',
            'theme_home_banner_grid_2_link' => '#',
            'theme_home_banner_grid_3_link' => '#',
            'theme_home_banner_grid_4_link' => '#',
            'theme_home_popular_cities' => [],
            'theme_home_intro' => '',
            'theme_home_news_limit' => 3,
            'theme_home_banner_grid_1_top_text' => '',
            'theme_home_banner_grid_2_top_text' => '',
            'theme_home_banner_grid_3_top_text' => '',
            'theme_home_banner_grid_4_top_text' => '',
            'theme_home_banner_grid_1_title' => '',
            'theme_home_banner_grid_2_title' => '',
            'theme_home_banner_grid_3_title' => '',
            'theme_home_banner_grid_4_title' => '',
            'theme_home_banner_grid_1_sub_title' => '',
            'theme_home_banner_grid_2_sub_title' => '',
            'theme_home_banner_grid_3_sub_title' => '',
            'theme_home_banner_grid_4_sub_title' => '',
            'theme_home_header_banner_img' => '',
            'theme_home_app_icon' => false,
            'theme_home_app_title' => 'Chạm vào bất cứ lúc nào<br/>và bất cứ nơi đâu!',
            'theme_home_app_desc' => 'iSalon là một ứng dụng đặt chỗ và tìm kiếm các dịch vụ về tóc<br/>Tải xuống ngay trên App Store và Google Play.',
            'theme_home_app_image' => false,
            'theme_home_popup_image' => false,
            'theme_home_popup_url' => '',
            'theme_home_popup_enabled' => false,

        ];
        $settings = getSettings($settings);
        return [
            new FieldGroup(__('Header Banner'),[
                new FieldInputFile(
                    'theme_home_header_banner_img',
                    $settings['theme_home_header_banner_img'],
                    'Ảnh banner',
                    '',
                    true,
                    FieldInputFile::buildConfigs(
                        'Chon ảnh cho banner',
                        'Chọn ảnh',
                        ['theme_files'],
                        ['image']
                    )
                ),
                new FieldInputText(
                    'theme_home_header_banner_title',
                    $settings['theme_home_header_banner_title'],
                    'Slogan',
                    '',
                    true,
                    FieldInputText::buildConfigs()
                ),
                new FieldInputRepeater(
                    'theme_home_header_feature_text',
                    $settings['theme_home_header_feature_text'],
                    'Feature Text',
                    '',
                    true,
                    FieldInputRepeater::buildConfigs([
                        new FieldInputText(
                            'title',
                            '',
                            'Tiêu đề',
                            '',
                            true,
                            FieldInputText::buildConfigs()
                        ),
                        new FieldInputText(
                            'desc',
                            '',
                            'Mô tả',
                            '',
                            true,
                            FieldInputText::buildConfigs()
                        ),
                    ])
                ),
            ]),
            new FieldGroup(__('Banner Grid'),[
                new FieldInputFile(
                    'theme_home_banner_grid_1',
                    $settings['theme_home_banner_grid_1'],
                    'Banner 1 (445 x 594)',
                    '',
                    true,
                    FieldInputFile::buildConfigs(
                        'Chon ảnh cho banner 1 (445 x 594)',
                        'Chọn ảnh',
                        ['theme_files'],
                        ['image']
                    )
                ),
                new FieldInputText(
                    'theme_home_banner_grid_1_link',
                    $settings['theme_home_banner_grid_1_link'],
                    'Banner 1 link',
                    '',
                    true,
                    FieldInputText::buildConfigs()
                ),
                new FieldInputText(
                    'theme_home_banner_grid_1_top_text',
                    $settings['theme_home_banner_grid_1_top_text'],
                    'Banner 1 Top Text',
                    '',
                    false,
                    FieldInputText::buildConfigs()
                ),
                new FieldInputText(
                    'theme_home_banner_grid_1_title',
                    $settings['theme_home_banner_grid_1_title'],
                    'Banner 1 Tiêu đề lớn',
                    '',
                    false,
                    FieldInputText::buildConfigs()
                ),
                new FieldInputText(
                    'theme_home_banner_grid_1_sub_title',
                    $settings['theme_home_banner_grid_1_sub_title'],
                    'Banner 1 Tiêu đề nhỏ',
                    '',
                    false,
                    FieldInputText::buildConfigs()
                ),

                new FieldInputFile(
                    'theme_home_banner_grid_2',
                    $settings['theme_home_banner_grid_2'],
                    'Banner 2 (635 x 282)',
                    '',
                    true,
                    FieldInputFile::buildConfigs(
                        'Chon ảnh cho banner 2 (635 x 282)',
                        'Chọn ảnh',
                        ['theme_files'],
                        ['image']
                    )
                ),
                new FieldInputText(
                    'theme_home_banner_grid_2_link',
                    $settings['theme_home_banner_grid_2_link'],
                    'Banner 2 link',
                    '',
                    true,
                    FieldInputText::buildConfigs()
                ),
                new FieldInputText(
                    'theme_home_banner_grid_2_top_text',
                    $settings['theme_home_banner_grid_2_top_text'],
                    'Banner 2 Top Text',
                    '',
                    false,
                    FieldInputText::buildConfigs()
                ),
                new FieldInputText(
                    'theme_home_banner_grid_2_title',
                    $settings['theme_home_banner_grid_2_title'],
                    'Banner 2 Tiêu đề lớn',
                    '',
                    false,
                    FieldInputText::buildConfigs()
                ),
                new FieldInputText(
                    'theme_home_banner_grid_2_sub_title',
                    $settings['theme_home_banner_grid_2_sub_title'],
                    'Banner 2 Tiêu đề nhỏ',
                    '',
                    false,
                    FieldInputText::buildConfigs()
                ),

                new FieldInputFile(
                    'theme_home_banner_grid_3',
                    $settings['theme_home_banner_grid_3'],
                    'Banner 3 (305 x 282)',
                    '',
                    true,
                    FieldInputFile::buildConfigs(
                        'Chon ảnh cho banner 3(305 x 282)',
                        'Chọn ảnh',
                        ['theme_files'],
                        ['image']
                    )
                ),
                new FieldInputText(
                    'theme_home_banner_grid_3_link',
                    $settings['theme_home_banner_grid_3_link'],
                    'Banner 3 link',
                    '',
                    true,
                    FieldInputText::buildConfigs()
                ),
                new FieldInputText(
                    'theme_home_banner_grid_3_top_text',
                    $settings['theme_home_banner_grid_3_top_text'],
                    'Banner 3 Top Text',
                    '',
                    false,
                    FieldInputText::buildConfigs()
                ),
                new FieldInputText(
                    'theme_home_banner_grid_3_title',
                    $settings['theme_home_banner_grid_3_title'],
                    'Banner 3 Tiêu đề lớn',
                    '',
                    false,
                    FieldInputText::buildConfigs()
                ),
                new FieldInputText(
                    'theme_home_banner_grid_3_sub_title',
                    $settings['theme_home_banner_grid_3_sub_title'],
                    'Banner 3 Tiêu đề nhỏ',
                    '',
                    false,
                    FieldInputText::buildConfigs()
                ),

                new FieldInputFile(
                    'theme_home_banner_grid_4',
                    $settings['theme_home_banner_grid_4'],
                    'Banner 4 (305 x 282)',
                    '',
                    true,
                    FieldInputFile::buildConfigs(
                        'Chon ảnh cho banner 4(305 x 282)',
                        'Chọn ảnh',
                        ['theme_files'],
                        ['image']
                    )
                ),
                new FieldInputText(
                    'theme_home_banner_grid_4_link',
                    $settings['theme_home_banner_grid_4_link'],
                    'Banner 4 link',
                    '',
                    true,
                    FieldInputText::buildConfigs()
                ),
                new FieldInputText(
                    'theme_home_banner_grid_4_top_text',
                    $settings['theme_home_banner_grid_4_top_text'],
                    'Banner 4 Top Text',
                    '',
                    false,
                    FieldInputText::buildConfigs()
                ),
                new FieldInputText(
                    'theme_home_banner_grid_4_title',
                    $settings['theme_home_banner_grid_4_title'],
                    'Banner 4 Tiêu đề lớn',
                    '',
                    false,
                    FieldInputText::buildConfigs()
                ),
                new FieldInputText(
                    'theme_home_banner_grid_4_sub_title',
                    $settings['theme_home_banner_grid_4_sub_title'],
                    'Banner 4 Tiêu đề nhỏ',
                    '',
                    false,
                    FieldInputText::buildConfigs()
                ),
            ]),
            new FieldGroup('Địa danh phổ biến', [
                new FieldInputRepeater(
                    'theme_home_popular_cities',
                    $settings['theme_home_popular_cities'],
                    'Danh sách thành phố',
                    '',
                    false,
                    FieldInputRepeater::buildConfigs([
                        new FieldInputMapLocationLevelOne(
                            'city_id',
                            '',
                            'Thành phố',
                            '',
                            true,
                            FieldInputMapLocationLevelOne::buildConfigs(0)
                        ),
                        new FieldInputFile(
                            'img',
                            '',
                            'Ảnh cover',
                            '',
                            true,
                            FieldInputFile::buildConfigs(
                                'Chon ảnh cho địa phương này',
                                'Chọn ảnh',
                                ['theme_files'],
                                ['image']
                            )
                        ),
                    ])
                ),
            ]),
            new FieldGroup('Intro', [
                new FieldInputTinyMCE(
                    'theme_home_intro',
                    $settings['theme_home_intro'],
                    'Intro',
                    '',
                    false,
                    $tiny_cfg
                )
            ]),
            new FieldGroup('Tin tức mới', [
                new FieldInputTouchSpin(
                    'theme_home_news_limit',
                    $settings['theme_home_news_limit'],
                    'Số tin hiển thị',
                    '',
                    true
                ),
            ]),
            new FieldGroup('Giới thiệu app', [
                new FieldInputFile(
                    'theme_home_app_icon',
                    $settings['theme_home_app_icon'],
                    'App icon',
                    '',
                    false,
                    FieldInputFile::buildConfigs(
                        'Chọn ảnh',
                        'Chọn ảnh',
                        ['theme_files'],
                        ['image']
                    )
                ),
                new FieldInputText(
                    'theme_home_app_title',
                    $settings['theme_home_app_title'],
                    'Tiêu đề giới thiêu',
                    '',
                    true,
                    FieldInputText::buildConfigs()
                ),
                new FieldInputText(
                    'theme_home_app_desc',
                    $settings['theme_home_app_desc'],
                    'Nội dung giới thiêu',
                    '',
                    true,
                    FieldInputText::buildConfigs()
                ),
                new FieldInputFile(
                    'theme_home_app_image',
                    $settings['theme_home_app_image'],
                    'Ảnh app',
                    '',
                    false,
                    FieldInputFile::buildConfigs(
                        'Chọn ảnh',
                        'Chọn ảnh',
                        ['theme_files'],
                        ['image']
                    )
                ),
            ]),
            new FieldGroup('Popup', [
                new FieldInputFile(
                    'theme_home_popup_image',
                    $settings['theme_home_popup_image'],
                    'Popup Image',
                    '',
                    false,
                    FieldInputFile::buildConfigs(
                        'Chọn ảnh',
                        'Chọn ảnh',
                        ['theme_files'],
                        ['image']
                    )
                ),
                new FieldInputText(
                    'theme_home_popup_url',
                    $settings['theme_home_popup_url'],
                    'Popup URL',
                    '',
                    true,
                    FieldInputText::buildConfigs()
                ),
                new FieldInputBoolean(
                    'theme_home_popup_enabled',
                    $settings['theme_home_popup_enabled'],
                    'Enable',
                    '',
                    true,
                    FieldInputText::buildConfigs()
                ),
            ]),
        ];
    }
}