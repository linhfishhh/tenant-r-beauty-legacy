@foreach($items as $item)
    @if($item['parent'] == $parent)
        @if($parent == false)
            <li class="navigation-header" data-menu-id="{!! $item['id'] !!}" data-menu-order="{!! $item['order'] !!}">
                <span>{{$item['title']}}</span> <i class="{{$item['icon']}}" title="{{$item['title']}}"></i>
                @if($item['has_children'])
                    @include('backend.includes.mainnav_render', ['items' => $items, 'parent' => $item['id']])
                @endif
            </li>
        @else
            @if($item['active'])
                <li class="active" data-menu-id="{!! $item['id'] !!}">
            @else
                <li data-menu-id="{!! $item['id'] !!}">
                    @endif
                    <a href="{{$item['link']}}"><i class="{{$item['icon']}}"></i> <span>{{$item['title']}}</span></a>
                    @if($item['has_children'])
                        <ul>
                            @include('backend.includes.mainnav_render', ['items' => $items, 'parent' => $item['id']])
                        </ul>
                    @endif
                </li>
        @endif
    @endif
@endforeach