@enqueueCSS('bootstrap', getThemeAssetUrl('libs/bootstrap/css/bootstrap.min.css'))
@enqueueCSS('sf-font', getThemeAssetUrl('libs/styles/font.css'))
@enqueueCSS('fa-font', getThemeAssetUrl('libs/fa/css/font-awesome.min.css'))
@enqueueCSS('sweet-alert', getThemeAssetUrl('libs/sweetalert/sweetalert.css'))
@enqueueCSS('master-page', getThemeAssetUrl('libs/styles/master.css'))
@enqueueJS('jquery', getThemeAssetUrl('libs/jquery.min.js'), JS_LOCATION_HEAD)
@enqueueJS('account-kit', 'https://sdk.accountkit.com/vi_VN/sdk.js', JS_LOCATION_HEAD)
@enqueueJS('app-ini', url('assets/ui/js/core/init.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueJS('popper', getThemeAssetUrl('libs/popper.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueJS('bootstrap', getThemeAssetUrl('libs/bootstrap/js/bootstrap.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueJS('sticky', getThemeAssetUrl('libs/jquery.sticky.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueJS('sweet-alert', getThemeAssetUrl('libs/sweetalert/sweetalert.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueJS('inputmask', getThemeAssetUrl('libs/inputmask/jquery.inputmask.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueJS('inputmask.binding', getThemeAssetUrl('libs/inputmask/bindings/inputmask.binding.js'), JS_LOCATION_HEAD, 'jquery')
@extends('layouts.base')
@php
    $logged = Auth::user();
    $checkLogged = Auth::check();
    $master_configs = getSettingsFromPage('theme_config_master');
    $master_configs = collect($master_configs);
    loadThemeMenus();
    $menus = [
            'main'=>getThemeMenu('main'),
            'footer1'=>getThemeMenu('footer1'),
            'footer2'=>getThemeMenu('footer2'),
            'footer3'=>getThemeMenu('footer3'),
    ];
    $menu_mode = isset($menu_mode)?$menu_mode:0;
    $qdsd = '#';
    $csbm = '#';
    $t = getSetting('theme_master_quy_dinh', false);
    if($t){
        if(isset($t['posts'])){
               $tt = \Modules\ModHairWorld\Entities\PostTypes\News::find($t['posts']);
                if($tt){
                    $qdsd = $tt->getUrl();
                }
        }
    }

    $t = getSetting('theme_master_chinh_sach', false);
    if($t){
         if(isset($t['posts'])){
            $tt = \Modules\ModHairWorld\Entities\PostTypes\News::find($t['posts']);
            if($tt){
                $csbm = $tt->getUrl();
            }
        }
    }
@endphp
@section('page_language') vi @endsection
@push('page_meta')
    <link rel="canonical" href="{!! Request::url() !!}" />
    <meta http-equiv="content-language" content="vi" />
    <meta name="msvalidate.01" content="F79D84DFC5227FFA338990E30D6C19A5" />
    <meta property="og:locale" content="vi_VN" />
    <meta property="og:site_name" content="{{ $master_configs['theme_master_site_title'] }}" />
    <meta property="og:url" content="{!! Request::url() !!}" />
    <meta property="fb:app_id" content="1017282308444421"/>
    <meta property="article:author" content="https://www.facebook.com/iSalon.vn" />
@endpush
@section('page_title')
    @hasSection('current_page_title') @yield('current_page_title') @else {!! $master_configs->get('theme_master_site_title', 'Th??? Gi???i T??c') !!}@endif
@endsection
@push('page_head')
    <link rel="manifest" href="/manifest.json" />
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
    <script>
        var OneSignal = window.OneSignal || [];
        OneSignal.push(function() {
            OneSignal.init({
                appId: "{!! config('onesignal.ONESIGNAL_CUSTOMER_ID') !!}",
            });
            // OneSignal.log.setLevel('trace');
            var id = localStorage.getItem('user_location');
            if(id){
                OneSignal.sendTag("location", id);
            }
            @auth
                OneSignal.sendTag("user_id", "{!! me()->id !!}");
                OneSignal.sendTag("role_id", "{!! me()->role_id !!}");
            @endif
        });
    </script>
    @stack('child_page_head')
@endpush
@push('page_body_js')
    <script type="text/javascript">
        $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
            if(options.url.includes('{!! url('') !!}')){
                options.headers = {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                };
            }
        });
    </script>
@endpush
@section('page_body')
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-N5XXBRX');</script>
    <!-- End Google Tag Manager -->
    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window,document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '351318888767424');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1"
             src="https://www.facebook.com/tr?id=351318888767424&ev=PageView
	&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-128776978-1"></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-778363075"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-128776978-1');
        gtag('config', 'AW-778363075');
    </script>
    <!-- Event snippet for Booking conversion page In your html page, add the snippet and call gtag_report_conversion when someone clicks on the chosen link or button. -->
    <script> function gtag_report_conversion(url, id) { var callback = function () { if (typeof(url) != 'undefined') { window.location = url; } }; gtag('event', 'conversion', { 'send_to': 'AW-778363075/zagaCOiv4pABEMPBk_MC', 'value': 2.0, 'currency': 'USD', 'transaction_id': id, 'event_callback': callback }); return false; } </script>
    <script> (function(a, b, d, c, e) { a[c] = a[c] || [];
            a[c].push({ "atm.start": (new Date).getTime(), event: "atm.js" });
            a = b.getElementsByTagName(d)[0]; b = b.createElement(d); b.async = !0;
            b.src = "//deqik.com/tag/corejs/" + e + ".js"; a.parentNode.insertBefore(b, a)
        })(window, document, "script", "atmDataLayer", "ATM5NKMBELH2M");
    </script>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N5XXBRX"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- Load Facebook SDK for JavaScript -->
    <div id="fb-root"></div>
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                xfbml : true,
                version : 'v3.3'
            });
        };

        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>

    <!-- Your customer chat code -->
    <div class="fb-customerchat"
         page_id="941543739381495"
         greeting_dialog_display="hide">
    </div>


    <!-- End Google Tag Manager (noscript) -->
    @php
        $avatar = getThemeAssetUrl('img/blank-avatar.png');
        if($logged){
            $avatar_file = me()->avatar;
            if($avatar_file){
                $avatar = $avatar_file->getThumbnailUrl('default', getThemeAssetUrl('img/blank-avatar.png'));
            }
        }
    @endphp
    <div class="d-lg-block d-none page-header-block">
        <div id="page-header" class="device-desktop{!! isset($dark_theme)&&$dark_theme?' dark-theme':'' !!}">
            <div class="wrapper clearfix">
                <div class="logo float-left">
                    <a href="{!! url('') !!}">
                        @if(isset($dark_theme)&&$dark_theme)
                            <img src="{!! getThemeAssetUrl('img/logo_dark.png') !!}">
                        @else
                            <img src="{!! getThemeAssetUrl('img/logo.png') !!}">
                        @endif
                    </a>
                </div>
                <div class="menu float-left">
                    @if($menu_mode)
                        sdsd
                    @else
                        @php
                            $configs = buildMenuComponentConfigs(
                                    'main', 0, false, false, '', '', 'active');
                        @endphp
                        @component('frontend.components.menu',$configs)
                        @endcomponent
                    @endif
                </div>
                @if(!$logged)
                    <div class="register-salon float-right">
                        <a href="{!! route('frontend.salon_register') !!}">Tr??? th??nh ch??? salon</a>
                    </div>
                @endif
                <div class="small-menu float-right">
                    <ul>
                        @if($checkLogged)
                            <li><a href="{!! route('frontend.salon_register') !!}">Tr??? th??nh ch??? salon</a></li>
                            <li>
                                <a href="{!! route('frontend.account.help') !!}">Tr??? gi??p</a>
                            </li>
                        @endif
                        @if(!$checkLogged)
                            <li>
                                <a href="#" class="show-login-form-link">????ng nh???p</a>
                            </li>
{{--                            <li>--}}
{{--                                <a href="#" class="show-register-form-link">????ng k??</a>--}}
{{--                            </li>--}}
                        @else
                            <li class="account-notify check-notification-indicator">
                                <a href="{!! route('frontend.account.notification') !!}">Th??ng b??o</a>
                            </li>
                            <li class="account">
                                <a href="{!! route('frontend.account.profile') !!}">
                                    <img src="{!! $avatar !!}">
                                </a>
                                <ul>
                                    <li>
                                        <a href="{!! route('frontend.account.profile') !!}">Trang c?? nh??n</a>
                                    </li>
                                    <li>
                                        <a href="{!! route('frontend.account.edit') !!}">Xem & s???a h??? s??</a>
                                    </li>
                                    <li>
                                        <a href="{!! route('frontend.account.payment') !!}">Th??ng tin thanh to??n</a>
                                    </li>
                                    <li>
                                        <a href="{!! route('frontend.account.history') !!}">L???ch s??? ?????t ch???</a>
                                    </li>
                                    <li>
                                        <a href="{!! route('frontend.account.share') !!}">M???i b???n b??</a>
                                    </li>
                                    <li>
                                        <a href="{!! route('frontend.account.fav_Salon') !!}">Y??u th??ch</a>
                                    </li>
                                    <li>
                                        <a href="{!! route('frontend.account.reset_password') !!}">?????i m???t kh???u</a>
                                    </li>
                                    <li>
                                        <a href="{!! route('frontend.logout') !!}">????ng xu???t</a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="d-lg-none page-header-block">
        <div id="page-header-mobile">
            <div class="wrapper">
                <div class="row">
                    <div class="col-4">
                        <div class="menu">
                            <div class="btn-show-menu">
                                <i class="fa fa-list"></i>
                            </div>
                            <div class="menu-wrapper">
                                @php
                                    $configs = buildMenuComponentConfigs(
                                            'main', 0, false, false, '', '', 'active');
                                @endphp
                                @component('frontend.components.menu',$configs)
                                @endcomponent
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="logo">
                            <a href="{!! url('') !!}">
                                <img src="{!! getThemeAssetUrl('img/logo_dark.png') !!}">
                            </a>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="menu menu-right">
                            <div class="btn-show-menu check-notification-indicator">
                                @if(!$logged)
                                    <i class="fa fa-user-o"></i>
                                @else
                                    <img src="{!! $avatar !!}">
                                @endif
                            </div>
                            <div class="menu-wrapper">
                                <ul>
                                    <li><a href="{!! route('frontend.salon_register') !!}">Tr??? th??nh ch??? salon</a></li>
                                    @if(!$logged)
                                        <li>
                                            <a href="#" class="show-login-form-link">????ng nh???p</a>
                                        </li>
{{--                                        <li>--}}
{{--                                            <a href="#" class="show-register-form-link">????ng k??</a>--}}
{{--                                        </li>--}}
                                    @else
                                        <li>
                                            <a href="{!! route('frontend.account.help') !!}">Tr??? gi??p</a>
                                        </li>
                                        <li>
                                            <a href="{!! route('frontend.account.notification') !!}">Xem c??c th??ng b??o</a>
                                        </li>
                                        <li>
                                            <a href="{!! route('frontend.account.edit') !!}">Xem & s???a h??? s??</a>
                                        </li>
                                        <li>
                                            <a href="{!! route('frontend.account.payment') !!}">Th??ng tin thanh to??n</a>
                                        </li>
                                        <li>
                                            <a href="{!! route('frontend.account.history') !!}">L???ch s??? ?????t ch???</a>
                                        </li>
                                        <li>
                                            <a href="{!! route('frontend.account.reset_password') !!}">?????i m???t kh???u</a>
                                        </li>
                                        <li>
                                            <a href="{!! route('frontend.logout') !!}">????ng xu???t</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @yield('page_content')
    <div id="page-footer">
        <div class="container">
            <div class="page-footer-top">
                <div class="wrapper">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="menu">
                                @php
                                    $configs = buildMenuComponentConfigs(
                                            'footer1', 1, false, false);
                                @endphp
                                @component('frontend.components.menu',$configs)
                                @endcomponent
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="menu">
                                @php
                                    $configs = buildMenuComponentConfigs(
                                            'footer2', 1, false, false);
                                @endphp
                                @component('frontend.components.menu',$configs)
                                @endcomponent
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="clearfix app-block">
                                <div class="app-block-items float-right">
                                    <div class="clearfix">
                                        <div class="float-left item">
                                            <a target="_blank"  href="{!! $master_configs->get('theme_master_mobile_app_ios', '#') !!}">
                                                <img src="{!! getThemeAssetUrl('img/appstore.png') !!}">
                                            </a>
                                        </div>
                                        <div class="float-left item">
                                            <a href="{!! $master_configs->get('theme_master_mobile_app_android', '#') !!}">
                                                <img src="{!! getThemeAssetUrl('img/googleplay.png') !!}">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="app-block-title float-right">
                                    <div class="title">T???i xu???ng ngay tr??n<br>App Store v?? Google Play.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="page-footer-bottom">
                <div class="row">
                    <div class="col-md-6">
                        <div class="tos-link">
                            @php
                                $configs = buildMenuComponentConfigs(
                                        'footer3', 1, false, false);
                            @endphp
                            @component('frontend.components.menu',$configs)
                            @endcomponent
                        </div>
                        <div class="copyright">
                            {!! $master_configs->get('theme_master_copyright', '') !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="icons">
                            <a href="http://online.gov.vn/WebsiteDisplay.aspx?DocId=49811" target="_blank" class="dkbct">
                                <img src="{!! getThemeAssetUrl('img/dkbct.png') !!}">
                            </a>
                            @if($master_configs->get('theme_master_social_links', null))
                                @foreach($master_configs->get('theme_master_social_links') as $item)
                                    <a target="_blank" href="{!! $item['link'] !!}">
                                        <i class="{!! $item['icon'] !!}"></i>
                                    </a>
                                @endforeach
                            @endif
                            <a href="#" class="gotop">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('page_footer_js')
{{--    <div class="modal modal-register-step" tabindex="-1" role="dialog" id="modal-register">--}}
{{--        <div class="modal-dialog" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-body">--}}
{{--                    <div class="dismiss-modal">--}}
{{--                        <div data-dismiss="modal" class="btn-close"><i class="fa fa-close"></i></div>--}}
{{--                    </div>--}}
{{--                    <div class="block-title">????ng k?? t??i kho???n</div>--}}
{{--                    <div class="block-content">--}}
{{--                        <div class="social-buttons">--}}
{{--                            <a href="{!! route('frontend.login.social', ['provider' => 'facebook']) !!}" class="block-button facebook">--}}
{{--                                <i class="fa fa-facebook"></i>--}}
{{--                                <span>Facebook</span>--}}
{{--                            </a>--}}
{{--                            <a href="{!! route('frontend.login.social', ['provider' => 'google']) !!}" class="block-button google">--}}
{{--                                <i class="fa fa-google"></i>--}}
{{--                                <span>Google</span>--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                        <form>--}}
{{--                            <div class="block-form">--}}
{{--                                <div class="field">--}}
{{--                                    <input name="phone" placeholder="S??? ??i???n tho???i" spellcheck="false" autocomplete="off">--}}
{{--                                </div>--}}
{{--                                <div class="field">--}}
{{--                                    <input name="email" placeholder="Email li??n h???" spellcheck="false" autocomplete="off">--}}
{{--                                </div>--}}
{{--                                <div class="field">--}}
{{--                                    <input name="name" placeholder="H??? t??n" spellcheck="false" autocomplete="off">--}}
{{--                                </div>--}}
{{--                                <div class="field">--}}
{{--                                    <input type="password" name="password" placeholder="M???t kh???u ????ng nh???p" spellcheck="false" autocomplete="off">--}}
{{--                                </div>--}}
{{--                                <div class="field">--}}
{{--                                    <input type="password" name="password_confirmation" placeholder="Nh???p l???i m???t kh???u" spellcheck="false" autocomplete="off">--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <button class="block-button submit">--}}
{{--                                <span>????ng k?? t??i kho???n</span>--}}
{{--                            </button>--}}
{{--                        </form>--}}
{{--                        <div class="tos">--}}
{{--                            Nh???n "????ng k??", ngh??a l?? b???n ?????ng ?? v???i<br>--}}
{{--                            <a href="{!! $qdsd !!}" target="_blank">Quy ?????nh b???o m???t</a>--}}
{{--                            v??--}}
{{--                            <a href="{!! $csbm !!}" target="_blank">Th???a thu???n s??? d???ng</a>--}}
{{--                        </div>--}}
{{--                        <div class="block-note">--}}
{{--                            <span>???? c?? t??i kho???n? <a href="#" class="show-login-form-link-r">????NG NH???P</a></span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="modal modal-register-step" tabindex="-1" role="dialog" id="modal-account-validator">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="dismiss-modal">
                        <div data-dismiss="modal" class="btn-close"><i class="fa fa-close"></i></div>
                    </div>
                    <div class="img-account-valid">
                        <img src="{!! getThemeAssetUrl('img/account_val.png') !!}">
                    </div>
                    <div class="size-wrapper">
                        <div class="block-title">X??c minh t??i kho???n</div>
                        <div class="block-content">
                            <div class="send">
                                <span>M???t tin nh???n ???? g???i ?????n</span>
                                <a class="phone"></a>
                            </div>
                            <div class="check">Vui l??ng ki???m tra tin nh???n tr??n ??i???n tho???i v?? nh???p 6 ch??? s??? ???????c cung c???p trong tin nh???n ????? x??c nh???n t??i kho???n</div>
                            <form class="block-form">
                                <div class="field">
                                    <input name="code" type="text" spellcheck="false" autocomplete="off" placeholder="Nh???p m?? s??? g???m ch??? s???">
                                </div>
                                <button class="block-button submit">
                                    <span>Ti???p t???c</span>
                                </button>
                            </form>
                        </div>
                        <div class="block-note">
                            <span>Ch??a nh???n ???????c tin nh???n? <a href="#" class="refresh-verify-code">G???i x??c minh l???i</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-register-step" tabindex="-1" role="dialog" id="modal-account-phone">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="dismiss-modal">
                        <div data-dismiss="modal" class="btn-close"><i class="fa fa-close"></i></div>
                    </div>
                    <div class="size-wrapper">
                        <div class="block-title">S??? ??i???n tho???i</div>
                        <div class="block-content">
                            <div class="message">
                                ????? ?????m b???o th??ng tin d???ch v??? ???????c chuy???n t???i ?????n b???n k???p th???i v?? d??? d??ng, vui l??ng cung c???p s??? ??i???n tho???i ????? c?? th??? nh???n tin nh???n th??ng b??o t??? ch??ng t??i
                            </div>
                            <form class="block-form">
                                <div class="field">
                                    <input name="phone" type="text" spellcheck="false" autocomplete="off" placeholder="S??? ??i???n tho???i">
                                </div>
                                <button class="block-button submit">
                                    <span>Ti???p t???c</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-register-step" tabindex="-1" role="dialog" id="modal-account-success">
        <div class="dismiss-modal">
            <div data-dismiss="modal" class="btn-close no-icon"></div>
        </div>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="img-account-success">
                        <img src="{!! getThemeAssetUrl('img/resuccess.png') !!}">
                    </div>
                    <div class="size-wrapper">
                        <div class="block-title">Ch??c m???ng b???n!</div>
                        <div class="block-content">
                            <div class="message">
                                Ch??ng t??i c?? th??? cho b???n bi???t khi c??
                                ai ???? th??ng b??o cho b???n ho???c th??ng b??o
                                cho b???n v??? c??c ho???t ?????ng t??i kho???n
                                quan tr???ng kh??c.
                            </div>
                            <a class="block-button" id="btn-register-done-refresh">
                                <span>C??, th??ng b??o cho t??i</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-register-step" tabindex="-1" role="dialog" id="modal-reset-password">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="dismiss-modal">
                        <div data-dismiss="modal" class="btn-close"><i class="fa fa-close"></i></div>
                    </div>
                    <div class="img-reset-password">
                        <img src="{!! getThemeAssetUrl('img/resetpass1.png') !!}">
                    </div>
                    <div class="size-wrapper">
                        <div class="block-title">?????t l???i m???t kh???u</div>
                        <div class="block-content">
                            <form class="block-form" id="form-reset-password">
                                <div class="field">
                                    <input name="phone" type="text" spellcheck="false" autocomplete="off" placeholder="S??? ??i???n tho???i">
                                </div>
                                <div class="field">
                                    <input type="password" name="password" placeholder="M???t kh???u m???i" spellcheck="false" autocomplete="off">
                                </div>
                                <div class="field">
                                    <input type="password" name="password_confirmation" placeholder="Nh???p l???i m???t kh???u" spellcheck="false" autocomplete="off">
                                </div>
                                <button class="block-button submit">
                                    <span>TI???P T???C</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-register-step" tabindex="-1" role="dialog" id="modal-reset-password-send">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="dismiss-modal">
                        <div data-dismiss="modal" class="btn-close"><i class="fa fa-close"></i></div>
                    </div>
                    <div class="img-reset-password">
                        <img src="{!! getThemeAssetUrl('img/resetpass2.png') !!}">
                    </div>
                    <div class="size-wrapper">
                        <div class="block-title">?????t l???i m???t kh???u</div>
                        <div class="block-content">
                            <div class="send">
                                <div class="normal">Tin nh???n x??c nh???n ?????i m???t kh???u g???i ?????n</div>
                                <div class="phone">0956987562</div>
                            </div>
                            <div class="message">
                                H??y ki???m tra tin nh???n v?? nh???p m?? 6 ch??? s??? ???????c cung c???p trong tin nh???n
                            </div>
                            <form class="block-form">
                                <div class="field">
                                    <input name="code" type="text" spellcheck="false" autocomplete="off" placeholder="Nh???p m?? 6 ch??? s???">
                                </div>
                                <button class="block-button submit">
                                    <span>KH???I T???O M???T KH???U</span>
                                </button>
                                <div class="block-note">
                                    <span>Ch??a nh???n ???????c tin nh???n? <a class="refresh-verify-code" href="#">G???i x??c minh l???i</a></span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-register-step" tabindex="-1" role="dialog" id="modal-reset-password-success">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="dismiss-modal">
                        <div data-dismiss="modal" class="btn-close"><i class="fa fa-close"></i></div>
                    </div>
                    <div class="img-account-success">
                        <img src="{!! getThemeAssetUrl('img/resetpass3.png') !!}">
                    </div>
                    <div class="size-wrapper">
                        <div class="block-title">Ch??c m???ng b???n!</div>
                        <div class="block-content">
                            <div class="send">
                                <div class="normal">M???t kh???u m???i c???a b???n ???? ???????c kh???i t???o th??nh c??ng</div>
                            </div>
                            <div class="message">
                                B???n c?? th??? d??ng m???t kh???u n??y ????? ????ng nh???p ngay t??? b??y gi???
                            </div>
                            <a class="block-button" data-dismiss="modal">
                                <span>T??i ???? hi???u</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-register-step" tabindex="-1" role="dialog" id="modal-login-nt">
        <div class="dismiss-modal">
        <div data-dismiss="modal" class="btn-close no-icon"></div>
        </div>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
{{--                    <div class="dismiss-modal">
                            <div data-dismiss="modal" class="btn-close"><i class="fa fa-close"></i></div>
                        </div>--}}
                    <div class="block-title">????ng nh???p</div>
                    @component(getThemeViewName('components.login_formV2'))
                    @endcomponent
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-register-step" tabindex="-1" role="dialog" id="login-success">
        <div class="dismiss-modal">
        <div data-dismiss="modal" class="btn-close no-icon"></div>
        </div>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <img src="/assets/images/login-success-icon.png" alt="welcome" class="welcome-icon">
                    <h3>Ch??c m???ng b???n!</h3>
                    <div>Ch??ng t??i c?? th??? cho b???n bi???t khi c?? ai ???? th??ng b??o cho b???n ho???c th??ng b??o
                        cho b???n v??? c??c ho???t ?????ng t??i kho???n quan tr???ng kh??c.</div>
                    <div class="buttons">
                        <button class="login">TI???P T???C</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        console.log('check_logged', '{{$checkLogged}}');
        console.log('ssid', '{{Session::getId()}}');
        var $modal_login = $('#modal-login-nt').modal({
            show: false,
            backdrop: 'static'
        });

        var $modal_register = $('#modal-register').modal({
            show: false,
            backdrop: 'static'
        });
        var $modal_account_validator = $('#modal-account-validator').modal({
            show: false,
            backdrop: 'static'
        });
        var $modal_account_success = $('#modal-account-success').modal({
            show: false,
            backdrop: 'static'
        });
        var $modal_account_phone = $('#modal-account-phone').modal({
            show: false,
            backdrop: 'static'
        });
        $modal_account_success.on('hide.bs.modal', function () {
            window.location = '{!! url('') !!}';
        });
        var $modal_reset_password = $('#modal-reset-password').modal({
            show: false,
            backdrop: 'static'
        });
        var $modal_reset_password_send = $('#modal-reset-password-send').modal({
            show: false,
            backdrop: 'static'
        });
        var $modal_reset_password_success = $('#modal-reset-password-success').modal({
            show: false,
            backdrop: 'static'
        });

        function WaShowRegisterForm(){
            $modal_register.modal('show');
            $('#page-header-mobile .menu').removeClass('active');
            $('#float-form-login').removeClass('active');
        }
        function WaShowValidationAccountForm(data, social){
            $modal_register.modal('hide');
            $modal_account_phone.modal('hide');

            $modal_account_validator.find('.phone').html(data.phone);
            $modal_account_validator.find('form').data('data', data);
            $modal_account_validator.find('form').data('social', social === true);
            cleanErrorMessage($modal_account_validator.find('form'));
            $modal_account_validator.find('form input[name=code]').val('');
            $modal_account_validator.modal('show');
        }

        $('#btn-register-done-refresh').click(function () {
            window.location = '{!! Request::url() !!}';
        });

        function WaShowSuccessForm(data){
            $modal_account_validator.modal('hide');
            $modal_account_success.find('form').data('data', data);
            $modal_account_success.modal('show');
        }
        function WaShowAccountPhoneForm(data){
            $modal_register.modal('hide');
            $modal_account_phone.find('form').data('data', data);
            $modal_account_phone.modal('show');
        }

        @if(Session::has('social_new_account'))
        @php
            $user_data = Session::get('social_new_account');
        @endphp
        WaShowAccountPhoneForm({
            token: '{!! $user_data->token !!}'
        });
        @endif

        @if(Session::has('social_connect_message_error'))
        swal("L???i k???t n???i", '{{Session::get('social_connect_message_error')}}', "error")
        @endif

        function WaShowResetPassForm(){
            $('#page-header-mobile .menu').removeClass('active');
            $('#float-form-login').removeClass('active');
            $modal_reset_password.modal('show');
        }
        function WaShowResetPassSendForm(data){
            $modal_reset_password_send.find('.phone').html(data.phone);
            $modal_reset_password_send.find('form').data('data', data);
            $modal_reset_password.modal('hide');
            $modal_reset_password_send.modal('show');
        }
        function WaShowResetPasswordSuccess(){
            $modal_reset_password_send.modal('hide');
            $modal_reset_password_success.modal('show');
        }

        function wa_review_items_component_load($id, $data) {
            if($data.current_page == 1){
                $('#'+$id+' .review-list').html('');
            }
            $('#'+$id+' .review-list').append($data.html);
            if($data.current_page == $data.last_page){
                $('#'+$id+' .load-more').addClass('d-none');
            }
            else{
                $('#'+$id+' .load-more').removeClass('d-none');
            }
            $('#'+$id+' .load-more').data('page', $data.current_page);
        }

        function ratingStars($score){
            $score = $score<0?0:$score;
            $score = $score>5?5:$score;
            var $stars = [];
            var $round = Math.floor($score);
            var $remain = $score - $round;
            var $half = $remain >= 0.5 ? 1: 0;
            for (var $i = 1; $i<=$round; $i++){
                $stars.push('fa fa-star');
            }
            if($half){
                $stars.push('fa fa-star-half-o');
            }
            var $missing = 5 - ($round + $half);
            for ($i = 1; $i<=$missing; $i++){
                $stars.push('fa fa-star-o');
            }
            var html = '<div class="rating-stars" title="'+$score+'">';
            $($stars).each(function () {
                html += '<i class="'+this+'"></i>'
            });
            return html+'</div>';
        }

        $(function () {
            /** test **/
            $('#modal-register .social-buttons .block-button').click(function () {
                //WaShowAccountPhoneForm();
                //return false;
            });
            /** endtest **/
            $('.show-reset-password-link').click(function () {
                WaShowResetPassForm();
                return false;
            });
            $('.show-register-form-link').click(function () {
                $modal_login.modal('hide');
                WaShowRegisterForm();
                return false;
            });
            $('.show-login-form-link-r').click(function () {
                $modal_register.modal('hide');
                $modal_login.modal('show');
                return false;
            });
            $('#modal-register form').submit(function () {
                var form = $(this);
                var data = $(this).serializeObject();
                $.ajax({
                    url: '{!! route('frontend.account.register.step_one') !!}',
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    beforeSend: function () {
                        $(form).parents('.modal').addClass('loading');
                        cleanErrorMessage(form);
                    },
                    complete: function () {
                        $(form).parents('.modal').removeClass('loading')
                    },
                    success: function(json){
                        WaShowValidationAccountForm(data);
                    },
                    error: function (json) {
                        if(json.status === 400){
                            handleErrorMessage(form, {
                                responseJSON: {
                                    errors: {
                                        phone: [
                                            json.responseJSON.message
                                        ]
                                    }
                                }
                            })
                        }
                        else{
                            handleErrorMessage(form, json)
                        }
                    }
                });
                return false;
            });
            $('#modal-account-validator form').submit(function () {
                var form = $(this);
                var dt = $(form).data('data');
                var social = $(form).data('social');
                var data = $(this).serializeObject();
                data.phone = dt.phone;
                dt.code = data.code;
                if(social){
                    $.ajax({
                        url: '{!! route('frontend.social_create_account') !!}',
                        type: 'post',
                        dataType: 'json',
                        data: dt,
                        beforeSend: function () {
                            $(form).parents('.modal').addClass('loading');
                            cleanErrorMessage(form);
                        },
                        complete: function () {
                            $(form).parents('.modal').removeClass('loading')
                        },
                        success: function(json){
                            if(json === true){
                                WaShowSuccessForm({
                                    redirect: '{!! Request::url() !!}'
                                });
                            }
                            else{
                                handleErrorMessage(form, {
                                    responseJSON: {
                                        errors: {
                                            code: [
                                                'Sai m?? x??c nh???n'
                                            ]
                                        }
                                    }
                                })
                            }
                        },
                        error: function (json) {
                            if(json.status === 400){
                                handleErrorMessage(form, {
                                    responseJSON: {
                                        errors: {
                                            code: [
                                                json.responseJSON.message
                                            ]
                                        }
                                    }
                                })
                            }
                            else{
                                handleErrorMessage(form, json)
                            }
                        }
                    });
                }
                else{
                    $.ajax({
                        url: '{!! route('frontend.account.register.step_two') !!}',
                        type: 'post',
                        dataType: 'json',
                        data: dt,
                        beforeSend: function () {
                            $(form).parents('.modal').addClass('loading');
                            cleanErrorMessage(form);
                        },
                        complete: function () {
                            $(form).parents('.modal').removeClass('loading')
                        },
                        success: function(json){
                            if(json){
                                WaShowSuccessForm({
                                    redirect: '{!! Request::url() !!}'
                                });
                            }
                            else{
                                handleErrorMessage(form, {
                                    responseJSON: {
                                        errors: {
                                            code: [
                                                'Sai m?? x??c nh???n'
                                            ]
                                        }
                                    }
                                })
                            }
                        },
                        error: function (json) {
                            if(json.status === 400){
                                handleErrorMessage(form, {
                                    responseJSON: {
                                        errors: {
                                            code: [
                                                json.responseJSON.message
                                            ]
                                        }
                                    }
                                })
                            }
                            else{
                                handleErrorMessage(form, json)
                            }
                        }
                    });
                }
                return false;
            });

            $('.modal-register-step .refresh-verify-code').click(function(){
                var form = $(this).parents('.modal-body').find('form');
                var data = form.data('data');
                $.ajax({
                    url: '{!! route('frontend.refresh_verify_code') !!}',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        phone: data.phone
                    },
                    beforeSend: function () {
                        $(form).parents('.modal').addClass('loading');
                        cleanErrorMessage(form);
                    },
                    complete: function () {
                        $(form).parents('.modal').removeClass('loading')
                    },
                    success: function(json){
                        alert('???? g???i th??nh c??ng')
                    },
                    error: function (json) {
                        if(json.status === 400){
                            handleErrorMessage(form, {
                                responseJSON: {
                                    errors: {
                                        code: [
                                            json.responseJSON.message
                                        ]
                                    }
                                }
                            })
                        }
                        else{
                            handleErrorMessage(form, json)
                        }
                    }
                });
                return false;
            });

            $('#modal-account-phone form').submit(function () {
                var form = $(this);
                var data = $(form).serializeObject();
                var dt = $(form).data('data');
                data.token = dt.token;
                $.ajax({
                    url: '{!! route('frontend.social_add_phone') !!}',
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    beforeSend: function () {
                        cleanErrorMessage(form);
                        $(form).parents('.modal').addClass('loading');
                    },
                    complete: function () {
                        $(form).parents('.modal').removeClass('loading');
                    },
                    success: function(json){
                        //console.log(json);
                        WaShowValidationAccountForm({
                            phone: data.phone,
                            token: data.token
                        }, true);
                    },
                    error: function (json) {
                        if(json.status === 400){
                            handleErrorMessage(form, {
                                responseJSON: {
                                    errors: {
                                        phone: [
                                            json.responseJSON.message
                                        ]
                                    }
                                }
                            })
                        }
                        else{
                            handleErrorMessage(form, json)
                        }
                    }
                });
                return false;
            });

            $('#modal-reset-password form').submit(function () {
                //WaShowResetPassSendForm();
                var form = $(this);
                var data = $(this).serializeObject();
                $.ajax({
                    url: '{!! route('frontend.request_password_reset') !!}',
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    beforeSend: function () {
                        cleanErrorMessage(form);
                    },
                    complete: function () {

                    },
                    success: function(json){
                        //console.log(json);
                        WaShowResetPassSendForm(data);

                    },
                    error: function (json) {
                        if(json.status === 400){
                            handleErrorMessage(form, {
                                responseJSON: {
                                    errors: {
                                        phone: [
                                            json.responseJSON.message
                                        ]
                                    }
                                }
                            })
                        }
                        else{
                            handleErrorMessage(form, json)
                        }
                    }
                });
                return false;
            });


            $('#modal-reset-password-send form').submit(function () {
                //
                var form = $(this);
                var data = $(this).serializeObject();
                //console.log(data);
                var send_data = form.data('data');
                send_data.code = data.code;
                //console.log(send_data);
                $.ajax({
                    url: '{!! route('frontend.request_password_reset_save') !!}',
                    type: 'post',
                    dataType: 'json',
                    data: send_data,
                    beforeSend: function () {
                        cleanErrorMessage(form);
                        $(form).parents('.modal').addClass('loading');
                    },
                    complete: function () {
                        $(form).parents('.modal').removeClass('loading');
                    },
                    success: function(json){
                        if(json){
                            WaShowResetPasswordSuccess({
                                redirect: '{!! Request::url() !!}'
                            });
                        }
                        else{
                            handleErrorMessage(form, {
                                responseJSON: {
                                    errors: {
                                        code: [
                                            'Sai m?? x??c nh???n'
                                        ]
                                    }
                                }
                            })
                        }
                    },
                    error: function (json) {
                        if(json.status === 400){
                            //console.log(json);
                            handleErrorMessage(form, {
                                responseJSON: {
                                    errors: {
                                        code: [
                                            json.responseJSON.message
                                        ]
                                    }
                                }
                            })
                        }
                        handleErrorMessage(form, json)
                    }
                });
                return false;
            });
        });
        @if(me())
        function loadNotificationIndicator() {
            $.ajax({
                url: '{!! route('frontend.account.notification.check') !!}',
                type: 'get',
                dataType: 'json',
                success: function (json) {
                    if(json){
                        $('.check-notification-indicator').addClass('has-notify');
                    }
                    else{
                        $('.check-notification-indicator').removeClass('has-notify');
                    }
                },
                error: function () {
                    $('.check-notification-indicator').removeClass('has-notify');
                }
            });
        }
        loadNotificationIndicator();
        @endif
    </script>

    <script type="text/javascript">
        $(function () {
            $(document).ready(function(){
                $('.show-login-form-link').click(function (e) {
                    e.stopPropagation();
                    // $('#float-form-login').toggleClass('active');
                    $modal_login.modal('show');
                    $('#page-header-mobile .menu').removeClass('active');
                    return false;
                });
                //$("#page-header.device-desktop, #page-header-mobile").sticky({topSpacing:0});
                $('body').on('click', function (e) {
                    var c = $('#page-header-mobile .menu.active').length;
                    $('#page-header-mobile .menu').removeClass('active');
                    if(c){
                        return false;
                    }
                });
                $('#page-header-mobile .menu .btn-show-menu').click(function (e) {
                    e.stopPropagation();
                    if(!$(this).parents('.menu').hasClass('active')){
                        $('#page-header-mobile .menu').removeClass('active');
                        $(this).parents('.menu').addClass('active');
                    }
                    else{
                        $('#page-header-mobile .menu').removeClass('active');
                    }
                });
                $('#page-header-mobile .menu').click(function (e) {
                    e.stopPropagation();
                });

                function getUserCity(city){
                    $.ajax({
                        url: '{!! route('api.location_find') !!}',
                        method: 'post',
                        data: {
                            find: city
                        },
                        success: function (rs) {
                            localStorage.setItem('user_location', rs.id);
                            console.log('detected location', rs.id);
                            $('#home-location-selector').val(rs.id).trigger('change')
                        }
                    })
                }
                function initLocatorService(){
                    var id = localStorage.getItem('user_location');
                    if(id){
                        if(id != null){
                            $('#home-location-selector').val(id).trigger('change')
                            return;
                        }
                    }
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            var latLng = position.coords.latitude+','+position.coords.longitude;
                            console.log(latLng);
                            $.ajax({
                                url: 'https://maps.googleapis.com/maps/api/geocode/json?latlng='+latLng+'&key=AIzaSyDdFnvCEcIXBBn14aXPaunDzHkGXrv-910&language=vi',
                                success: function (rs) {
                                    if(rs.results){
                                        $(rs.results).each(function(){
                                            var types = this.types;
                                            if(types.indexOf('administrative_area_level_1')>-1){
                                                var city = this.formatted_address.toLowerCase().replace(', vi???t nam', '');
                                                getUserCity(city);
                                            }
                                        });
                                    }
                                }
                            });
                        }, function (e) {
                            console.log(e);
                        });
                    }
                }
                initLocatorService();
            });
        })
    </script>
@endpush
@include(getThemeViewName('includes.google_map_api'))
