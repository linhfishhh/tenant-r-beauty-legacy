@enqueueCSS('select2', getThemeAssetUrl('libs/select2/css/select2.min.css'), 'bootstrap')
@enqueueJS('select2', getThemeAssetUrl('libs/select2/js/select2.full.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueCSS('cart-step-2-page', getThemeAssetUrl('libs/styles/cart-step-2.css'), 'cart')
@extends(getThemeViewName('cart.master'))

@php
    $step = 2;
    $cart_title = 'Thanh toán không thành công';
    $cart_desc = '';
@endphp

@section('cart_content')
    <div class="payment-error-content">
        <div class="payment-error-message">
            {!! $message !!}
        </div>
        <div class="payment-buttons">
            @if($retry)
                <div>
                    <a class="payment-button" href="{{$retry}}">Thử thanh toán lại</a>
                </div>
            @endif
            @if($cancel)
                    <div>
                        <a class="payment-button" href="{{$cancel}}">Huỷ đặt chỗ</a>
                    </div>
            @endif
        </div>
    </div>
@endsection
@push('page_footer_js')
@endpush