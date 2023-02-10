<?php

namespace App\Classes\WidgetType;


use App\Classes\FieldInput\FieldInputTextArea;
use App\Classes\WidgetType;
use App\Classes\WidgetTypeWithFieldInput;

class WidgetTypeText extends WidgetTypeWithFieldInput {

	function get_id(): string {
		return 'text';
	}

	function get_title(): string {
		return __('Văn bản');
	}

	function get_order(): int {
		return 1;
	}

	function get_group_id(): string {
		return 'basic';
	}


	function get_icon(): string {
		return 'icon-file-text';
	}

    protected function registerFieldInputs(): array
    {
        return [
            new FieldInputTextArea(
                'text',
                '',
                __('Văn bản hiển thị'),
                '',
                1,
                FieldInputTextArea::buildConfigs(__('Nhập văn bản hiển thị'),5)),
        ];
    }
}