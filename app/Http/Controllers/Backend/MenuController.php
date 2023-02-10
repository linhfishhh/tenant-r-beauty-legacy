<?php

namespace App\Http\Controllers\Backend;

use App\Classes\Theme;
use App\Events\MenuTypeRegister;
use App\Http\Requests\MenuOptionSave;
use App\Http\Requests\MenuStoreUpdate;
use App\Menu;
use App\MenuItem;
use App\ThemeMenu;
use DataTables;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Redirect;
use Response;

class MenuController extends Controller
{
    function locations(Request $request)
    {
        $theme = Theme::getCurrentTheme();
        $menus = Menu::query()->orderBy('id')->get();
        if($theme){
            $locations = Theme::getMenuLocations();
            $load_menus = ThemeMenu::whereTheme($theme->getAlias())->get();
        }
        else{
            $locations = [];
            $load_menus = '';
        }
        return view('backend.pages.menu.locations', ['locations' => $locations, 'menus' => $menus, 'load_menus' => $load_menus]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws AuthorizationException
     */
    function saveLocations(Request $request){
        if($request->ajax()){
            $theme = Theme::getCurrentTheme();
            if(!$theme){
                throw new AuthorizationException('');
            }
            $menus = ThemeMenu::whereTheme($theme->getAlias())->get();
            foreach ($menus as $menu){
                $menu->delete();
            }
            $locations = $request->get('menus', false);
            foreach ($locations as $location){
                if(!$location['menu_id']){
                    continue;
                }
                $menu = new ThemeMenu();
                $menu->language = $location['language'];
                $menu->menu_id = $location['menu_id'];
                $menu->location = $location['location'];
                $menu->theme = $theme->getAlias();
                $menu->save();
            }
            return Response::json($locations);
        }
        return Redirect::route('backend.menu.location.index');
    }

    function library(Request $request)
    {
        if ($request->ajax()) {
            $menus = Menu::withCount('items');
            return DataTables::eloquent($menus)->addColumn('items_count', function (Menu $menu) {
                return $menu->items_count;
            })
                ->addColumn('link', function (Menu $menu) {
                    return route('backend.menu.edit', [$menu]);
                })
                ->make(true);
        }
        return view('backend.pages.menu.library');
    }

    function editMenu(Request $request, Menu $menu){
        $event = app('menu_types');
        return view('backend.pages.menu.edit', [
            'model' => $menu,
            'event' => $event,
        ]);
    }

    function updateMenu(MenuStoreUpdate $form, Menu $menu){
        if($form->ajax()){
            $menu->title = $form->get('title');
            $menu->save();
            $items = MenuItem::whereMenuId($menu->id)->get();
            foreach ($items as $item){
                $item->delete();
            }
            $item_datas = $form->get('items', []);
            $this->storeItem($item_datas, "0", 0, $menu->id);
            return Response::json('');
        }
        return Redirect::route('backend.menu.library.index');
    }

    function createMenu(Request $request){
        $event = app('menu_types');
        return view('backend.pages.menu.edit', [
            'model' => null,
            'event' => $event
        ]);
    }

    function storeMenu(MenuStoreUpdate $form){
        if($form->ajax()){
            $menu = new Menu();
            $menu->title = $form->get('title');
            $menu->save();
            $item_datas = $form->get('items', []);
            $this->storeItem($item_datas, "0", 0, $menu->id);
            return Response::json(route('backend.menu.edit', ['menu' => $menu]));
        }
        return Redirect::route('backend.menu.library.index');
    }

    private function storeItem($item_datas, $parent_key, $parent_id, $menu_id){
        foreach ($item_datas as $item_data){
            if($item_data['parent'] == $parent_key){
                $data = $item_data['data'];
                $item = new MenuItem();
                $item->title = $data['title'];
                $item->parent_id = $parent_id;
                $item->icon = $data['icon']==null?'':$data['icon'];
                $item->classes = $data['classes']==null?'':$data['classes'];
                $item->attributes = $data['attributes']==null?'':$data['attributes'];
                $item->target = $data['target'];
                $item->login_status = $data['login_status'];
                $item->roles = json_encode(isset($data['roles'])?$data['roles']:[]);
                $item->options = json_encode(isset($data['options'])?$data['options']:[]);
                $item->type = $data['type'];
                $item->menu_id = $menu_id;
                $item->save();
                $this->storeItem($item_datas, $item_data['key'], $item->id, $menu_id);
            }
        }
    }

    function destroyMenu(Request $request){
        $ids = $request->get('ids', []);
        /** @var \Illuminate\Database\Eloquent\Builder $menus */
        $menus = Menu::whereIn('id', $ids);
        $menus =  $menus->get();
        foreach ($menus as $menu){
            $menu->delete();
        }
        if ($request->ajax()) {
            return Response::json('');
        }

        return Redirect::route('backend.menu.library.index');
    }

    function saveMenuOption(MenuOptionSave $form){
        if($form->ajax()){
            $item = [
                'title' => $form->get('title'),
                'data' => []
            ];
            $keys = [
                'title',
                'icon',
                'classes',
                'attributes',
                'target',
                'roles',
                'login_status'
            ];
            $info = $form->only($keys);
            $keys[] = 'menu_type';
            $options = $form->except($keys);
            $item['data']['info'] = $info;
            $item['data']['options'] = $options;
            $item['data']['type'] = $form->get('menu_type');
            if(!isset($item['data']['info']['roles'])){
                $item['data']['info']['roles'] = [];
            }
            if(!isset($item['data']['info']['icon'])){
                $item['data']['info']['icon'] = '';
            }
            return Response::json($item);
        }
        return Redirect::route('backend.menu.library.index');
    }
}
