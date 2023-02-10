@enqueueCSS('select2', getThemeAssetUrl('libs/select2/css/select2.min.css'), 'bootstrap')
@enqueueJS('select2', getThemeAssetUrl('libs/select2/js/select2.full.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueCSS('cart-step-2-page', getThemeAssetUrl('libs/styles/cart-step-2.css'), 'cart')
@extends(getThemeViewName('cart.master'))
@push('child_page_head')
    <!-- Ads -->
    <script>
        gtag('event', 'conversion', {
            'send_to': 'AW-778363075/rZJaCJCF7JcBEMPBk_MC',
            'transaction_id': ''
        });
    </script>
@endpush
@php
    $step = 2;
    $cart_title = 'Phương thức thanh toán';
    $cart_desc = 'Bạn hãy chọn cho mình một phương thức thanh toán phù hợp nhất.';
    /** @var \Modules\ModHairWorld\Entities\Salon $salon */
    /** @var \Modules\ModHairWorld\Entities\SalonService[]|\Illuminate\Database\Eloquent\Collection $items */
    /** @var \Carbon\Carbon $cart_time */
    $user_addresses = [];
    $user_extra = null;
    if(me()){
        $user_addresses = \Modules\ModHairWorld\Entities\UserAddress::whereUserId(me()->id)->with(['lv1', 'lv2', 'lv3'])->get();
        $user_extra = \Modules\ModHairWorld\Entities\UserExtra::fromUserID(me()->id);
    }
@endphp
@if(!me())
    @section('cart_top')
        <div id="cart-login">
            <div class="container">
                <div class="form-wrapper">
                    <div class="form-title-block">
                        Để đặt chỗ nhanh hơn bạn có thể <a href="#" class="show-register-form-link">Đăng ký</a> <span>(sẽ giúp bạn quản lý đơn hàng và cập nhật thông tin mới nhất của isalon)</span>
                    </div>
                    <form>
                        <div class="form-content">
                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="row field-row">
                                        <div class="col-lg-6">
                                            <div class="field">
                                                <input name="login" autocomplete="off" type="text" spellcheck="false" placeholder="Số điện thoại hoặc email">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="field">
                                                <input name="password" autocomplete="off" type="password" spellcheck="false" placeholder="Mật khẩu đăng nhập">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="buttons">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="or-div">
                                                    <div class="reset-password-link">
                                                        <a href="#" class="show-reset-password-link">Lấy lại mật khẩu</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <button style="cursor: pointer" class="login">ĐĂNG NHẬP</button>
                                            </div>
                                            <div class="col-lg-3">
                                                <button style="cursor: pointer" type="button" class="facebook">
                                                    <i class="fa fa-facebook"></i>
                                                    <span>Facebook</span>
                                                </button>
                                            </div>
                                            <div class="col-lg-3">
                                                <button style="cursor: pointer" class="google" type="button">
                                                    <i class="fa fa-google-plus"></i>
                                                    <span>Google</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection
    @push('page_footer_js')
        <script type="text/javascript">
            $(function () {
                $('#cart-login form').submit(function () {
                    var form = $(this);
                    var data = $(this).serializeObject();
                    $.ajax({
                        url: '{!! route('frontend.login.check') !!}',
                        type: 'post',
                        dataType: 'json',
                        data: data,
                        beforeSend: function(){

                        },
                        success: function (json) {
                            window.location = '{!! route('frontend.cart.2') !!}';
                        },
                        complete: function(){

                        },
                        error: function (json) {
                            handleErrorMessage(form, json);
                        }
                    });
                    return false;
                });
                $('#cart-login button.google').click(function () {
                    window.location = '{!! route('frontend.login.social', ['provider' => 'google',
                'go_to' => route('frontend.cart.2')
                ]) !!}';
                });

                $('#cart-login button.facebook').click(function () {
                    window.location = '{!! route('frontend.login.social', ['provider' => 'facebook',
                'go_to' => route('frontend.cart.2')
                ]) !!}';
                });
            })
        </script>
    @endpush
@endif
@section('cart_content')
    <form class="cart-step-2" id="form-cart-step-2">
        <div class="row">
            <div class="col-md-6">
                <div class="payment-methods">
                    <div class="payment-list">
                        <div class="item">
                            <label class="radio-container">
                                <input value="salon" type="radio" name="payment_method"
                                    @if(!$user_extra || !in_array($user_extra, ['salon', ['nganluong']]))
                                        checked="checked"
                                    @else
                                        @if($user_extra->payment_method == 'salon')
                                        checked="checked"
                                        @endif
                                    @endif
                                >
                                <span class="checkmark"></span>
                                <div class="title">Thanh toán tại salon</div>
                                <div class="desc">Hỗ trợ thanh toán tiền mặt / Thẻ (Tùy thuộc vào từng salon)</div>
                            </label>
                        </div>
                        {{--<div class="item nganluong">--}}
                            {{--<label class="radio-container">--}}
                                {{--<input value="onepay" type="radio" name="payment_method"--}}
                                   {{--@if($user_extra && $user_extra->payment_method == 'onepay')--}}
                                   {{--checked="checked"--}}
                                    {{--@endif--}}
                                {{-->--}}
                                {{--<span class="checkmark"></span>--}}
                                {{--<div class="title">Thanh toán trực tuyến qua OnePay</div>--}}
                                {{--<div class="desc">Hỗ trợ thanh toán thẻ ATM, thẻ ghi nợ, thẻ tín dụng, chuyển khoản</div>--}}
                            {{--</label>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="cart-detail">
                    <a href="{!! route('frontend.cart.1') !!}" class="time-date d-block">
                        <div class="time">
                            {!! $cart_time->format('H:i') !!}
                        </div>
                        <div class="date">
                            <div>
                                @php
                                    switch ($cart_time->dayOfWeek){
                                        case 1:
                                            $thu = 'Thứ hai';
                                            break;
                                        case 2:
                                            $thu = 'Thứ ba';
                                        break;
                                        case 3:
                                            $thu = 'Thứ tư';
                                        break;
                                        case 4:
                                            $thu = 'Thứ năm';
                                        break;
                                        case 5:
                                            $thu = 'Thứ sáu';
                                        break;
                                        case 7:
                                            $thu = 'Thứ bảy';
                                        break;
                                        default:
                                            $thu = 'Chủ nhật';
                                            break;
                                    }
                                @endphp
                                {!! $thu !!}
                            </div>
                            <div>{!! $cart_time->format('d/m/Y') !!}</div>
                        </div>
                    </a>
                    <div class="salon-name">
                        {!! $salon->name !!}
                    </div>
                    <div class="cart-items">
                        @php
                        $sum = 0;
                        @endphp
                        @foreach($items as $item)
                            @php
                            $option_id = session()->get('wa_cart_items')[$item->id]['option_id'];
                            $quantity = session()->get('wa_cart_items')[$item->id]['amount'];
                            $price = $item->getOptionFinalPrice($option_id);
                            $current_sum = $price*$quantity;
                            $sum += $current_sum;
                            @endphp
                            <div class="item">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="title">{!! $item->getOptionName($option_id) !!}</div>
                                        <div class="quantity">Số lượng: {!! $quantity !!}</div>
                                    </div>
                                    <div class="col-5">
                                        <div class="price">
                                            {!! number_format($current_sum/1000, 0, '.', '.') !!}K
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="item coupon d-none">
                            <div class="row">
                                <div class="col-7">
                                    <div class="quantity">
                                        Mã giảm giá
                                    </div>
                                    <input autocomplete="off" spellcheck="false" placeholder="Mã giảm giá">
                                </div>
                                <div class="col-5">
                                    <div class="price">
                                        0K
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item sum">
                            <div class="row">
                                <div class="col-7">
                                    <div class="quantity">
                                        Tổng tiền
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="price">
                                        {!! number_format($sum/1000, 0, '.', '.') !!}K
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="submit">
                        <button>
                            Thanh toán
                        </button>
                        <div class="note">
                            Nhấn nút thanh toán nghĩa là bạn đã đọc và đồng ý với
                            <a href="#" target="_blank">Quy định bảo mật</a>
                            và
                            <a href="#" target="_blank">Thỏa thuận sử dụng</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('page_footer_js')
    <script type="text/javascript">
        $(function () {

            $('#form-cart-step-2').submit(function () {
                @if(!me())
                $('html, body').animate({
                    scrollTop: $("body").offset().top
                }, 500, function () {
                    swal("", "Vui lòng đăng nhập để tiếp tục", "warning")
                });
                @else

                var data = $(this).serializeObject();
                $.ajax({
                    url: '{!! route('frontend.cart.2.save') !!}',
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    beforeSend: function () {
                        $('#form-cart-step-2').addClass('loading');
                    },
                    complete: function () {
                        $('#form-cart-step-2').removeClass('loading');
					
                    },
                    success: function (json) {
                        if(json){
							console.log(json);
							var id = json.substr(json.lastIndexOf('/') + 1);
							//gtag_report_conversion('{!! $salon->url() !!}', id);
							gtag_report_conversion(json, id);
                            //window.location = json;
							//console.log(id);
                        }
                    },
                    error: function (json) {
                        console.log(json);
                        if(json.status === 400){
                            swal({
                                title: "Không thể tạo đơn hàng",
                                text: json.responseJSON.message,
                                type: "warning",
                                confirmButtonClass: "btn-danger",
                                confirmButtonText: "Đã hiểu",
                            });
                        }
                    }
                });

                @endif
                    return false;
            });


        });
    </script>
@endpush