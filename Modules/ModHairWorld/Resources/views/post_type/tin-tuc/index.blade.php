@enqueueCSS('new-cat-page', getThemeAssetUrl('libs/styles/news_cat.css'), 'master-page')
@enqueueJS('grid-a-licious', getThemeAssetUrl('libs/jquery.grid-a-licious.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueJS('infinity-scroll', getThemeAssetUrl('libs/infinite-scroll.pkgd.min.js'), JS_LOCATION_HEAD, 'jquery')
@extends(getThemeViewName('master'))
@php
/** @var \Modules\ModHairWorld\Entities\PostTypes\News[]|\Illuminate\Pagination\LengthAwarePaginator $posts */
/** @var \Modules\ModHairWorld\Entities\PostTypes\News $first_post */
$first_post = $posts->first();
@endphp
@section('current_page_title')
    Tin tức, bài viết
@endsection
@php
    $og_img = getNoThumbnailUrl();
     $og_width = 500;
     $og_height = 500;
@endphp
@push('page_meta')
    <meta property="og:title" content="Bài viết - iSalon"/>
    <meta property="og:image" content="{{ $og_img }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:image:secure_url" content="{{ $og_img }}" />
    <meta property="og:image:width" content="{{$og_width}}" />
    <meta property="og:image:height" content="{{$og_height}}" />
@endpush
@section('page_content')
    <div class="page-headline">
        <div class="container">
            <div class="page-title">
                Tin tức cập nhật
            </div>
        </div>
    </div>
    @if($posts->count())
    <!--region First post-->
    <div class="first-news">
        <div class="wrapper">
            <div class="img-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="img" style="background-image: url('{!! $first_post->cover?$first_post->cover->getUrl():getNoThumbnailUrl() !!}')"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8 d-none d-lg-block">

                        </div>
                        <div class="col-lg-4">
                            <div class="content">
                                <div class="inner news-item">
                                    <div class="title">
                                        <a href="{!! $first_post->getUrl() !!}">
                                            {!! $first_post->title !!}
                                        </a>
                                    </div>
                                    <div class="date">{!! $first_post->published_at->format('d/m/Y') !!}</div>
                                    <div class="desc">
                                        {!! $first_post->description !!}
                                    </div>
                                    <div class="more"><a href="{!! $first_post->getUrl() !!}">XEM THÊM</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--endregion-->
    <!--region Post list-->
    <div class="news-grid">
        <div class="container">
            <div class="news-grid-wrapper" data-next="{!! $posts->nextPageUrl() !!}">
                @foreach($posts as $k=>$item)
                    @if($k==0)
                        @continue
                    @endif
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
            </div>
        </div>
    </div>
    <div id="loading-status">Đang tải thêm tin...</div>
    <!--endregion-->
    @else
        <div class="no-post">
            <div class="container">
                <div class="text">
                    <div><i class="fa fa-newspaper-o" aria-hidden="true"></i></div>
                    Chưa có bài viết tương ứng nào!
                </div>
            </div>
        </div>
    @endif
@endsection
@push('page_footer_js')
    <script type="text/javascript">
        $(".news-grid-wrapper").gridalicious({width: 300,gutter: 30, animate: true,});
        $('.news-grid .container .news-grid-wrapper').infiniteScroll({
            // options
            path: '.pagination__next',
            append: false,
            history: false,
            scrollThreshold: 500,
        });
        $('.news-grid .container .news-grid-wrapper').on( 'load.infiniteScroll', function( event, response ) {
            if($(response).find('.news-item ').length>0){
                $(".news-grid-wrapper").gridalicious('append', $(response).find('.news-item '));
            }
        });
        $('.news-grid .container .news-grid-wrapper').on( 'request.infiniteScroll', function( event, path ) {
            $('#loading-status').addClass('loading');
        });

        $('.news-grid .container .news-grid-wrapper').on( 'load.infiniteScroll', function( event, response, path ) {
            $('#loading-status').removeClass('loading');
        });

    </script>
@endpush