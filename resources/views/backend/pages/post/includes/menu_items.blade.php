@php
    /** @var \App\Classes\PostType $post_type */
@endphp
@event(new \App\Events\BeforeHtmlBlock('sidebar.groups'))
<div class="sidebar-category" data-group-id="menu-lib">
    <div class="category-title">
        <span>{{$post_type::getSingular()}}</span>
        <ul class="icons-list">
            <li><a href="#" data-action="collapse" class=""></a></li>
        </ul>
    </div>
    <div class="category-content no-padding" style="display: block;">
        <ul class="navigation navigation-alt navigation-accordion datatable-post-actions">
            @event(new \App\Events\BeforeHtmlBlock('sidebar.groups.'.$post_type::getTypeSlug()))
            @hasPermission($post_type::getCreatePermissionID())
                <li>
                    <a href="{{route('backend.post.create', ['post_type'=>$post_type::getTypeSlug()])}}"><i class="icon-add"></i> {{__('Tạo mới')}}</a>
                </li>
            @endif
            @route('backend.post.index')
                @hasPermission($post_type::getTrashPermissionID())
                <li>
                    <a class="action-restore"><i class="icon-redo2"></i> {{__('Phục hồi')}}</a>
                </li>
                <li>
                    <a class="action-trashed"><i class="icon-trash"></i> {{__('Tạm xóa')}}</a>
                </li>
                @endif
                @hasPermission($post_type::getDeletePermissionID())
                <li>
                    <a class="action-delete"><i class="icon-trash-alt"></i> {{__('Xóa vĩnh viễn')}}</a>
                </li>
                @endif
            @endif
            @event(new \App\Events\AfterHtmlBlock('sidebar.groups.'.$post_type::getTypeSlug()))
        </ul>
    </div>
</div>
@if(count($post_type::getTaxonomies())>0)
<div class="sidebar-category" data-group-id="menu-location">
    <div class="category-title">
        <span>{{__('Quản lý phân loại')}}</span>
        <ul class="icons-list">
            <li><a href="#" data-action="collapse" class=""></a></li>
        </ul>
    </div>
    <div class="category-content no-padding" style="display: block;">
        <ul class="navigation navigation-alt navigation-accordion datatable-users-actions">
            @event(new \App\Events\BeforeHtmlBlock('sidebar.groups.'.$post_type::getTypeSlug().'.taxonomy'))
            @foreach($post_type::getTaxonomies() as $taxonomy=>$rel)
                @php
                /** @var \App\Classes\Taxonomy $taxonomy */
                @endphp
                <li>
                    <a href="{{route('backend.taxonomy.index', ['post_type'=>$post_type::getTypeSlug(), 'taxonomy' => $taxonomy::getTaxSlug()])}}"><i class="{{$taxonomy::getMenuIcon()}}"></i> {{$taxonomy::getMenuTitle()}}</a>
                </li>
            @endforeach
            @event(new \App\Events\AfterHtmlBlock('sidebar.groups.'.$post_type::getTypeSlug().'.taxonomy'))
        </ul>
    </div>
</div>
@endif
@event(new \App\Events\AfterHtmlBlock('sidebar.groups'))