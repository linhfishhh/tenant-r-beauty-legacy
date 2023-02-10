@enqueueCSS('payment-page', getThemeAssetUrl('libs/styles/payment.css'), 'account-menu')
@extends(getThemeViewName('test.account.master'))
@section('content')
    <div class="content-box">
        <div class="content-title">Thông tin thanh toán</div>
        <div class="content-body">
            <form id="payment-page-form">
                <div class="message">
                    Chọn phương thức thanh toán mặc định cho tài khoản của bạn
                </div>
                <div class="options">
                    <div class="option">
                        <label class="radio-container">
                            <input type="radio" name="radio">
                            <span class="checkmark"></span>
                            <div class="title">Thanh toán trực tuyến qua nganluong.vn</div>
                            <img src="{!! getThemeAssetUrl('img/nll.png') !!}">
                        </label>
                    </div>
                    <div class="option">
                        <label class="radio-container">
                            <input type="radio" name="radio">
                            <span class="checkmark"></span>
                            <div class="title">Thanh toán tại salon</div>
                            <div class="desc">Tiền mặt / Thẻ (Tùy thuộc vào từng salon)</div>
                        </label>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection