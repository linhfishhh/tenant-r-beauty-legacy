@enqueueCSS('salon-page', getThemeAssetUrl('libs/styles/salon.css'), 'master-page')
@enqueueCSS('owl', getThemeAssetUrl('libs/owl/assets/owl.carousel.min.css'), 'master-page')
@enqueueJS('owl', getThemeAssetUrl('libs/owl/owl.carousel.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueCSS('fancybox', getThemeAssetUrl('libs/fancybox/jquery.fancybox.min.css'), 'master-page')
@enqueueJS('fancybox', getThemeAssetUrl('libs/fancybox/jquery.fancybox.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueCSS('select2', getThemeAssetUrl('libs/select2/css/select2.min.css'), 'bootstrap')
@enqueueJS('select2', getThemeAssetUrl('libs/select2/js/select2.full.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueJS('popper', getThemeAssetUrl('libs/popper.min.js'), JS_LOCATION_HEAD, 'jquery')
@extends(getThemeViewName('master'))
@section('current_page_title')
    {!! $salon->name !!}
@endsection
@push('page_head')
    <script xmlns="">
        var dataLayer = [];
        dataLayer.push({
            'dynx_itemid': '{!! $salon->id !!}',
            'dynx_pagetype': 'offerdetail',
            'dynx_totalvalue': {!! $salon->price_to_cache !!}
        });
    </script>
@endpush
@section('page_content')
    @php
        /** @var \Modules\ModHairWorld\Entities\Salon $salon */
        /** @var \Modules\ModHairWorld\Entities\SalonServiceReviewCriteria[] $criterias */
        /** @var \Modules\ModHairWorld\Entities\Salon[] $related_salons */
        $dark_theme = 1;
        //$salon->load(['services', 'services.logos', 'services.logos.image']);
        $og_img = getNoThumbnailUrl();
        $og_width = 500;
        $og_height = 500;
        if($salon->cover){
        //$og_img = $salon->cover->getThumbnailUrl('large');
        $og_img = route('frontend.facebook_thumb', ['upload'=>$salon->cover_id, 'file_name'=>'thumb_'.time().'.jpg']);
        $og_width = 1200;
        $og_height = 628;
        }

        //$og_img_ = preg_replace("/^https:/i", "http:", $og_img);
        $og_img_ = $og_img;

        $info = str_limit(strip_tags($salon->info), 300);
        $info = preg_replace( "/\r|\n/", " ", $info );

        $keywords = trim($salon->meta_keywords);
        $keywords_string = '';
        if($keywords){
        $keywords = preg_split('/\r\n|[\r\n]/', $keywords);
        foreach ($keywords as $index=>$keyword){
        if($index != 0){
        $keywords_string .= ', ';
        }
        $keywords_string .= $keyword;
        }
        }
        $salon_cover = '';
        if($salon->cover){
        $salon_cover = $salon->cover->getThumbnailUrl('large', false);
        }

    @endphp
    @push('page_meta')
        <meta property="og:title" content="{{ $salon->name }}"/>
        <meta property="og:image" content="{{ $og_img_ }}"/>
        <meta property="og:description" content="{{ $info }}"/>
        <meta property="og:type" content="article"/>

        <meta name="description" content="{{ $info }}"/>
        <meta property="og:image:secure_url" content="{{ $og_img }}"/>
        <meta property="og:image:width" content="{{$og_width}}"/>
        <meta property="og:image:height" content="{{$og_height}}"/>
        @if($keywords_string)
            <meta name="keywords" content="{{$keywords_string}}"/>
        @endif
    @endpush
    <!-- Modal -->
    <div class="modal fade" id="salonCommentModal" tabindex="-1" role="dialog" aria-labelledby="salonCommentModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="salonCommentModalLabel">Viết bài đánh giá</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <form action="{!! route('frontend.salon.review.add') !!}" method="POST" id="frm-comment"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="service_id" value="{{ $salon->services->first() ? $salon->services->first()->id : ''}}"/>
                        {{--                        <div class="form-group">--}}
                        {{--                            <label for="select-services-comment">Chọn dịch vụ nhận xét</label>--}}
                        {{--                            <div class="select-services border pr-3">--}}
                        {{--                                <select class="form-control" id="select-services-comment">--}}
                        {{--                                    <option value="-1">Nhận xét chung</option>--}}
                        {{--                                    @foreach($salon->services as $service)--}}
                        {{--                                        <option value="{{$service->id}}">{{ $service->name }}
                        </option>--}}
                        {{--                                    @endforeach--}}
                        {{--                                </select>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}

                        <div class="form-group">
                            <label for="rating-services-comment">Chọn dịch vụ nhận xét</label>
                            <div class="rating-services">
                                <input type="number" class="rating" name="rating" id="txtRating">
                               <div class="error-rating text-alert" style="font-size: 16px; color: red;"></div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <h5>Nhận xét của bạn</h5>
                        <div class="form-group">
                            <input type="text" class="form-control" name="title" id="salonCommentTitle"
                                   placeholder="Nhập tiêu đề đánh giá tại đây">
                            <div class="error-title text-alert"></div>
                        </div>
                        <div class="form-group">
                        <textarea class="form-control" name="description" id="salonCommentDescription" rows="3"
                                  placeholder="Nhập mô tả tại đây"></textarea>
                            <div class="error-description text-alert"></div>
                        </div>
                        <div class="form-group multi-image d-flex py-3">
                            <div class="items d-flex">
                            </div>
                            <button type="button" class="btn-add-img-cmt btn btn-link p-0">
                                <img width="80" height="80" src="{{ asset('assets/images/no-image.jpeg') }}" alt="">
                            </button>
                        </div>
                        <div class="text-center error-image alert alert-danger d-none">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-send-comment" 
                            class="btn btn-primary text-uppercase py-2 px-4 bg-white text-dark border-0">Gửi
                        đánh giá
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-map-view-salon-info" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bản đồ kết quả</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="map-view">
                        <div class="map" style="background-color: #e2edf1">
                            @component(getThemeViewName('components.simple_map'), [
                            'id' => 'salon-map-'.$salon->id,
                            'location' => [
                            'lat' => $salon->map_lat?$salon->map_lat:0,
                            'lng' => $salon->map_long?$salon->map_long:0,
                            'zoom' => $salon->map_zoom?$salon->map_zoom:13
                            ]
                            ])
                            @endcomponent
                        </div>
                        <div class="selected-salon">
                            <div class="wrapper">
                                <div class="salon-list-result">
                                    <div class="salon">
                                        <div class="minize">
                                            Thu xuống
                                        </div>
                                        <div class="row salon-info-zone">
                                            <div class="col-12 col-md-6 d-none d-sm-block">
                                                <img class="w-100"
                                                     src="{{ $salon_cover ? $salon_cover : getNoThumbnailUrl() }}"
                                                     alt="{{ $salon->name }}">
                                            </div>
                                            <div class="col-12 col-md-6 ">
                                                <div class="title mt-2 pl-3 pr-3 p-md-0">
                                                    {{ $salon->name }}
                                                </div>
                                                <div class="location pl-3 pr-3 p-md-0">
                                                    <i class="fa fa-map-marker"></i>
                                                    <span>{{ $salon->address_cache }}</span>
                                                </div>
                                                <div class="salon-rating d-inline-flex pl-3 pr-3 p-md-0 mb-3">
                                                    <div class="rating-score">
                                                        {!! floor($salon->rating * 2 ) / 2 !!}
                                                    </div>
                                                    <div class="rating-detail ml-2 d-inline-flex">
                                                        <div class="star-block">
                                                            @component(getThemeViewName('components.rating_stars'), ['score'
                                                            => $salon->rating]) @endcomponent
                                                        </div>
                                                        <div class="total ml-2">
                                                            ({!!$salon->rating_count !!})
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="salon-gallery{!! $salon->gallery->count()?'':' empty' !!}">
        <div class="container">
            <div class="salon-share-like">
                <a href="#" id="salon-share"><i class="fa fa-share-alt" aria-hidden="true"></i></a>
                @auth
                    <a href="#" id="salon-like" class="{!! $salon->likedBy(me()->id)?'active':'' !!}">
                        <i class="fa fa-heart-o" aria-hidden="true"></i></a>
                @endif
            </div>
            @if($salon->gallery->count())
                <div class="img-list owl-carousel">
                    @foreach($salon->gallery as $item)
                        @if($item->image)
                            <a data-fancybox="gallery" class="d-block" href="{!! $item->image->getUrl() !!}"
                               style="background-image: url('{!! $item->image->getThumbnailUrl('large', getNoThumbnailUrl()) !!}')">
                            </a>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <div class="salon-page-header">
        <div class="container">
            <div class="salon-name">
                {!! $salon->name !!}{!! $salon->certified?'<i title="Đã chứng thực"
                class="fa fa-check-circle salon-name-verified" aria-hidden="true"></i>':'' !!}
            </div>
            <div class="salon-address">
                {{ $salon->address_cache }}
                <ul>
                    <li><a href="javascript:void(0)" id="map-view-link">{{ _('Chi tiết địa điểm') }}</a></li>
                </ul>
            </div>
            <a class="salon-rating clearfix d-block" href="#review">
                <div class="rating-score">
                    {!! floor($salon->rating * 2 ) / 2!!}
                </div>
                <div class="rating-detail">
                    <div class="star-block">
                        @component(getThemeViewName('components.rating_stars'), ['score' => $salon->rating]) @endcomponent
                    </div>
                    <div class="stats-block">
                        @if($salon->rating>0)
                            {!! $salon->rating_count !!} nhận xét & đánh giá
                        @else
                            Chưa có nhận xét
                        @endif
                    </div>
                </div>
                <div class="link">
                    <i class="fa fa-chevron-right"></i>
                </div>
            </a>
        </div>
    </div>
    <div class="salon-detail">
        <div class="container">

            {{--                        <div class="col-lg-4 order-lg-1">--}}
            {{--                            <div class="salon-map">--}}
            {{--                                <div class="map">--}}
            {{--                                    @component(getThemeViewName('components.simple_map'), [--}}
            {{--                                        'id' => 'salon-map-'.$salon->id,--}}
            {{--                                        'location' => [--}}
            {{--                                            'lat' => $salon->map_lat?$salon->map_lat:0,--}}
            {{--                                            'lng' => $salon->map_long?$salon->map_long:0,--}}
            {{--                                            'zoom' => $salon->map_zoom?$salon->map_zoom:13--}}
            {{--                                        ]--}}
            {{--                                    ])--}}
            {{--                                    @endcomponent--}}
            {{--                                </div>--}}
            {{--                                <div class="info">--}}
            {{--                                    <div class="item location-working">--}}
            {{--                                        <div class="location">--}}
            {{--                                            <i class="fa fa-map-marker"></i>--}}
            {{--                                            <span>{!! $salon->getAddressLine() !!}</span>--}}
            {{--                                        </div>--}}
            {{--                                        <div class="work-hours">--}}
            {{--                                            <div class="row">--}}
            {{--                                                <div class="col-md-7">--}}
            {{--                                                    <div class="title">Ngày giờ mở cửa</div>--}}
            {{--                                                    <div class="sub-title">{!! $salon->timeWeekDays() !!}</div>--}}
            {{--                                                </div>--}}
            {{--                                                <div class="col-md-5">--}}
            {{--                                                    <div class="hours">--}}
            {{--                                                        {!! $salon->timeWorkingHours() !!}--}}
            {{--                                                        <a href="#info">Chi tiết</a>--}}
            {{--                                                    </div>--}}
            {{--                                                </div>--}}
            {{--                                            </div>--}}
            {{--                                        </div>--}}
            {{--                                    </div>--}}
            {{--                                    @if($salon->certified)--}}
            {{--                                        <div class="item feature">--}}
            {{--                                            <div class="img"><img src="{!! getThemeAssetUrl('img/salon_verify.png') !!}"></div>--}}
            {{--                                            <div class="title">Salon được xác minh bởi iSalon</div>--}}
            {{--                                        </div>--}}
            {{--                                    @endif--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                        </div>--}}
            <div class="">
                <div class="d-none d-lg-block">
                    <div class="local-nav" id="local-nav">
                        <ul class="nav container">
                            @if($salon->getHotServices())
                                <li class="nav-item"><a class="nav-link" href="#hot-services">Dịch vụ hot</a></li>
                            @endif
                            <li class="nav-item"><a class="nav-link" href="#salon-services">Dịch vụ</a></li>
                            <li class="nav-item"><a class="nav-link" href="#info">Thông tin</a></li>
                            <li class="nav-item"><a class="nav-link" href="#showcase">Bộ sưu tập</a></li>
                            <li class="nav-item"><a class="nav-link" href="#review">Đánh giá</a></li>
                        </ul>
                    </div>
                </div>
                @if($salon->getHotServices())
                    <div class="hot-service">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="hot-service-title">
                                    Các dịch vụ nổi bật của chúng tôi
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="hot-service-block" id="hot-services">
                                    @component(getThemeViewName('components.salon_services'), ['items' => $items,
                                    'can_book'=>$salon->open]) @endcomponent
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="salon-service" id="salon-services">
                    <div class="salon-service-cats">
                        <div class="list-items">
                            @if ($salon->isInPromo() || $salon->sale_up_to_percent_cache > 0)
                                <a href=#sale id="filter-sale" data-id="-1" class="sale cats-item shadow-sm">
                                    <div class="wrapper">
                                        <div class="image">
                                            <img src="{{ asset('assets/images/icon/sale.png') ? asset('assets/images/icon/sale.png'): getNoThumbnailUrl() }}"
                                                 alt="">
                                        </div>
                                        <div class="title mt-1">
                                            Khuyến mãi
                                        </div>
                                    </div>
                                </a>
                            @endif
                            <a href="JavaScript:void(0);" data-id="0" class="show-all cats-item shadow-sm">
                                <div class="wrapper">
                                    <div class="image">
                                        <img src="{{ asset('assets/images/icon/all.jpg') ? asset('assets/images/icon/all.jpg'): getNoThumbnailUrl() }}"
                                             alt="">
                                    </div>
                                    <div class="title mt-1">
                                        Tất cả
                                    </div>
                                </div>
                            </a>
                            @foreach($salon->service_categories as $k=>$cat)
                                <a href="JavaScript:void(0);" data-id="{!! $cat->id !!}" class="cats-item shadow-sm">
                                    <div class="wrapper">
                                        <div class="image">
                                            <img src="{{ \App\UploadedFile::find($cat->cover_id) && \App\UploadedFile::find($cat->cover_id)->getUrl() ? \App\UploadedFile::find($cat->cover_id)->getThumbnailUrl('small_ka', false) : getNoThumbnailUrl() }}"
                                                 alt="">
                                        </div>
                                        <div class="title mt-1">
                                            {!! $cat->title !!}
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-9 col-md-offset-5 m-auto">
                            {{--                            <div class="salon-service-text">Chúng tôi mong muốn mang lại dịch vụ tốt nhất cho bạn!</div>--}}
                            <div class="salon-service-block" id="salon-service-block">
                                @component(getThemeViewName('components.salon_services'), ['items' => $salon->services,
                                'salon'=> $salon, $limit = 5, 'can_book'=>$salon->open]) @endcomponent
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="salon-info">
        <div class="container">
            <div class="row no-gutters" id="info">
                <div class="col-12 col-md-9 m-auto">
                    <div class="info-block basic-info card">
                        <div class="card-body">
                            <div class="info-block-title pt-3 clearfix">
                                {{--                <div class="icon">--}}
                                {{--                    <img src="{!! getThemeAssetUrl('img/info_icon_1.png') !!}">--}}
                                {{--                </div>--}}
                                <div class="text">Thông tin chung</div>
                            </div>
                            <div class="info-block-content">
                                <!--region main info -->
                                <div class="main-basic-info">
                                    <div class="row no-gutters">
                                        <div class="col-lg-8 pr-3">
                                            <div class="intro-block">
                                                {!! $salon->info !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    {{ _('Thời gian mở cửa') }}
                                                </div>
                                                <div class="times-block card-body pt-3 pb-3 pl-3 pr-2" id="salon-open-hours">
                                                    <ul>
                                                        @foreach($salon->times as $item)
                                                            <li>
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <div class="w-day">
                                                                            {!! $item->weekDayText() !!}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <div class="w-hour">
                                                                            {!! $item->workHourText() !!}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--endregion-->

                                @if($salon->extended_info->count())
                                    @foreach($salon->extended_info as $item)
                                        <div class="sub-basic-info extended-info">
                                            <div class="sub-info-block">
                                                <div class="sub-info-block-title clearfix">
                                                    <div class="icon">
                                                        <i class="{!! $item->icon !!}"></i>
                                                    </div>
                                                    <div class="text">{!! $item->title !!}</div>
                                                </div>
                                                <div class="sub-info-block-content">
                                                    {!! $item->content !!}
                                                </div>
                                            </div>
                                        </div>
                                @endforeach
                            @endif
                            <!--endregion-->
                            </div>
                        </div>
                    </div>
                    <!--endregion-->
                    <!--region stylist-->
                    @if($salon->stylist->count())
                        <div class="sub-basic-info stylist-info card mt-3">
                            <div class="card-body">
                                <div class="sub-info-block">
                                    <div class="sub-info-block-title clearfix">
                                        <div class="text">Stylist</div>
                                    </div>
                                    <div class="sub-info-block-content">
                                        <div class="stylist-list owl-carousel">
                                            @foreach($salon->stylist as $item)
                                                <div class="stylist">
                                                    <div class="wrapper">
                                                        <div class="img">
                                                            <img
                                                                    src="{!! $item->avatar?$item->avatar->getThumbnailUrl('default', getNoAvatarUrl()):getNoAvatarUrl() !!}">
                                                        </div>
                                                        <div class="name">
                                                            {!! $item->name !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                <!--region extended info-->
                    <!--region brands-->
                    @if($salon->brands->count())
                        <div class="sub-basic-info brand-info card mt-3">
                            <div class="sub-info-block card-body">
                                <div class="sub-info-block-title clearfix">
                                    <div class="text">Sản phẩm được sử dụng tại địa điểm này</div>
                                </div>
                                <div class="sub-info-block-content">
                                    <div class="brand-list owl-carousel">
                                        @foreach($salon->brands as $item)
                                            <div class="brand"
                                                 style="background-image: url('{!! $item->logo?$item->logo->getThumbnailUrl('medium_ka', getNoThumbnailUrl()):getNoThumbnailUrl() !!}')">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                <!--endregion-->
                    @if($salon->training_info)
                        <div class="sub-basic-info training-info card mt-3">
                            <div class="sub-info-block card-body">
                                <div class="sub-info-block-title clearfix">
                                    <div class="text">Đào tạo</div>
                                </div>
                                <div class="sub-info-block-content">
                                    {!! $salon->training_info !!}
                                </div>
                                <a id="read-more" href="javascript:void(0)">+ Chi tiết</a>
                            </div>
                        </div>
                    @endif

                <!--region showcase-->
                    @if($salon->showcases->count())
                        <div class="info-block gallery-info card" id="showcase">
                            <div class="card-body">
                                <div class="info-block-title clearfix">
                                    <div class="text">Tác phẩm</div>
                                </div>
                                <div class="info-block-content">
                                    <div class="showcase owl-carousel">
                                        @foreach($salon->showcases as $gk=>$showcase)
                                            @if($showcase->items->count() == 0)
                                                @continue
                                            @endif
                                            @php
                                                /** @var \Modules\ModHairWorld\Entities\SalonShowcaseItem $first_item */
                                                $first_item = $showcase->items->first();
                                            @endphp
                                            <div class="item">
                                                <a data-caption="{{$showcase->name }}"
                                                   data-fancybox="gallery_{!! $gk !!}"
                                                   href="{!! $first_item->image?$first_item->image->getUrl():getNoThumbnailUrl() !!}"
                                                   class="d-block wrapper">
                                                    <div class="cover"
                                                         style="background-image:
                                                                 url({!! $first_item->image?$first_item->image->getThumbnailUrl('large', getNoThumbnailUrl()):getNoThumbnailUrl() !!})">
                                                        @auth
                                                            <div data-id="{!! $showcase->id !!}"
                                                                 class="like{!! $showcase->likedBy(me()->id)?' liked':'' !!}"></div>
                                                        @endauth
                                                        <div class="item-count">{!! $showcase->items->count() !!}ảnh
                                                        </div>
                                                    </div>
                                                    <div class="title">{!! $showcase->name !!}</div>
                                                </a>
                                                <div class="d-none">
                                                    @foreach($showcase->items as $k=>$i)
                                                        @if($k==0)
                                                            @continue
                                                        @endif
                                                        <a data-caption="{{$showcase->name }}"
                                                           data-fancybox="gallery_{!! $gk !!}"
                                                           href="{!! $i->image?$i->image->getUrl():getNoThumbnailUrl() !!}"></a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <!--endregion-->
                <div class="col-12 col-md-9 ml-auto mr-auto card mt-3">
                    <div class="info-block review-info" id="review">
                        <div class="info-block-title card-body clearfix mb-0">
                            <div class="text">Xếp hạng sao</div>
                        </div>
                        <div class="info-block-content">
                            <div class="rating-box card-body">
                                <div class="row ">
                                    <div class="col-md-3">
                                        <div class="total-rating">
                                            <div class="number">
                                                <div class="current">{!! floor($salon->rating * 2 ) / 2 !!}</div>
                                                <div class="total">/5</div>
                                            </div>

                                            <div class="star-block">
                                                @component(getThemeViewName('components.rating_stars'),
                                                ['score'=>$salon->rating])
                                                @endcomponent
                                            </div>
                                            @if($salon->rating_count)
                                                <div class="text-block">Có {!! $salon->rating_count !!} đánh
                                                    giá nhận xét
                                                </div>
                                            @else
                                                <div class="text-block">Chưa có đánh giá nào</div>
                                            @endif


                                        </div>
                                    </div>
                                    {{--                                    <div class="col-lg-7">--}}
                                    {{--                                        <div class="detail-rating">--}}
                                    {{--                                            <div class="row">--}}
                                    {{--                                                @foreach($criterias as $criteria)--}}
                                    {{--                                                    <div class="col-md-6">--}}
                                    {{--                                                        <div class="rating-detail-item">--}}
                                    {{--                                                            <div class="row">--}}
                                    {{--                                                                <div class="col-6">--}}
                                    {{--                                                                    <div class="rating-star-block">--}}
                                    {{--                                                                        @component(getThemeViewName('components.rating_stars'), ['score' => $criteria_ratings->get($criteria->id, 0.0)])--}}
                                    {{--                                                                        @endcomponent--}}
                                    {{--                                                                    </div>--}}
                                    {{--                                                                </div>--}}
                                    {{--                                                                <div class="col-6">--}}
                                    {{--                                                                    <div class="rating-title">--}}
                                    {{--                                                                        {!! $criteria->name !!}--}}
                                    {{--                                                                    </div>--}}
                                    {{--                                                                </div>--}}
                                    {{--                                                            </div>--}}
                                    {{--                                                        </div>--}}
                                    {{--                                                    </div>--}}
                                    {{--                                                @endforeach--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                    <div class="col-lg-5">
                                        <div class="rating-filter">
                                            <div class="item clearfix for-star-5">
                                                <div class="content clearfix">
                                                    <div class="star-block">
                                                        @component(getThemeViewName('components.rating_stars'),
                                                        ['score'=>5])
                                                        @endcomponent
                                                    </div>
                                                    <div class="stats">
                                                        <div class="bg">
                                                            <div class="prb" style="width: 0%"></div>
                                                        </div>
                                                    </div>
                                                    <div class="number">0</div>
                                                </div>
                                            </div>
                                            <div class="item clearfix for-star-4">
                                                <div class="content clearfix">
                                                    <div class="star-block">
                                                        @component(getThemeViewName('components.rating_stars'),
                                                        ['score'=>4])
                                                        @endcomponent
                                                    </div>
                                                    <div class="stats">
                                                        <div class="bg">
                                                            <div class="prb" style="width: 0%"></div>
                                                        </div>
                                                    </div>
                                                    <div class="number">0</div>
                                                </div>
                                            </div>
                                            <div class="item clearfix for-star-3">
                                                <div class="content clearfix">
                                                    <div class="star-block">
                                                        @component(getThemeViewName('components.rating_stars'),
                                                        ['score'=>3])
                                                        @endcomponent
                                                    </div>
                                                    <div class="stats">
                                                        <div class="bg">
                                                            <div class="prb" style="width: 0%"></div>
                                                        </div>
                                                    </div>
                                                    <div class="number">0</div>
                                                </div>
                                            </div>
                                            <div class="item clearfix for-star-2">
                                                <div class="content clearfix">
                                                    <div class="star-block">
                                                        @component(getThemeViewName('components.rating_stars'),
                                                        ['score'=>2])
                                                        @endcomponent
                                                    </div>
                                                    <div class="stats">
                                                        <div class="bg">
                                                            <div class="prb" style="width: 0%"></div>
                                                        </div>
                                                    </div>
                                                    <div class="number">0</div>
                                                </div>
                                            </div>
                                            <div class="item clearfix for-star-1">
                                                <div class="content clearfix">
                                                    <div class="star-block">
                                                        @component(getThemeViewName('components.rating_stars'),
                                                        ['score'=>1])
                                                        @endcomponent
                                                    </div>
                                                    <div class="stats">
                                                        <div class="bg">
                                                            <div class="prb" style="width: 0%"></div>
                                                        </div>
                                                    </div>
                                                    <div class="number">0</div>
                                                </div>
                                            </div>
{{--                                            <div class="item clearfix for-star-0">--}}
{{--                                                <div class="content clearfix">--}}
{{--                                                    <div class="star-block">--}}
{{--                                                        @component(getThemeViewName('components.rating_stars'),--}}
{{--                                                        ['score'=>0])--}}
{{--                                                        @endcomponent--}}
{{--                                                    </div>--}}
{{--                                                    <div class="stats">--}}
{{--                                                        <div class="bg">--}}
{{--                                                            <div class="prb" style="width: 0%"></div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="number">0</div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
                                        </div>
                                    </div>

                                    
                                        <div class="col-12 col-lg-4">
                                            <div class="title">
                                                <h5>Hãy cho chúng tôi biết cảm nhận của bạn !</h5>
                                            </div>
                                            @if( me() != null)
                                            <button id="btn-comment" data-toggle="modal" data-target="#salonCommentModal"
                                            class="btn btn-block btn-lg p-3">Viết nhận xét
                                            <span class="ml-2"> <img src="{{ asset('assets/images/icon/pencil.png') }}"
                                               alt="pencil"> </span>
                                           </button>
                                           @elseif( me() == null)
                                           <button id="btn-comment" onclick="requiredLogin()" class="btn btn-block btn-lg p-3">Viết nhận xét
                                            <span class="ml-2"> <img src="{{ asset('assets/images/icon/pencil.png') }}"
                                               alt="pencil"> </span>
                                           </button>
                                            @endif
                                       </div>

                                </div>
                            </div>
                            <div class="row no-gutters">
                                <div class="col-12 col-lg-6 border">
                                    <div class="card-body"> Nhận xét & đánh giá về dịch vụ</div>
                                </div>
                                <div class="col-6 col-lg-3 border review-comment-filter">
                                    <select id="review-comment-filter" class="btn-bloack">
                                        <option value="0" selected>Mới nhất</option>
                                        <option value="1">Cũ nhất</option>
                                        <option value="2">Hữu ích</option>
                                        {{--                                        <option value="3">Liên quan</option>--}}
                                    </select>
                                </div>
                                <div class="col-6 col-lg-3 border review-star-filter">
                                    <select id="review-star-filter" class="btn-bloack">
                                        <option value="-1" selected>Tất cả sao</option>
                                        {{--                                                @foreach($salon->services as $cat)--}}
                                        {{--                                                    <option value="{!! $cat->id !!}">{!! $cat->name !!}</option>--}}
                                        {{--                                                @endforeach--}}
                                        <option value="1">1 Sao</option>
                                        <option value="2">2 Sao</option>
                                        <option value="3">3 Sao</option>
                                        <option value="4">4 Sao</option>
                                        <option value="5">5 Sao</option>
                                    </select>
                                </div>
                            </div>
                            <div class="review-box">
                                <div class="row">
                                    <div class="col-md">
                                        <div class="right-sec">
                                                @component(getThemeViewName('components.review_items'), [
                                                'id' => 'salon_review_box'
                                                ])
                                                @endcomponent
                                        </div>
                                    </div>
                                    {{--                                    <div class="col-md-4 order-md-0">--}}
                                    {{--                                        <div class="review-filter">--}}
                                    {{--                                            <div class="wrapper">--}}
                                    {{--                                                <div class="block-title">--}}
                                    {{--                                                    Lọc đánh giá--}}
                                    {{--                                                </div>--}}
                                    {{--                                                <div class="cat-filter">--}}

                                    {{--                                                </div>--}}
                                    {{--                                                <div class="rating-filter">--}}
                                    {{--                                                    <div class="item clearfix for-star-0">--}}
                                    {{--                                                        <label class="radio-container">--}}
                                    {{--                                                            <input checked="checked" value="-1" type="radio"--}}
                                    {{--                                                                   name="radio">--}}
                                    {{--                                                            <span class="checkmark"></span>--}}
                                    {{--                                                        </label>--}}
                                    {{--                                                        <div class="content clearfix">--}}
                                    {{--                                                            <div class="star-block">--}}
                                    {{--                                                                @component(getThemeViewName('components.rating_stars'), ['score'=>0])--}}
                                    {{--                                                                @endcomponent--}}
                                    {{--                                                            </div>--}}
                                    {{--                                                            <div class="stats">--}}
                                    {{--                                                                <div class="bg">--}}
                                    {{--                                                                    <div class="prb" style="width: 0%"></div>--}}
                                    {{--                                                                </div>--}}
                                    {{--                                                            </div>--}}
                                    {{--                                                            <div class="number">0</div>--}}
                                    {{--                                                        </div>--}}
                                    {{--                                                    </div>--}}
                                    {{--                                                    <div class="item clearfix for-star-1">--}}
                                    {{--                                                        <label class="radio-container">--}}
                                    {{--                                                            <input value="1" type="radio" name="radio">--}}
                                    {{--                                                            <span class="checkmark"></span>--}}
                                    {{--                                                        </label>--}}
                                    {{--                                                        <div class="content clearfix">--}}
                                    {{--                                                            <div class="star-block">--}}
                                    {{--                                                                @component(getThemeViewName('components.rating_stars'), ['score'=>1])--}}
                                    {{--                                                                @endcomponent--}}
                                    {{--                                                            </div>--}}
                                    {{--                                                            <div class="stats">--}}
                                    {{--                                                                <div class="bg">--}}
                                    {{--                                                                    <div class="prb" style="width: 0%"></div>--}}
                                    {{--                                                                </div>--}}
                                    {{--                                                            </div>--}}
                                    {{--                                                            <div class="number">0</div>--}}
                                    {{--                                                        </div>--}}
                                    {{--                                                    </div>--}}
                                    {{--                                                    <div class="item clearfix for-star-2">--}}
                                    {{--                                                        <label class="radio-container">--}}
                                    {{--                                                            <input value="2" type="radio" name="radio">--}}
                                    {{--                                                            <span class="checkmark"></span>--}}
                                    {{--                                                        </label>--}}
                                    {{--                                                        <div class="content clearfix">--}}
                                    {{--                                                            <div class="star-block">--}}
                                    {{--                                                                @component(getThemeViewName('components.rating_stars'), ['score'=>2])--}}
                                    {{--                                                                @endcomponent--}}
                                    {{--                                                            </div>--}}
                                    {{--                                                            <div class="stats">--}}
                                    {{--                                                                <div class="bg">--}}
                                    {{--                                                                    <div class="prb" style="width: 0%"></div>--}}
                                    {{--                                                                </div>--}}
                                    {{--                                                            </div>--}}
                                    {{--                                                            <div class="number">0</div>--}}
                                    {{--                                                        </div>--}}
                                    {{--                                                    </div>--}}
                                    {{--                                                    <div class="item clearfix for-star-3">--}}
                                    {{--                                                        <label class="radio-container">--}}
                                    {{--                                                            <input value="3" type="radio" name="radio">--}}
                                    {{--                                                            <span class="checkmark"></span>--}}
                                    {{--                                                        </label>--}}
                                    {{--                                                        <div class="content clearfix">--}}
                                    {{--                                                            <div class="star-block">--}}
                                    {{--                                                                @component(getThemeViewName('components.rating_stars'), ['score'=>3])--}}
                                    {{--                                                                @endcomponent--}}
                                    {{--                                                            </div>--}}
                                    {{--                                                            <div class="stats">--}}
                                    {{--                                                                <div class="bg">--}}
                                    {{--                                                                    <div class="prb" style="width: 0%"></div>--}}
                                    {{--                                                                </div>--}}
                                    {{--                                                            </div>--}}
                                    {{--                                                            <div class="number">0</div>--}}
                                    {{--                                                        </div>--}}
                                    {{--                                                    </div>--}}
                                    {{--                                                    <div class="item clearfix for-star-4">--}}
                                    {{--                                                        <label class="radio-container">--}}
                                    {{--                                                            <input value="4" type="radio" name="radio">--}}
                                    {{--                                                            <span class="checkmark"></span>--}}
                                    {{--                                                        </label>--}}
                                    {{--                                                        <div class="content clearfix">--}}
                                    {{--                                                            <div class="star-block">--}}
                                    {{--                                                                @component(getThemeViewName('components.rating_stars'), ['score'=>4])--}}
                                    {{--                                                                @endcomponent--}}
                                    {{--                                                            </div>--}}
                                    {{--                                                            <div class="stats">--}}
                                    {{--                                                                <div class="bg">--}}
                                    {{--                                                                    <div class="prb" style="width: 0%"></div>--}}
                                    {{--                                                                </div>--}}
                                    {{--                                                            </div>--}}
                                    {{--                                                            <div class="number">0</div>--}}
                                    {{--                                                        </div>--}}
                                    {{--                                                    </div>--}}
                                    {{--                                                    <div class="item clearfix for-star-5">--}}
                                    {{--                                                        <label class="radio-container">--}}
                                    {{--                                                            <input value="5" type="radio" name="radio">--}}
                                    {{--                                                            <span class="checkmark"></span>--}}
                                    {{--                                                        </label>--}}
                                    {{--                                                        <div class="content clearfix">--}}
                                    {{--                                                            <div class="star-block">--}}
                                    {{--                                                                @component(getThemeViewName('components.rating_stars'), ['score'=>5])--}}
                                    {{--                                                                @endcomponent--}}
                                    {{--                                                            </div>--}}
                                    {{--                                                            <div class="stats">--}}
                                    {{--                                                                <div class="bg">--}}
                                    {{--                                                                    <div class="prb" style="width: 0%"></div>--}}
                                    {{--                                                                </div>--}}
                                    {{--                                                            </div>--}}
                                    {{--                                                            <div class="number">0</div>--}}
                                    {{--                                                        </div>--}}
                                    {{--                                                    </div>--}}
                                    {{--                                                </div>--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </div>--}}
                                    {{--                                        <div class="featured-salon d-none">--}}
                                    {{--                                            <img src="{!! getThemeAssetUrl('img/featured_salon.png') !!}">--}}
                                    {{--                                            <div class="text">--}}
                                    {{--                                                Cửa hành được nhiều<br>--}}
                                    {{--                                                đánh giá tốt nhất tuần qua--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($related_salons)
        <div class="related-salon">
            <div class="container">
                <div class="block-title">Liên quan</div>
                <div class="salon-list owl-carousel">
                    @foreach($related_salons as $item)
                        <a href="{!! $item->url() !!}" class="item d-block">
                            <div class="img">
                                <img
                                        src="{!! $item->cover?$item->cover->getThumbnailUrl('large', getNoThumbnailUrl()):getNoThumbnailUrl() !!}">
                                @if($item->sale_up_to_percent_cache > 0)
                                    <div class="service-sale-max">
                                        <div class="lbl">Giảm đến</div>
                                        <div class="svl">{!! $item->sale_up_to_percent_cache !!}%</div>
                                    </div>
                                @endif
                            </div>
                            <div class="info">
                                <div class="title">{!! $item->name !!}</div>
                                <div class="location"><i class="fa fa-map-marker" aria-hidden="true"></i> {!!
                        $item->getAddressLine() !!}</div>
                                <div class="rating">
                                    <div class="number">{!! number_format($item->rating, 1, '.', '.') !!}</div>
                                    <div class="stars">
                                        @component(getThemeViewName('components.rating_stars'), ['score' => $item->rating])
                                        @endcomponent
                                    </div>
                                    <div class="total">({!! $item->rating_count !!})</div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    <div class="page-bread-cumber">
        <div class="container">
            <a href="#">Trang chủ</a> / <a href="#">Xu hướng tóc</a>
        </div>
    </div>
    @php
        $cart_total_count = array_sum(array_map(function ($item){return $item['amount'];}, session('wa_cart_items', [])));
    @endphp

    <div class="floating-cart {!! session('wa_cart_items', [])?'':'wa-hide' !!}">
        <div class="wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-6">
                        <div class="main clearfix">
                            <div class="count">
                                <img src="{!! getThemeAssetUrl('img/cart.png') !!}">
                                <div class="number">{!! $cart_total_count !!}</div>
                            </div>
                            <div class="sum">
                                {!! number_format(session('wa_cart_total', 0)/1000.0, 0).'K' !!}
                            </div>
                            <div class="desc">
                                <div class="big"><span class="number">{!! $cart_total_count !!}</span> dịch vụ được thêm
                                    vào giỏ hàng
                                </div>
                                <div class="small">Bạn có thể chọn thêm hoặc tiếp tục</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="link">
                            <form action="{!! route('frontend.cart.createOrder') !!}" method="POST" id="frm-create-order">
                                {{ csrf_field() }}
                                <button class="btn" type="submit" style="padding-top: 5px;width: 220px;height: 42px;font-size: 20px;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.1);background-color: white">Chọn thời gian</button>
                            </form>
                            {{--<a href="{!! route('frontend.cart.1') !!}">Chọn thời gian</a>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('page_footer_js')
    <script src="{{ asset('assets/ui/js/plugins/star-rating/simple-rating.js') }}"></script>
    <script type="text/javascript">
        {{--$('#frm-create-order').on('submit', (function (e) {--}}
            {{--// e.preventDefault();--}}
            {{--$.ajax({--}}
                {{--url: '{!! route('frontend.cart.createOrder') !!}',--}}
                {{--type: 'post',--}}
                {{--dataType: 'json',--}}
                {{--data: {--}}
                    {{--_token: '{{csrf_token()}}'--}}
                {{--},--}}
                {{--beforeSend: function () {--}}

                {{--},--}}
                {{--complete: function () {--}}

                {{--},--}}
                {{--success: function (json) {--}}

                {{--}--}}
            {{--});--}}
        {{--}));--}}

        function requiredLogin(){
            swal({
                title: "{!! __('Đăng nhập') !!}",
                text: "{!! __('Vui lòng đăng nhập!') !!}",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#EF5350",
                confirmButtonText: "{{__('Đồng ý')}}",
                cancelButtonText: "{{__('Hủy bỏ')}}",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                 $modal_login = $('#modal-login-nt').modal({
                    show: false,
                    backdrop: 'static'
                });
                 $modal_login.modal('show');
                 return false;
             }
         });
        }
        function changeStarStats($star, $current, $total) {
            var target = $('#review .rating-filter .for-star-' + $star);
            target.find('.number').html($current);
            var percent = $current * 100.0 / $total;
            target.find('.prb').css('width', percent + '%');
        }

        $('.modal').on('show.bs.modal', function (e) {
            $('html').css('overflow', 'hidden');
        });
        $('.modal').on('hidden.bs.modal', function (e) {
            $('html').removeAttr('style');
        });
        $('#map-view-link').click(function () {
            $('#modal-map-view-salon-info').modal('show');
        });

        $('a#read-more').click(function () {
            $('.training-info .sub-info-block-content').toggleClass('show-all');
            $(this).remove();
        });

        function loadReviews($page) {
            // var service_id = $('#review-cat-filter').val();
            var rating = $('#review-star-filter').val();
            var sort = $('#review-comment-filter').val();
            if (typeof rating == "undefined") {
                rating = -1;
            }
            $.ajax({
                url: '{!! route('frontend.salon.review.list', ['salon'=>$salon->id]) !!}',
                type: 'get',
                dataType: 'json',
                data: {
                    // service_id: service_id,
                    rating: rating,
                    sort: sort,
                    page: $page
                },
                beforeSend: function () {
                    $('.salon-info .review-info .review-box').addClass('loading');
                },
                complete: function () {
                    $('.salon-info .review-info .review-box').removeClass('loading');
                },
                success: function (json) {
                    wa_review_items_component_load('salon_review_box', json);
                }
            });
        }

        function readURL(input, id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#image-comment' + id)
                        .removeClass('d-none')
                        .attr('src', e.target.result)
                        .width(80)
                        .height(80);
                };
                $('.multi-image .items .item[data-id="' + id + '"]').removeClass('d-none');
                reader.readAsDataURL(input.files[0]);
            }
        };

        function addImageComment(id) {
            html = '<div class="item d-none" data-id="' + id + '">';
            html += '<label for="salonCommentImage' + id + '" class="mb-0 py-0 pr-2">';
            html += '<img class="d-none" id="image-comment' + id + '" src="#" alt="your image"/>';
            html += '</label>';
            html += '<input hidden type="file" accept="image/*" name="images[]" id="salonCommentImage' + id + '" onchange="readURL(this,' + id + ');"/>';
            html += '<button type="button" class="btn btn-link" id="remove-image-comment" onclick="removeImageComment(' + id + ');">' +
                '<span aria-hidden="true">&times;</span></button>';
            html += '</div>';

            $('.multi-image .items').append(html);
            $('#salonCommentImage' + id).click();
        };

        function removeImageComment(id) {
            $('.multi-image .items .item[data-id="' + id + '"]').remove();
        }

        $('.btn-add-img-cmt').click(function () {
            var item = $('.multi-image .items');
            var lastItem = item.children('.item').last();
            var lastItemAttr = lastItem.attr('data-id');
            var noImage = lastItem.hasClass('d-none');
            var countItem = item.children('.item').not('.d-none').length;

            if (lastItemAttr === undefined) {
                lastItemAttr = 1;
            } else {
                if (noImage) {
                    lastItem.remove();
                } else {
                    lastItemAttr++;
                }
            }

            if (countItem < 3) {
                addImageComment(lastItemAttr);
            } else {
                $('#salonCommentModal .error-image').slideDown();
                $('#salonCommentModal .error-image').removeClass('d-none').html('Tối đa 3 ảnh');
                setTimeout(function () {
                    $('#salonCommentModal .error-image').slideUp();
                }, 5000);
                ;
            }
        });
       
        $('#frm-comment #salonCommentTitle').change(function () {
            if ($(this).val().length > 100) {
                $(this).parent().addClass('alert').find('.error-title').html('Tiêu đề quá dài, tối đa 100 kí tự');
            } else {
                $(this).parent().removeClass('alert').find('.error-title').html('');
            }
            if ($(this).val().length < 3) {
                $(this).parent().addClass('alert').find('.error-title').html('Tiêu đề quá ngắn, ít nhất 3 kí tự!');
            } else {
                $(this).parent().removeClass('alert').find('.error-title').html('');
            }
        });

        $('#frm-comment #salonCommentDescription').change(function () {
            if ($(this).val().length > 1000) {
                $(this).parent().addClass('alert').find('.error-description').html('Mô tả quá dài, tối đa 1000 kí tự');
            } else {
                $(this).parent().removeClass('alert').find('.error-description').html('');
            }
            if ($(this).val().length < 3) {
                $(this).parent().addClass('alert').find('.error-description').html('Mô tả quá ngắn, ít nhất 3 kí tự!');
            } else {
                $(this).parent().removeClass('alert').find('.error-description').html('');
            }
        });

        $('#btn-send-comment').click(function () {
            
            $('#frm-comment').submit();
           
        });
        $('#frm-comment').on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '{!! route('frontend.salon.review.add') !!}',
                type: 'post',
                dataType: 'json',
                data: new FormData(this),
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#btn-send-comment').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>\n' +
                        '<span class="sr-only">Loading...</span>');
                },
                complete: function () {
                    $('#btn-send-comment').html('Gửi đánh giá');
                },
                success: function (json) {
                    $('#salonCommentTitle').val('');
                    $('#salonCommentDescription').val('');
                    $('.multi-image .items .item').remove();
                    $('#salonCommentModal').modal('hide');
                    setTimeout(function () {
                        swal("Thành công!", "Bài đánh giá của bạn đã được đăng thành công!", "success");
                        loadReviews(1);
                    }, 500);
                },
                error: function (xhr) {
                    $('.multi-image .items .item').remove();
                    $('#btn-send-comment').html('Gửi đánh giá');
                    if(xhr.responseJSON.status === 'errorImage'){
                        $('#salonCommentModal .error-image').slideDown();
                        $('#salonCommentModal .error-image').removeClass('d-none').html(xhr.responseJSON.message);
                        setTimeout(function () {
                            $('#salonCommentModal .error-image').slideUp();
                        }, 5000);
                    }
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        if (key === 'title') {
                            $('#frm-comment .error-title').html(value).parent().addClass('alert');
                        } else if (key === 'description') {
                            $('#frm-comment .error-description').html(value).parent().addClass('alert');
                        } else if (key === 'rating') {
                            $('#frm-comment .error-rating').html(value).parent().addClass('alert');
                        }
                    });
                },
            });
        }));


        $('.review-filter .rating-filter input[type=radio]').change(function () {
            loadReviews(1);
        });


        $('#review-star-filter').select2({
            width: '100%'
        }).on('change', function () {
            loadReviews(1);
        }).trigger('change');


        $('#review-comment-filter').select2({
            width: '100%'
        }).on('change', function () {
            loadReviews(1);
        }).trigger('change');

        $('#select-services-comment').select2({
            width: '100%',
            placeholder: 'Chọn dịch vụ ',
            allowClear: true,
        });
        $('#frm-comment .rating').rating();

        $.ajax({
            url: '{!! route('frontend.salon.review.filter.rating_list_by_service_cat', ['salon' => $salon->id]) !!}',
            type: 'get',
            dataType: 'json',
            data: {
                id: -1
            },
            beforeSend: function () {
                $('.salon-info .review-info .review-box').addClass('loading');
            },
            complete: function () {

            },
            success: function (json) {
                console.log(json)
                if (json.hasOwnProperty('length')) {
                    if (json.length == 6) {
                        var total = json[0];
                        $(json).each(function (i, v) {
                            changeStarStats(i, v, total);
                        });
                        loadReviews(1);
                    }
                }
            }
        });


        {{--$('#review-cat-filter').select2({--}}
        {{--    width: '100%'--}}
        {{--}).on('change', function () {--}}
        {{--    var id = $(this).val();--}}
        {{--    $.ajax({--}}
        {{--        url: '{!! route('frontend.salon.review.filter.rating_list_by_service_cat', ['salon' => $salon->id]) !!}',--}}
        {{--        type: 'get',--}}
        {{--        dataType: 'json',--}}
        {{--        data: {--}}
        {{--            id: id--}}
        {{--        },--}}
        {{--        beforeSend: function () {--}}
        {{--            $('.salon-info .review-info .review-box').addClass('loading');--}}
        {{--        },--}}
        {{--        complete: function () {--}}

        {{--        },--}}
        {{--        success: function (json) {--}}
        {{--            if (json.hasOwnProperty('length')) {--}}
        {{--                if (json.length == 6) {--}}
        {{--                    var total = json[0];--}}
        {{--                    $(json).each(function (i, v) {--}}
        {{--                        changeStarStats(i, v, total);--}}
        {{--                    });--}}
        {{--                    loadReviews(1);--}}
        {{--                }--}}
        {{--            }--}}
        {{--        }--}}
        {{--    });--}}
        {{--}).trigger('change');--}}
        $('.salon-gallery .img-list').owlCarousel({
            margin: 0,
            loop: true,
            items: 1,
            dots: 0,
            nav: 1,
            navText: [
                '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ],
            responsive: {
                768: {
                    autoWidth: true,
                    items: 2,
                }
            }
        });
        $('.stylist-list').owlCarousel({
            margin: 0,
            loop: false,
            items: 2,
            dots: 0,
            nav: 1,
            navText: [
                '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ],
            responsive: {
                992: {
                    items: 9,
                },
                768: {
                    items: 5,
                },
                575: {
                    items: 3,
                }
            }
        });
        $('.brand-list').owlCarousel({
            margin: 15,
            loop: false,
            items: 2,
            dots: 0,
            nav: 1,
            items: 1,
            navText: [
                '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ],
            responsive: {
                992: {
                    items: 4,
                },
                768: {
                    items: 3,
                },
                575: {
                    items: 2,
                }
            }
        });

        $('.showcase').owlCarousel({
            nav: 1,
            margin: 15,
            navText: [
                '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ],
            responsive: {
                0: {
                    items: 1,
                    autoWidth: false,
                },
                500: {
                    items: 2,
                    autoWidth: false,
                },
                992: {
                    items: 1,
                    autoWidth: true,
                }
            }
        });

        $('.related-salon .salon-list').owlCarousel({
            margin: 30,
            loop: 0,
            items: 1,
            dots: 0,
            nav: 1,
            navText: [
                '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ],
            responsive: {
                768: {
                    items: 3,
                }
            }
        });
        $('#local-nav').sticky();
        $('body').scrollspy({target: '#local-nav', offset: 100});
        $('#salon-share').popover({
            content: function () {
                return '<div class="salon-share-icon-list">' +
                    '<a href="https://www.facebook.com/sharer/sharer.php?u={!! $salon->url() !!}" style="background-color: #3F51B5" target="_blank"><i class="fa fa-facebook"></i></a>' +
                    '<a href="https://plus.google.com/share?url={!! $salon->url() !!}" style="background-color: #a94442" target="_blank"><i class="fa fa-google-plus"></i></a>' +
                    '<a href="https://twitter.com/home?status={!! $salon->url() !!}" style="background-color: #00BCD4" target="_blank"><i class="fa fa-twitter"></i></a></div>'
            },
            placement: 'bottom',
            title: 'Chia sẻ thông tin salon này',
            trigger: 'focus',
            html: true
        });
        $(function () {
            $('.salon-list-result .minize').click(function () {
                $(this).parents('.selected-salon').toggleClass('mini');
            });

            $('.service-list .service .add-button .icon').click(function () {
                @auth
                @if(!$salon->open)
                swal("Salon ngoại tuyến!", "Salon hiện tại đã ngoại tuyến không tiếp nhận đơn đặt chỗ mới, nếu bạn muốn đặt chỗ xin vui lòng quay lại sau nhé!", "warning")
                        @else
                var id = $(this).data('id');
                var service_node = $(this).parents('.service')[0];
                var action = function (option_id) {
                    var url = '{!! route('frontend.service.add_to_cart', ['service'=>'???']) !!}';
                    var node = $(this);
                    url = url.replace('???', id);
                    $.ajax({
                        url: url,
                        type: 'post',
                        dataType: 'json',
                        data: {
                            option_id: option_id
                        },
                        beforeSend: function () {
                            node.addClass('loading');
                        },
                        complete: function () {
                            node.removeClass('loading');
                        },
                        success: function (json) {
                            if (json.hasOwnProperty('added') && json.hasOwnProperty('count')) {
                                if (json.added) {
                                    $(service_node).addClass('added');
                                } else {
                                    $(service_node).removeClass('added');
                                }
                                $('.floating-cart .number').html(json.count);
                                $('.floating-cart .sum').html(json.total);
                                if (json.count == 0) {
                                    $('.floating-cart').addClass('wa-hide');
                                } else {
                                    $('.floating-cart').removeClass('wa-hide');
                                }
                            }
                        }
                    });
                };
                if ($(service_node).hasClass('added')) {
                    action(false);
                } else {
                    addCartChooseOptions(id, action);
                }
                @endif
                @else
                $('.show-login-form-link').click();
                @endif
            });

            $('#salon_review_box .load-more').click(function () {
                var page = $(this).data('page');
                loadReviews(page + 1);
                return false;
            });

            function toggleService(hide) {
                $('#salon-service-block .service').removeClass('not-toggle');
                $('#salon-service-block .show-all-service').addClass('d-none');
                if (hide) {
                    var slist = $('#salon-service-block .service').not('.d-none');
                    var limit = {!! $service_limit !!};
                    $(slist).each(function (index, node) {
                        if (index + 1 > limit) {
                            $(node).addClass('not-toggle');
                        }
                    });
                    if (slist.length > limit) {
                        $('#salon-service-block .show-all-service').removeClass('d-none');
                        $('#salon-service-block .show-all-service .service-count').html(slist.length);
                    }
                }
            }

            $('#salon-service-block .show-all-service a').click(function () {
                toggleService(false);
                return false;
            });

            $('.salon-service-cats .cats-item').click(function () {
                var cat = $(this).data('id');
                $('.salon-service-cats .cats-item').removeClass('active');
                $(this).addClass('active');
                $('#salon-service-block .service').removeClass('first-child');
                if (cat == 0) {
                    $('#salon-service-block .service').removeClass('d-none');
                } else if (cat == -1) {
                    $('#salon-service-block .service').not('[data-is-sale=1]').addClass('d-none');
                    $('#salon-service-block .service[data-is-sale=1]').removeClass('d-none');
                } else {
                    $('#salon-service-block .service').not('[data-cat=' + cat + ']').addClass('d-none');
                    $('#salon-service-block .service[data-cat=' + cat + ']').removeClass('d-none');
                }
                $($('#salon-service-block .service').not('.d-none')[0]).addClass('first-child');

                toggleService(true);
            });
            @php
                $service_cat_id = Request::get('service_cat');
            @endphp
            @if($service_cat_id)
            $('.salon-service-cats li[data-id={!! $service_cat_id !!}]').click();
            $("html, body").animate({
                scrollTop: $("#salon-service-block").offset().top - 80
            }, 500);
            @else
            $('.salon-service-cats .cats-item.show-all').click();
            var hasSale = window.location.hash.substr(1);
            if  (hasSale === 'sale') {
                $('#filter-sale').click();
            }

            @endif

            @auth
            $('#salon-like').click(function () {
                if ($(this).hasClass('loading')) {
                    return;
                }
                var a = $(this);
                $.ajax({
                    url: '{!! route('frontend.salon.like', ['salon'=>$salon->id]) !!}',
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function () {
                        a.addClass('loading');
                    },
                    complete: function () {
                        a.removeClass('loading');
                    },
                    success: function (json) {
                        if (json) {
                            a.addClass('active');
                        } else {
                            a.removeClass('active');
                        }
                    }
                });
                return false;
            });
            $('#showcase .item .like').click(function (e) {
                e.stopPropagation();
                if ($(this).hasClass('loading')) {
                    return false;
                }
                var id = $(this).data('id');
                var node = $(this);
                var url = '{!! route('frontend.salon.showcase.like', ['salon'=>$salon->id, 'showcase'=>'???']) !!}';
                url = url.replace('???', id);
                $.ajax({
                    url: url,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function () {
                        node.addClass('loading');
                    },
                    complete: function () {
                        node.removeClass('loading');
                    },
                    success: function (json) {
                        $(node).removeClass('liked');
                        if (json) {
                            $(node).addClass('liked');
                        }
                    }
                });
                return false;
            });
            @endauth
        });
        @if(!$salon->open)
        swal("Salon ngoại tuyến!", "Salon hiện tại đã ngoại tuyến không tiếp nhận đơn đặt chỗ mới, nếu bạn muốn đặt chỗ xin vui lòng quay lại sau nhé!", "warning")
        @else
        @if(Request::get('service'))
        showServiceQuickView({!! Request::get('service') !!});
        @endif
        @endif
    </script>
    @include(getThemeViewName('components.service_option'))
@endpush