@php
    /** @var \Modules\ModHairWorld\Entities\PostTypes\News[]|\Illuminate\Pagination\LengthAwarePaginator $posts */
@endphp
@foreach($posts as $k=>$item)
    <div class="news-item item">
        <div class="img">
            <img src="{!! $item->cover?$item->cover->getThumbnailUrl('medium_ka', getNoThumbnailUrl()):getNoThumbnailUrl() !!}">
        </div>
        <div class="content">
            <div class="content-wrapper">
                <div class="title">
                    <a href="{!! $item->getUrl() !!}">{!! $item->title !!}</a>
                </div>
                <div class="date">{!! $item->published_at->format('d/m/Y') !!}</div>
                <div class="desc">
                    {!! $item->description !!}
                </div>
                <div class="more"><a href="{!! $item->getUrl()  !!}">XEM THÊM</a></div>
            </div>
        </div>
    </div>
@endforeach
@if($posts->currentPage() != $posts->lastPage())
    <a class="pagination__next" href="{!! $posts->nextPageUrl() !!}"></a>
@endif