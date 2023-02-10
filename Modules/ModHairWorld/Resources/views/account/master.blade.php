@enqueueCSS('account-menu', getThemeAssetUrl('libs/styles/account-menu.css'), 'master-page')
@php
$dark_theme = 1;
$menu_mode = 1;
@endphp
@extends(getThemeViewName('master'))
@section('page_content')
    <div class="main-content">
        <div class="container">
            <a href="{!! route('frontend.account.profile') !!}" class="account-info d-block">
                <div class="name">{!! me()->name !!}</div>
                <div class="date">Gia nhập ngày {!! me()->created_at->format('d/m/Y') !!}</div>
            </a>
            <div class="row">
                <div class="col-md-3">
                    <div class="side-menu">
                        @php
                        $items = [
                            [
                                'icon' => 'fa fa-list-alt',
                                'title' => 'Chỉnh sửa hồ sơ',
                                'link' => 'frontend.account.edit'
                            ],
                            [
                                'icon' => 'fa fa-bell',
                                'title' => 'Thông báo',
                                'link' => 'frontend.account.notification'
                            ],
                            [
                                'icon' => 'fa fa-calendar',
                                'title' => 'Lịch sử đặt chỗ',
                                'link' => 'frontend.account.history'
                            ],
                            [
                                'icon' => 'fa fa-credit-card',
                                'title' => 'Thông tin thanh toán',
                                'link' => 'frontend.account.payment'
                            ],
                            [
                                'icon' => 'fa fa-gift',
                                'title' => 'Mời bạn bè',
                                'link' => 'frontend.account.share'
                            ],
                            [
                                'icon' => 'fa fa-heart',
                                'title' => 'Yêu thích',
                                'link' => 'frontend.account.fav_Salon',
                                'link2' => 'frontend.account.fav_showcase',
                            ],
                            [
                                'icon' => 'fa fa-exclamation-circle',
                                'title' => 'Trợ giúp',
                                'link' => 'frontend.account.help'
                            ],
                            [
                                'icon' => 'fa fa-lock',
                                'title' => 'Thay đổi mật khẩu',
                                'link' => 'frontend.account.reset_password'
                            ],
                        ];
                        @endphp
                        <ul>
                            @foreach($items as $item)
                                <li class="{!! Route::currentRouteName() == $item['link']||(isset($item['link2']) && ($item['link2']==Route::currentRouteName())) ? 'active':'' !!}">
                                    <a href="{!! $item['link']!='#'?route($item['link']):'#' !!}" class="d-block">
                                        <i class="{!! $item['icon'] !!}"></i>
                                        <span>{!! $item['title'] !!}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-md-9">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <div class="page-bread-cumber">
        <div class="container">
            <a href="#">Trang chủ</a> / <a href="#">Xu hướng tóc</a>
        </div>
    </div>
@endsection