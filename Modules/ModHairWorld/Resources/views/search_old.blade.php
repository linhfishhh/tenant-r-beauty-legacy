@enqueueCSS('search-page', getThemeAssetUrl('libs/styles/search.old.css'), 'master-page')
@enqueueCSS('select2', getThemeAssetUrl('libs/select2/css/select2.min.css'), 'bootstrap')
@enqueueJS('select2', getThemeAssetUrl('libs/select2/js/select2.full.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueCSS('datepicker', getThemeAssetUrl('libs/datepicker/css/bootstrap-datepicker.standalone.min.css'), 'bootstrap')
@enqueueJS('datepicker', getThemeAssetUrl('libs/datepicker/js/bootstrap-datepicker.min.js'), JS_LOCATION_HEAD, 'bootstrap')
@enqueueJS('datepicker-language', getThemeAssetUrl('libs/datepicker/js/locales/bootstrap-datepicker.vi.min.js'), JS_LOCATION_HEAD, 'datepicker')
@extends(getThemeViewName('master'))
@section('current_page_title')
    Tìm kiếm
@endsection
@push('page_head')
    <script>
        var dataLayer = [];
        dataLayer.push({
            'dynx_itemid':'',
            'dynx_pagetype' : 'searchresults',
            'dynx_totalvalue' : 0
        });
    </script>
@endpush
@php
    $og_img = getNoThumbnailUrl();
     $og_width = 500;
     $og_height = 500;
@endphp
@push('page_meta')
    <meta property="og:title" content="Tìm kiếm salon - iSalon"/>
    <meta property="og:image" content="{{ $og_img }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:image:secure_url" content="{{ $og_img }}" />
    <meta property="og:image:width" content="{{$og_width}}" />
    <meta property="og:image:height" content="{{$og_height}}" />
@endpush
@section('page_content')
    @php
        $dark_theme = 1;
        /** @var \Modules\ModHairWorld\Entities\Salon[] $result */
        $headline = getSetting('theme_search_headline', '');
        $headline_bg = getSetting('theme_search_headline_bg', false);
        $headline_bg_mb = getSetting('theme_search_headline_bg_mb', false);
        $headline_link = getSetting('theme_search_link', false);
        if($headline_bg){
            $f = \App\UploadedFile::find($headline_bg);
            $fm = \App\UploadedFile::find($headline_bg_mb);
            if($f && $fm){
                $bg = $f->getUrl();
                $bgm = $fm->getUrl();
                if($bg && $bgm){
                    $headline_bg = $bg;
                    $headline_bg_bm = $bgm;
                }
            }
        }
    @endphp
    @include(getThemeViewName('includes.service_quickview'))
    <a
        @if($headline_link)
        href="{!! $headline_link !!}"
        @endif
        class="search-page-header common-page-header" style="display: block">
        @if(isset($headline_bg) && isset($headline_bg_bm))
            <img class="d-none d-sm-block" style="width: 100%;" src="{!! $headline_bg !!}">
            <img class="d-sm-none" style="width: 100%;" src="{!! $headline_bg_bm !!}">
        @endif
    </a>
    <div class="main-content">
        <div class="container">
            <div class="row main-zone">
                <div class="col-lg-4">
                    <form id="filter-form" type="get">
                    @if(Request::has('utm_source'))
                        <input type="hidden" name="utm_source" value="{!! Request::get('utm_source') !!}">
                    @endif
                        @if(Request::has('utm_medium'))
                            <input type="hidden" name="utm_source" value="{!! Request::get('utm_medium') !!}">
                        @endif
                        @if(Request::has('utm_campaign'))
                            <input type="hidden" name="utm_source" value="{!! Request::get('utm_campaign') !!}">
                        @endif
                        @if(Request::has('utm_content'))
                            <input type="hidden" name="utm_source" value="{!! Request::get('utm_content') !!}">
                        @endif
                    <div class="filter-section">
                     <div class="filter-controls">
                        <div class="filter-order">
                            <select id="filter-order" name="order_by">
                                <option value="">Sắp xếp mặc định</option>
                                <option value="booking">Sắp xếp theo độ phổ biến</option>
                                <option value="price">Sắp xếp theo giá</option>
                                <option value="sale">Sắp xếp theo giảm giá</option>
                                <option value="rating">Sắp xếp theo sao</option>
                            </select>
                        </div>
                        <button type="button" class="filter-toggle-button map-toggle ">
                            <span>Bản đồ</span>
                        </button>
                        <button type="button" class="filter-toggle-button filter-toggle minxs" >
                            <span>Bộ lọc</span>
                        </button>
                        
                    </div>
                    <div class="map-filter">
                        <div class="map" style="min-height: 300px">
                        </div>
                        <div class="map-open">
                            <a href="#" id="map-view-link">
                                <img src="{!! getThemeAssetUrl('img/map_open_icon.png') !!}">
                                <span>Mở kết quả trên bản đồ</span>
                            </a>
                        </div>
                    </div>
                        <div id="main-filters">
                            <div class="mobile-filter-float-header">
                                <div class="row mobile-filter-top">
                                    <div class="col-6">
                                        <div class="mobile-filter-title">Bộ lọc</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mobile-filter-button">
                                            <button type="button">Đóng lại</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="filter-block">
                                <div class="block-title">
                                    Vị trí
                                </div>
                                <div class="block-content">
                                    <div class="location-search">
                                        <div class="icon">
                                            <img src="{!! getThemeAssetUrl('img/marker.png') !!}">
                                        </div>
                                        @php
                                            $address_search = request([
                                                'address_lat',
                                                'address_lng',
                                                'address_type',
                                                'address'
                                            ]);
                                        @endphp
                                        @if($address_search && $address_search['address'] && $address_search['address_lat'] && $address_search['address_lng'] && $address_search['address_type'])
                                            <input value="{!!$address_search['address']  !!}" name="address" id="address_autocomplete" autocomplete="off" spellcheck="false" placeholder="Nhập địa điểm">
                                            <input value="{!!$address_search['address_lat']  !!}" type="hidden" name="address_lat">
                                            <input value="{!!$address_search['address_lng']  !!}" type="hidden" name="address_lng" >
                                            <input value="{!!$address_search['address_type']  !!}" type="hidden" name="address_type">
                                        @else
                                            <input name="address" id="address_autocomplete" autocomplete="off" spellcheck="false" placeholder="Nhập địa điểm">
                                            <input type="hidden" name="address_lat">
                                            <input type="hidden" name="address_lng" >
                                            <input type="hidden" name="address_type">
                                        @endif
                                    </div>
                                    <div class="sub-block distance-filter">
                                        <div class="sub-block-title">Khoảng cách</div>
                                        @component(getThemeViewName('components.slider'), [
                                        'id' => 'distance-filter',
                                        'unit' => 'Km',
                                        'attributes' => 'type="range" min="1" max="50" step="1" value="50"',
                                        'name' => 'distance'
                                        ]
                                        )
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                            <div class="filter-block">
                                <div class="block-title">
                                    Bộ lọc
                                </div>
                                <div class="block-content">
                                    <div class="basic-fillter filter-basic-type">
                                        <div class="checkbox-list">
                                            <div class="item">
                                                <label class="checkbox-container">
                                                    <input name="is_sale" value="1" type="checkbox" {!! Request::get('is_sale')?'checked="checked"':'' !!}>
                                                    <span class="checkmark"></span>
                                                    Đang khuyến mãi
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sub-block keyword-filter">
                                        <div class="sub-block-title">Từ khoá tìm kiếm</div>
                                        <div class="location-search">
                                            <div class="icon">
                                                <img src="{!! getThemeAssetUrl('img/keyword.png') !!}">
                                            </div>
                                            <input value="{!! request()->get('keyword', '') !!}" autocomplete="off" spellcheck="false" name="keyword" placeholder="Tên salon hoặc dịch vụ">
                                        </div>
                                    </div>
                                    <div class="sub-block time-filter">
                                        <div class="sub-block-title">Có phục vụ vào ngày</div>
                                        <div class="location-search">
                                            <div class="icon">
                                                <img src="{!! getThemeAssetUrl('img/date.png') !!}">
                                            </div>
                                            @php
                                                if($workday = request()->get('workday')){
                                                    try{
                                                        \Carbon\Carbon::createFromFormat('d/m/Y', $workday);
                                                    }
                                                    catch (Exception $exception){
                                                        $workday = '';
                                                    }
                                                }
                                            @endphp
                                            <input value="{!! $workday !!}" autocomplete="off" spellcheck="false" name="workday" placeholder="Chọn ngày">
                                        </div>
                                    </div>
                                    <div class="sub-block rating-filter">
                                        <div class="sub-block-title">Đánh giá salon</div>
                                        <select name="rating">
                                            <option {!! Request::get('rating', 0)==0?'selected="selected"':'' !!} value="0">Tất cả</option>
                                            <option {!! Request::get('rating', 0)==1?'selected="selected"':'' !!} value="1">1 Sao</option>
                                            <option {!! Request::get('rating', 0)==2?'selected="selected"':'' !!} value="2">2 Sao</option>
                                            <option {!! Request::get('rating', 0)==3?'selected="selected"':'' !!} value="3">3 Sao</option>
                                            <option {!! Request::get('rating', 0)==4?'selected="selected"':'' !!} value="4">4 Sao</option>
                                            <option {!! Request::get('rating', 0)==5?'selected="selected"':'' !!} value="5">5 Sao</option>
                                        </select>
                                    </div>
                                    <div class="sub-block price-filter">
                                        <div class="sub-block-title">Giá dịch vụ</div>
                                        @component(getThemeViewName('components.slider'), [
                                        'id' => 'price-filter',
                                        'name' => 'price',
                                        'unit' => 'K',
                                        'attributes' => 'type="range" min="0" max="999" step="10" value="'.Request::get('price', 0).'"'
                                        ]
                                        )
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                            @php
                            $locations = Modules\ModHairWorld\Entities\DiaPhuongTinhThanhPho::get(['id', 'name'])->filter(function($item){
                                return !in_array($item->id, [1, 48, 79]);
                            })->values();
                            $locations->prepend([
                                'id' => 48,
                                'name' => 'Thành phố Đà Nẵng'
                            ]);
                             $locations->prepend([
                                'id' => 79,
                                'name' => 'Thành phố Hồ Chí Minh'
                            ]);
                                $locations->prepend([
                                'id' => 1,
                                'name' => 'Thành Phố Hà Nội'
                            ]);
                            $has_search = false;
                            $location_lv = request()->get('location_lv');
                            if($location_lv){
                                $location = request()->get('location', []);
                                if(!is_array($location)){
                                    $location = [$location];
                                }

                                if($location){
                                    $location_info = $locations->filter(function($item) use($location){
                                        return in_array($item['id'].'', $location);
                                    });
                                    if($location_info){
                                            $has_search = true;
                                    }
                                }
                            }
                            @endphp
                            <div class="filter-block @if(!$has_search) d-none @endif" id="filter-block-location">
                                <div class="block-title">
                                    Khu vực
                                </div>
                                <div class="block-content">
                                    <div class="area-filter">
                                        <div class="checkbox-list">
                                            @if($has_search)
                                                @foreach($location_info as $info)
                                                    <div class="item" data-id="{!! $info['id'] !!}">
                                                        <label class="checkbox-container">
                                                            <input checked="checked" name="location[]" value="{!! $info['id'] !!}" type="checkbox">
                                                            <span class="checkmark"></span>
                                                            {!! $info['name'] !!}
                                                        </label>
                                                    </div>
                                                @endforeach
                                                <input type="hidden" name="location_lv" value="{!! $location_lv !!}">
                                            @endif
                                        </div>
                                        <div class="show-all">
                                            <img src="{!! getThemeAssetUrl('img/plus.png') !!}">
                                            <span>Nhiều hơn</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @php
                                $has_search = false;
                                $cats = Modules\ModHairWorld\Entities\SalonServiceCategory::orderBy('ordering', 'asc')->get(['id', 'title']);
                                $cat = Request::get('cat', []);
                                if(!is_array($cat)){
                                    $cat = [$location];
                                }
                                if($cat){
                                    $cat_list = $cats->filter(function($item) use($cat){
                                        return in_array($item->id.'', $cat);
                                    });
                                    if($cat_list){
                                        $has_search = true;
                                    }
                                }
                            @endphp
                            <div class="filter-block d-none" id="filter-block-cat">
                                <div class="block-title">
                                    Dịch vụ
                                </div>
                                <div class="block-content">
                                    <div class="cat-filter">
                                        <div class="checkbox-list">
                                            @if($has_search)
                                                @foreach($cat_list as $cat)
                                                    <div class="item" data-id="{!! $cat->id !!}">
                                                        <label class="checkbox-container">
                                                            <input checked="checked" name="cat[]" value="{!! $cat->id !!}" type="checkbox">
                                                            <span class="checkmark"></span>
                                                            {!! $cat->title !!}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="show-all">
                                            <img src="{!! getThemeAssetUrl('img/plus.png') !!}">
                                            <span>Nhiều hơn</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                   
                     </div>
                    </form>
                
                </div>
                <div class="col-lg-8">
                    <div class="salon-list-result">

                    </div>
                    <div class="load-more d-none"><i class="fa fa-caret-down" aria-hidden="true"></i> TẢI THÊM</div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-bread-cumber">
        <div class="container">
            <a href="#">Trang chủ</a> / <a href="#">Xu hướng tóc</a>
        </div>
    </div>
    <div class="modal fade" id="modal-map-view" tabindex="-1" role="dialog" aria-hidden="true">
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

                        </div>
                        <div class="selected-salon">
                            <div class="wrapper">
                                <div class="salon-list-result">
                                    <div class="salon">
                                        <div class="minize">
                                            Thu xuống
                                        </div>
                                        <div class="row salon-info-zone">

                                        </div>
                                    </div>
                                </div>
                                <div class="nav-block clearfix">
                                    <div class="prev"><i class="fa fa-chevron-left"></i></div>
                                    <div class="next"><i class="fa fa-chevron-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('page_footer_js')
    <template id="search-salon-tpl">
        <div class="salon">
            <div class="row">
                <div class="col-md-6">
                    <div class="img">
                        {sale_max}
                        <a href="{salon_url}">
                            <img src="{salon_cover}">
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="title">
                        <a href="{salon_url}">{salon_name}{verified}</a>
                    </div>
                    <div class="location">
                        <i class="fa fa-map-marker"></i>
                        <span title="{address}">{address}</span>
                    </div>
                    <div class="rating">
                        <div class="number">{rating}</div>
                        <div class="stars">
                            {rating_stars}
                        </div>
                        <div class="total">({rating_count})</div>
                    </div>
                    <div class="services">
                        {services}
                    </div>
                </div>
            </div>
        </div>
    </template>
    <template id="search-salon-service-tpl">
        <div class="service {promo}" onclick="showServiceQuickView({service_id});return false;" style="cursor: pointer">
            <div class="row">
                <div class="col-md-6">
                    <div class="service-title">
                        <a href="#">{service_name}</a>
                    </div>
                    <div class="service-time">
                        {service_time}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="service-price">
                        {promo_remain}
                        {price}
                        {sale}
                    </div>
                </div>
            </div>
        </div>
    </template>
    <template id="search-filter-chk-tpl">
        <div class="item" data-id="{value}">
            <label class="checkbox-container">
                <input name="{name}[]" value="{value}" type="checkbox">
                <span class="checkmark"></span>
                <span class="checkmark-title">{title}</span>
                {{--<div class="number">{number}</div>--}}
            </label>
        </div>
    </template>
    <template id="map-salon-service-tpl">
        <div class="service">
            <div class="row">
                <div class="col-7">
                    <div class="service-title">
                        <a href="#" onclick="showServiceQuickView({service_id});return false;">{service_name}</a>
                    </div>
                    <div class="service-time">
                        {service_time}
                    </div>
                </div>
                <div class="col-5">
                    <div class="service-price">
                        {price}
                    </div>
                </div>
            </div>
        </div>
    </template>
    <template id="map-salon-tpl">
        <div class="col-md-6">
            <div class="img">
                <a href="{salon_url}" style="background-image: url('{salon_cover}')">
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="right-block">
                <div class="title">
                    <a href="{salon_url}">{salon_name}</a>
                </div>
                <div class="location">
                    <i class="fa fa-map-marker"></i>
                    <span>{address}</span>
                </div>
                <div class="rating">
                    <div class="number">{rating}</div>
                    <div class="stars">
                        {rating_stars}
                    </div>
                    <div class="total">({rating_count})</div>
                </div>
                <div class="services">
                    {services}
                </div>
                <div class="view">
                    <a href="{salon_url}">Xem thêm</a>
                </div>
            </div>
        </div>
    </template>
    <template id="ajax-no-salon-tpl">
        <div class="no-salon-found">
            <i class="fa fa-info-circle"></i>
            <div>{!! __('Không tìm thấy salon tương ứng nào!') !!}</div>
        </div>
    </template>
    <script type="text/javascript">
        var $cats = {!! json_encode($cats); !!};
        var $locations = {!! json_encode($locations) !!};
        $('.rating-filter select').select2({
            width: '100%',
            minimumResultsForSearch: Infinity
        });
        $('#filter-order').select2({
            width: '100%',
            minimumResultsForSearch: Infinity
        });
        var $page_search_map;
        var $modal_search_map;
        var $page_search_markers = [];
        var $last_address = '';
        var $limit_marker = null;
        $(window).on('googleMapInit', function () {
            $page_search_map = new google.maps.Map(
                $('.map-filter .map')[0]
                , {
                    center: {lat: 0, lng: 0},
                    zoom: 13,
                    disableDefaultUI: true,
                    draggable: false,
                    scrollwheel: false,
                    disableDoubleClickZoom: true
                });
            $modal_search_map = new google.maps.Map(
                $('#modal-map-view .map')[0]
                , {
                    center: {lat: 0, lng: 0},
                    zoom: 13,
                });

            var $address_autocomplete = new google.maps.places.Autocomplete(document.getElementById('address_autocomplete'),
                {
                    componentRestrictions: {country: "vn"}
                }
            );
            $address_autocomplete.addListener('place_changed', function() {
                $('#filter-form input[name=address_lat]').val('');
                $('#filter-form input[name=address_lng]').val('');
                $('#filter-form input[name=address_type]').val('');
                var place = $address_autocomplete.getPlace();
                if(place.hasOwnProperty('geometry')){
                    console.log(place);
                    $('#filter-form input[name=address_lat]').val(place.geometry.location.lat());
                    $('#filter-form input[name=address_lng]').val(place.geometry.location.lng());
                    $('#filter-form input[name=address_type]').val(place.types[0]);
                }
                $('#filter-form').submit();
            });
        });
        $(function () {
            $('.mobile-filter-button').click(function () {
                $('.filter-controls .filter-toggle').click();
            });

            $('.filter-controls .map-toggle').click(function(){
                $(this).toggleClass('active');
                $('.filter-section .map-filter').toggleClass('active');
            });
            $('.filter-controls .filter-toggle').click(function(){
                $(this).toggleClass('active');
                $('html').toggleClass('filter-mobile-active');
            });

            $('.filter-block .show-all').click(function () {
                $(this).parents('.filter-block').addClass('show-all');
            });

            $('#distance-filter').on('slide_change', function (e) {
                $('#filter-form').submit();
            });

            function loadSearchResult(page){
                var form = $('#filter-form')[0];
                var data = $(form).serializeObject();

                data.page = page;
                $.ajax({
                    url: '{!! route('frontend.search') !!}',
                    type: 'get',
                    dataType: 'json',
                    data: data,
                    beforeSend: function () {
                        $('.main-content .main-zone').addClass('loading');
                    },
                    complete: function () {
                        $('.main-content .main-zone').removeClass('loading');
                    },
                    success: function (json) {
                        if(json.hasOwnProperty('result')){
                            if(page==1){
                                $('.main-content .main-zone .salon-list-result').html('');
                                for(var i=0;i<$page_search_markers.length;i++) {
                                    $page_search_markers[i].setMap(null);
                                }
                                $page_search_markers = [];
                            }
                            $('.main-content .main-zone .load-more').data('next', json.next_page);
                            if(json.is_last_page){
                                $('.main-content .main-zone .load-more').addClass('d-none');
                            }
                            else{
                                $('.main-content .main-zone .load-more').removeClass('d-none');
                            }
                            $(json.result).each(function () {
                                var m =new google.maps.Marker({
                                    position: {
                                        lat: this.location_lat,
                                        lng: this.location_lng
                                    },
                                    map: $page_search_map,
                                    title: this.salon_name,
                                    data: this
                                });
                                $page_search_markers.push(m);
                                var tpl = $('#search-salon-tpl').html();
                                tpl = tpl.replace(/{rating}/g, this.rating);
                                tpl = tpl.replace(/{rating_count}/g, this.rating_count);
                                tpl = tpl.replace(/{rating_stars}/g, this.rating_stars);
                                tpl = tpl.replace(/{salon_cover}/g, this.salon_cover);
                                tpl = tpl.replace(/{salon_name}/g, this.salon_name);
                                tpl = tpl.replace(/{verified}/g, this.verified?'<i title="Đã chứng thực" class="fa fa-check-circle salon-name-verified" aria-hidden="true"></i>':'');
                                tpl = tpl.replace(/{salon_url}/g, this.salon_url);
                                tpl = tpl.replace(/{address}/g, this.address);
                                if(this.sale_of_up_to>0){
                                    tpl = tpl.replace(/{sale_max}/g, '<div class="service-sale-max"><div class="lbl">Giảm đến</div><div class="svl">'+this.sale_of_up_to+'%</div></div>');
                                }
                                else{
                                    tpl = tpl.replace(/{sale_max}/g, '');
                                }
                                var services = '';
                                $(this.services).each(function () {
                                    var tpl = $('#search-salon-service-tpl').html();
                                    tpl = tpl.replace(/{service_id}/g, this.service_id);
                                    tpl = tpl.replace(/{service_name}/g, this.service_name);
                                    tpl = tpl.replace(/{service_time}/g, this.service_time);
                                    var promo = this.promo ? 'has-promo': '';
                                    tpl = tpl.replace(/{promo}/g, promo);

                                    var remain = '';
                                    if(promo){
                                        remain = '<div class="promo-remain">Deal còn lại '+(this.promo.limit - this.promo.current)+'/'+this.promo.limit+'</div>'
                                    }
                                    tpl = tpl.replace(/{promo_remain}/g, remain);

                                    if(this.sale_percent>0 && !promo){
                                        tpl = tpl.replace(/{sale}/g, '<div class="service-sale-percent"><div>GIẢM '+this.sale_percent+'%</div></div>');
                                    }
                                    else{
                                        tpl = tpl.replace(/{sale}/g, '');
                                    }
                                    var price = '';
                                    if(this.sale_off || promo){
                                        if(!promo){
                                            price += '<span class="old">'+this.price_org+'</span> <span class="current">'+this.price_final+'</span>'
                                        }
                                        else{
                                            price += '<span class="old">'+this.price_org+'</span> <span class="current">'+this.promo.price+'</span>'
                                        }
                                    }
                                    else{
                                        price += '<span class="current">'+this.price_final+'</span>'
                                    }
                                    tpl = tpl.replace(/{price}/g, price);
                                    services += tpl;
                                });
                                tpl = tpl.replace(/{services}/g, services);
                                $('.main-content .main-zone .salon-list-result').append(tpl);
                            });

                            var bounds = new google.maps.LatLngBounds();
                            for(i=0;i<$page_search_markers.length;i++) {
                                bounds.extend($page_search_markers[i].getPosition());
                            }
                            $page_search_map.fitBounds(bounds);

                            if($page_search_markers.length == 1){
                                $page_search_map.setZoom(15);
                            }

                            var cats = $cats;
                            $('#filter-block-cat .checkbox-list input[type=checkbox]').not(':checked').each(function () {
                                $(this).parents('.item').remove();
                            });
                            $('#filter-block-cat .checkbox-list .item .number').html(0);
                            if(cats.length != 0){
                                $('#filter-block-cat').removeClass('d-none');
                                $(cats).each(function () {
                                    if($('#filter-block-cat .checkbox-list .item[data-id='+this.id+']').length != 0){
                                        $('#filter-block-cat .checkbox-list .item[data-id='+this.id+'] .number').html(this.salons_count);
                                        return true;
                                    }
                                    var tpl = $('#search-filter-chk-tpl').html();
                                    tpl = tpl.replace(/{name}/g, 'cat');
                                    tpl = tpl.replace(/{value}/g, this.id);
                                    tpl = tpl.replace(/{title}/g, this.title);
                                    //tpl = tpl.replace(/{number}/g, '');
                                    $('#filter-block-cat .checkbox-list').append(tpl);
                                });
                            }
                            else{
                                if($('#filter-block-cat .checkbox-list input[type=checkbox]:checked').length == 0){
                                    $('#filter-block-cat').addClass('d-none');
                                }
                                else{
                                    $('#filter-block-cat').removeClass('d-none');
                                }
                            }
                            $('#filter-block-cat').removeClass('show-all');
                            if($('#filter-block-cat .item').length <= 5){
                                $('#filter-block-cat').addClass('show-all');
                            }

                            var locations = $locations;
                            var current_location_lv = 0;
                            if($('#filter-block-location .checkbox-list input[name=location_lv]').length>0){
                                current_location_lv = $('#filter-block-location .checkbox-list input[name=location_lv]').val();
                            }
                            if(current_location_lv != json.location_lv){
                                $('#filter-block-location .checkbox-list').html('');
                            }
                            else{
                                $('#filter-block-location .checkbox-list input[type=checkbox]').not(':checked').each(function () {
                                    $(this).parents('.item').remove();
                                });

                                $('#filter-block-location .checkbox-list .item .number').html(0);
                            }
                            if(locations.length != 0){
                                $('#filter-block-location').removeClass('d-none');
                                $(locations).each(function () {
                                    //console.log(item);
                                    if($('#filter-block-location .checkbox-list .item[data-id='+this.id+']').length != 0){
                                        $('#filter-block-location .checkbox-list .item[data-id='+this.id+'] .number').html(this.salons_count);
                                        return true;
                                    }
                                    var tpl = $('#search-filter-chk-tpl').html();
                                    tpl = tpl.replace(/{name}/g, 'location');
                                    tpl = tpl.replace(/{value}/g, this.id);
                                    tpl = tpl.replace(/{title}/g, this.name);
                                    // tpl = tpl.replace(/{number}/g, this.salons_count);
                                    $('#filter-block-location .checkbox-list').append(tpl);
                                });
                            }
                            else{
                                if($('#filter-block-location .checkbox-list input[type=checkbox]:checked').length == 0){
                                    $('#filter-block-location').addClass('d-none');
                                }
                                else{
                                    $('#filter-block-location').removeClass('d-none');
                                }
                            }
                            if($('#filter-block-location .checkbox-list input[name=location_lv]').length>0){
                                $('#filter-block-location .checkbox-list input[name=location_lv]').remove();
                            }
                            $('#filter-block-location .checkbox-list').append(
                                '<input type="hidden" name="location_lv" value="'+json.location_lv+'">'
                            );

                            $('#filter-block-location').removeClass('show-all');
                            if($('#filter-block-location .item').length <= 5){
                                $('#filter-block-location').addClass('show-all');
                            }

                            if($limit_marker != null){
                                $limit_marker.setMap(null);
                                $limit_marker = null;
                            }
                            if($('#filter-form input[name=address_lat]').val()>0 && $('#filter-form input[name=address_lng]').val()>0){
                                var circleOptions = {
                                    strokeColor: 'blue',
                                    strokeOpacity: 0.0,
                                    strokeWeight: 2,
                                    fillColor: 'blue',
                                    fillOpacity: 0.35,
                                    map: $page_search_map,
                                    center: {
                                        lat: $('#filter-form input[name=address_lat]').val()*1.0,
                                        lng: $('#filter-form input[name=address_lng]').val()*1.0
                                    },
                                    radius: $('#filter-form input[name=distance]').val()*1000.0
                                };
                                $limit_marker = new google.maps.Circle(circleOptions);
                                $page_search_map.fitBounds($limit_marker.getBounds());
                            }

                            if((json.result.length == 0) && (page == 1)){
                                var empty_html = $('#ajax-no-salon-tpl').html();
                                $('.main-content .main-zone .salon-list-result').append(empty_html);
                            }
                        }
                    }

                });
            }

            $('#price-filter').on('slide_change', function (e) {
                $('#filter-form').submit();
            });

            $('.rating-filter select').on('change', function () {
                $('#filter-form').submit();
            });

            $('.filter-order select').on('change', function () {
                $('#filter-form').submit()
            });

            $('#filter-form').submit(
                function () {
                    old_keyword = $('#filter-form input[name=keyword]').val();
                    $last_address = $('#address_autocomplete').val();
                    var data = $(this).serialize();
                    var url = data;
                    var lstring = '';
                    var lchecked = $('.area-filter input[type=checkbox]:checked');
                    $(lchecked).each(function (index, item) {
                        var title = $(this).parent().find('span.checkmark-title').html();
                        if(index === 0){
                            lstring += '&location_name=';
                        }
                        else{
                            lstring += ', '
                        }
                        lstring += title;
                    });

                    window.history.pushState(null,null, '{!! route('frontend.search') !!}?'+url+lstring);
                    loadSearchResult(1);
                    return false
                }
            );

            $('#filter-form').submit();

            $('.main-content .main-zone .load-more').click(function () {
                var next = $(this).data('next');
                loadSearchResult(next);
            });

            $('.filter-basic-type input[type=checkbox]').change(function () {
                $('#filter-form').submit();
            });

            $('#filter-block-location').on('change', '.checkbox-list input[type=checkbox]', function () {
                $('#filter-form').submit();
            });

            $('#filter-block-cat').on('change', '.checkbox-list input[type=checkbox]', function () {
                $('#filter-form').submit();
            });

            var $modal_map_view = $('#modal-map-view').modal({
                show: false
            });

            $modal_map_view.find('.minize').click(function () {
                $(this).parents('.selected-salon').toggleClass('mini');
            });

            function showMapSalonInfo(marker){
                $modal_map_view.find('.selected-salon').data('data', marker);
                var data = marker.data;
                $modal_search_map.setCenter({
                    lat: data.location_lat,
                    lng: data.location_lng
                });
                $modal_search_map.setZoom(data.location_zoom);
                var tpl = $('#map-salon-tpl').html();
                tpl = tpl.replace(/{rating}/g, data.rating);
                tpl = tpl.replace(/{rating_count}/g, data.rating_count);
                tpl = tpl.replace(/{rating_stars}/g, data.rating_stars);
                tpl = tpl.replace(/{salon_cover}/g, data.salon_cover);
                tpl = tpl.replace(/{salon_name}/g, data.salon_name);
                tpl = tpl.replace(/{salon_url}/g, data.salon_url);
                tpl = tpl.replace(/{address}/g, data.address);
                var services = '';
                $(data.services).each(function (i, v) {
                    if(i == 2){
                        return false;
                    }
                    var tpl = $('#map-salon-service-tpl').html();
                    tpl = tpl.replace(/{service_id}/g, this.service_id);
                    tpl = tpl.replace(/{service_name}/g, this.service_name);
                    tpl = tpl.replace(/{service_time}/g, this.service_time);
                    var price = '';
                    if(this.sale_off){
                        price += '<span class="old">'+this.price_org+'</span> <span class="current">'+this.price_final+'</span>'
                    }
                    else{
                        price += '<span class="current">'+this.price_final+'</span>'
                    }
                    tpl = tpl.replace(/{price}/g, price);
                    services += tpl;
                });
                tpl = tpl.replace(/{services}/g, services);
                $('.salon-info-zone').html(tpl);
                if(marker.data_index == 0){
                    $modal_map_view.find('.nav-block .prev').hide();
                }
                else{
                    $modal_map_view.find('.nav-block .prev').show();
                }

                if(marker.data_last){
                    $modal_map_view.find('.nav-block .next').hide();
                }
                else{
                    $modal_map_view.find('.nav-block .next').show();
                }
            }

            $modal_map_view.find('.nav-block .prev').click(function () {
                var marker = $(this).parents('.selected-salon').data('data');
                //var data = marker.data;
                var new_marker = $modal_map_marker[marker.data_index-1];
                showMapSalonInfo(new_marker);
                //console.log(marker)
            });

            $modal_map_view.find('.nav-block .next').click(function () {
                var marker = $(this).parents('.selected-salon').data('data');
                //var data = marker.data;
                var new_marker = $modal_map_marker[marker.data_index+1];
                showMapSalonInfo(new_marker);
                //console.log(marker)
            });

            $modal_map_view.on('click', '.view-zoom', function () {
                var data = $(this).parents('.selected-salon').data('data');
                data = data.data;
                $modal_search_map.setCenter({
                    lat: data.location_lat,
                    lng: data.location_lng
                });
                $modal_search_map.setZoom(data.location_zoom);
                return false;
            });
            var $modal_map_marker;
            $('#map-view-link').click(function () {
                $($modal_map_marker).each(function () {
                    this.setMap(null);
                });
                $modal_map_marker = [];
                if($page_search_markers.length == 0){
                    return;
                }
                var bounds = new google.maps.LatLngBounds();
                for(var i=0;i<$page_search_markers.length;i++) {
                    bounds.extend($page_search_markers[i].getPosition());
                    var m =new google.maps.Marker({
                        position: $page_search_markers[i].getPosition(),
                        map: $modal_search_map,
                        title: $page_search_markers[i].title,
                        data: $page_search_markers[i].data,
                        data_index: i,
                        data_last: (i==$page_search_markers.length-1)
                    });
                    $modal_map_marker.push(m);
                    m.addListener('click', function() {
                        showMapSalonInfo(this);
                    });
                    if(i==0){
                        showMapSalonInfo(m);
                    }
                }
                $modal_search_map.fitBounds(bounds);

                if($page_search_markers.length == 1){
                    $modal_search_map.setZoom(15);
                }
                if($modal_search_map.getZoom() < 5){
                    $modal_search_map.setZoom(5);
                }
                $modal_map_view.modal('show');
                return false;
            });
            var old_keyword;
            $('#filter-form input[name=keyword]').blur(function () {
                if($(this).val() != old_keyword){
                    $('#filter-form').submit();
                }
            });
            $('#filter-form input[name=keyword]').keypress(function (e) {
                if(e.which == 13) {
                    $('#filter-form').submit();
                }
            });

            $('#filter-form input[name=workday]').datepicker({
                language: 'vi',
                startDate: '{!! now()->format('d/m/Y') !!}'
            }).on('change', function () {
                $('#filter-form').submit();
            });

        });
    </script>
    @include(getThemeViewName('components.service_option'))
@endpush
@include(getThemeViewName('includes.google_map_api'))