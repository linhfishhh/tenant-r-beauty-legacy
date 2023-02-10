<?php

namespace App\Http\Controllers\Backend;

use App\Classes\Theme;
use App\Http\Controllers\Controller;
use App\Http\Requests\SidebarStoreUpdate;
use App\Http\Requests\WidgetOptionSave;
use App\Sidebar;
use App\ThemeSidebar;
use App\Widget;
use DataTables;
use Illuminate\Http\Request;
use Redirect;
use Response;

class SidebarController extends Controller
{
    function locations(Request $request){
        $theme = Theme::getCurrentTheme();
	    $sidebars = Sidebar::query()->orderBy('id')->get();
	    if($theme){
            $locations = Theme::getSidebarLocations();
            $load_sidebars = ThemeSidebar::whereTheme($theme->getAlias())->get();
        }
	    else{
	        $locations = [];
	        $load_sidebars = [];
        }
	    return view('backend.pages.sidebar.locations', ['locations' => $locations, 'sidebars' => $sidebars, 'load_sidebars' => $load_sidebars]);

    }

	function saveLocations(Request $request){
		if($request->ajax()){
			$theme = Theme::getCurrentTheme();
			$sidebars = ThemeSidebar::whereTheme($theme->getAlias())->get();
			foreach ($sidebars as $sidebar){
				$sidebar->delete();
			}
			$locations = $request->get('sidebars', false);
			foreach ($locations as $location){
				if(!$location['sidebar_id']){
					continue;
				}
				$sidebar = new ThemeSidebar();
				$sidebar->language = $location['language'];
				$sidebar->sidebar_id = $location['sidebar_id'];
				$sidebar->location = $location['location'];
				$sidebar->theme = $theme->getAlias();
				$sidebar->save();
			}
			return Response::json($locations);
		}
		return Redirect::route('backend.sidebar.location.index');
	}

    function library(Request $request){
        if ($request->ajax()) {
            $sidebars = Sidebar::withCount('widgets');
            return DataTables::eloquent($sidebars)->addColumn('widgets_count', function (Sidebar $sidebar) {
                return $sidebar->widgets_count;
            })
                ->addColumn('link', function (Sidebar $sidebar) {
                    return route('backend.sidebar.edit', [$sidebar]);
                })
                ->make(true);
        }
        return view('backend.pages.sidebar.library');
    }

    function destroySidebar(Request $request){
        $ids = $request->get('ids', []);
        /** @var \Illuminate\Database\Eloquent\Builder $sidebars */
        $sidebars = Sidebar::whereIn('id', $ids);
        $sidebars =  $sidebars->get();
        foreach ($sidebars as $sidebar){
            $sidebar->delete();
        }
        if ($request->ajax()) {
            return Response::json('');
        }

        return Redirect::route('backend.sidebar.library.index');
    }

    function createSidebar(){
        $event = app('widget_types');
        return view('backend.pages.sidebar.edit', [
            'model' => null,
            'event' => $event
        ]);
    }

    function storeSidebar(SidebarStoreUpdate $form){
	    if($form->ajax()){
		    $sidebar = new Sidebar();
		    $sidebar->title = $form->get('title');
		    $sidebar->save();
		    $this->storeWidgets( $form->get( 'widgets'), $sidebar->id);
		    return Response::json(route('backend.sidebar.edit', ['sidebar' => $sidebar]));
	    }
	    return Redirect::route('backend.sidebar.library.index');
    }

    function editSidebar(Sidebar $sidebar){
	    $event = app('widget_types');
	    return view('backend.pages.sidebar.edit', [
		    'model' => $sidebar,
		    'event' => $event,
	    ]);
    }

    function updateSidebar(SidebarStoreUpdate $form, Sidebar $sidebar){
	    if($form->ajax()){
		    $sidebar->title = $form->get('title');
		    $sidebar->save();
		    $widgets = Widget::whereSidebarId( $sidebar->id)->get();
		    foreach ($widgets as $widget){
		    	$widget->delete();
		    }
		    $this->storeWidgets( $form->get( 'widgets'), $sidebar->id);
		    return Response::json('');
	    }
	    return Redirect::route('backend.sidebar.library.index');
    }

	function storeWidgets($widget_datas, $sidebar_id){
		foreach ($widget_datas as $widget_data){
			$widget = new Widget();
			$widget->title = $widget_data['title'];
			$widget->type = $widget_data['type'];
			$widget->classes = $widget_data['classes']==null?'':$widget_data['classes'];
			$widget->login_status = $widget_data['login_status'];
			$widget->roles = json_encode(isset($widget_data['roles'])?$widget_data['roles']:[]);
			$widget->sidebar_id = $sidebar_id;
			$widget->options = json_encode( $widget_data['options']);
			$widget->save();
		}
	}

	function saveWidgetOption(WidgetOptionSave $form){
		if($form->ajax()){
			$keys = [
				'title',
				'classes',
				'roles',
				'login_status'
			];
			$item = $form->only( $keys);
			if(!$form->has( 'roles')){
				$item['roles'] = [];
			}
			$keys[] = 'widget_type';
			$options = $form->except( $keys);
			$item['options'] = $options;
			$item['type'] = $form->get( 'widget_type');
			return Response::json($item);
		}
		return Redirect::route('backend.sidebar.library.index');
	}
}
