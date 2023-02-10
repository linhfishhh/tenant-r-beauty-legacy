<?php

namespace App\Classes\WidgetType;


use App\Classes\FieldInput\FieldInputMenu;
use App\Classes\WidgetTypeWithFieldInput;

class WidgetTypeMenu extends WidgetTypeWithFieldInput {

	function get_id(): string {
		return 'menu';
	}

	function get_title(): string {
		return __('Menu');
	}

	function get_order(): int {
		return 2;
	}

	function get_group_id(): string {
		return 'basic';
	}

    function get_icon(): string {
        return 'icon-menu7';
    }

    protected function registerFieldInputs(): array
    {
        return [
            new FieldInputMenu(
                'menu',
                '',
                __('Menu hiển thị'),
                '',
                1,
                FieldInputMenu::buildConfigs(0)
            )
        ];
    }
}