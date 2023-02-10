@enqueueCSS('datepicker', getThemeAssetUrl('libs/datepicker/css/bootstrap-datepicker.standalone.min.css'), 'bootstrap')
@enqueueJS('datepicker', getThemeAssetUrl('libs/datepicker/js/bootstrap-datepicker.min.js'), JS_LOCATION_HEAD, 'bootstrap')
@enqueueJS('datepicker-language', getThemeAssetUrl('libs/datepicker/js/locales/bootstrap-datepicker.vi.min.js'), JS_LOCATION_HEAD, 'datepicker')
@enqueueCSS('select2', getThemeAssetUrl('libs/select2/css/select2.min.css'), 'bootstrap')
@enqueueCSS('owl', getThemeAssetUrl('libs/owl/assets/owl.carousel.min.css'), 'bootstrap')
@enqueueJS('select2', getThemeAssetUrl('libs/select2/js/select2.full.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueJS('owl', getThemeAssetUrl('libs/owl/owl.carousel.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueJS('nice-scroll', getThemeAssetUrl('libs/nicescroll/jquery.nicescroll.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueJS('moment', getThemeAssetUrl('libs/moment.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueCSS('home-page', getThemeAssetUrl('libs/styles/home.css'), 'master-page')
@php
    $dark_theme = 0;
    /** @var \Illuminate\Support\Collection $page_configs */
    /** @var \Modules\ModHairWorld\Entities\PostTypes\News[] $latest_news  */
    $master_configs = getSettingsFromPage('theme_config_master');
    $master_configs = collect($master_configs);
    $header_banner = $page_configs->get('theme_home_header_banner_img', null);
    if($header_banner){
        $header_banner_ = \App\UploadedFile::find($header_banner);
        if($header_banner_){
            $header_banner = $header_banner_->getUrl();
        }
    }
    $og_img = getNoThumbnailUrl();
    $og_width = 500;
    $og_height = 500;
@endphp
@extends(getThemeViewName('master'))
@push('page_meta')
    <meta name="description" content="{{ $master_configs['theme_master_site_desc'] }}"/>
    <meta property="og:title" content="{{ $master_configs['theme_master_site_title'] }}"/>
    <meta property="og:image" content="{{ $og_img }}"/>
    <meta property="og:description" content="{{ $master_configs['theme_master_site_desc'] }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:image:secure_url" content="{{ $og_img }}"/>
    <meta property="og:image:width" content="{{$og_width}}"/>
    <meta property="og:image:height" content="{{$og_height}}"/>
@endpush
@push('page_head')
    <script>
        var dataLayer = [];
        dataLayer.push({
            'dynx_itemid': '',
            'dynx_pagetype': 'home',
            'dynx_totalvalue': 0
        });
    </script>
@endpush
@section('page_content')
    <!--region Search tool-->
    <div id="banner-search-tool">
        <div class="search-form">
            <div class="container">
                <div class="d-flex d-lg-block">
                <div class="toggle-wrap pr-2 d-block d-lg-none">
                    <img src="/assets/images/arrow-down.png" id="search-toggle">
                </div>
                <div class="tab-contents">
                    <div id="search-result-box">
                        <div class="content">
                        </div>
                    </div>
                    <div id="fake-search-input" class="active">
                        <div class="p15px">
                        <img src="{!! getThemeAssetUrl('img/icons/search.png') !!}">
                        <div class="search-review">
                            <h4>Tìm kiếm theo salon/dịch vụ</h4>
                            <div class="review-content">
                                <span id="cat-review">Tất cả dịch vụ</span> &#8226; <span id="location-review">Thành phố Hà Nội</span>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="tab-content" data-id="search-1">
                        <h4 class="text-center">Tìm và đặt lịch làm đẹp tại <span style="color: #FF5C39">1000+</span> Salon trên toàn Quốc</h4>
                        <div class="wrapper">
                            <form id="search-form-one" method="get" action="{!! route('frontend.search') !!}">
                                <div class="row no-gutters">
                                    <div class="col-12 col-lg-4">
                                        <div id="search-with-hint">
                                            <input placeholder="Tìm kiếm theo salon/dịch vụ" name="keyword" type="text"
                                                   spellcheck="false" autocapitalize="none" autocomplete='off'>
                                            {{--                                            <div class="close-search"><i class="fa fa-arrow-left" aria-hidden="true"></i></div>--}}
                                            <div class="do-search"><img
                                                        src="{!! getThemeAssetUrl('img/icons/search.png') !!}"></div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <div class="home-cat-selector">
                                            <div class="do-search">
                                                <img src="{!! getThemeAssetUrl('img/icons/dot_list.png') !!}">
                                            </div>
                                            <select name="cat[]" id="home-cat-selector">
                                                <option>Tất cả dịch vụ</option>
                                                @foreach(\Modules\ModHairWorld\Entities\SalonServiceCategory::orderBy('title', 'asc')->get() as $item)
                                                    <option value="{!! $item->id !!}">
                                                        {!! $item->title !!}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        <div class="row no-gutters">
                                            <div class="col-12 col-lg-8">
                                                <div class="home-cat-selector">
                                                    <div class="do-search">
                                                        <img src="{!! getThemeAssetUrl('img/icons/map_marker.png') !!}">
                                                    </div>
                                                    <select name="location[]" id="home-location-selector">
                                                        @foreach(\Modules\ModHairWorld\Entities\DiaPhuongTinhThanhPho::orderBy('name', 'asc')->get(['id', 'name']) as $item)
                                                            <option value="{!! $item->id !!}">
                                                                {!! $item->name !!}
                                                            </option>
                                                        @endforeach
                                                        <input type="hidden" name="location_lv" value="1"/>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-4">
                                                <div class="submit-button">
                                                    <button type="button">Tìm kiếm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="wrapper">
            <div class="inner">
                <div class="home-banner">
{{--                    <div class="search-form">--}}
{{--                        @if($page_configs->has('theme_home_header_banner_title'))--}}
{{--                            <div class="slogan">{!! $page_configs->get('theme_home_header_banner_title') !!}</div>--}}
{{--                        @endif--}}
{{--                    </div>--}}
                    <div id="home-slider">
                        <div class="owl-carousel d-none d-lg-block" style="display: none">
                            @foreach($home_slides as $slideGroup)
                                <div class="home-slide">
                                    <div class="row no-gutters align-items-stretch">
                                        @foreach($slideGroup as $colIndex=>$slideCol)
                                            <div class="col-12
                                        @if(sizeof($slideGroup) > 1)
                                            @if($colIndex === 0)
                                                    col-lg-8
@else
                                                    col-lg-4
@endif
                                            @endif">
                                                @foreach($slideCol as $slide)
                                                    <a href="{!! $slide['link'] !!}">
                                                        <img src="{!! $slide['image'] !!}"/>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="owl-carousel d-block d-lg-none" style="display: none">
                            @foreach($home_slides as $slideGroup)
                                @foreach($slideGroup as $colIndex=>$slideCol)
                                    @foreach($slideCol as $slide)
                                    <div class="home-slide">
                                        <a href="{!! $slide['link'] !!}">
                                            <img src="{!! $slide['image'] !!}"/>
                                        </a>
                                    </div>
                                    @endforeach
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!--endregion-->
    <!--region Feature text-->
    @if($page_configs->has('theme_home_header_feature_text'))
        <div id="feature-items">
            <div class="container">
                <div class="wrapper">
                    <div class="row">
                        @foreach($page_configs->get('theme_home_header_feature_text') as $item)
                            <div class="col-md-4">
                                <div class="item">
                                    <div class="title">{!! $item['title'] !!}</div>
                                    <div class="desc">
                                        {!! $item['desc'] !!}
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
    <div id="promo-block">
        <div class="container">
            <div class="block-title-sp">
                <div class="block-title-text">FLASH DEAL</div>
            </div>
            <div class="promo-salons owl-carousel"></div>
        </div>
    </div>
    <!--region Banner grid-->
    <div id="banner-grid">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <div class="banner-1 banner" style="cursor: pointer;"
                         onclick="window.location='{!! $banner_grid_1_link !!}'">
                        @if($banner_grid_1)
                            <a>
                                <img src="{!! $banner_grid_1 !!}">
                                <div class="info">
                                    <div class="info-wrapper">
                                        <div class="top-text">{!! $banner_top_text_1 !!}</div>
                                        <div class="bottom-info">
                                            <div class="title">{!! $banner_top_title_1 !!}</div>
                                            <div class="sub-title">{!! $banner_top_sub_title_1 !!}</div>
                                            @if($banner_top_text_1 || $banner_top_title_1 || $banner_top_sub_title_1)
                                                <a href="{!! $banner_grid_1_link !!}" class="button-b">
                                                    Chi tiết
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="banner-2 banner" style="cursor: pointer;"
                         onclick="window.location='{!! $banner_grid_2_link !!}'">
                        @if($banner_grid_2)
                            <a style="display: block">
                                <img src="{!! $banner_grid_2 !!}">
                                <div class="info">
                                    <div class="info-wrapper">
                                        <div class="top-text">{!! $banner_top_text_2 !!}</div>
                                        <div class="bottom-info">
                                            <div class="title">{!! $banner_top_title_2 !!}</div>
                                            <div class="sub-title">{!! $banner_top_sub_title_2 !!}</div>
                                            @if($banner_top_text_2 || $banner_top_title_2 || $banner_top_sub_title_2)
                                                <a href="{!! $banner_grid_2_link !!}" class="button-b">
                                                    Chi tiết
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="banner-3 banner" style="cursor: pointer;"
                                 onclick="window.location='{!! $banner_grid_3_link !!}'">
                                @if($banner_grid_3)
                                    <a href="{!! $banner_grid_3_link !!}">
                                        <img src="{!! $banner_grid_3 !!}">
                                        <div class="info">
                                            <div class="info-wrapper">
                                                <div class="top-text">{!! $banner_top_text_3 !!}</div>
                                                <div class="bottom-info">
                                                    <div class="title">{!! $banner_top_title_3 !!}</div>
                                                    <div class="sub-title">{!! $banner_top_sub_title_3 !!}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="banner-4 banner" style="cursor: pointer;"
                                 onclick="window.location='{!! $banner_grid_4_link !!}'">
                                @if($banner_grid_4)
                                    <a href="{!! $banner_grid_4_link !!}">
                                        <img src="{!! $banner_grid_4!!}">
                                        <div class="info">
                                            <div class="info-wrapper">
                                                <div class="top-text">{!! $banner_top_text_4 !!}</div>
                                                <div class="bottom-info">
                                                    <div class="title">{!! $banner_top_title_4 !!}</div>
                                                    <div class="sub-title">{!! $banner_top_sub_title_4 !!}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--endregion-->
    <!--region Popular Cities-->
    @if($popular_cities)
        <div id="salon-in-cities">
            <div class="container">
                <div class="block-title">Thành phố phổ biến</div>
                <div class="block-list">
                    <div class="row">
                        @foreach($popular_cities as $item)
                            <div class="col-md-4 col-sm-6">
                                <a href="{!! route('frontend.search', ['location_lv' => 1, 'location' => $item['id']]) !!}"
                                   class="item d-block">
                                    <div class="img">
                                        <img src="{!! $item['img'] !!}">
                                    </div>
                                    <div class="name-number">
                                        <span class="name">{!! $item['name'] !!}</span>
                                        <span class="number">({!! $item['count'] !!})</span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!--endregion-->
    <!--region Latest news-->
    <div id="news-block" class="d-none">
        <div class="container">
            <div class="block-title">
                Bài viết mới nhất
                <div class="link">
                    <a target="_blank" href="https://isalon.vn/tin-tuc">Xem tất cả ></a>
                </div>
            </div>
            <div class="list owl-carousel">
            </div>
        </div>
    </div>
    <!--endregion-->
    <!--region Intro-->
    @if($page_configs->has('theme_home_intro'))
        <div id="headline-block" style="background-image: url('{!! getThemeAssetUrl('img/featureblockbg.jpg') !!}')">
            <div class="wrapper">
                <div class="inner">
                    <div class="container">
                        <div class="content">
                            {!! $page_configs->get('theme_home_intro') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!--endregion-->
    <!--region mobile app links-->
    <div id="mobile-block">
        <div class="wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="content">
                            <div class="logo">
                                @php
                                    $app_icon = $page_configs->get('theme_home_app_icon', false);
                                    if($app_icon){
                                        $app_icon = \App\UploadedFile::find($app_icon);
                                        if($app_icon){
                                            $app_icon = $app_icon->getUrl();
                                        }
                                    }
                                @endphp
                                <img src="{!! $app_icon !!}">
                            </div>
                            <div class="title">
                                {!! $page_configs->get('theme_home_app_title', '') !!}
                            </div>
                            <div class="desc">
                                {!! $page_configs->get('theme_home_app_desc', '') !!}
                            </div>
                            <div class="links">
                                <a target="_blank"
                                   href="{!! $master_configs->get('theme_master_mobile_app_ios', '#') !!}" class="link">
                                    <img src="{!! getThemeAssetUrl('img/appstore.png') !!}">
                                </a>
                                <a target="_blank"
                                   href="{!! $master_configs->get('theme_master_mobile_app_android', '#') !!}"
                                   class="link">
                                    <img src="{!! getThemeAssetUrl('img/googleplay.png') !!}">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="img">
                            @php
                                $app_icon = $page_configs->get('theme_home_app_image', false);
                                 if($app_icon){
                                     $app_icon = \App\UploadedFile::find($app_icon);
                                     if($app_icon){
                                         $app_icon = $app_icon->getUrl();
                                     }
                                 }
                            @endphp
                            <img src="{!! $app_icon!!}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--endregion-->
    <!-- app install popup -->
    @if($page_configs->has('theme_home_popup_image') && $page_configs->has('theme_home_popup_enabled') && $page_configs->get('theme_home_popup_enabled', false))
        <div aria-hidden="true" class="modal fade" id="appInstallPopup" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body mb-0 p-0 container">
                        <button style="position: absolute; right: 0; transform: translate(50%, -50%); -ms-transform: translate(50%, -50%); background: transparent; border:  none" data-dismiss="modal">
                            <img src="{!! getThemeAssetUrl('img/close_popup.png') !!}" alt="" style="width:30px; height: 30px">
                        </button>
                        @php
                            $popup_img_url = $page_configs->get('theme_home_popup_image', false);
                             if($popup_img_url){
                                 $popup_img_url = \App\UploadedFile::find($popup_img_url);
                                 if($popup_img_url){
                                     $popup_img_url = $popup_img_url->getUrl();
                                 }
                             }
                        $popup_link = $page_configs->get('theme_home_popup_url', 'https://isalon.vn');
                        @endphp
                        <a href="{!! $popup_link !!}">
                            <img src="{!! $popup_img_url !!}" alt="" style="width:100%; height: auto">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@include(getThemeViewName('includes.google_map_api'))
@push('page_footer_js')
    <script type="text/javascript">
        var isMobile;
        $( document ).ready(function() {
            isMobile = window.matchMedia("only screen and (max-width: 991px)").matches;
            $(window).on('resize', function () {
                isMobile = window.matchMedia("only screen and (max-width: 991px)").matches;
            });
        });
        $(window).on('load',function() {
            if (typeof(Storage) !== "undefined") {
                var didShow = sessionStorage.getItem("didShowAppInstallPopup");
                if (didShow !== "true") {
                    sessionStorage.setItem("didShowAppInstallPopup", "true");
                    $('#appInstallPopup').modal('show');
                }
            }
        });
        $('#search-toggle, #fake-search-input').on('click', function () {
            var $search_toggle = $('#search-toggle');
            if ($search_toggle.hasClass('active')) {
                $('#banner-search-tool .search-form .tab-contents .tab-content').removeClass('active');
                $('#banner-search-tool > .wrapper').removeClass('active');
                $('#fake-search-input').addClass('active');
            } else {
                $('#banner-search-tool .search-form .tab-contents .tab-content').addClass('active');
                $('#banner-search-tool > .wrapper').addClass('active');
                $('#fake-search-input').removeClass('active');
            }
            $search_toggle.toggleClass('active');
        });
        $('#search-form-one').submit(function () {
            var keyword = $('#search-form-one input[name=keyword]').val().trim();
            if (keyword.length == 0) {
                return true;
            }
            var history = localStorage.getItem('search_history');
            if (!history) {
                history = [];
            } else {
                history = JSON.parse(history);
            }
            var new_history = [];
            $(history).each(function (index, item) {
                if (item) {
                    if (item.keyword.toLowerCase() != keyword.toLowerCase()) {
                        new_history.push(item)
                    }
                }
            });
            var d = new Date();
            if (new_history.length > 7) {
                new_history = new_history.splice(0, 7);
            }
            new_history.unshift({
                keyword: keyword,
                date: d.getTime()
            });
            localStorage.setItem('search_history', JSON.stringify(new_history));
//return false;
        });

        $('#home-cat-selector').select2({
            width: '100%',
            dropdownPosition: 'below',
            minimumResultsForSearch: Infinity,
            placeholder: "Tất cả dịch vụ",
            dropdownCssClass: "search-tool-dropdown",
            allowClear: true
        })
            .on("select2:open", function () {
                $('#select2-home-cat-selector-results').niceScroll({
                    cursorcolor: 'white',
                    autohidemode: false
                });
                $('#search-result-box').removeClass('active');
            })
            .on('change', function() {
                var data = $('#home-cat-selector').select2('data');
                if (data.length > 0 && data[0]) {
                    $('#cat-review').text(data[0].text);
                    if (isMobile) {
                        $('#search-form-one').submit();
                    }
                } else {
                    $('#cat-review').text('Tất cả dịch vụ');
                }
            })
            .val(null).trigger('change');
        var location_val = '';
        var is_location_open = false;
        $('#home-location-selector').select2({
            width: '100%',
            dropdownPosition: 'below',
            minimumResultsForSearch: Infinity,
            placeholder: "Chọn địa phương",
            dropdownCssClass: "search-tool-dropdown",
            allowClear: true
        })
            .on("select2:open", function () {
                is_location_open = true;
                $('#select2-home-location-selector-results').niceScroll({
                    cursorcolor: 'white',
                    autohidemode: false
                });
                $('#search-result-box').removeClass('active');
            })
            .on('change', function() {
                var data = $('#home-location-selector').select2('data');
                if (data.length > 0 && data[0]) {
                    console.log(data[0]);
                    $('#location-review').text(data[0].text);
                    if (isMobile && location_val && is_location_open) {
                        $('#search-form-one').submit();
                    }
                    location_val = data[0].id;
                } else {
                    $('#location-review').text('Thành phố Hà Nội');
                }
            })
            .val(1).trigger('change');

        $('#search-form-one button').click(function () {
            $('#search-form-one').submit();
        });
        $('#search-form-workday').datepicker({
            language: 'vi',
            startDate: '{!! now()->format('d/m/Y') !!}',
        }).datepicker('setDate', '{!! now()->format('d/m/Y') !!}');
        $(function () {
            $('#banner-search-tool .tab').click(function () {
                var id = $(this).data('id');
                $('#banner-search-tool .tab').removeClass('active');
                $('#banner-search-tool .tab-content').removeClass('active');
                $(this).addClass('active');
                $('#banner-search-tool .tab-content').hide();
                $('#banner-search-tool .tab-content[data-id=' + id + ']').addClass('active');
                $('#banner-search-tool .tab-content[data-id=' + id + ']').fadeIn();
            });
        });
        $(document).ready(function () {
            $.ajax({
                url: 'https://isalon.vn/tin-tuc/api/get_recent_posts',
                type: 'get',
                dataType: 'json',
                success: function (json) {
                    var c = 0;
                    $(json.posts).each(function (i, post) {
                        if (post.thumnail) {
                            c++;
                            var html = '                          <div class="news-item">' +
                                '                                <a target="_blank" href="' + post.url + '" class="item d-block">' +
                                '                                    <div class="img">' +
                                '                                        <img src="' + post.thumnail + '">' +
                                '                                    </div>' +
                                '                                    <div class="title">' + post.title + '</div>' +
                                '                                </a>' +
                                '                            </div>';
                            $("#news-block .list").append(html);
                        }
                    });
                    if (c > 0) {
                        $("#news-block").removeClass('d-none');
                        $("#news-block .list").owlCarousel({
                            loop: false,
                            margin: 30,
                            autoWidth: true,
                            responsive: {
                                768: {
                                    nav: true,
                                    //items: 3,

                                },
                                640: {
                                    nav: false,
                                    //items: 2,
                                },
                                0: {
                                    nav: false,
                                    //items: 1,
                                }
                            }
                        });
                    }
                },
            });
            $('#home-slider .owl-carousel').owlCarousel({
                loop: true,
                autoplay: true,
                autoplayTimeout: {!! $home_slider_configs['timeout']?$home_slider_configs['timeout']:2500 !!},
                autoplayHoverPause: true,
                items: 1,
                autoplaySpeed: {!! $home_slider_configs['nav_speed']?$home_slider_configs['nav_speed']:2500 !!},
                margin: 0,
                nav: false,
            });
            var delayTimer;
            var keyword = '';

            function fetchSearchHint() {
                $.ajax({
                    url: '{!! route('api.search_hint') !!}',
                    method: 'post',
                    data: {
                        keyword: keyword,
                    },
                    success: function (json) {
                        $('#search-result-box .content').html('');
                        if (json) {
                            console.log(json);
                            if (json.cats.length > 0 || json.salons.length > 0 || json.services.length > 0) {
                                if (json.cats.length > 0) {
                                    var cats = '';
                                    $(json.cats).each(function (index, cat) {
                                        cats += '<a href="' + cat.link + '" " class="hint-cat">' + cat.name + '</a>';
                                    });
                                    $('#search-result-box .content').append(
                                        '<div class="hint-cats">' +
                                        cats +
                                        '</div>'
                                    );
                                }
                                if (json.services.length > 0) {
                                    var services = '';
                                    $(json.services).each(function (index, service) {
                                        services += '<a href="' + service.link + '" class="hint-service">' +
                                            '<div class="hint-service-name">' + service.name + '</div>' +
                                            '<div class="hint-service-info">' +
                                            '<span class="hint-service-price">' + (service.ranged ? 'Từ ' : '') + service.price_from_html + '</span>' +
                                            '<span class="hint-service-salon"> tại ' + service.salon.name + ' - ' + service.salon.location_name + '</span>' +
                                            '</div>' +
                                            '</a>';
                                    });
                                    $('#search-result-box .content').append(
                                        '<div class="services">' +
                                        services +
                                        '</div>'
                                    );
                                }
                                if (json.salons.length > 0) {
                                    var salons = '';
                                    $(json.salons).each(function (index, salon) {
                                        salons += '<a href="' + salon.link + '" class="hint-service">' +
                                            '<div class="hint-service-name">' + salon.name + '</div>' +
                                            '<div class="hint-service-info">' +
                                            '<span class="hint-service-salon">' + salon.address + '</span>' +
                                            '</div>' +
                                            '</a>';
                                    });
                                    $('#search-result-box .content').append(
                                        '<div class="services">' +
                                        salons +
                                        '</div>'
                                    );
                                }
                                $('#search-result-box').addClass('active');
                                $("#search-result-box .content").getNiceScroll().resize();
                            } else {
                                $('#search-result-box').removeClass('active');
                            }
                        } else {
                            $('#search-result-box').removeClass('active');
                        }
                    }
                });
            }

            $('#search-form-one input[name=keyword]').keyup(function (e) {
                var v = $(e.target).val().trim();
                if (v == '') {
                    v = 'Bạn muốn tìm gì?';
                }
                $('#fake-search-input .keyword').html(v);
                clearTimeout(delayTimer);
                delayTimer = setTimeout(function () {
                    keyword = $(e.target).val().trim();
                    if (keyword.length > 0) {
                        fetchSearchHint();
                    } else {
//$('#search-result-box').removeClass('active');
                        getSearchHistory();
                    }
                }, 500);
            });
            $('#search-result-box .content').niceScroll({
                cursorcolor: 'white',
                autohidemode: false
            });
            $('input[name=keyword]').focus(function () {
                $('#search-result-box').addClass('active');
                getSearchHistory();
            });
// var prevent_blur = false;
//
// $('input[name=keyword]').blur(function(e){
//     if(!prevent_blur){
//         $('#search-result-box').removeClass('active');
//     }
// });
            $(document).click(function (e) {
                if (e.target.closest("#banner-search-tool .tab-contents")) return;
                $('#search-result-box').removeClass('active');
            });
            $('#search-with-hint .close-search').click(function () {
                $('#search-with-hint').removeClass('active');
                $('html').removeClass('active-mobile-search');
            });
            $('#search-with-hint .do-search').click(function () {
                $('#search-form-one').submit();
            });

            // $('#fake-search-input').click(function () {
                // $('#search-with-hint').addClass('active');
                // $('html').addClass('active-mobile-search');
                // $('#search-with-hint input').focus();
            // });

            function getSearchHistory() {
                $('#search-result-box .content').html('');
                var search_history = localStorage.getItem('search_history');
                if (!search_history) {
                    $('#search-result-box').removeClass('active');
                    return;
                }
                var history = JSON.parse(search_history);
                console.log(history);
                if (!history) {
                    $('#search-result-box').removeClass('active');
                    return;
                }
//history.reverse();
                var add = '<div class="search-history"><div class="search-history-title">Những từ khoá đã tìm</div>';
                $(history).each(function (index, his) {
                    if (his) {
                        add += '<a data-keyword="' + his.keyword + '" class="search-history-item">' +
                            his.keyword +
                            '</a>';
                    }
                });
                $('#search-result-box .content').html(add);
                $('#search-result-box .search-history .search-history-item').click(function () {
                    var keyword = $(this).data('keyword');
                    $('#search-form-one input[name=keyword]').val(keyword);
                    $('#search-form-one').submit();
                });
                console.log(history);
            }

            function loadFlashSales() {
                $('#promo-block').addClass('d-none');
                $.ajax({
                    url: '{!! route('promo_Salons.html') !!}',
                    success: function (html) {
                        if (html.trim().length == 0) {
                            return
                        }
                        $('#promo-block .promo-salons').append(html);
                        $("#promo-block .promo-salons").owlCarousel({
                            loop: false,
                            margin: 30,
                            autoWidth: true,
                            responsive: {
                                768: {
                                    nav: true,
                                    //items: 3,

                                },
                                640: {
                                    nav: false,
                                    //items: 2,
                                },
                                0: {
                                    nav: false,
                                    //items: 1,
                                }
                            }
                        });
                        $('#promo-block').removeClass('d-none');
                    }
                });
            }

            loadFlashSales();
        });

    </script>
@endpush