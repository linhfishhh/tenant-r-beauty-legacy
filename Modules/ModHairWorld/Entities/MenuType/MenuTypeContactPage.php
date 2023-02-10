<?php

namespace Modules\ModHairWorld\Entities\MenuType;


use App\Classes\MenuType;

class MenuTypeContactPage extends MenuType
{
    public function getURL($item)
    {
        return route('frontend.contact');
    }

    public function checkActive($item)
    {
        return \Route::currentRouteNamed('frontend.contact');
    }


    function getID(): string
    {
        return 'contact';
    }

    function getTitle(): string
    {
        return 'Trang liên hệ';
    }

    function getOrder(): int
    {
        return 9;
    }

    function getGroupID(): string
    {
        return 'basic';
    }

    function getIcon(): string
    {
        return 'icon-phone2';
    }

    function getHtmlView()
    {
        return '';
    }

    function rules(array $rules): array
    {
        return [];
    }

    function messages(array $messages): array
    {
        return [];
    }
}