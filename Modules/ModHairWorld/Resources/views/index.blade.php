@enqueueCSS('datepicker', getThemeAssetUrl('libs/datepicker/css/bootstrap-datepicker.standalone.min.css'), 'bootstrap')
@enqueueJS('datepicker', getThemeAssetUrl('libs/datepicker/js/bootstrap-datepicker.min.js'), JS_LOCATION_HEAD, 'bootstrap')
@enqueueJS('datepicker-language', getThemeAssetUrl('libs/datepicker/js/locales/bootstrap-datepicker.vi.min.js'), JS_LOCATION_HEAD, 'datepicker')
@enqueueCSS('select2', getThemeAssetUrl('libs/select2/css/select2.min.css'), 'bootstrap')
@enqueueJS('select2', getThemeAssetUrl('libs/select2/js/select2.full.min.js'), JS_LOCATION_HEAD, 'jquery')
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
    <meta property="og:image:secure_url" content="{{ $og_img }}" />
    <meta property="og:image:width" content="{{$og_width}}" />
    <meta property="og:image:height" content="{{$og_height}}" />
@endpush
@section('page_content')
    <!--region Search tool-->
    <div id="banner-search-tool" style="background-image: url('{!! $header_banner?$header_banner:getThemeAssetUrl('img/home_banner_bg.jpg') !!}')">
        <div class="wrapper">
            <div class="inner">
                <div class="container">
                    <div class="search-form">
                        @if($page_configs->has('theme_home_header_banner_title'))
                        <div class="slogan">{!! $page_configs->get('theme_home_header_banner_title') !!}</div>
                        @endif
                        <div class="tabs clearfix">
                            <div class="float-left tab active" data-id="search-1">
                                <div class="tab-title">Tìm theo địa điểm</div>
                            </div>
                            <div class="float-left tab" data-id="search-2">
                                <div class="tab-title">Tìm theo salon/dịch vụ</div>
                            </div>
                        </div>
                        <div class="tab-contents">
                            <div class="tab-content active" data-id="search-1">
                                <div class="wrapper">
                                    <form id="search-form-one" method="get" action="{!! route('frontend.search') !!}">
                                        <div class="row no-gutters">
                                        <div class="col-md-3">
                                            <div class="search-form-field field-location">
                                                <div class="icon">
                                                    <img src="{!! getThemeAssetUrl('img/marker.png') !!}">
                                                </div>
                                                <input id="home-search-location" name="address" spellcheck="false" placeholder="Nhập địa điểm">
                                                <input name="address_lat" type="hidden">
                                                <input name="address_lng" type="hidden">
                                                <input name="address_type" type="hidden">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="search-form-field field-date">
                                                <div class="icon">
                                                    <img src="{!! getThemeAssetUrl('img/date.png') !!}">
                                                </div>
                                                <input autocomplete="off" id="search-form-workday" name="workday" spellcheck="false" placeholder="Cho chúng tôi biết ngày bạn rảnh">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="home-cat-selector">
                                                <select name="cat[]" id="home-cat-selector">
                                                    @foreach(\Modules\ModHairWorld\Entities\SalonServiceCategory::orderBy('title', 'asc')->get() as $item)
                                                        <option value="{!! $item->id !!}">
                                                            {!! $item->title !!}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="submit-button">
                                                <button type="button">Tìm kiếm</button>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-content" data-id="search-2">
                                <div class="wrapper">
                                    <form method="get" action="{!! route('frontend.search') !!}">
                                    <div class="row no-gutters">
                                        <div class="col-md-10">
                                            <div class="search-form-field field-keyword">
                                                <div class="icon">
                                                    <img src="{!! getThemeAssetUrl('img/keyword.png') !!}">
                                                </div>
                                                <input spellcheck="false" autocomplete="off" name="keyword" placeholder="Nhập salon hoặc dịch vụ bạn yêu thích">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="submit-button">
                                                <button type="submit">Tìm kiếm</button>
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
    <!--region Banner grid-->
    <div id="banner-grid">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <div class="banner-1 banner">
                        @if($banner_grid_1)
                        <a>
                            <img src="{!! $banner_grid_1 !!}">
                            <div class="info">
                                <div class="info-wrapper">
                                    <div class="top-text">{!! $banner_top_text_1 !!}</div>
                                    <div class="bottom-info">
                                        <div class="title">{!! $banner_top_title_1 !!}</div>
                                        <div class="sub-title">{!! $banner_top_sub_title_1 !!}</div>
                                        <a href="{!! $banner_grid_1_link !!}" class="button-b">
                                            Chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endif
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="banner-2 banner">
                        @if($banner_grid_2)
                        <a>
                            <img src="{!! $banner_grid_2 !!}">
                            <div class="info">
                                <div class="info-wrapper">
                                    <div class="top-text">{!! $banner_top_text_2 !!}</div>
                                    <div class="bottom-info">
                                        <div class="title">{!! $banner_top_title_2 !!}</div>
                                        <div class="sub-title">{!! $banner_top_sub_title_2 !!}</div>
                                        <a href="{!! $banner_grid_2_link !!}"  class="button-b">
                                            Chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </a>
                            @endif
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="banner-3 banner">
                                @if($banner_grid_3)
                                <a href="{!! $banner_grid_3_link !!}" >
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
                            <div class="banner-4 banner">
                                @if($banner_grid_4)
                                <a href="{!! $banner_grid_4_link !!}" >
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
                        <a href="{!! route('frontend.search', ['location_lv' => 1, 'location' => $item['id']]) !!}" class="item d-block">
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
    <!--region Latest news-->
    @if($latest_news)
    <div id="news-block">
        <div class="container">
            <div class="block-title">
                Bài viết mới nhất
                <div class="link">
                    <a href="{!! \Modules\ModHairWorld\Entities\PostTypes\News::getPublicIndexUrl() !!}">Xem tất cả ></a>
                </div>
            </div>
            <div class="list">
                <div class="row">
                    @foreach($latest_news as $k=>$item)
                        @php
                        /** @var \Modules\ModHairWorld\Entities\PostTypes\News $item */
                        @endphp
                        <div class="col-md-4">
                            <a href="{!! $item->getUrl() !!}" class="item d-block">
                                <div class="img">
                                    <img src="{!! $item->cover?$item->cover->getThumbnailUrl('medium', getNoThumbnailUrl()):getNoThumbnailUrl() !!}">
                                </div>
                                <div class="title">{!! $item['title'] !!}</div>
                            </a>
                        </div>
                    @endforeach
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
                                <a target="_blank"  href="{!! $master_configs->get('theme_master_mobile_app_ios', '#') !!}" class="link">
                                    <img src="{!! getThemeAssetUrl('img/appstore.png') !!}">
                                </a>
                                <a target="_blank"  href="{!! $master_configs->get('theme_master_mobile_app_android', '#') !!}" class="link">
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
@endsection
@include(getThemeViewName('includes.google_map_api'))
@push('page_footer_js')
    <script type="text/javascript">
        $('input[name=keyword]').focus(function () {
            $(this).tooltip('show');
        });
        $('input[name=keyword]').blur(function () {
            $(this).tooltip('hide');
        });
        $(window).on('googleMapInit', function () {
            var autocomplete = new google.maps.places.Autocomplete(document.getElementById('home-search-location'),
                {
                    componentRestrictions: {country: "vn"}
                }
            );
            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                if(place.hasOwnProperty('geometry')){
                    $('.search-form .field-location input[name=address_lat]').val(place.geometry.location.lat());
                    $('.search-form .field-location input[name=address_lng]').val(place.geometry.location.lng());
                    $('.search-form .field-location input[name=address_type]').val(place.types[0]);
                }
            });
        });
        $('#home-cat-selector').select2({
            width: '100%',
            //minimumResultsForSearch: Infinity,
            placeholder: "Tất cả dịch vụ",
            allowClear: true
        }).val(null).trigger('change');;
        $('#search-form-one button').click(function () {
            $('#search-form-one').submit();
        });
        $('#search-form-workday').datepicker({
            language: 'vi',
			startDate: '{!! now()->format('d/m/Y') !!}',
        })
        $(function () {
            $('#banner-search-tool .tab').click(function () {
                var id = $(this).data('id');
                $('#banner-search-tool .tab').removeClass('active');
                $('#banner-search-tool .tab-content').removeClass('active');
                $(this).addClass('active');
                $('#banner-search-tool .tab-content').hide();
                $('#banner-search-tool .tab-content[data-id='+id+']').addClass('active');
                $('#banner-search-tool .tab-content[data-id='+id+']').fadeIn();
            });
        });
    </script>
@endpush