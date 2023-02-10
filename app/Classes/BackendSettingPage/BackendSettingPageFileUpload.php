<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 03-Apr-18
 * Time: 09:51
 */

namespace App\Classes\BackendSettingPage;


use App\Classes\BackendSettingPageWithFieldInput;
use App\Classes\FieldGroup;
use App\Classes\FieldInput\FieldInputTextArea;
use App\Classes\FieldInput\FieldInputTouchSpin;

class BackendSettingPageFileUpload extends BackendSettingPageWithFieldInput
{


    public function slug(): string
    {
        return 'file_upload';
    }

    public function menuTitle(): string
    {
        return __('File tải lên');
    }

    public function menuIcon(): string
    {
        return 'icon-upload';
    }

    public function menuOrder(): int
    {
        return 0;
    }

    public function permissionTitle(): string
    {
        return __('Quản lý cấu hình file tải lên');
    }

    public function permissionOrder(): int
    {
        return 0;
    }

    public function pageTitle(): string
    {
        return __('Cấu hình file tải lên');
    }

    protected function fieldGroups(): array
    {
        return [
            new FieldGroup(__('Cấu hình tải lên'),[

                new FieldInputTouchSpin(
                    'file_upload_max_size',
                    getSetting('file_upload_max_size', getFileUploadMaxSize() / (1024 * 1024 * 1.0)),
                    __('Dung lượng cho phép'),
                    '',
                    true,
                    [
                        'min' => 0.01,
                        'max' => getFileUploadMaxSize()/(1024*1024*1.0),
                        'step' => 0.1,
                        'decimals' => 2,
                        'postfix' => 'MB'
                    ],
                    [
                        'autoload'=>1,
                    ]
                ),
                new FieldInputTextArea(
                    'file_group_image',
                    getSetting('file_group_image', implode(
                        PHP_EOL,
                        [
                            'jpg',
                            'jpeg',
                            'gif',
                            'png',
                            'psd',
                            'ai'
                        ])),
                    __('Tập tin hình ảnh hổ trợ'),
                    '',
                    true,
                    FieldInputTextArea::buildConfigs(__('Nhập phần mở rộng của tập tin'),10),
                    [
                        'autoload'=>1,
                    ]
                ),
                new FieldInputTextArea(
                    'file_group_text',
                    getSetting('file_group_text', implode(
                        PHP_EOL,
                        [
                            'txt',
                            'css',
                            'js',
                            'doc',
                            'docx',
                            'rtf',
                            'xls'
                        ])),
                    __('Tập tin văn bản hổ trợ'),
                    '',
                    true,
                    FieldInputTextArea::buildConfigs(__('Nhập phần mở rộng của tập tin'),10),
                    [
                        'autoload'=>1,
                    ]
                ),
                new FieldInputTextArea(
                    'file_group_media',
                    getSetting('file_group_media', implode(
                        PHP_EOL,
                        [
                            'aac', 'oga', 'wav', 'weba', 'mp3'
                        ])),
                    __('Tập tin đa phương tiện hổ trợ'),
                    '',
                    true,
                    FieldInputTextArea::buildConfigs(__('Nhập phần mở rộng của tập tin'),10),
                    [
                        'autoload'=>1,
                    ]
                ),

                new FieldInputTextArea(
                    'file_group_compressed',
                    getSetting('file_group_compressed', implode(
                        PHP_EOL,
                        [
                            'zip', 'rar', '7z', 'bz', 'bz2'
                        ])),
                    __('Tập tin ném hổ trợ'),
                    '',
                    true,
                    FieldInputTextArea::buildConfigs(__('Nhập phần mở rộng của tập tin'),10),
                    [
                        'autoload'=>1,
                    ]
                ),
            ]),
        ];
    }
}