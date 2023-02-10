<?php

namespace App\Listeners;

use App\Events\Sidebar\SidebarDeleted;
use App\ThemeSidebar;
use App\Widget;

class SidebarDeletedDefault
{
    public function __construct()
    {
        //
    }

    public function handle(SidebarDeleted $event)
    {
        $sidebar_id = $event->model->id;
        $items = Widget::whereSidebarId($sidebar_id)->get();
        foreach ($items as $item){
            $item->delete();
        }
        $locations = ThemeSidebar::whereSidebarId($sidebar_id)->get();
        foreach ($locations as $item){
            $item->delete();
        }
    }
}
