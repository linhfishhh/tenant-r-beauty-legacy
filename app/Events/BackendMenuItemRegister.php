<?php

namespace App\Events;

use App\Classes\BackendMenuItem;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Collection;
use Route;

class BackendMenuItemRegister {
    use Dispatchable;

    /** @var Collection $items */
    private $items;
	private $after_register;

	public function sortItems(){
        $this->items = $this->items->sortBy('order');
    }

    public function getItems(){
    	return $this->items;
    }

    /**
     * @param BackendMenuItem[] $items
     */
    public function register( $items = [] ) {
        if(!\Auth::user()){
            return;
        }
        /** @var Collection $menu */
        $menu = $this->items;
        foreach ( $items as $item ) {
            if ( $menu->has( $item->id ) ) {
                continue;
            }
            if ( $item->has_one_permission
                 && ! \Auth::user()
                           ->hasAnyPermissions(
                               $item->permissions ? $item->permissions : []
                           ) ) {
                continue;
            }
            if ( ! $item->has_one_permission
                 && ! \Auth::user()
                           ->hasAllPermissions(
                               $item->permissions ? $item->permissions : []
                           ) ) {
                continue;
            }
            $menu->put(
                $item->id,
                $item
            );
        }
    }
    
    private function backendMenuItemHasChildren(
        $item_id,
        $items
    ) {
        $rs = false;
        foreach ( $items as $item ) {
            /** @var \App\Classes\BackendMenuItem $item */
            if ( $item->parent == $item_id ) {
                $rs = true;
                break;
            }
        }
        
        return $rs;
    }
    
    public function checkIfRouteIsActive( $route_to_check ) {
        $rs = false;
        if ( is_array( $route_to_check ) ) {
            $route_data = $route_to_check;
            if ( count( $route_data ) == 2 ) {
                $route        = $route_data[0];
                $route_params = $route_data[1];
                if ( is_array( $route_params ) ) {
                    if ( Route::has( $route ) ) {
                        $rs = Route::currentRouteName() == $route;
                    }
                }
            }
            elseif(count( $route_data)==3){
	            $route = $route_data[0];
	            $route_params = $route_data[1];
	            $route_check_params = $route_data[2];
	            if(is_array( $route_params) && is_array( $route_check_params)){
		            if(Route::has( $route)){
			            $rs1 = true;
			            foreach ($route_check_params as $name=>$value){
				            if(Route::current()->parameter( $name) != $value){
					            $rs1 = false;
					            break;
				            }
			            }
			            $rs2 = Route::currentRouteName() == $route;
			            $rs = $rs1 && $rs2;
		            }
	            }
            }
        } elseif ( is_string( $route_to_check ) ) {
            if ( Route::has( $route_to_check ) ) {
                $rs = Route::currentRouteName() == $route_to_check;
            }
        }
        
        return $rs;
    }
    
    public function bladeItems() {
        /** @var \App\Classes\BackendMenuItem[] $menu */
        $menu = $this->items;
        $rs   = collect();
        foreach ( $menu as $item ) {
            $link   = '#';
            $active = false;
            if ( $item->route ) {
                if ( is_array( $item->route ) ) {
                    $route_data = $item->route;
                    if ( count( $route_data ) == 2 ) {
                        $route        = $route_data[0];
                        $route_params = $route_data[1];
                        if ( is_array( $route_params ) ) {
                            if ( Route::has( $route ) ) {
                                $link   = route(
                                    $route,
                                    $route_params
                                );
                                $active = Route::currentRouteName() == $route;
                            }
                        }
                    }
                    elseif(count( $route_data)==3){
	                    $route = $route_data[0];
	                    $route_params = $route_data[1];
	                    $route_check_params = $route_data[2];
	                    if(is_array( $route_params) && is_array( $route_check_params)){
		                    if(Route::has( $route)){
			                    $link   = route(
				                    $route,
				                    $route_params
			                    );
			                    $rs1 = true;
			                    foreach ($route_check_params as $name=>$value){
				                    if(Route::current()->parameter( $name) != $value){
					                    $rs1 = false;
					                    break;
				                    }
			                    }
			                    $rs2 = Route::currentRouteName() == $route;
			                    $active = $rs1 && $rs2;
		                    }
	                    }
                    }
                } elseif ( is_string( $item->route ) ) {
                    if ( Route::has( $item->route ) ) {
                        $link   = route( $item->route );
                        $active = Route::currentRouteName() == $item->route;
                    }
                    else{
                        $link   = $item->route;
                    }
                }
            }
            $event = new BackendMenuItemCheckActive( $item->id );
            event( $event );
            foreach ( $event->include_routes as $route ) {
                $sub_active = $this->checkIfRouteIsActive( $route );
                if ( $sub_active ) {
                    $active = true;
                    break;
                }
            }
            $rs->put(
                $item->id,
                [
                    'id'           => $item->id,
                    'title'        => $item->title,
                    'icon'         => $item->icon,
                    'link'         => $link,
                    'parent'       => $item->parent,
                    'active'       => $active,
                    'order'        => $item->order,
                    'has_children' => $this->backendMenuItemHasChildren(
                        $item->id,
                        $menu
                    ),
                ]
            );
        }
        $rs = $this->removeLeafs( $rs);;
        return $rs;
    }
    
    public function __construct() {
        $this->items = collect();
	    $this->after_register = [];
    }

	public function hook_after_register(\Closure $function){
		$this->after_register[] = $function;
	}

	public function do_after_register(){
		foreach ($this->after_register as $func){
			$func($this);
		}
	}
    
    private function countleafs($items){
        $c = 0;
        foreach ($items as $item){
            if(!$this->backendMenuItemHasChildrenA(
                $item['id'],
                $items)){
                if($item['link'] == '#'){
                    $c++;
                }
            }
        }
        return $c;
    }
    
    private function removeLeafs($items){
        $rs = [];
        foreach ($items as $item){
            if(!$this->backendMenuItemHasChildrenA(
                $item['id'],
                $items)){
                if($item['link'] == '#'){
                    continue;
                }
            }
            $rs[] = $item;
        }
        if($this->countleafs( $rs)>0){
            return $this->removeLeafs( $rs);
        }
        else{
            return $rs;
        }
    }
    
    private function backendMenuItemHasChildrenA(
        $item_id,
        $items
    ) {
        $rs = false;
        foreach ( $items as $item ) {
            /** @var \App\Classes\BackendMenuItem $item */
            if ( $item['parent'] == $item_id ) {
                $rs = true;
                break;
            }
        }
        
        return $rs;
    }
    
    
}
