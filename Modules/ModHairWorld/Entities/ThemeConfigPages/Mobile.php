<?php

namespace Modules\ModHairWorld\Entities\ThemeConfigPages;


use App\Classes\BackendSettingPageWithFieldInput;
use App\Classes\FieldGroup;
use App\Classes\FieldInput\FieldInputBoolean;
use App\Classes\FieldInput\FieldInputColor;
use App\Classes\FieldInput\FieldInputFile;
use App\Classes\FieldInput\FieldInputFontAwesome;
use App\Classes\FieldInput\FieldInputPost;
use App\Classes\FieldInput\FieldInputRepeater;
use App\Classes\FieldInput\FieldInputSlider;
use App\Classes\FieldInput\FieldInputText;
use App\Classes\FieldInput\FieldInputTextArea;
use App\Classes\FieldInput\FieldInputTinyMCE;
use App\Classes\FieldInput\FieldInputTouchSpin;

class Mobile extends BackendSettingPageWithFieldInput
{

    protected function slug(): string
    {
        return 'theme_config_mobile';
    }

    protected function menuTitle(): string
    {
        return 'Ứng dụng mobile';
    }

    protected function menuIcon(): string
    {
        return 'icon-phone';
    }

    protected function menuOrder(): int
    {
        return 0;
    }

    protected function permissionTitle(): string
    {
        return 'Cấu hình ứng dụng di động';
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
        return 'Cấu hình ừng dụng di động';
    }

    /**
     * @return array|FieldGroup[]
     */
    protected function fieldGroups(): array
    {
        $settings = [
            'theme_mobile_join_tos' => '',
            'theme_mobile_manager_tos' => '',
            'theme_mobile_manager_intro' => '',
            'theme_mobile_home_banners' => [],
            'theme_mobile_manager_rating_tab_rating_desc' => '',
            'theme_mobile_manager_rating_tab_accept' => '',
            'theme_mobile_manager_rating_tab_cancel' => '',
            'theme_mobile_map_marker_color_1' => '#ef5248',
            'theme_mobile_map_marker_color_2' => '#ffffff',
            'theme_mobile_map_marker_alt_color_1' => '#ef5248',
            'theme_mobile_map_marker_alt_color_2' => '#ffffff',
            'theme_mobile_map_marker_sl_color_1' => '#ef5248',
            'theme_mobile_map_marker_sl_color_2' => '#ffffff',
            'theme_mobile_show_unverified_map_marker' => true,
            'theme_mobile_map_search_radius' => 250,
            'theme_mobile_map_salon_limit' => 20,
            'theme_mobile_map_search_radius_list' => [
                1000,
                1500,
                2000
            ],
        ];
        $settings = getSettings($settings);
        return [
            new FieldGroup(
                'Quy định khi tham gia',
                [
                    new FieldInputTinyMCE(
                        'theme_mobile_join_tos',
                        $settings['theme_mobile_join_tos'],
                        'Nội dung',
                        '',
                        true,
                        FieldInputTinyMCE::buildConfigs()
                    ),
                ]
            ),
            new FieldGroup(
                'Điều khoản chính sách cho chủ salon',
                [
                    new FieldInputTinyMCE(
                        'theme_mobile_manager_tos',
                        $settings['theme_mobile_manager_tos'],
                        'Chính sách & điều khoản',
                        '',
                        true,
                        FieldInputTinyMCE::buildConfigs()
                    ),
                ]
            ),
            new FieldGroup(
                'Giới thiệu ứng dụng chủ salon',
                [
                    new FieldInputTinyMCE(
                        'theme_mobile_manager_intro',
                        $settings['theme_mobile_manager_intro'],
                        'Nội dung',
                        '',
                        true,
                        FieldInputTinyMCE::buildConfigs()
                    ),
                ]
            ),
            new FieldGroup(
                'Customer - Trang home',
                [

                    new FieldInputColor(
                        'theme_mobile_map_marker_color_1',
                        $settings['theme_mobile_map_marker_color_1'],
                        'Màu map marker salon đã xác thực - vòng tròn lớn',
                        '',
                        true,
                        FieldInputColor::buildConfigs()
                    ),
                    new FieldInputColor(
                        'theme_mobile_map_marker_color_2',
                        $settings['theme_mobile_map_marker_color_2'],
                        'Màu map marker salon đã xác thực - vòng tròn nhỏ',
                        '',
                        true,
                        FieldInputColor::buildConfigs()
                    ),

                    new FieldInputBoolean(
                        'theme_mobile_show_unverified_map_marker',
                        $settings['theme_mobile_show_unverified_map_marker'],
                        'Hiển thị salon chưa xác thực trên bản đồ',
                        '',
                        true,
                        FieldInputBoolean::buildConfigs('Bật', 'Tắt')
                    ),

                    new FieldInputColor(
                        'theme_mobile_map_marker_alt_color_1',
                        $settings['theme_mobile_map_marker_alt_color_1'],
                        'Màu map marker salon chưa xác thực - vòng tròn lớn',
                        '',
                        true,
                        FieldInputColor::buildConfigs()
                    ),
                    new FieldInputColor(
                        'theme_mobile_map_marker_alt_color_2',
                        $settings['theme_mobile_map_marker_alt_color_2'],
                        'Màu map marker salon chưa xác thực - vòng tròn nhỏ',
                        '',
                        true,
                        FieldInputColor::buildConfigs()
                    ),

                    new FieldInputColor(
                        'theme_mobile_map_marker_sl_color_1',
                        $settings['theme_mobile_map_marker_sl_color_1'],
                        'Màu map marker salon được nhấn chọn - vòng tròn lớn',
                        '',
                        true,
                        FieldInputColor::buildConfigs()
                    ),
                    new FieldInputColor(
                        'theme_mobile_map_marker_sl_color_2',
                        $settings['theme_mobile_map_marker_sl_color_2'],
                        'Màu map marker salon được nhấn chọn - vòng tròn nhỏ',
                        '',
                        true,
                        FieldInputColor::buildConfigs()
                    ),

                    new FieldInputTouchSpin(
                        'theme_mobile_map_search_radius',
                        $settings['theme_mobile_map_search_radius'],
                        'Bán kính tìm kiếm trên bản đồ (bản cũ)',
                        '',
                        true,
                        [
                            'min' => 10,
                            'max' => 5000,
                            'step' => 1,
                            'decimals' => 0,
                            'postfix' => 'mét'
                        ]
                    ),
                    new FieldInputRepeater(
                        'theme_mobile_map_search_radius_list',
                        $settings['theme_mobile_map_search_radius_list'],
                        'Bán kính tìm kiếm trên bản đồ (version 2)',
                        'Danh sách bán kính tăng dần, cái đầu tiên sẽ là mặc định',
                        true,
                        FieldInputRepeater::buildConfigs([
                            new FieldInputTouchSpin(
                                'radius',
                                0,
                                'Bán kính',
                                '',
                                true,
                                [
                                    'min' => 50,
                                    'max' => 10000,
                                    'step' => 1,
                                    'decimals' => 0,
                                    'postfix' => 'mét'
                                ]
                            )
                        ])
                    ),

                    new FieldInputTouchSpin(
                        'theme_mobile_map_salon_limit',
                        $settings['theme_mobile_map_salon_limit'],
                        'Giới hạn kết quả trên bản đồ',
                        '0: Không giới hạn (chậm)',
                        true,
                        [
                            'min' => 0,
                            'max' => 999999999,
                            'step' => 1,
                            'decimals' => 0,
                            'postfix' => 'Salon'
                        ]
                    ),

                    new FieldInputRepeater(
                        'theme_mobile_home_banners',
                        $settings['theme_mobile_home_banners'],
                        'Danh sách banner',
                        '',
                        true,
                        FieldInputRepeater::buildConfigs([
                            new FieldInputFile(
                                'banner',
                                null,
                                'Ảnh banner',
                                '',
                                true,
                                FieldInputFile::buildConfigs('Chọn ảnh banner','Chọn ảnh', ['mobile_files'], ['image'])
                            ),
                            new FieldInputText(
                                'query',
                                '',
                                'Truy vấn',
                                '',
                                false,
                                FieldInputText::buildConfigs()
                            )
                        ])
                    ),
                ]
            ),
            new FieldGroup(
                'Manager - Trang xếp hạng',
                [
                    new FieldInputTextArea(
                        'theme_mobile_manager_rating_tab_rating_desc',
                        $settings['theme_mobile_manager_rating_tab_rating_desc'],
                        'Mô tả đánh giá xếp hạng',
                        '',
                        true,
                        FieldInputTextArea::buildConfigs('',10)
                    ),
                    new FieldInputTextArea(
                        'theme_mobile_manager_rating_tab_accept',
                        $settings['theme_mobile_manager_rating_tab_accept'],
                        'Mô tả chỉ số chấp nhận đặt chỗ',
                        '',
                        true,
                        FieldInputTextArea::buildConfigs('',10)
                    ),
                    new FieldInputTextArea(
                        'theme_mobile_manager_rating_tab_cancel',
                        $settings['theme_mobile_manager_rating_tab_cancel'],
                        'Mô tả chỉ số huỷ đặt chỗ',
                        '',
                        true,
                        FieldInputTextArea::buildConfigs('',10)
                    ),
                ]
            ),
        ];
    }
}