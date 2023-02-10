<?php

namespace App\Classes\MenuType;

use App\Classes\FieldInput\FieldInputTerm;
use App\Classes\MenuTypeWithFieldInput;

class MenuTypeTerm extends MenuTypeWithFieldInput
{

    function get_id(): string
    {
        return 'term';
    }

    function get_title(): string
    {
        return __('Phân loại nội dung');
    }

    function get_order(): int
    {
        return 0;
    }

    function get_group_id(): string
    {
        return 'content';
    }

    function get_icon(): string
    {
        return 'icon-folder';
    }

    protected function registerFieldInputs(): array
    {
        return [
            new FieldInputTerm(
                'post_type',
                '',
                __('Trang phân loại nội dung'),
                '',
                1,
                FieldInputTerm::buildConfigs(0)
            ),

        ];
    }
}