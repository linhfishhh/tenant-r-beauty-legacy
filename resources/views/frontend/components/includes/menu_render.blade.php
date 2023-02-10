@php
/** @var \App\MenuItem $item */
@endphp
@foreach($items as $item)
    @if($item->parent_id == $parent_id)
        @php
            $active_if_child_active = $data['active_if_child_active'];
            $has_submenu = $item->checkIfHasChildrenIn($items);
            $is_active = $item->isActive() || ($item->hasActiveChildrenIn($items) && $active_if_child_active);
            $url = $item->getURL();
            $item_class = $data['item_class'];
            $has_submenu_class = $data['has_submenu_class'];
            $link_class = $data['link_class'];
            $submenu_class = $data['submenu_class'];
            $show_icon = $data['show_icon'];
            $active_item_class = $data['active_item_class'];
            $active_link_class = $data['active_link_class'];

            $item_class = $has_submenu?$item_class.' '.$has_submenu_class:$item_class;
            if($is_active){
                $item_class = $item_class . ' '.$active_item_class;
                $link_class = $link_class . ' '.$active_link_class;
            }

            $item_class = trim($item_class);
            $link_class = trim($link_class);
            $current_depth = $current_depth + 1;
        @endphp
        <li @if($item_class) class="{!! $item_class !!}" @endif>
            <a target="{!! $item->target !!}" @if($link_class) class="{!! $link_class !!}" @endif  href="{!! $url !!}">
                @if($show_icon && $item->icon)
                    <i class="{!! $item->icon !!}"></i>
                    @enqueueCSSByID(config('view.ui.files.css.icomoon.id'))
                @endif
                <span>{!! $item->title !!}</span>
            </a>
            @if($has_submenu && (($depth_limit == 0) || ($current_depth<=$depth_limit)))
                <ul @if($submenu_class) class="{!! $submenu_class !!}" @endif>
                    @include('frontend.components.includes.menu_render', ['items'=>$menu->items, 'parent_id' => $item->id, 'data' => $data, 'current_depth' => ($current_depth), 'depth_limit' => $depth_limit])
                </ul>
            @endif
        </li>
    @endif
@endforeach