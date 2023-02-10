@event(new \App\Events\BeforeHtmlBlock('sidebar.groups'))
<div class="sidebar-category" data-group-id="menu-lib">
    <div class="category-title">
        <span>{{__('Hành động')}}</span>
        <ul class="icons-list">
            <li><a href="#" data-action="collapse" class=""></a></li>
        </ul>
    </div>
    <div class="category-content no-padding" style="display: block;">
        <ul class="navigation navigation-alt navigation-accordion datatable-comment-actions">
            @event(new \App\Events\BeforeHtmlBlock('sidebar.groups.actions'))
            <li>
                <a class="action-delete"><i class="icon-trash"></i> {{__('Xóa bỏ')}}</a>
            </li>
            <li>
                <a class="action-published-on"><i class="icon-eye"></i> {{__('Cho phép hiển thị')}}</a>
            </li>
            <li>
                <a class="action-published-off"><i class="icon-eye-blocked"></i> {{__('Không phép hiển thị')}}</a>
            </li>
            @event(new \App\Events\AfterHtmlBlock('sidebar.groups.actions'))
        </ul>
    </div>
</div>
@event(new \App\Events\AfterHtmlBlock('sidebar.groups'))