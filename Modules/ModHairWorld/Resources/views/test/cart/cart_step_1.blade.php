@enqueueCSS('datepicker', getThemeAssetUrl('libs/datepicker/css/bootstrap-datepicker.standalone.min.css'), 'bootstrap')
@enqueueJS('datepicker', getThemeAssetUrl('libs/datepicker/js/bootstrap-datepicker.min.js'), JS_LOCATION_HEAD, 'bootstrap')
@enqueueJS('datepicker-language', getThemeAssetUrl('libs/datepicker/js/locales/bootstrap-datepicker.vi.min.js'), JS_LOCATION_HEAD, 'datepicker')
@enqueueCSS('cart-step-1-page', getThemeAssetUrl('libs/styles/cart-step-1.css'), 'cart')
@extends(getThemeViewName('test.cart.master'))
@section('content')
    @php
        $step = 1;
        $cart_title = 'Thông tin dịch vụ';
        $cart_desc = 'Bạn hãy chọn cho mình thời gian hợp lý nhất.';
    @endphp
    @section('cart_content')
        <div class="cart-step-1">
            <div class="row">
                <div class="col-md-6">
                    <div class="cart-date-time">
                        <div class="cart-date">
                            <div class="datepicker-selector"></div>
                            <input type="hidden" id="cart-date">
                        </div>
                        <div class="cart-time date-not-select">
                            <div class="not-select-date time-message">
                                <img src="{!! getThemeAssetUrl('img/cdate_not.png') !!}">
                                <div class="message">
                                    Vui lòng chọn ngày bạn muốn salon sẽ phục vụ cho bạn.
                                </div>
                            </div>
                            <div class="not-aval time-message">
                                <img src="{!! getThemeAssetUrl('img/cdate_no.png') !!}">
                                <div class="message">
                                    Dịch vụ này hiện không có sẵn
                                    trong thời gian này, bạn vui lòng
                                    chọn ngày khác.
                                </div>
                            </div>
                            <div class="time-list">
                                @php
                                    $items = [
                                        '08:30',
                                        '09:00',
                                        '09:30',
                                        '10:00',
                                        '10:30',
                                        '11:00',
                                        '11:30',
                                        '12:00',
                                        '12:30',
                                        '13:00',
                                        '13:30',
                                    ];
                                @endphp
                                @foreach($items as $item)
                                    <div class="item">
                                        <label class="radio-container">
                                            <input type="radio" name="radio">
                                            <span class="checkmark"></span>
                                            <div class="time">{!! $item !!}</div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <form action="{!! route('test.cart.2') !!}">
                    <div class="order-info">
                        <div class="order-detail">
                            <div class="salon-name">Salon Tóc Tây</div>
                            <div class="detail-list">
                                @php
                                    $items = [
                                        [
                                            'title' => 'Nhộm tóc',
                                            'time' => '40',
                                            'quantity' => 1,
                                            'price' => '100'
                                        ],
                                        [
                                            'title' => 'Tạo kiểu tóc',
                                            'time' => '30',
                                            'quantity' => 1,
                                            'price' => '350'
                                        ],
                                    ];
                                @endphp
                                @foreach($items as $item)
                                    <div class="detail">
                                        <div class="title">
                                            {!! $item['title'] !!}
                                        </div>
                                        <div class="row">
                                            <div class="col-7">
                                                <span class="time">{!! $item['time'] !!} phút</span>
                                                <div class="input">
                                                    <div class="sub">-</div>
                                                    <input value="{!! $item['quantity'] !!}">
                                                    <div class="add">+</div>
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <div class="price-delete">
                                                    <span class="price">{!! $item['price'] !!}K</span>
                                                    <span class="delete"><i class="fa fa-remove"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="order-action">
                            <div class="add-more">
                                <a href="{!! route('test.salon.post') !!}">
                                    <i class="fa fa-plus-circle"></i>
                                    <span>Thêm dịch vụ khác từ salon này</span>
                                </a>
                            </div>
                            <div class="submit">
                                <button>
                                    Tiếp tục
                                </button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection
@endsection
@push('page_footer_js')
    <script type="text/javascript">
        $('.order-detail .detail .input .add').click(function () {
            var input = $(this).parent().find('input');
            var old = $(input).val();
            if(!isNaN(old)){
                $(input).val((old*1)+1)
            }

        });
        $('.order-detail .detail .input .sub').click(function () {
            var input = $(this).parent().find('input');
            var old = $(input).val();
            if(!isNaN(old)){
                var newvar = (old*1)-1;
                if(newvar<=0){
                    newvar = 1;
                }
                $(input).val(newvar);
            }

        });
        $('.cart-date-time .datepicker-selector').datepicker({
            language: 'vi'
        });
        $('.cart-date-time .datepicker-selector').on('changeDate', function() {
            $('.cart-date-time .cart-time').removeClass('date-not-select');
            $('.cart-date-time .cart-time').removeClass('no-time-aval');
            $('.cart-date-time .cart-time').addClass('allow-select-time');
            $('#cart-date').val(
                $('.cart-date-time .datepicker-selector').datepicker('getFormattedDate')
            );
        });
    </script>
@endpush