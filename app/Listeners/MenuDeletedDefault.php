<?php

namespace App\Listeners;

use App\Events\Menu\MenuDeleted;
use App\MenuItem;
use App\ThemeMenu;

class MenuDeletedDefault
{
    public function __construct()
    {
        //
    }

    public function handle(MenuDeleted $event)
    {
        $menu_id = $event->model->id;
        $items = MenuItem::whereMenuId($menu_id)->get();
        foreach ($items as $item){
            $item->delete();
        }
        $locations = ThemeMenu::whereMenuId($menu_id)->get();
        foreach ($locations as $item){
            $item->delete();
        }
    }
}
