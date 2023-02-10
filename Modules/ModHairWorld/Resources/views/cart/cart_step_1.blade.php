@enqueueCSS('datepicker', getThemeAssetUrl('libs/datepicker/css/bootstrap-datepicker.standalone.min.css'), 'bootstrap')
@enqueueJS('datepicker', getThemeAssetUrl('libs/datepicker/js/bootstrap-datepicker.min.js'), JS_LOCATION_HEAD, 'bootstrap')
@enqueueJS('datepicker-language', getThemeAssetUrl('libs/datepicker/js/locales/bootstrap-datepicker.vi.min.js'), JS_LOCATION_HEAD, 'datepicker')
@enqueueJS('moment', getThemeAssetUrl('libs/moment.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueCSS('cart-step-1-page', getThemeAssetUrl('libs/styles/cart-step-1.css'), 'cart')
@extends(getThemeViewName('cart.master'))
@section('content')
    @php
        $step = 1;
        $cart_title = 'Thông tin dịch vụ';
        $cart_desc = 'Bạn hãy chọn cho mình thời gian hợp lý nhất.';
        /** @var \Modules\ModHairWorld\Entities\Salon $salon */
        /** @var \Modules\ModHairWorld\Entities\SalonService[]|\Illuminate\Database\Eloquent\Collection $items */


    @endphp
    @section('cart_content')
        <div class="cart-step-1">
            <form action="{!! route('frontend.cart.2') !!}" id="form-cart-step-1">
            <div class="row">
                <div class="col-md-6">
                    <div class="cart-date-time">
                        <div class="cart-date">
                            <div class="datepicker-selector"></div>
                            <input type="hidden" id="cart-date" name="date">
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
                            <div class="time-list row" id="cart-time-list">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="order-info">
                        <div class="order-detail">
                            <div class="salon-name">{!! $salon->name !!}</div>
                            <div class="detail-list">
                                @foreach($items as $item)
                                    @php
                                    $option_id = session()->get('wa_cart_items')[$item->id]['option_id'];
                                    $amount = session()->get('wa_cart_items')[$item->id]['amount'];
                                    @endphp
                                    <div class="detail" data-id="{!! $item->id !!}">
                                        <div class="title">
                                            {!! $item->getOptionName($option_id) !!}
                                        </div>
                                        <div class="row">
                                            <div class="col-8">
                                                <span class="time">{!! $item->timeText() !!}</span>
                                                <div class="input">
                                                    <div class="sub">-</div>
                                                    <input readonly value="{!! $amount!!}">
                                                    <div class="add">+</div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="price-delete">
                                                    <span class="price">{!! $item->getOptionFinalPriceHtml($option_id) !!}</span>
                                                    <span class="delete" data-id="{!! $item->id !!}"><i class="fa fa-remove"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="order-action">
                            <div class="add-more">
                                <a href="{!! $salon->url() !!}">
                                    <i class="fa fa-plus-circle"></i>
                                    <span>Thêm dịch vụ khác từ salon này</span>
                                </a>
                            </div>
                            <div class="submit">
                                <button class="btn" id="cart-go-next">
                                    Tiếp tục
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    @endsection
@endsection
@push('page_footer_js')
    <template id="cart-time-item-tpl">
        <div class="item col-md-4">
            <label class="radio-container">
                <input {checked} value="{time}" type="radio" name="time">
                <span class="checkmark"></span>
                <div class="time">{text}</div>
            </label>
        </div>
    </template>
    <script type="text/javascript">
        var dp = $('.cart-date-time .datepicker-selector').datepicker({
            language: 'vi',
            startDate: '{!! now()->format('d/m/Y') !!}',
            daysOfWeekDisabled: {!! json_encode(array_values($disabled_week_days)) !!},
            weekStart: 1
        });
        //dp.setDate('{!! now()->addDay(1)->format('d/m/Y') !!}');
        $(function () {

            var $salon_time = {
                @foreach($salon->times as $time)
                {!! $time->weekday !!} : {
                    'start': '{!! $time->start !!}',
                    'end': '{!! $time->end !!}'
                },
                @endforeach
            };

            function updateCart(node){
                var amount = $(node).find('input').val();
                var id = $(node).data('id');
                $.ajax({
                    url: '{!! route('frontend.cart.update_amount') !!}',
                    type: 'post',
                    dataType: 'json',
                    data:{
                        amount: amount,
                        id: id
                    },
                    beforeSend: function () {
                        $('#form-cart-step-1').addClass('loading');
                    },
                    complete: function () {
                        $('#form-cart-step-1').removeClass('loading');
                    },
                    success: function (json) {
                        if(json){
                        }
                    }
                });
            }

            $('.order-detail .detail .input .add').click(function () {
                var input = $(this).parent().find('input');
                var old = $(input).val();
                if(!isNaN(old)){
                    $(input).val((old*1)+1)
                }
                var node = $(this).parents('.detail');
                updateCart(node);
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
                var node = $(this).parents('.detail');
                updateCart(node);
            });

            $('.cart-date-time .datepicker-selector').on('changeDate', function(e) {
                $('.cart-date-time .cart-time').removeClass('date-not-select');
                $('.cart-date-time .cart-time').removeClass('no-time-aval');
                $('.cart-date-time .cart-time').addClass('allow-select-time');
                $('#cart-date').val(
                    $('.cart-date-time .datepicker-selector').datepicker('getFormattedDate')
                );
                var weekday = e.date.getDay();
                if(weekday == 0){
                    weekday = 7;
                }
                var data = $salon_time[weekday];
                var select = moment(e.date);
                var selectText = select.format('Y/M/D');
                var start = moment(selectText+' '+data.start, 'Y/M/D HH:mm:ss');
				var md = start.format('mm');
				var ma = 0;
				if(md<30){
					ma = 30 - md;
				}
				else if(md>30){
					ma = 60 - md;
				}
				//start = start.add(ma, 'm');
                var end = moment(selectText+' '+data.end, 'Y/M/D HH:mm:ss');
                var test = start;
                var c = 0;
                $('#cart-time-list').html('');
                while(test.diff(end, 'minutes')<0){
                    //console.log(test.diff(moment(), 'minutes'));
                    if(test.diff(moment(), 'minutes')<60){
                        test = test.add(30, 'm');
                        continue;
                    }
                    var tpl = $('#cart-time-item-tpl').html();
                    tpl = tpl.replace(/{time}/g, test.format('HH:mm')+':00');
                    tpl = tpl.replace(/{text}/g, test.format('HH:mm'));
                    if(c == 0){
                        tpl = tpl.replace(/{checked}/g, 'checked="checked"');
                    }
                    else{
                        tpl = tpl.replace(/{checked}/g, '');
                    }
                    $('#cart-time-list').append(tpl);
                    test = test.add(30, 'm');
                    c++;
                }
                if(c === 0){
                    $('.cart-time .not-aval').show();
                    $('#cart-time-list').hide();
                }
                else{
                    $('.cart-time .not-aval').hide();
                    $('#cart-time-list').show();
                }
            });
            $('.cart-date-time .datepicker-selector').datepicker('setDate', '{!! now()->addDay(1)->format('d/m/Y') !!}');

            $('#form-cart-step-1').submit(function () {
                var timecheck = $(this).find('input[name=time]:checked').length;
                if(timecheck == 0){
                    swal("", "Vui lòng chọn ngày giờ sử dụng dịch vụ", "warning");
                    return false;
                }
                var detailcheck = $(this).find('.order-info .detail').length;
                if(detailcheck == 0){
                    swal("", "Bạn chưa chọn dịch vụ nào để đặt chỗ", "warning");
                    return false;
                }
                var data = $(this).serializeObject();
                $.ajax({
                    url: "{!! route('frontend.cart.1.save') !!}",
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    beforeSend: function () {
                        $('#form-cart-step-1').addClass('loading');
                    },
                    complete: function () {
                        $('#form-cart-step-1').removeClass('loading');
                    },
                    success: function (json) {
                        if(json){
                            window.location = '{!! route('frontend.cart.2') !!}';
                        }
                        //console.log(json);
                    }
                });
                return false;
            });

            $('#form-cart-step-1 .order-info .detail .delete').click(function () {
                var id = $(this).data('id');
                var node = $(this).parents('.detail');
                console.log(node);
                $.ajax({
                    url: '{!! route('frontend.cart.remove_item') !!}',
                    type: 'post',
                    dataType: 'json',
                    data: {
                      id: id
                    },
                    beforeSend: function () {
                        $('#form-cart-step-1').addClass('loading');
                    },
                    complete: function () {
                        $('#form-cart-step-1').removeClass('loading');
                    },
                    success: function (json) {
                        if(json){
                            $(node).fadeOut(function () {
                                node.remove();
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush