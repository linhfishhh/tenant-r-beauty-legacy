<?php

namespace App\Classes\WidgetType;


use App\Classes\FieldInput\FieldInputTinyMCE;
use App\Classes\WidgetTypeWithFieldInput;

class WidgetTypeTinyMCE extends WidgetTypeWithFieldInput {

	function get_id(): string {
		return 'tinymce';
	}

	function get_title(): string {
		return __('Văn bản nâng cao');
	}

	function get_order(): int {
		return 1;
	}

	function get_group_id(): string {
		return 'basic';
	}


	function get_icon(): string {
		return 'icon-profile';
	}

    protected function registerFieldInputs(): array
    {
        return [
            new FieldInputTinyMCE(
                'text',
                '',
                __('Văn bản hiển thị'),
                '',
                1,
                FieldInputTinyMCE::buildConfigs([]))
        ];
    }
}