@enqueueCSS('account-menu', getThemeAssetUrl('libs/styles/account-menu.css'), 'master-page')
@php
$dark_theme = 1;
@endphp
@extends(getThemeViewName('master'))
@section('page_content')
    <div class="main-content">
        <div class="container">
            <div class="account-info">
                <div class="name">Thạch Minh Trang</div>
                <div class="date">Gia nhập ngày 30/04/2018</div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="side-menu">
                        @php
                        $items = [
                            [
                                'icon' => 'fa fa-list-alt',
                                'title' => 'Chỉnh sửa hồ sơ',
                                'link' => 'test.account.edit'
                            ],
                            [
                                'icon' => 'fa fa-bell',
                                'title' => 'Thông báo',
                                'link' => 'test.account.notification'
                            ],
                            [
                                'icon' => 'fa fa-calendar',
                                'title' => 'Lịch sử đặt chỗ',
                                'link' => 'test.account.history'
                            ],
                            [
                                'icon' => 'fa fa-credit-card',
                                'title' => 'Thông tin thanh toán',
                                'link' => 'test.account.payment'
                            ],
                            [
                                'icon' => 'fa fa-gift',
                                'title' => 'Mời bạn bè',
                                'link' => 'test.account.share'
                            ],
                            [
                                'icon' => 'fa fa-heart',
                                'title' => 'Yêu thích',
                                'link' => 'test.account.fav_Salon',
                                'link2' => 'test.account.fav_showcase',
                            ],
                            [
                                'icon' => 'fa fa-exclamation-circle',
                                'title' => 'Trợ giúp',
                                'link' => 'test.account.help'
                            ],
                            [
                                'icon' => 'fa fa-lock',
                                'title' => 'Thay đổi mật khẩu',
                                'link' => 'test.account.reset_password'
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