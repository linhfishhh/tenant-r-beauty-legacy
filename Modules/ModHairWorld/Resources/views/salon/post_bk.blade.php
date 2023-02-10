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
@endphp
@push('page_meta')
<meta property="og:title" content="{{ $salon->name }}"/>
<meta property="og:image" content="{{ $og_img_ }}"/>
<meta property="og:description" content="{{ $info }}"/>
<meta property="og:type" content="article"/>

<meta name="description" content="{{ $info }}"/>
<meta property="og:image:secure_url" content="{{ $og_img }}" />
<meta property="og:image:width" content="{{$og_width}}" />
<meta property="og:image:height" content="{{$og_height}}" />
@if($keywords_string)
    <meta name="keywords" content="{{$keywords_string}}" />
@endif
@endpush
<div class="salon-page-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-7">
                <div class="salon-name">
                    {!! $salon->name !!}{!! $salon->certified?'<i title="Đã chứng thực" class="fa fa-check-circle salon-name-verified" aria-hidden="true"></i>':'' !!}
                </div>
            </div>
            <div class="col-lg-4 col-md-5 clearfix">
                <a class="salon-rating clearfix d-block" href="#review">
                    <div class="rating-score">
                        {!! number_format($salon->rating, 1)!!}
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
    </div>
</div>
<div class="salon-gallery{!! $salon->gallery->count()?'':' empty' !!}">
    <div class="container">
        <div class="salon-share-like">
            <a href="#" id="salon-share"><i class="fa fa-share-alt"></i></a>
            @auth
            <a href="#" id="salon-like" class="{!! $salon->likedBy(me()->id)?'active':'' !!}"><i class="fa fa-heart"></i></a>
            @endif
        </div>
        @if($salon->gallery->count())
        <div class="img-list owl-carousel">
            @foreach($salon->gallery as $item)
                    @if($item->image)
                    <a data-fancybox="gallery" class="d-block" href="{!! $item->image->getUrl() !!}" style="background-image: url('{!! $item->image->getThumbnailUrl('large', getNoThumbnailUrl()) !!}')">
                    </a>
                    @endif
            @endforeach
        </div>
        @endif
    </div>
</div>
<div class="salon-detail">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 order-lg-1">
                <div class="salon-map">
                    <div class="map">
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
                    <div class="info">
                        <div class="item location-working">
                            <div class="location">
                                <i class="fa fa-map-marker"></i>
                                <span>{!! $salon->getAddressLine() !!}</span>
                            </div>
                            <div class="work-hours">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="title">Ngày giờ mở cửa</div>
                                        <div class="sub-title">{!! $salon->timeWeekDays() !!}</div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="hours">
                                            {!! $salon->timeWorkingHours() !!}
                                            <a href="#info">Chi tiết</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($salon->certified)
                            <div class="item feature">
                                <div class="img"><img src="{!! getThemeAssetUrl('img/salon_verify.png') !!}"></div>
                                <div class="title">Salon được xác minh bởi iSalon</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-8 order-lg-0">
                <div class="d-none d-lg-block">
                    <div class="local-nav" id="local-nav">
                        <div class="container">
                            <ul class="nav">
                                @if($salon->getHotServices())
                                <li class="nav-item"><a class="nav-link" href="#hot-services">Dịch vụ hot</a></li>
                                @endif
                                <li class="nav-item"><a class="nav-link" href="#salon-services">Dịch vụ</a></li>
                                <li class="nav-item"><a class="nav-link" href="#info">Thông tin</a></li>
                                @if($salon->showcases->count())
                                <li class="nav-item"><a class="nav-link" href="#showcase">Tác phẩm</a></li>
                                @endif
                                <li class="nav-item"><a class="nav-link" href="#review">Đánh giá</a></li>
                            </ul>
                        </div>
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
                                @php
                                @endphp
                                @component(getThemeViewName('components.salon_services'), ['items' => $items, 'can_book'=>$salon->open]) @endcomponent
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="salon-service" id="salon-services">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="salon-service-title">
                                <img src="{!! getThemeAssetUrl('img/dv_icon.png') !!}">
                                <span>Dịch vụ</span>
                            </div>
                            <div class="salon-service-cats">
                                <ul>
                                    <li data-id="0" class="show-all">
                                        <div class="wrapper">
                                            <div class="title">
                                                Tất cả
                                            </div>
                                            <div class="count">
                                                ({!! $salon->services->count() !!})
                                            </div>
                                        </div>
                                    </li>
                                    @foreach($salon->service_categories as $k=>$cat)
                                        <li data-id="{!! $cat->id !!}">
                                            <div class="wrapper">
                                                <div class="title">
                                                    {!! $cat->title !!}
                                                </div>
                                                <div class="count">
                                                    ({!! $salon->countServiceByCat($cat->id) !!})
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="salon-service-text">Chúng tôi mong muốn mang lại dịch vụ tốt nhất cho bạn!</div>
                            <div class="salon-service-block" id="salon-service-block">
                                @component(getThemeViewName('components.salon_services'), ['items' => $salon->services, $limit = 5, 'can_book'=>$salon->open]) @endcomponent
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="salon-info">
    <div class="container">
        <div class="info-block basic-info" id="info">
            <div class="info-block-title clearfix">
                <div class="icon">
                    <img src="{!! getThemeAssetUrl('img/info_icon_1.png') !!}">
                </div>
                <div class="text">Thông tin chung</div>
            </div>
            <div class="info-block-content">
                <!--region main info -->
                <div class="main-basic-info">
                    <div class="row">
                    <div class="col-lg-8">
                        <div class="intro-block">
                            {!! $salon->info !!}
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="times-block" id="salon-open-hours">
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
                <!--endregion-->
                <!--region stylist-->
                @if($salon->stylist->count())
                    <div class="sub-basic-info stylist-info">
                        <div class="sub-info-block">
                            <div class="sub-info-block-title clearfix">
                                <div class="icon">
                                    <img src="{!! getThemeAssetUrl('img/sub_icon_1.png') !!}">
                                </div>
                                <div class="text">Stylist</div>
                            </div>
                            <div class="sub-info-block-content">
                                <div class="stylist-list owl-carousel">
                                    @foreach($salon->stylist as $item)
                                        <div class="stylist">
                                            <div class="wrapper">
                                                <div class="img">
                                                    <img src="{!! $item->avatar?$item->avatar->getThumbnailUrl('default', getNoAvatarUrl()):getNoAvatarUrl() !!}">
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
                @endif
                <!--endregion-->
                <!--region brands-->
                @if($salon->brands->count())
                <div class="sub-basic-info brand-info">
                    <div class="sub-info-block">
                        <div class="sub-info-block-title clearfix">
                            <div class="icon">
                                <img src="{!! getThemeAssetUrl('img/sub_icon_2.png') !!}">
                            </div>
                            <div class="text">Những thương hiệu sử dụng tại salon</div>
                        </div>
                        <div class="sub-info-block-content">
                            <div class="brand-list owl-carousel">
                                @foreach($salon->brands as $item)
                                    <div class="brand" style="background-image: url('{!! $item->logo?$item->logo->getThumbnailUrl('medium_ka', getNoThumbnailUrl()):getNoThumbnailUrl() !!}')">

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <!--endregion-->
                <!--region extended info-->
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
        <!--region showcase-->
        @if($salon->showcases->count())
            <div class="info-block gallery-info" id="showcase">
                <div class="info-block-title clearfix">
                    <div class="icon">
                        <img src="{!! getThemeAssetUrl('img/info_icon_2.png') !!}">
                    </div>
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
                                <a data-caption="{{$showcase->name }}" data-fancybox="gallery_{!! $gk !!}" href="{!! $first_item->image?$first_item->image->getUrl():getNoThumbnailUrl() !!}" class="d-block wrapper">
                                    <div class="cover" style="background-image: url('{!! $first_item->image?$first_item->image->getThumbnailUrl('large', getNoThumbnailUrl()):getNoThumbnailUrl() !!}')">
                                        @auth
                                        <div data-id="{!! $showcase->id !!}" class="like{!! $showcase->likedBy(me()->id)?' liked':'' !!}"></div>
                                        @endauth
                                        <div class="item-count">{!! $showcase->items->count() !!} ảnh</div>
                                    </div>
                                    <div class="title">{!! $showcase->name !!}</div>
                                </a>
                                <div class="d-none">
                                    @foreach($showcase->items as $k=>$i)
                                        @if($k==0)
                                            @continue
                                        @endif
                                        <a data-caption="{{$showcase->name }}" data-fancybox="gallery_{!! $gk !!}" href="{!! $i->image?$i->image->getUrl():getNoThumbnailUrl() !!}"></a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        <!--endregion-->
        <div class="info-block review-info" id="review">
            <div class="info-block-title clearfix">
                <div class="icon">
                    <img src="{!! getThemeAssetUrl('img/info_icon_3.png') !!}">
                </div>
                <div class="text">Nhận xét & đánh giá</div>
            </div>
            <div class="info-block-content">
                <div class="rating-box">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="total-rating">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="number">
                                            <div class="current">{!! number_format($salon->rating, 1) !!}</div>
                                            <div class="total">/5</div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="star-block">
                                            @component(getThemeViewName('components.rating_stars'), ['score'=>$salon->rating])
                                            @endcomponent
                                        </div>
                                        @if($salon->rating_count)
                                        <div class="text-block">Có {!! $salon->rating_count !!} đánh giá nhận xét</div>
                                        @else
                                            <div class="text-block">Chưa có đánh giá nào</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="detail-rating">
                                <div class="row">
                                    @foreach($criterias as $criteria)
                                    <div class="col-md-6">
                                        <div class="rating-detail-item">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="rating-star-block">
                                                        @component(getThemeViewName('components.rating_stars'), ['score' => $criteria_ratings->get($criteria->id, 0.0)])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="rating-title">
                                                        {!! $criteria->name !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="review-box">
                    <div class="row">
                        <div class="col-md-8 order-md-1">
                            <div class="right-sec">
                                @component(getThemeViewName('components.review_items'), [
                                    'id' => 'salon_review_box'
                                ])
                                @endcomponent
                            </div>
                        </div>
                        <div class="col-md-4 order-md-0">
                            <div class="review-filter">
                                <div class="wrapper">
                                    <div class="block-title">
                                        Lọc đánh giá
                                    </div>
                                    <div class="cat-filter">
                                        <select id="review-cat-filter">
                                            <option value="-1">Tất cả dịch vụ</option>
                                            @foreach($salon->services as $cat)
                                                <option value="{!! $cat->id !!}">{!! $cat->name !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="rating-filter">
                                        <div class="item clearfix for-star-0">
                                            <label class="radio-container">
                                                <input checked="checked" value="-1" type="radio" name="radio">
                                                <span class="checkmark"></span>
                                            </label>
                                            <div class="content clearfix">
                                                <div class="star-block">
                                                    @component(getThemeViewName('components.rating_stars'), ['score'=>0])
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
                                            <label class="radio-container">
                                                <input value="1" type="radio" name="radio">
                                                <span class="checkmark"></span>
                                            </label>
                                            <div class="content clearfix">
                                                <div class="star-block">
                                                    @component(getThemeViewName('components.rating_stars'), ['score'=>1])
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
                                            <label class="radio-container">
                                                <input value="2" type="radio" name="radio">
                                                <span class="checkmark"></span>
                                            </label>
                                            <div class="content clearfix">
                                                <div class="star-block">
                                                    @component(getThemeViewName('components.rating_stars'), ['score'=>2])
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
                                            <label class="radio-container">
                                                <input value="3" type="radio" name="radio">
                                                <span class="checkmark"></span>
                                            </label>
                                            <div class="content clearfix">
                                                <div class="star-block">
                                                    @component(getThemeViewName('components.rating_stars'), ['score'=>3])
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
                                            <label class="radio-container">
                                                <input value="4" type="radio" name="radio">
                                                <span class="checkmark"></span>
                                            </label>
                                            <div class="content clearfix">
                                                <div class="star-block">
                                                    @component(getThemeViewName('components.rating_stars'), ['score'=>4])
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
                                        <div class="item clearfix for-star-5">
                                            <label class="radio-container">
                                                <input value="5" type="radio" name="radio">
                                                <span class="checkmark"></span>
                                            </label>
                                            <div class="content clearfix">
                                                <div class="star-block">
                                                    @component(getThemeViewName('components.rating_stars'), ['score'=>5])
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
                                    </div>
                                </div>
                            </div>
                            <div class="featured-salon d-none">
                                <img src="{!! getThemeAssetUrl('img/featured_salon.png') !!}">
                                <div class="text">
                                    Cửa hành được nhiều<br>
                                    đánh giá tốt nhất tuần qua
                                </div>
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
        <div class="block-title">Có thể bạn quan tâm</div>
        <div class="salon-list owl-carousel">
            @foreach($related_salons as $item)
                <a href="{!! $item->url() !!}" class="item d-block">
                    <div class="img">
                        <img src="{!! $item->cover?$item->cover->getThumbnailUrl('large', getNoThumbnailUrl()):getNoThumbnailUrl() !!}">
                    </div>
                    <div class="info">
                        <div class="title">{!! $item->name !!}</div>
                        <div class="location"><i class="fa fa-map-marker" aria-hidden="true"></i> {!! $item->getAddressLine() !!}</div>
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

<div class="floating-cart {!! session('wa_cart_items', [])?'':'wa-hide' !!}">
    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="main clearfix">
                        <div class="count">
                            <img src="{!! getThemeAssetUrl('img/cart.png') !!}">
                            <div class="number">{!! array_sum(session('wa_cart_items', [])) !!}</div>
                        </div>
                        <div class="sum">
                            {!! number_format(session('wa_cart_total', 0)/1000.0, 0).'K' !!}
                        </div>
                        <div class="desc">
                            <div class="big"><span class="number">{!! array_sum(session('wa_cart_items', [])) !!}</span> dịch vụ được thêm vào giỏ hàng</div>
                            <div class="small">Bạn có thể chọn thêm hoặc tiếp tục</div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="link">
                        <a href="{!! route('frontend.cart.1') !!}">Chọn thời gian</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('page_footer_js')
<script type="text/javascript">
    function changeStarStats($star, $current, $total) {
        var target = $('.review-filter .rating-filter .for-star-'+$star);
        target.find('.number').html($current);
        var percent = $current*100.0/$total;
        target.find('.prb').css('width', percent+'%');
    }
    function loadReviews($page){
        var service_id = $('#review-cat-filter').val();
        var rating = $('.review-filter .rating-filter input[type=radio]:checked').val();
        if(typeof rating == "undefined"){
            rating = -1;
        }
        $.ajax({
            url: '{!! route('frontend.salon.review.list', ['salon'=>$salon->id]) !!}',
            type: 'get',
            dataType: 'json',
            data: {
                service_id: service_id,
                rating: rating,
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
    $('.review-filter .rating-filter input[type=radio]').change(function () {
        loadReviews(1);
    });
    $('#review-cat-filter').select2({
        width: '100%'
    }).on('change', function () {
        var id = $(this).val();
        $.ajax({
            url: '{!! route('frontend.salon.review.filter.rating_list_by_service_cat', ['salon' => $salon->id]) !!}',
            type: 'get',
            dataType: 'json',
            data: {
              id: id
            },
            beforeSend: function () {
                $('.salon-info .review-info .review-box').addClass('loading');
            },
            complete: function () {

            },
            success: function (json) {
                if(json.hasOwnProperty('length')){
                    if(json.length == 6){
                        var total = json[0];
                        $(json).each(function (i, v) {
                            changeStarStats(i, v, total);
                        });
                        loadReviews(1);
                    }
                }
            }
        });
    }).trigger('change');
    $('.salon-gallery .img-list').owlCarousel({
        margin:0,
        loop:true,
        items:1,
        dots: 0,
        nav: 1,
        navText: [
            '<i class="fa fa-angle-left" aria-hidden="true"></i>',
            '<i class="fa fa-angle-right" aria-hidden="true"></i>'
        ],
        responsive:{
            768: {
                autoWidth:true,
                items:2,
            }
        }
    });
    $('.stylist-list').owlCarousel({
        margin:0,
        loop:false,
        items:2,
        dots: 0,
        nav: 1,
        navText: [
            '<i class="fa fa-angle-left" aria-hidden="true"></i>',
            '<i class="fa fa-angle-right" aria-hidden="true"></i>'
        ],
        responsive:{
            992:{
                items:9,
            },
            768:{
                items:5,
            },
            575:{
                items:3,
            }
        }
    });
    $('.brand-list').owlCarousel({
        margin:15,
        loop:false,
        items:2,
        dots: 0,
        nav: 1,
        navText: [
            '<i class="fa fa-angle-left" aria-hidden="true"></i>',
            '<i class="fa fa-angle-right" aria-hidden="true"></i>'
        ],
        responsive:{
            992:{
                items:4,
            },
            768:{
                items:3,
            },
            575:{
                items:2,
            }
        }
    });

    $('.showcase').owlCarousel({
        margin:30,
        loop:false,
        items:1,
        dots: 0,
        nav: 1,
        //stagePadding: 50,
        autoWidth:true,
        navText: [
            '<i class="fa fa-angle-left" aria-hidden="true"></i>',
            '<i class="fa fa-angle-right" aria-hidden="true"></i>'
        ],
        responsive:{
            768: {
                items:2,
            },
        }
    });

    $('.related-salon .salon-list').owlCarousel({
        margin:30,
        loop:0,
        items:1,
        dots: 0,
        nav: 1,
        navText: [
            '<i class="fa fa-angle-left" aria-hidden="true"></i>',
            '<i class="fa fa-angle-right" aria-hidden="true"></i>'
        ],
        responsive:{
            768: {
                items:3,
            }
        }
    });
    $('#local-nav').sticky();
    $('body').scrollspy({ target: '#local-nav', offset: 100 });
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

        $('.service-list .service .add-button .icon').click(function () {
            @if(!$salon->open)
            swal("Salon ngoại tuyến!", "Salon hiện tại đã ngoại tuyến không tiếp nhận đơn đặt chỗ mới, nếu bạn muốn đặt chỗ xin vui lòng quay lại sau nhé!", "warning")
            @else
                var service_node = $(this).parents('.service')[0];
                var id = $(this).data('id');
                var url = '{!! route('frontend.service.add_to_cart', ['service'=>'???']) !!}';
                var node = $(this);
                url = url.replace('???', id);
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    beforeSend: function () {
                        node.addClass('loading');
                    },
                    complete: function () {
                        node.removeClass('loading');
                    },
                    success: function (json) {
                        if(json.hasOwnProperty('added') && json.hasOwnProperty('count')){
                            if(json.added){
                                $(service_node).addClass('added');
                            }
                            else{
                                $(service_node).removeClass('added');
                            }
                            $('.floating-cart .number').html(json.count);
                            $('.floating-cart .sum').html(json.total);
                            if(json.count == 0){
                                $('.floating-cart').addClass('wa-hide');
                            }
                            else{
                                $('.floating-cart').removeClass('wa-hide');
                            }
                        }
                    }
                });
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
            if(hide){
                var slist = $('#salon-service-block .service').not('.d-none');
                var limit = {!! $service_limit !!};
                $(slist).each(function (index, node) {
                    if(index + 1 > limit){
                        $(node).addClass('not-toggle');
                    }
                });
                if(slist.length > limit){
                    $('#salon-service-block .show-all-service').removeClass('d-none');
                    $('#salon-service-block .show-all-service .service-count').html(slist.length);
                }
            }
        }

        $('#salon-service-block .show-all-service a').click(function () {
            toggleService(false);
            return false;
        });

        $('.salon-service-cats li').click(function () {
            var cat = $(this).data('id');
            $('.salon-service-cats li').removeClass('active');
            $(this).addClass('active');
            $('#salon-service-block .service').removeClass('first-child');
            if(cat == 0){
                $('#salon-service-block .service').removeClass('d-none');
            }
            else{
                $('#salon-service-block .service').not('[data-cat='+cat+']').addClass('d-none');
                $('#salon-service-block .service[data-cat='+cat+']').removeClass('d-none');
            }
            $($('#salon-service-block .service').not('.d-none')[0]).addClass('first-child');
            toggleService(true);
        });

        $('.salon-service-cats li.show-all').click();

        @auth
        $('#salon-like').click(function () {
            if($(this).hasClass('loading')){
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
                    if(json){
                        a.addClass('active');
                    }
                    else{
                        a.removeClass('active');
                    }
                }
            });
            return false;
        });
        $('#showcase .item .like').click(function (e) {
            e.stopPropagation();
            if($(this).hasClass('loading')){
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
                    if(json){
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
    @endif
</script>
@endpush