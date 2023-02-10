<?php

namespace App\Classes\MenuType;

use App\Classes\FieldInput\FieldInputFile;
use App\Classes\MenuTypeWithFieldInput;

class MenuTypeFile extends MenuTypeWithFieldInput
{

    function get_id(): string
    {
        return 'file';
    }

    function get_title(): string
    {
        return __('Tập tin');
    }

    function get_order(): int
    {
        return 0;
    }

    function get_group_id(): string
    {
        return 'basic';
    }

    function get_icon(): string
    {
        return 'icon-image2';
    }

    protected function registerFieldInputs(): array
    {
        return [
            new FieldInputFile(
                'post_type',
                null,
                __('Tập tin'),
                '',
                1,
                FieldInputFile::buildConfigs(__('CHỌN TẬP TIN CẦN LIÊN KẾT'))
            ),

        ];
    }
}