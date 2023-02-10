@enqueueCSS('payment-page', getThemeAssetUrl('libs/styles/payment.css'), 'account-menu')
@extends(getThemeViewName('account.master'))
@section('content')
    @php
    /** @var \Modules\ModHairWorld\Entities\UserExtra $info */
    @endphp
    <div class="content-box">
        <div class="content-title">Thông tin thanh toán</div>
        <div class="content-body">
            <form id="payment-page-form">
                <div class="message">
                    Chọn phương thức thanh toán mặc định cho tài khoản của bạn
                </div>
                <div class="options">
                    @foreach(\Modules\ModHairWorld\Entities\SalonOrder::getPaymentMethods() as $method)
                        <div class="option">
                            <label class="radio-container payment-type">
                            <input {!! $info->payment_method==$method['id']?'checked="checked"':'' !!} value="{!! $method['id'] !!}" type="radio" name="payment_type">
                            <span class="checkmark"></span>
                            <div class="title">{!! $method['title'] !!}</div>
                            <div class="desc">{!! $method['desc'] !!}</div>
                        </label>
                        </div>
                    @endforeach
                </div>
            </form>
        </div>
    </div>
@endsection
@push('page_footer_js')
    <script type="text/javascript">
        $(function () {
            $('#payment-page-form .payment-type input').click(function (e) {
                e.stopPropagation();
                $('#payment-page-form').submit();
            });
            $('#payment-page-form').submit(function () {
                var method = $(this).find('input[name=payment_type]:checked').val();
                var form = $(this);
                $.ajax({
                    url: '{!! route('frontend.account.payment.save') !!}',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        payment_method: method
                    },
                    beforeSend: function () {
                        $(form).addClass('loading');
                    },
                    complete: function () {
                        $(form).removeClass('loading');
                    },
                    success: function (json) {

                    }
                });
                return false;
            });
        });
    </script>
@endpush