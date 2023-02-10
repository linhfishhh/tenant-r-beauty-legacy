@enqueueCSS('bootstrap', getThemeAssetUrl('libs/bootstrap/css/bootstrap.min.css'))
@enqueueCSS('sf-font', getThemeAssetUrl('libs/styles/font.css'))
@enqueueCSS('fa-font', getThemeAssetUrl('libs/fa/css/font-awesome.min.css'))
@enqueueCSS('sweet-alert', getThemeAssetUrl('libs/sweetalert/sweetalert.css'))
@enqueueJS('jquery', getThemeAssetUrl('libs/jquery.min.js'), JS_LOCATION_HEAD)
@enqueueJS('app-ini', url('assets/ui/js/core/init.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueJS('bootstrap', getThemeAssetUrl('libs/bootstrap/js/bootstrap.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueJS('sweet-alert', getThemeAssetUrl('libs/sweetalert/sweetalert.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueCSS('bcsm', getThemeAssetUrl('libs/styles/bcsm.css'))
@extends('layouts.base')
@section('page_title')
    Đăng ký chủ salon
@endsection
@php
    $og_img = getNoThumbnailUrl();
     $og_width = 500;
     $og_height = 500;
@endphp
@push('page_meta')
    <meta property="og:title" content="Đăng ký salon của bạn - iSalon"/>
    <meta property="og:image" content="{{ $og_img }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:image:secure_url" content="{{ $og_img }}" />
    <meta property="og:image:width" content="{{$og_width}}" />
    <meta property="og:image:height" content="{{$og_height}}" />
@endpush
@push('page_body_js')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
@endpush
@section('page_body')
    @php
    $settings = getSettingsFromPage('theme_config_manager');
    $settings = collect($settings);
    $ios_link = getSetting('theme_master_mobile_app_ios', '#');
    $android_link = getSetting('theme_master_mobile_app_android', '#');
    @endphp
    <div class="page-menu d-none d-md-block">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <div class="logo">
                        <a href="{!! url('') !!}">
                            <img src="{!! getThemeAssetUrl('img/logo_dark.png') !!}">
                        </a>
                    </div>
                    <ul class="menu">
                        <li><a href="#page-intro">GIỚI THIÊU</a></li>
                        <li><a href="#page-app">ỨNG DỤNG</a></li>
                        <li><a href="#page-features">TÍNH NĂNG</a></li>
                        <li><a href="#page-partners">ĐỐI TÁC</a></li>
                        <li><a href="#page-quote">Ý KIẾN KHÁCH HÀNG</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <div class="buttons">
                        <button class="go-to-register" type="button">
                            BẮT ĐẦU
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-menu-mobile d-md-none">
        <div class="container">
            <div class="row">
                <div class="col-4">
                    <div class="logo">
                        <a href="{!! url('') !!}">
                            <img src="{!! getThemeAssetUrl('img/logo_dark.png') !!}">
                        </a>
                    </div>
                </div>
                <div class="col-8">
                    <div class="menu-button-wrapper">
                        <div class="menu-button">
                            <i class="fa fa-bars" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mobile-menu">
            <div class="container">
                <div class="wrapper">
                    <ul class="menu">
                        <li><a href="#page-intro">GIỚI THIÊU</a></li>
                        <li><a href="#page-app">ỨNG DỤNG</a></li>
                        <li><a href="#page-features">TÍNH NĂNG</a></li>
                        <li><a href="#page-partners">ĐỐI TÁC</a></li>
                        <li><a href="#page-quote">Ý KIẾN KHÁCH HÀNG</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="page-header" style="background-image: url('{!! getThemeAssetUrl('img/bcsm/headerbg.jpg') !!}')">
        <div class="page-header-wrapper">
            <div class="page-header-inner">
                <div class="container">
                    <div class="page-header-title">
                        {!! $settings->get('theme_config_manager_intro_text_1', '') !!}
                    </div>
                    <div class="page-header-sub-title">
                        {!! $settings->get('theme_config_manager_intro_text_2', '') !!}
                    </div>
                    <div class="email-input clearfix">
                        <input id="mailorphone" placeholder="Cho chúng tôi biết email hoặc số điện thoại của bạn"
                               type="text">
                        <button class="go-to-register with-email-phone" type="button">THAM GIA NGAY</button>
                    </div>
                    <div class="note">{!! $settings->get('theme_config_manager_intro_text_3', '') !!}</div>
                    <a href="#" class="more" style="display: block">
                        <div class="text">Tìm hiểu ngay</div>
                        <img src="{!! getThemeAssetUrl('img/bcsm/arrow_more.png') !!}">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="page-intro" id="page-intro">
        <div class="container">
            <div class="headline-block">
                <div class="title">
                    {!! $settings->get('theme_config_manager_feature_headline', '') !!}
                </div>
                <div class="content">
                    {!! $settings->get('theme_config_manager_feature_headline_sub', '') !!}
                </div>
            </div>
            <div class="feature-list">
                <div class="row">
                    @foreach($settings->get('theme_config_manager_feature_list', []) as $item)
                        @php
                        $image = \App\UploadedFile::find($item['image'] );
                        if($image){
                            $image = $image->getUrl();
                        }
                        @endphp
                        <div class="col-lg-4 col-sm-6">
                            <div class="feature">
                                @if($image)
                                <img src="{!! $image !!}">
                                @endif
                                <div class="title">{!! $item['title'] !!}</div>
                                <div class="content">{!! $item['content'] !!}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="page-app-intro" id="page-app">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="title">
                        {!! $settings->get('theme_config_manager_app_link_title', '') !!}
                    </div>
                    <div class="content">
                        {!! $settings->get('theme_config_manager_app_link_desc', '') !!}
                    </div>
                    <div class="app-links">
                        <a class="app-link" href="{!! $android_link !!}">
                            <img src="{!! getThemeAssetUrl('img/bcsm/googleplay.png')  !!}">
                        </a>
                        <a class="app-link" href="{!! $ios_link !!}">
                            <img src="{!! getThemeAssetUrl('img/bcsm/appstore.png')  !!}">
                        </a>
                        <div class="web-link">
                            Trên trang web tại <a href="#">https://isalon.vn</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    @php
                    $image = $settings->get('theme_config_manager_app_link_image');
                    if($image){
                        $image = \App\UploadedFile::find($image);
                        if($image){
                            $image = $image->getUrl();
                        }
                    }
                    @endphp
                    <div class="cover">
                        <img src="{!! $image !!}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-app-detail">
        <div class="container">
            <div class="title">{!! $settings->get('theme_config_manager_app_manager_title', '') !!}</div>
            <div class="content">
                {!! $settings->get('theme_config_manager_app_manager_content', '') !!}
            </div>
            @php
            $image = $settings->get('theme_config_manager_app_manager_image');
            if($image){
                $image = \App\UploadedFile::find($image);
                if($image){
                    $image = $image->getUrl();
                }
            }
            @endphp
            <img src="{!! $image !!}">
        </div>
    </div>
    @php
        $features = $settings->get('theme_config_manager_app_manager_features', []);
        $column1 = [
        ];
        $c = 0;
        foreach ($features as $feature){
            $img = $feature['icon'];
            if($img){
                $img = \App\UploadedFile::find($img);
                if($img){
                    $img = $img->getUrl();
                }
            }
            $column1[] = [
                'title' => $feature['title'],
                'content' => $feature['content'],
                'icon' => $img
            ];
            $c++;
            if($c>=3){
                break;
            }
        }

        $column2 = [
        ];

        if(count($features)>3){
            $c = 0;
            $i = 0;
            foreach ($features as $feature){
                 $i++;
                if($i<=3){
                    continue;
                }
                $img = $feature['icon'];
                if($img){
                    $img = \App\UploadedFile::find($img);
                    if($img){
                        $img = $img->getUrl();
                    }
                }
                $column2[] = [
                    'title' => $feature['title'],
                    'content' => $feature['content'],
                    'icon' => $img
                ];
                $c++;
                if($c>=3){
                    break;
                }
            }
        }
    @endphp
    <div class="page-app-features" id="page-features">
        <div class="container">
            <div class="row no-gutters">
                <div class="col-md-4 d-sm-none">
                    <div class="column-1 column">
                        @foreach($column1 as $item)
                            <div class="feature">
                                <div class="info">
                                    <div class="title">{!! $item['title'] !!}</div>
                                    <div class="content">{!! $item['content'] !!}</div>
                                </div>
                                <div class="icon">
                                    <img src="{!! $item['icon'] !!}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-4 d-sm-block">
                    <div class="column-3 column">
                        @foreach($column1 as $item)
                            <div class="feature">
                                <div class="icon">
                                    <img src="{!! $item['icon'] !!}">
                                </div>
                                <div class="info">
                                    <div class="title">{!! $item['title'] !!}</div>
                                    <div class="content">{!! $item['content'] !!}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="column-2 image-phone">
                        @php
                        $image = $settings->get('theme_config_manager_app_manager_features_image');
                        if($image){
                            $image = \App\UploadedFile::find($image);
                            if($image){
                                $image = $image->getUrl();
                            }
                        }
                        @endphp
                        <img src="{!! $image !!}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="column-3 column">
                        @foreach($column2 as $item)
                            <div class="feature">
                                <div class="icon">
                                    <img src="{!! $item['icon'] !!}">
                                </div>
                                <div class="info">
                                    <div class="title">{!! $item['title'] !!}</div>
                                    <div class="content">{!! $item['content'] !!}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-logos" id="page-partners">
        <div class="container">
            <div class="title">
                Đối tác của chúng tôi
            </div>
            <div class="row">
                @foreach($settings->get('theme_config_manager_logos') as $item)
                    @php
                    $image = $item['logo'];
                    if($image){
                        $image = \App\UploadedFile::find($image);
                        if($image){
                            $image = $image->getUrl();
                        }
                    }
                    @endphp
                    <div class="col-md-3 col-sm-6">
                        <a href="{!! $item['link'] !!}" style="display: block" class="logo">
                            <img src="{!! $image !!}">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="page-quote" id="page-quote">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <div class="quote">
                        <div class="title">{!! $settings->get('theme_config_manager_tes_title', '') !!}</div>
                        <div class="content">
                            “
                            {!! $settings->get('theme_config_manager_tes_content', '') !!}
                            ”
                        </div>
                        <div class="name">{!! $settings->get('theme_config_manager_tes_cus_name', '') !!}</div>
                        <div class="job">{!! $settings->get('theme_config_manager_tes_cus_job', '') !!}</div>
                    </div>
                </div>
                <div class="col-sm-4">
                    @php
                    $image = $settings->get('theme_config_manager_tes_cus_image');
                    if($image){
                        $image = \App\UploadedFile::find($image);
                        if($image){
                            $image = $image->getUrl();
                        }
                    }
                    @endphp
                    <img src="{!! $image !!}">
                </div>
            </div>
        </div>
    </div>
    <form class="page-form" id="page-form">
        <div class="container">
            <div class="wrapper">
                <div class="title">{!! $settings->get('theme_config_manager_form_title', '') !!}</div>
                <div class="desc">
                    {!! $settings->get('theme_config_manager_form_desc', '') !!}
                </div>
                <a style="display: block; height: 40px; line-height: 40px; padding: 0 15px;    border: none;
    background: #FF5C39;
    color: white;
    cursor: pointer; text-decoration: none" target="_blank" href="http://bit.ly/2CKeLlK">Tham gia ngay</a>
            </div>
        </div>
    </form>
    <div class="page-footer">
        <div class="container">
            <div class="cpr">
                Nhãn hiệu và bản quyền @2018 iSalon.vn
            </div>
        </div>
    </div>
@endsection
@push('page_footer_js')
    <script type="text/javascript">
        $(function () {

            $('#btn_reset_form_contact_captcha').click(function () {
                $('#contact_form_captcha').attr('src', '{!! url('captcha/flat') !!}?'+Math.random());
            });

            $('.page-menu-mobile .menu-button').click(function () {
                $('.page-menu-mobile .mobile-menu').toggleClass('active');
            });
            $('button.go-to-register').click(function () {
                if ($(this).hasClass('with-email-phone')) {
                    var val = $('#mailorphone').val();
                    if (!isNaN(val)) {
                        $('#phone').val(val);
                    }
                    else {
                        $('#email').val(val);
                    }
                }
                $('html, body').animate({
                    scrollTop: $("#page-form").offset().top
                }, 2000);
            });
            $('.menu a').click(function () {
                var id = $(this).attr('href');
                $('html, body').animate({
                    scrollTop: $(id).offset().top
                }, 2000);
                return false;
            });
            $('#page-form').submit(function () {
                var form = $(this);
                var data = $(form).serializeObject();
                $.ajax(
                    {
                        url: '{!! route('frontend.salon_register.submit') !!}',
                        type: 'post',
                        dataType: 'json',
                        data: data,
                        beforeSend: function () {
                            $('#page-form button').addClass('disabled');
                        },
                        complete: function () {
                            $('#page-form button').removeClass('disabled');
                        },
                        success: function () {
                            $('#page-form .form').addClass('d-none');
                            $('#page-form .form-success').removeClass('d-none');
                        },
                        error: function (json) {
                            $('#contact_form_captcha').attr('src', '{!! url('captcha/flat?') !!}' + Math.random());
                            handleErrorMessage(form, json);
                        }
                    }
                );
                return false;
            });
        })
    </script>
@endpush
