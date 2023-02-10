@event(new \App\Events\BeforeHtmlBlock('sidebar.groups'))
<div class="sidebar-category" data-group-id="menu-lib">
    <div class="category-title">
        <span>{{__('Thư viện menu')}}</span>
        <ul class="icons-list">
            <li><a href="#" data-action="collapse" class=""></a></li>
        </ul>
    </div>
    <div class="category-content no-padding" style="display: block;">
        <ul class="navigation navigation-alt navigation-accordion datatable-users-actions">
            @event(new \App\Events\BeforeHtmlBlock('sidebar.groups.menu-lib'))
            <li>
                <a href="{{route('backend.menu.create')}}"><i class="icon-add"></i> {{__('Tạo menu')}}</a>
            </li>
            @notRoute('backend.menu.library.index')
            <li>
                <a href="{{route('backend.menu.library.index')}}"><i class="icon-tree6"></i> {{__('Thư viện menu')}}</a>
            </li>
            @endif
            @event(new \App\Events\AfterHtmlBlock('sidebar.groups.menu-lib'))
        </ul>
    </div>
</div>
@notRoute('backend.menu.location.index')
<div class="sidebar-category" data-group-id="menu-location">
    <div class="category-title">
        <span>{{__('Thiết lập menu')}}</span>
        <ul class="icons-list">
            <li><a href="#" data-action="collapse" class=""></a></li>
        </ul>
    </div>
    <div class="category-content no-padding" style="display: block;">
        <ul class="navigation navigation-alt navigation-accordion datatable-users-actions">
            @event(new \App\Events\BeforeHtmlBlock('sidebar.groups.menu-location'))
            <li>
                <a href="{{route('backend.menu.location.index')}}"><i class="icon-pencil5"></i> {{__('Menu trên giao diện')}}</a>
            </li>
            @event(new \App\Events\AfterHtmlBlock('sidebar.groups.menu-location'))
        </ul>
    </div>
</div>
@endif
@event(new \App\Events\AfterHtmlBlock('sidebar.groups'))