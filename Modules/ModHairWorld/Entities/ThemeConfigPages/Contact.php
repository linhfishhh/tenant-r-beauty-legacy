<?php
/**
 * Created by PhpStorm.
 * User: TRANG
 * Date: 20-Jun-18
 * Time: 10:41
 */

namespace Modules\ModHairWorld\Entities\ThemeConfigPages;


use App\Classes\BackendSettingPageWithFieldInput;
use App\Classes\FieldGroup;
use App\Classes\FieldInput\FieldInputFile;
use App\Classes\FieldInput\FieldInputMapLocation;
use App\Classes\FieldInput\FieldInputRepeater;
use App\Classes\FieldInput\FieldInputText;

class Contact extends BackendSettingPageWithFieldInput
{

    protected function slug(): string
    {
        return 'contact';
    }

    protected function menuTitle(): string
    {
        return 'Liên hệ';
    }

    protected function menuIcon(): string
    {
        return 'icon-phone';
    }

    protected function menuOrder(): int
    {
        return 2;
    }

    protected function permissionTitle(): string
    {
        return 'Quản lý cấu hình trang liên hệ';
    }

    protected function permissionOrder(): int
    {
        return 9;
    }

    protected function pageTitle(): string
    {
        return 'Cấu hình trang liên hệ';
    }

    public function getParentMenuSlug()
    {
        return 'theme_config';
    }


    /**
     * @return array|FieldGroup[]
     */
    protected function fieldGroups(): array
    {
        $settings = [
            'theme_contact_hotline' => '',
            'theme_contact_brands' => [],
            'theme_contact_headline_bg' => false,
            'theme_contact_headline' => 'Liên hệ với chúng tôi để được hổ trợ'
        ];
        $settings = getSettings($settings);
        return [
            new FieldGroup(
              'Thông tin chính',
                [
                    new FieldInputText(
                        'theme_contact_headline',
                        $settings['theme_contact_headline'],
                        'Tiêu đề Headline',
                        '',
                        false,
                        FieldInputText::buildConfigs('Tiêu đề Headline')
                    ),
                    new FieldInputFile(
                        'theme_contact_headline_bg',
                        $settings['theme_contact_headline_bg'],
                        'Ảnh nền headline',
                        false,
                        false,
                        FieldInputFile::buildConfigs('Chọn ảnh', 'Chọn ảnh',['theme_files'], ['image'])
                    ),
                    new FieldInputText(
                        'theme_contact_hotline',
                        $settings['theme_contact_hotline'],
                        'Hotline',
                        '',
                        false,
                        FieldInputText::buildConfigs('Nhập số hotline')
                    ),
                    new FieldInputRepeater(
                        'theme_contact_brands',
                        $settings['theme_contact_brands'],
                        'Các chi nhánh',
                        '',
                        false,
                        FieldInputRepeater::buildConfigs([
                            new FieldInputText(
                                'title',
                                '',
                                'Tên chi nhánh',
                                '',
                                true,
                                FieldInputText::buildConfigs('Nhập tên chi nhánh')
                            ),
                            new FieldInputText(
                                'address',
                                '',
                                'Địa chỉ',
                                '',
                                true,
                                FieldInputText::buildConfigs('Nhập địa chỉ chi nhánh')
                            ),
                            new FieldInputText(
                                'email',
                                '',
                                'Email liên hệ',
                                '',
                                false,
                                FieldInputText::buildConfigs('Nhập email liên hệ')
                            ),
                            new FieldInputText(
                                'phone',
                                '',
                                'Số điện thoại',
                                '',
                                false,
                                FieldInputText::buildConfigs('Nhập số điện thoại')
                            ),
                            new FieldInputText(
                                'fax',
                                '',
                                'Số fax',
                                '',
                                false,
                                FieldInputText::buildConfigs('Nhập số fax')
                            ),
                            new FieldInputMapLocation(
                                'location',
                                '',
                                'Vị trí trên bản đồ',
                                '',
                                true
                            ),
                        ])

                    )
                ]
            ),
        ];
    }
}