<?php

namespace App\Classes\MenuType;

use App\Classes\FieldInput\FieldInputTaxonomy;
use App\Classes\MenuTypeWithFieldInput;

class MenuTypeTaxonomy extends MenuTypeWithFieldInput
{

    function get_id(): string
    {
        return 'taxonomy';
    }

    function get_title(): string
    {
        return __('Danh sách phân loại');
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
        return 'icon-list2';
    }

    protected function registerFieldInputs(): array
    {
        return [
            new FieldInputTaxonomy(
                'taxonomy',
                '',
                __('Phân loại nội dung'),
                '',
                1,
                FieldInputTaxonomy::buildConfigs(0)
            ),

        ];
    }
}