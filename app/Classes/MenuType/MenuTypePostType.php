<?php

namespace App\Classes\MenuType;

use App\Classes\FieldInput\FieldInputPostType;
use App\Classes\MenuTypeWithFieldInput;
use App\Classes\PostType;

class MenuTypePostType extends MenuTypeWithFieldInput
{

    function get_id(): string
    {
        return 'post_type';
    }

    function get_title(): string
    {
        return __('Danh sách nội dung');
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
            new FieldInputPostType(
                'post_type',
                '',
                __('Loại nội dung'),
                '',
                1,
                FieldInputPostType::buildConfigs(0)
                ),

        ];
    }

    public function checkActive($item)
    {
        $options = $item->getOptions();
        if(!$options){
            return false;
        }
        /** @var PostType $post_type */
        $post_type = $options->get('post_type', false);
        if(!$post_type){
            return false;
        }
        return \Route::currentRouteNamed($post_type::getPublicIndexRouteName());
    }

    public function getURL($item)
    {
        $options = $item->getOptions();
        if(!$options){
            return false;
        }
        /** @var PostType $post_type */
        $post_type = $options->get('post_type', false);
        if(!$post_type){
            return false;
        }
        return $post_type::getPublicIndexUrl();
    }
}