@isset($location_id)
    @php
        $menu = getThemeMenu($location_id);
    @endphp
    @if($menu && $menu->items)
        @php
            $items = $menu->getVisibleItems();
            if(isset($title_html)):
                $title_html  = str_replace('{title}', $menu->title, $title_html);
            else:
                $title_html = '';
            endif;
            $data = [
                        'item_class' => isset($item_class)?$item_class:'',
                        'active_item_class' => isset($active_item_class)?$active_item_class:'',
                        'link_class' => isset($link_class)?$link_class:'',
                        'active_link_class' => isset($active_link_class)?$active_link_class:'',
                        'submenu_class' => isset($submenu_class)?$submenu_class:'',
                        'has_submenu_class' => isset($has_submenu_class)?$has_submenu_class:'',
                        'show_icon' => isset($show_icon)?$show_icon:false,
                        'active_if_child_active' => $active_if_child_active,
                    ];
            $current_depth = 1;
            $depth_limit = isset($depth_limit)?$depth_limit:0;
            if($depth_limit<0){
                $depth_limit = 0;
            }
        @endphp
        {!! $title_html !!}
        <ul class="{!! isset($menu_class)?$menu_class:'' !!}">
            @include('frontend.components.includes.menu_render', ['items'=>$items, 'parent_id' => 0, 'data' => $data, 'current_depth' => $current_depth, 'depth_limit' => $depth_limit])
        </ul>
    @endif
@endisset