<?php
/**
 * Created by PhpStorm.
 * User: TRANG
 * Date: 01-Jul-18
 * Time: 19:47
 */

namespace Modules\ModHairWorld\Entities\MenuType;


use App\Classes\MenuType;

class MenuTypeSearchPage extends MenuType
{

    public function getURL($item)
    {
        return route('frontend.search');
    }

    public function checkActive($item)
    {
        return \Route::currentRouteNamed('frontend.search');
    }

    function getID(): string
    {
        return 'search';
    }

    function getTitle(): string
    {
        return __('Trang tìm kiếm');
    }

    function getOrder(): int
    {
        return 1;
    }

    function getGroupID(): string
    {
        return 'basic';
    }

    function getIcon(): string
    {
        return ' icon-search4';
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