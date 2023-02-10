<?php

namespace App\Classes\MenuType;


use App\Classes\MenuType;

class MenuTypeCustomLink extends MenuType
{
    function getID(): string
    {
        return 'custom_link';
    }

    function getTitle(): string
    {
        return __('Liên kết tùy chọn');
    }

    function getOrder(): int
    {
        return 2;
    }

    function getGroupID(): string
    {
        return 'basic';
    }

    function getIcon(): string
    {
        return 'icon-link2';
    }

    function getHtmlView()
    {
        return 'backend.pages.menu.library.custom_link';
    }

    function rules(array $rules): array
    {
        $rules['url'] = ['required'];
        return $rules;
    }

    function messages(array $messages): array
    {
        $messages['link.required'] = __('Vui lòng nhập link liên kết');
        $messages['url.url'] = __('link liên kết không hợp lệ');
        return $messages;
    }

    public function getURL($item)
    {
        return $item->getOptions()->get('url');
    }
}