<?php

use Illuminate\Support\Collection;
const JS_LOCATION_HEAD    = 'head';
const JS_LOCATION_FOOTER  = 'footer';
const JS_LOCATION_BODY    = 'body';
const JS_LOCATION_DEFAULT = 'footer';

function enqueueBundle( $bundle_id ) {
    $bundle = Config::get(
        'view.ui.bundles.' . $bundle_id,
        false
    );
    if ( $bundle ) {
        if ( isset( $bundle['css'] ) ) {
            foreach ( $bundle['css'] as $css ) {
                $script_id = $css['src'];
                $require   = false;
                if ( isset( $css['require'] ) ) {
                    $require = $css['require'];
                };
                $attributes = [];
                if ( isset( $css['attributes'] ) ) {
                    $attributes = $css['attributes'];
                }
                enqueueCSSByID(
                    $script_id,
                    $require,
                    $attributes
                );
            }
        }
        if ( isset( $bundle['js'] ) ) {
            foreach ( $bundle['js'] as $js ) {
                $script_id = $js['src'];
                $require   = false;
                if ( isset( $js['require'] ) ) {
                    $require = $js['require'];
                };
                $attributes = [];
                if ( isset( $js['attributes'] ) ) {
                    $attributes = $js['attributes'];
                };
                $location = JS_LOCATION_DEFAULT;
                if ( isset( $js['location'] ) ) {
                    $location = $js['location'];
                }
                enqueueJSByID(
                    $script_id,
                    $location,
                    $require,
                    $attributes
                );
            }
        }
    }
}

function enqueueJSByID(
    $script_id,
    $location = JS_LOCATION_DEFAULT,
    $require = false,
    $attributes = []
) {
    $script_id = strtolower( $script_id );
    $file      = Config::get(
        'view.ui.files.js.' . $script_id . '.src',
        false
    );
    if ( $file ) {
        enqueueJS(
            $script_id,
            asset( $file ),
            $location,
            $require,
            $attributes
        );
    }
}

/**
 * @param        $script_id
 * @param        $script_file
 * @param string $location
 * @param bool   $require
 * @param array  $attributes
 *
 * @return bool|void
 */
function enqueueJS(
    $script_id,
    $script_file,
    $location = JS_LOCATION_DEFAULT,
    $require = false,
    $attributes = []
) {
    if ( ! in_array(
        $location,
        [
            JS_LOCATION_BODY,
            JS_LOCATION_HEAD,
            JS_LOCATION_FOOTER,
        ]
    ) ) {
        return false;
    }
    $script_id = strtolower( $script_id );
    if ( $require ) {
        $require = strtolower( $require );
    }
    /** @var Collection $queue */
    $queue = app( 'script_queue' );
    if ( ! $queue->has( $script_id ) ) {
        $queue->put(
            $script_id,
            [
                'src'        => $script_file,
                'require'    => $require,
                'attributes' => $attributes,
                'location'   => $location,
            ]
        );
    }
}

function _renderJS(
    $require,
    $queue,
    $location
) {
    foreach ( $queue as $script_id => $script ) {
        if ( $script['location'] != $location ) {
            continue;
        }
        if ( $script['require'] != $require ) {
            continue;
        }
        $attributes = '';
        if ( $script['attributes'] ) {
            $attributes = [];
            foreach ( $script['attributes'] as $name => $value ) {
                $attributes[] = sprintf(
                    '%s="%s"',
                    $name,
                    $value
                );
            }
            $attributes = implode(
                ' ',
                $attributes
            );
        }
        echo sprintf(
            '<script src="%s" %s type="text/javascript" data-js-id="%s"></script>' . PHP_EOL,
            $script['src'],
            $attributes,
            $script_id
        );
        _renderJS(
            $script_id,
            $queue,
            $location
        );
    }
}

/**
 * @param $location
 *
 * @return bool|void
 */
function showJSQueue(
    $location
) {
    if ( ! in_array(
        $location,
        [
            JS_LOCATION_BODY,
            JS_LOCATION_HEAD,
            JS_LOCATION_FOOTER,
        ]
    ) ) {
        return false;
    }
    /** @var Collection $queue */
    $queue = app( 'script_queue' );
    _renderJS(
        false,
        $queue,
        $location
    );
}

function enqueueCSSByID(
    $script_id,
    $require = false,
    $attributes = []
) {
    $script_id = strtolower( $script_id );
    $file      = Config::get(
        'view.ui.files.css.' . $script_id . '.src',
        false
    );
    if ( $file ) {
        enqueueCSS(
            $script_id,
            asset( $file ),
            $require,
            $attributes
        );
    }
}

function enqueueCSS(
    $script_id,
    $script_file,
    $require = false,
    $attributes = []
) {
    $script_id = strtolower( $script_id );
    if ( $require ) {
        $require = strtolower( $require );
    }
    /** @var Collection $queue */
    $queue = app( 'css_queue' );
    if ( ! $queue->has( $script_id ) ) {
        $queue->put(
            $script_id,
            [
                'src'        => $script_file,
                'require'    => $require,
                'attributes' => $attributes,
            ]
        );
    }
}

function _renderCSS(
    $require,
    $queue
) {
    foreach ( $queue as $script_id => $script ) {
        if ( $script['require'] != $require ) {
            continue;
        }
        $attributes = '';
        if ( $script['attributes'] ) {
            $attributes = [];
            foreach ( $script['attributes'] as $name => $value ) {
                $attributes[] = sprintf(
                    '%s="%s"',
                    $name,
                    $value
                );
            }
            $attributes = implode(
                ' ',
                $attributes
            );
        }
        echo sprintf(
            '<link rel="stylesheet" type="text/css" href="%s" %s data-css-id="%s" />' . PHP_EOL,
            $script['src'],
            $attributes,
            $script_id
        );
        _renderCSS(
            $script_id,
            $queue
        );
    }
}

function showCSSQueue() {
    /** @var Collection $queue */
    $queue = app( 'css_queue' );
    _renderCSS(
        false,
        $queue
    );
}
function getBackendMenuItems() {
    function backendMenuItemHasChildren($item_id, $items){
        $rs = false;
        foreach ($items as $item){
            /** @var \App\Classes\BackendMenuItem $item */
            if($item->parent == $item_id){
                $rs = true;
                break;
            }
        }
        return $rs;
    }
    function checkIfRouteIsActive($route_to_check){
        $rs = false;
        if(is_array( $route_to_check)){
            $route_data = $route_to_check;
            if(count( $route_data)==2){
                $route = $route_data[0];
                $route_params = $route_data[1];
                if(is_array( $route_params)){
                    if(Route::has( $route)){
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
        }
        elseif(is_string( $route_to_check)){
            if(Route::has( $route_to_check)){
                $rs = Route::currentRouteName() == $route_to_check;
            }
        }
        
        return $rs;
    }
    /** @var \App\Classes\BackendMenuItem[] $menu */
    $menu = app( 'backend_menus' );
    $rs   = collect();
    foreach ( $menu as $item ) {
        $link = '#';
        $active = false;
        if($item->route){
            if(is_array( $item->route)){
                $route_data = $item->route;
                if(count( $route_data)>1){
                    $route = $route_data[0];
                    $route_params = $route_data[1];
                    if(is_array( $route_params)){
                        if(Route::has( $route)){
                            $link = route( $route, $route_params);
                            $active = Route::currentRouteName() == $route;
                        }
                    }
                }
            }
            elseif(is_string( $item->route)){
                if(Route::has( $item->route)){
                    $link = route( $item->route);
                    $active = Route::currentRouteName() == $item->route;
                }
            }
        }
        $event = new \App\Events\BackendMenuItemCheckActive( $item->id);
        event($event);
        foreach ($event->include_routes as $route){
            $sub_active = checkIfRouteIsActive($route);
            if($sub_active){
                $active = true;
                break;
            }
        }
        $rs->put(
            $item->id,
            [
                'id'     => $item->id,
                'title'  => $item->title,
                'icon'   => $item->icon,
                'link'   => $link,
                'parent' => $item->parent,
                'active' => $active,
                'order' => $item->order,
                'has_children' => backendMenuItemHasChildren(
                    $item->id,
                    $menu)
            ]
        );
    }
    return $rs;
}

