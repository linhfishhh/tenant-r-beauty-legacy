@event(new \App\Events\BeforeHtmlBlock('sidebar.groups'))
<div class="sidebar-category" data-group-id="menu-lib">
    <div class="category-title">
        <span>{{__('Thư viện sidebar')}}</span>
        <ul class="icons-list">
            <li><a href="#" data-action="collapse" class=""></a></li>
        </ul>
    </div>
    <div class="category-content no-padding" style="display: block;">
        <ul class="navigation navigation-alt navigation-accordion datatable-users-actions">
            @event(new \App\Events\BeforeHtmlBlock('sidebar.groups.sidebar-lib'))
            <li>
                <a href="{{route('backend.sidebar.create')}}"><i class="icon-add"></i> {{__('Tạo sidebar')}}</a>
            </li>
            @notRoute('backend.sidebar.library.index')
            <li>
                <a href="{{route('backend.sidebar.library.index')}}"><i class="icon-tree6"></i> {{__('Thư viện sidebar')}}</a>
            </li>
            @endif
            @event(new \App\Events\AfterHtmlBlock('sidebar.groups.sidebar-lib'))
        </ul>
    </div>
</div>
@notRoute('backend.sidebar.location.index')
<div class="sidebar-category" data-group-id="menu-location">
    <div class="category-title">
        <span>{{__('Thiết lập sidebar')}}</span>
        <ul class="icons-list">
            <li><a href="#" data-action="collapse" class=""></a></li>
        </ul>
    </div>
    <div class="category-content no-padding" style="display: block;">
        <ul class="navigation navigation-alt navigation-accordion datatable-users-actions">
            @event(new \App\Events\BeforeHtmlBlock('sidebar.groups.sidebar-location'))
            <li>
                <a href="{{route('backend.sidebar.location.index')}}"><i class="icon-pencil5"></i> {{__('Sidebar trên giao diện')}}</a>
            </li>
            @event(new \App\Events\AfterHtmlBlock('sidebar.groups.sidebar-location'))
        </ul>
    </div>
</div>
@endif
@event(new \App\Events\AfterHtmlBlock('sidebar.groups'))