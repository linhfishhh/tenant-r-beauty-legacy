@enqueueCSS('cart', getThemeAssetUrl('libs/styles/cart.css'), 'master-page')
@php
    $dark_theme = 1;
@endphp
@extends(getThemeViewName('master'))
@section('page_content')
    @yield('cart_top')
    <div class="main-content">
        <div class="container">
            <div class="cart-wrapper">
                <div class="cart-header">
                    <div class="cart-step">
                        Bước {!! $step !!}/3
                    </div>
                    <div class="cart-title">
                        Đặt chỗ
                    </div>
                    <div class="cart-sub-title">
                        {!! $cart_title !!}
                    </div>
                    <div class="cart-desc">
                        {!! $cart_desc !!}
                    </div>
                </div>
                <div class="cart-content">
                    @yield('cart_content')
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