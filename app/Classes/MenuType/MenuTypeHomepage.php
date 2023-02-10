<?php

namespace App\Classes\MenuType;

use App\Classes\MenuType;
use App\Classes\Theme;

class MenuTypeHomepage extends MenuType
{

    function getID(): string
    {
        return 'home_page';
    }

    function getTitle(): string
    {
        return __('Trang chủ');
    }

    function getOrder(): int
    {
        return 0;
    }

    function getGroupID(): string
    {
        return 'basic';
    }

    function getIcon(): string
    {
        return 'icon-home';
    }

    function getHtmlView()
    {
        return false;
    }

    public function checkActive($item)
    {
        return \Route::currentRouteNamed('frontend.index');
    }

    public function getURL($item)
    {
        return url('');
    }


    function rules(array $rules): array
    {
        return $rules;
    }

    function messages(array $messages): array
    {
        return $messages;
    }
}