<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 03-Apr-18
 * Time: 23:31
 */

namespace App\Classes\BackendSettingPage;


use App\Classes\BackendSettingPageWithFieldInput;
use App\Classes\FieldGroup;
use App\Classes\FieldInput\FieldInputRepeater;
use App\Classes\FieldInput\FieldInputTextArea;
use App\Classes\FieldInput\FieldInputTouchSpin;

class BackendSettingPageTest extends BackendSettingPageWithFieldInput
{

    protected function slug(): string
    {
        return 'test';
    }

    protected function menuTitle(): string
    {
        return 'Test setting page';
    }

    protected function menuIcon(): string
    {
        return 'icon-puzzle3';
    }

    protected function menuOrder(): int
    {
        return 0;
    }

    protected function permissionTitle(): string
    {
        return 'Quản lý test settings page';
    }

    protected function permissionOrder(): int
    {
        return 0;
    }

    protected function pageTitle(): string
    {
        return 'Test page title';
    }

    /**
     * @return array|FieldGroup[]
     */
    protected function fieldGroups(): array
    {
        $settings = [
            'test_field_repeater' => '',
//            'test_field_touch_spin' => 1,
//            'test_field_text' => '',
//            'test_field_text_1' => '',
//            'test_field_textarea' => '',
//            'test_field_select_single' => '3',
//            'test_field_select_multiple' => ['2', '5'],
//            'test_field_boolean' => 1,
//            'test_field_color' => '#ffffff',
//            'test_field_date' => date('Y-m-d'),
//            'test_field_datetime' => date('Y-m-d h:i:s'),
//            'test_field_date_range' => FieldInputDateRange::buildValue(),
//            'test_field_datetime_range' => FieldInputDateRange::buildValue(),
//            'test_field_code' => '',
//            'test_field_menu' => 0,
//            'test_field_menu_mul' => 0,
//            'test_field_menu_type_group' => '',
//            'test_field_menu_type_group_mul' => '',
//            'test_field_menu_type' => 0,
//            'test_field_menu_type_mul' => 0,
//            'test_field_sidebar' => 0,
//            'test_field_sidebar_mul' => 0,
//            'test_field_widget_type_group' => '',
//            'test_field_widget_type_group_mul' => '',
//            'test_field_user' => 0,
//            'test_field_user_mul' => 0,
//            'test_field_icon' => 0,
//            'test_field_icon_mul' => 0,
//            'test_field_role' => 0,
//            'test_field_role_mul' => 0,
//            'test_field_lang' => 0,
//            'test_field_lang_mul' => 0,
//            'test_field_theme' => 0,
//            'test_field_theme_mul' => 0,
//            'test_field_permission' => 0,
//            'test_field_permission_mul' => 0,
//            'test_field_post_type' => 0,
//            'test_field_post_type_mul' => 0,
//            'test_field_tax' => 0,
//            'test_field_tax_mul' => 0,
//            'test_field_file_type_group' => 0,
//            'test_field_file_type_group_mul' => 0,
//            'test_field_widget_type' => '',
//            'test_field_widget_type_mul' => '',
//            'test_field_file_ext' => 0,
//            'test_field_file_ext_mul' => 0,
//            'test_field_file_cat' => '',
//            'test_field_file_cat_mul' => '',
//            'test_field_route' => '',
//            'test_field_route_mul' => '',
//            'test_field_backend_css' => '',
//            'test_field_backend_css_mul' => '',
//            'test_field_backend_js' => '',
//            'test_field_backend_js_mul' => '',
//            'test_field_limitless_color' => '',
//            'test_field_limitless_color_mul' => '',
//            'test_field_file_select' => '',
//            'test_field_file' => '',
//            'test_field_slider' => '',
//            'test_field_slider_mul' => '',
//            'test_field_tinymce' => '',
        ];
//        $settings['test_field_post'] = 0;
//        $settings['test_field_post_mul'] = 0;
//        $settings['test_field_term'] = 0;
//        $settings['test_field_term_mul'] = 0;
        $settings = getSettings($settings);

        $fields = [
            new FieldInputRepeater(
                'test_field_repeater',
                $settings['test_field_repeater'],
                __('Repeater field'),
                '',
                true,
                FieldInputRepeater::buildConfigs([
                    new FieldInputTextArea(
                        'child_textarea',
                        '',
                        'Child Field 1',
                        '',
                        1,
                        FieldInputTextArea::buildConfigs('test child 1',3)
                        ),
                    new FieldInputTouchSpin(
                        'child_touch_spin',
                        1,
                        __('Touch spin field'),
                        '',
                        true,
                        [
                            'min' => 0.01,
                            'max' => 2,
                            'step' => 0.1,
                            'decimals' => 2,
                            'postfix' => 'MB'
                        ]
                    ),
                    new FieldInputRepeater(
                        'test_field_repeater_nested',
                        [],
                        __('Repeater field nested'),
                        '',
                        true,
                        FieldInputRepeater::buildConfigs([
                            new FieldInputTextArea(
                                'child_textarea_nested',
                                '',
                                'Child Field 1 nested',
                                '',
                                1,
                                FieldInputTextArea::buildConfigs('test child nested',3)
                            ),
                            new FieldInputTouchSpin(
                                'child_touch_spin_nested',
                                1,
                                __('Touch spin field nested'),
                                '',
                                true,
                                [
                                    'min' => 0.01,
                                    'max' => 20,
                                    'step' => 0.1,
                                    'decimals' => 2,
                                    'postfix' => 'MB'
                                ]
                            ),

                        ])
                    )
                ], 'THÊM ITEM', 'ITEM')
            ),

//            new FieldInputTouchSpin(
//                'test_field_touch_spin',
//                $settings['test_field_touch_spin'],
//                __('Touch spin field'),
//                '',
//                true,
//                [
//                    'min' => 0.01,
//                    'max' => 2,
//                    'step' => 0.1,
//                    'decimals' => 2,
//                    'postfix' => 'MB'
//                ],
//                [
//                    'autoload'=>0,
//                ]
//            ),
//
//            new FieldInputText(
//                'test_field_text',
//                $settings['test_field_text'],
//                'Text Field',
//                '',
//                true,
//                FieldInputText::buildConfigs('Placeholder','Prepend', '')
//            ),
//
//            new FieldInputText(
//                'test_field_text_1',
//                $settings['test_field_text_1'],
//                'Text Field',
//                '',
//                true,
//                FieldInputText::buildConfigs('Placeholder', '', 'Append')
//            ),
//
//            new FieldInputTextArea(
//                'test_field_textarea',
//                $settings['test_field_textarea'],
//                'Textarea Field',
//                '',
//                true,
//                FieldInputTextArea::buildConfigs('My placeholder',3)
//            ),
//
//            new FieldInputSelect(
//                'test_field_select_single',
//                $settings['test_field_select_single'],
//                'Select Single Field',
//                '',
//                true,
//                FieldInputSelect::buildConfigs(
//                    [
//                        '1' => 'Option 01',
//                        '2' => 'Option 02',
//                        '3' => 'Option 03',
//                        '4' => 'Option 04',
//                        '5' => 'Option 05',
//                    ],
//                    0
//                )
//            ),
//
//            new FieldInputSelect(
//                'test_field_select_multiple',
//                $settings['test_field_select_multiple'],
//                'Select Multiple Field',
//                '',
//                true,
//                FieldInputSelect::buildConfigs(
//                    [
//                        '1' => 'Option 01',
//                        '2' => 'Option 02',
//                        '3' => 'Option 03',
//                        '4' => 'Option 04',
//                        '5' => 'Option 05',
//                    ],
//                    1
//                )
//            ),
//
//            new FieldInputBoolean(
//                'test_field_boolean',
//                $settings['test_field_boolean'],
//                'Boolean Field',
//                '',
//                true,
//                FieldInputBoolean::buildConfigs('Có','Không','success', 'danger')
//            ),
//
//            new FieldInputColor(
//                'test_field_color',
//                $settings['test_field_color'],
//                'Color Field',
//                '',
//                true,
//                FieldInputColor::buildConfigs()
//            ),
//
//            new FieldInputDate(
//                'test_field_date',
//                $settings['test_field_date'],
//                'Date Field',
//                '',
//                true,
//                FieldInputDate::buildConfigs(date('Y-m-d'),'','up')
//            ),
//
//            new FieldInputDatetime(
//                'test_field_datetime',
//                $settings['test_field_datetime'],
//                'Datetime Field',
//                '',
//                true,
//                FieldInputDatetime::buildConfigs(date('Y-m-d h:i:s'),'','up')
//            ),
//
//            new FieldInputDateRange(
//                'test_field_date_range',
//                $settings['test_field_date_range'],
//                'Date Range Field',
//                '',
//                true,
//                FieldInputDateRange::buildConfigs(date('Y-m-d'),'','up')
//            ),
//
//            new FieldInputDatetimeRange(
//                'test_field_datetime_range',
//                $settings['test_field_datetime_range'],
//                'Datetime Range Field',
//                '',
//                true,
//                FieldInputDateRange::buildConfigs(date('Y-m-d h:i:s'),'','up')
//            ),
//
//            new FieldInputCode(
//                'test_field_code',
//                $settings['test_field_code'],
//                'Code Field',
//                '',
//                true,
//                FieldInputCode::buildConfigs('ace/mode/javascript')
//            ),
//
//            new FieldInputMenu(
//                'test_field_menu',
//                $settings['test_field_menu'],
//                'Menu Field Single',
//                '',
//                true,
//                FieldInputMenu::buildConfigs(0)
//            ),
//
//            new FieldInputMenu(
//                'test_field_menu_mul',
//                $settings['test_field_menu_mul'],
//                'Menu Field Multiple',
//                '',
//                true,
//                FieldInputMenu::buildConfigs(1)
//            ),
//
//            new FieldInputMenuItemTypeGroup(
//                'test_field_menu_type_group',
//                $settings['test_field_menu_type_group'],
//                'Menu Item Type Group Field Single',
//                '',
//                true,
//                FieldInputMenuItemTypeGroup::buildConfigs(0)
//            ),
//
//            new FieldInputMenuItemTypeGroup(
//                'test_field_menu_type_group_mul',
//                $settings['test_field_menu_type_group_mul'],
//                'Menu Item Type Group Field Multiple',
//                '',
//                true,
//                FieldInputMenuItemTypeGroup::buildConfigs(1)
//            ),
//
//            new FieldInputMenuItemType(
//                'test_field_menu_type',
//                $settings['test_field_menu_type'],
//                'Menu Item Types Field Single',
//                '',
//                true,
//                FieldInputMenuItemType::buildConfigs(0)
//            ),
//
//            new FieldInputMenuItemType(
//                'test_field_menu_type_mul',
//                $settings['test_field_menu_type_mul'],
//                'Menu Item Types Field Multiple',
//                '',
//                true,
//                FieldInputMenuItemType::buildConfigs(1)
//            ),
//
//            new FieldInputSidebar(
//                'test_field_sidebar',
//                $settings['test_field_sidebar'],
//                'Sidebar Field Single',
//                '',
//                true,
//                FieldInputSidebar::buildConfigs(0)
//            ),
//
//            new FieldInputSidebar(
//                'test_field_sidebar_mul',
//                $settings['test_field_sidebar_mul'],
//                'Sidebar Field Multiple',
//                '',
//                true,
//                FieldInputSidebar::buildConfigs(1)
//            ),
//
//            new FieldInputWidgetTypeGroup(
//                'test_field_widget_type_group',
//                $settings['test_field_widget_type_group'],
//                'Widget Type Group Field Single',
//                '',
//                true,
//                FieldInputWidgetTypeGroup::buildConfigs(0)
//            ),
//
//            new FieldInputWidgetTypeGroup(
//                'test_field_widget_type_group_mul',
//                $settings['test_field_widget_type_group_mul'],
//                'Widget Type Group Field Multiple',
//                '',
//                true,
//                FieldInputWidgetTypeGroup::buildConfigs(1)
//            ),
//
//            new FieldInputWidgetType(
//                'test_field_widget_type',
//                $settings['test_field_widget_type'],
//                'Widget Type Field Single',
//                '',
//                true,
//                FieldInputWidgetType::buildConfigs(0)
//            ),
//
//            new FieldInputWidgetType(
//                'test_field_widget_type_mul',
//                $settings['test_field_widget_type_mul'],
//                'Widget Type Field Multiple',
//                '',
//                true,
//                FieldInputWidgetType::buildConfigs(1)
//            ),
//
//            new FieldInputUser(
//                'test_field_user',
//                $settings['test_field_user'],
//                'User Field Single',
//                '',
//                true,
//                FieldInputUser::buildConfigs(0)
//            ),
//
//            new FieldInputUser(
//                'test_field_user_mul',
//                $settings['test_field_user_mul'],
//                'User Field Multiple',
//                '',
//                true,
//                FieldInputUser::buildConfigs(1)
//            ),
//
//            new FieldInputIcon(
//                'test_field_icon',
//                $settings['test_field_icon'],
//                'Icon Field Single',
//                '',
//                true,
//                FieldInputIcon::buildConfigs(0)
//            ),
//
//            new FieldInputIcon(
//                'test_field_icon_mul',
//                $settings['test_field_icon_mul'],
//                'Icon Field Multiple',
//                '',
//                true,
//                FieldInputIcon::buildConfigs(1)
//            ),
//
//            new FieldInputRole(
//                'test_field_role',
//                $settings['test_field_role'],
//                'Role Field Single',
//                '',
//                true,
//                FieldInputRole::buildConfigs(0)
//            ),
//
//            new FieldInputRole(
//                'test_field_role_mul',
//                $settings['test_field_role_mul'],
//                'Role Field Multiple',
//                '',
//                true,
//                FieldInputRole::buildConfigs(1)
//            ),
//
//            new FieldInputLanguage(
//                'test_field_lang',
//                $settings['test_field_lang'],
//                'Language Field Single',
//                '',
//                true,
//                FieldInputLanguage::buildConfigs(0)
//            ),
//
//            new FieldInputLanguage(
//                'test_field_lang_mul',
//                $settings['test_field_lang_mul'],
//                'Language Field Multiple',
//                '',
//                true,
//                FieldInputLanguage::buildConfigs(1)
//            ),
//
//            new FieldInputTheme(
//                'test_field_theme',
//                $settings['test_field_theme'],
//                'Theme Field Single',
//                '',
//                true,
//                FieldInputTheme::buildConfigs(0)
//            ),
//
//            new FieldInputTheme(
//                'test_field_theme_mul',
//                $settings['test_field_theme_mul'],
//                'Theme Field Multiple',
//                '',
//                true,
//                FieldInputTheme::buildConfigs(1)
//            ),
//
//            new FieldInputPermission(
//                'test_field_permission',
//                $settings['test_field_permission'],
//                'Permission Field Single',
//                '',
//                true,
//                FieldInputPermission::buildConfigs(0)
//            ),
//
//            new FieldInputPermission(
//                'test_field_permission_mul',
//                $settings['test_field_permission_mul'],
//                'Permission Field Multiple',
//                '',
//                true,
//                FieldInputPermission::buildConfigs(1)
//            ),
//
//            new FieldInputPostType(
//                'test_field_post_type',
//                $settings['test_field_post_type'],
//                'Post Type Field Single',
//                '',
//                true,
//                FieldInputPostType::buildConfigs(0)
//            ),
//
//            new FieldInputPostType(
//                'test_field_post_type_mul',
//                $settings['test_field_post_type_mul'],
//                'Post Type Field Multiple',
//                '',
//                true,
//                FieldInputPostType::buildConfigs(1)
//            ),
//
//            new FieldInputTaxonomy(
//                'test_field_tax',
//                $settings['test_field_tax'],
//                'Taxonomy Field Single',
//                '',
//                true,
//                FieldInputTaxonomy::buildConfigs(0)
//            ),
//
//            new FieldInputTaxonomy(
//                'test_field_tax_mul',
//                $settings['test_field_tax_mul'],
//                'Taxonomy Field Multiple',
//                '',
//                true,
//                FieldInputTaxonomy::buildConfigs(1)
//            ),
//
//            new FieldInputFileGroup(
//                'test_field_file_type_group',
//                $settings['test_field_file_type_group'],
//                'File Type Group Field Single',
//                '',
//                true,
//                FieldInputFileGroup::buildConfigs(0)
//            ),
//
//            new FieldInputFileGroup(
//                'test_field_file_type_group_mul',
//                $settings['test_field_file_type_group_mul'],
//                'File Type Group Field Multiple',
//                '',
//                true,
//                FieldInputFileGroup::buildConfigs(1)
//            ),
//
//            new FieldInputFileExtension(
//                'test_field_file_ext',
//                $settings['test_field_file_ext'],
//                'File Extension Field Single',
//                '',
//                true,
//                FieldInputFileExtension::buildConfigs(0)
//            ),
//
//            new FieldInputFileExtension(
//                'test_field_file_ext_mul',
//                $settings['test_field_file_ext_mul'],
//                'File Extension Field Multiple',
//                '',
//                true,
//                FieldInputFileExtension::buildConfigs(1)
//            ),
//
//            new FieldInputFileCategory(
//                'test_field_file_cat',
//                $settings['test_field_file_cat'],
//                'File Category Field Single',
//                '',
//                true,
//                FieldInputFileCategory::buildConfigs(0)
//            ),
//
//            new FieldInputFileCategory(
//                'test_field_file_cat_mul',
//                $settings['test_field_file_cat_mul'],
//                'File Category Extension Field Multiple',
//                '',
//                true,
//                FieldInputFileCategory::buildConfigs(1)
//            ),
//
//            new FieldInputRoute(
//                'test_field_route',
//                $settings['test_field_route'],
//                'Route Field Single',
//                '',
//                true,
//                FieldInputRoute::buildConfigs(0)
//            ),
//
//            new FieldInputRoute(
//                'test_field_route_mul',
//                $settings['test_field_route_mul'],
//                'Route Field Multiple',
//                '',
//                true,
//                FieldInputRoute::buildConfigs(1)
//            ),
//
//            new FieldInputBackendCSS(
//                'test_field_backend_css',
//                $settings['test_field_backend_css'],
//                'Backend CSS Field Single',
//                '',
//                true,
//                FieldInputBackendCSS::buildConfigs(0)
//            ),
//
//            new FieldInputBackendCSS(
//                'test_field_backend_css_mul',
//                $settings['test_field_backend_css_mul'],
//                'Backend CSS Field Multiple',
//                '',
//                true,
//                FieldInputBackendCSS::buildConfigs(1)
//            ),
//
//            new FieldInputBackendJS(
//                'test_field_backend_js',
//                $settings['test_field_backend_js'],
//                'Backend JS Field Single',
//                '',
//                true,
//                FieldInputBackendJS::buildConfigs(0)
//            ),
//
//            new FieldInputBackendJS(
//                'test_field_backend_js_mul',
//                $settings['test_field_backend_js_mul'],
//                'Backend JS Field Multiple',
//                '',
//                true,
//                FieldInputBackendJS::buildConfigs(1)
//            ),
//
//            new FieldInputLimitlessColorClasses(
//                'test_field_limitless_color',
//                $settings['test_field_limitless_color'],
//                'Limitless Color Field Single',
//                '',
//                true,
//                FieldInputLimitlessColorClasses::buildConfigs(0)
//            ),
//
//            new FieldInputLimitlessColorClasses(
//                'test_field_limitless_color_mul',
//                $settings['test_field_limitless_color_mul'],
//                'Limitless Color Field Multiple',
//                '',
//                true,
//                FieldInputLimitlessColorClasses::buildConfigs(1)
//            ),

        ];

//        $fields[] = new FieldInputPost(
//            'test_field_post',
//            $settings['test_field_post'],
//            'Post Field Single',
//            '',
//            0,
//            FieldInputPost::buildConfigs(0, 0)
//        );
//
//        $fields[] = new FieldInputPost(
//            'test_field_post_mul',
//            $settings['test_field_post_mul'],
//            'Post Field Multiple',
//            '',
//            0,
//            FieldInputPost::buildConfigs(1, 1)
//        );
//
//        $fields[] = new FieldInputTerm(
//            'test_field_term',
//            $settings['test_field_term'],
//            'Term Field Single',
//            '',
//            0,
//            FieldInputTerm::buildConfigs(0, 0)
//        );
//
//        $fields[] = new FieldInputTerm(
//            'test_field_term_mul',
//            $settings['test_field_term_mul'],
//            'Term Field Multiple',
//            '',
//            0,
//            FieldInputTerm::buildConfigs(1, 1)
//        );
//
//        $fields[] = new FieldInputFileList(
//            'test_field_file_select',
//            $settings['test_field_file_select'],
//            'File list Field',
//            '',
//            0,
//            FieldInputFileList::buildConfigs('Test Field','',['user'], ['image'])
//        );
//
//        $fields[] = new FieldInputFile(
//            'test_field_file',
//            $settings['test_field_file'],
//            'File Field',
//            '',
//            0,
//            FieldInputFile::buildConfigs('Test Field','',['user'], ['image'])
//        );
//
//        $fields[] = new FieldInputSlider(
//            'test_field_slider',
//            $settings['test_field_slider'],
//            'Slider Field Single',
//            '',
//            0,
//            FieldInputSlider::buildConfigs(0)
//        );
//
//        $fields[] = new FieldInputSlider(
//            'test_field_slider_mul',
//            $settings['test_field_slider_mul'],
//            'Slider Field Multiple',
//            '',
//            0,
//            FieldInputSlider::buildConfigs(1)
//        );
//
//        $fields[] = new FieldInputTinyMCE(
//            'test_field_tinymce',
//            $settings['test_field_tinymce'],
//            'TinyMCE Field',
//            '',
//            1,
//            FieldInputTinyMCE::buildConfigs([
//                'height' => 500,
//                'menubar' => false,
//                'toolbar1' => 'formatselect fontsizeselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat ',
//                'toolbar2' => 'waimage | searchreplace autolink directionality advcode visualblocks visualchars fullscreen image link media table charmap hr nonbreaking anchor insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern | code',
//                'plugins' => 'searchreplace directionality advcode visualblocks visualchars fullscreen image link media table charmap hr nonbreaking anchor insertdatetime advlist lists textcolor contextmenu colorpicker textpattern',
//                'content_css' => [
//                    url(config('view.ui.files.css.bootstrap.src'))
//                 ],
//                'wa_image_insert' => [
//                    'title' => __('Chọn ảnh thêm vào'),
//                    'limit' => ['image'],
//                    'select' => 1
//                ],
//            ])
//        );


        return [
            new FieldGroup('Test Field Group', $fields)
        ];
    }
}