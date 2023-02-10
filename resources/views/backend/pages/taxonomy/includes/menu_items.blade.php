@php
/** @var \App\Classes\Taxonomy $taxonomy */
/** @var \App\Classes\PostType $post_type */
@endphp
@event(new \App\Events\BeforeHtmlBlock('sidebar.groups'))
<div class="sidebar-category" data-group-id="{{$taxonomy::getTaxSlug()}}">
    <div class="category-title">
        <span>{{$taxonomy::getSingular()}}</span>
        <ul class="icons-list">
            <li><a href="#" data-action="collapse" class=""></a></li>
        </ul>
    </div>
    <div class="category-content no-padding" style="display: block;">
        <ul class="navigation navigation-alt navigation-accordion datatable-taxonomy-actions">
            @event(new \App\Events\BeforeHtmlBlock('sidebar.groups.'.$taxonomy::getTaxSlug()))
            <li>
                <a href="{{route('backend.taxonomy.create', ['post_type' => $post_type::getTypeSlug(), 'taxonomy' => $taxonomy::getTaxSlug()])}}"><i class="icon-add"></i> {{__('Tạo mới')}}</a>
            </li>
            @route('backend.taxonomy.index')
            <li>
                <a class="action-delete"><i class="icon-trash"></i> {{__('Xóa bỏ')}}</a>
            </li>
            @else
                <li>
                    <a href="{{route('backend.taxonomy.index', ['taxonomy'=>$taxonomy::getTaxSlug(), 'post_type'=>$post_type::getTypeSlug()])}}"><i class="{{$taxonomy::getMenuIcon()}}"></i> {{$taxonomy::getMenuTitle()}}</a>
                </li>
            @endif
            @event(new \App\Events\AfterHtmlBlock('sidebar.groups.'.$taxonomy::getTaxSlug()))
        </ul>
    </div>
</div>
<div class="sidebar-category" data-group-id="{{$post_type::getTypeSlug()}}">
    <div class="category-title">
        <span>{{$post_type::getMenuTitle()}}</span>
        <ul class="icons-list">
            <li><a href="#" data-action="collapse" class=""></a></li>
        </ul>
    </div>
    <div class="category-content no-padding" style="display: block;">
        <ul class="navigation navigation-alt navigation-accordion">
            @event(new \App\Events\BeforeHtmlBlock('sidebar.groups.'.$post_type::getTypeSlug()))
            <li>
                <a href="{{route('backend.post.create', ['post_type'=>$post_type::getTypeSlug()])}}"><i class="icon-add"></i> {{__('Tạo mới')}}</a>
            </li>
            <li>
                <a href="{{route('backend.post.index', ['post_type'=>$post_type::getTypeSlug()])}}"><i class="{{$post_type::getMenuIcon()}}"></i> {{$post_type::getMenuIndexTitle()}}</a>
            </li>
            @event(new \App\Events\AfterHtmlBlock('sidebar.groups.'.$post_type::getTypeSlug()))
        </ul>
    </div>
</div>
@event(new \App\Events\AfterHtmlBlock('sidebar.groups'))