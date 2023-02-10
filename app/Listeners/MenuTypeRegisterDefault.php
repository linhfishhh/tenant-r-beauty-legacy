<?php

namespace App\Listeners;

use App\Classes\MenuType\MenuTypeCustomLink;
use App\Classes\MenuType\MenuTypeFile;
use App\Classes\MenuType\MenuTypeHomepage;
use App\Classes\MenuType\MenuTypePost;
use App\Classes\MenuType\MenuTypePostType;
use App\Classes\MenuType\MenuTypeTaxonomy;
use App\Classes\MenuType\MenuTypeTerm;
use App\Classes\MenuTypeGroup;
use App\Events\MenuTypeRegister;

class MenuTypeRegisterDefault
{
    public function handle(MenuTypeRegister $event)
    {
        $group = new MenuTypeGroup('basic', __('Cơ bản'), -1);
        $event->registerTypeGroup($group);

        $type = new MenuTypeHomepage();
        $event->registerType($type);

        $type = new MenuTypeCustomLink();
        $event->registerType($type);

        $type = new MenuTypeFile();
        $event->registerType($type);

        $post_types = getPostTypes();
        if(count($post_types)>0){
            $group = new MenuTypeGroup('content', __('Nội dung'), 0);
            $event->registerTypeGroup($group);

            $type = new MenuTypePost();
            $event->registerType($type);

            $type = new MenuTypePostType();
            $event->registerType($type);

            $type = new MenuTypeTerm();
            $event->registerType($type);

            $type = new MenuTypeTaxonomy();
            $event->registerType($type);
        }
    }
}
