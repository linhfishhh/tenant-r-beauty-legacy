@enqueueCSS('select2', getThemeAssetUrl('libs/select2/css/select2.min.css'), 'bootstrap')
@enqueueJS('select2', getThemeAssetUrl('libs/select2/js/select2.full.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueCSS('cart-step-2-page', getThemeAssetUrl('libs/styles/cart-step-2.css'), 'cart')
@extends(getThemeViewName('test.cart.master'))
@section('content')
    @php
        $step = 2;
        $cart_title = 'Phương thức thanh toán';
        $cart_desc = 'Bạn hãy chọn cho mình một phương thức thanh toán phù hợp nhất.';
    @endphp
@section('cart_content')
    <div class="cart-step-2">
        <div class="row">
            <div class="col-md-6">
                <div class="payment-methods">
                    <div class="payment-list">
                        <div class="item">
                            <label class="radio-container">
                                <input type="radio" name="radio" checked="checked">
                                <span class="checkmark"></span>
                                <div class="title">Thanh toán tại salon</div>
                                <div class="desc">Hỗ trợ thanh toán tiền mặt / Thẻ (Tùy thuộc vào từng salon)</div>
                            </label>
                        </div>
                        <div class="item nganluong">
                            <label class="radio-container">
                                <input type="radio" name="radio">
                                <span class="checkmark"></span>
                                <div class="title">Thanh toán trực tuyến qua nganluong.vn</div>
                                <div class="desc">Hỗ trợ thanh toán thẻ ATM, thẻ ghi nợ, thẻ tín dụng, chuyển khoản</div>
                            </label>
                        </div>
                    </div>
                    <div class="addresses">
                        <div class="block-title">Thông tin thanh toán</div>
                        <div class="item nganluong">
                            <label class="radio-container">
                                <input type="radio" name="radio2" checked="checked">
                                <span class="checkmark"></span>
                                <div class="title">Thông tin thanh toán mặc định</div>
                                <div class="desc">
                                    <div>Họ tên: Nguyễn Văn A</div>
                                    <div>Email: nguyenvana@gmail.com</div>
                                    <div>Số ĐT: 0956588965</div>
                                    <div>Địa chỉ: 48A, Lý Tự Trọng, Quận Ninh Kiều, TP. Cần Thơ</div>
                                </div>
                            </label>
                        </div>
                        <div class="item">
                            <label class="radio-container">
                                <input type="radio" name="radio2">
                                <span class="checkmark"></span>
                                <div class="title">Thông tin thanh toán khác</div>
                                <div class="addition-address">
                                    <div class="field">
                                        <input spellcheck="false" autocomplete="off" placeholder="Họ và tên">
                                    </div>
                                    <div class="field">
                                        <input spellcheck="false" autocomplete="off" placeholder="Điện thoại">
                                    </div>
                                    <div class="field">
                                        <input type="text" spellcheck="false" autocomplete="off" placeholder="Số nhà, ngỏ, đường...">
                                    </div>
                                    <div class="field">
                                        <select data-width="100%" name="tinh_thanh_pho_id" class="form-control"></select>                                </div>
                                    <div class="field">
                                        <select data-width="100%" name="quan_huyen_id" class="form-control"></select>
                                    </div>
                                    <div class="field">
                                        <select data-width="100%" name="phuong_xa_thi_tran_id" class="form-control"></select>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="cart-detail">
                    <div class="time-date">
                        <div class="time">
                            15:30
                        </div>
                        <div class="date">
                            <div>Thứ sáu</div>
                            <div>20/04/2018</div>
                        </div>
                    </div>
                    <div class="salon-name">
                        Salon Tóc Tây
                    </div>
                    <div class="cart-items">
                        @php
                            $items = [
                                [
                                    'title' => 'Nhộm tóc',
                                    'quantity' => 1,
                                    'price' => '100'
                                ],
                                [
                                    'title' => 'Tạo kiểu tóc',
                                    'quantity' => 1,
                                    'price' => '350'
                                ],
                            ];
                        @endphp
                        @foreach($items as $item)
                            <div class="item">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="title">{!! $item['title'] !!}</div>
                                        <div class="quantity">Số lượng: {!! $item['quantity'] !!}</div>
                                    </div>
                                    <div class="col-5">
                                        <div class="price">
                                            {!! $item['price'] !!}K
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="item coupon">
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
                                        450K
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form action="{!! route('test.cart.3') !!}">
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
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@endsection
@push('page_footer_js')
    <script type="text/javascript">
        $(function () {
            $('select[name=phuong_xa_thi_tran_id]').select2({
                width: "100%",
                placeholder: '{!! __('Chọn phường/xã/thị trấn') !!}',
                ajax: {
                    url: '{!! route('info.phuong_xa.list') !!}',
                    dataType: 'json',
                    data: function (params) {
                        var v = $('select[name=quan_huyen_id]').val();
                        var query = {
                            search: params.term,
                            page: params.page || 1,
                            maqh: v
                        };
                        return query;
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        var items = [];
                        $.each(data.data, function () {
                            items.push({
                                id: this.id,
                                text: this.name
                            });
                        });
                        return {
                            results: items,
                            pagination: {
                                more: (params.page * 30) < data.total
                            }
                        };
                    }
                }
            }).on('change', function () {
                var v = $('select[name=quan_huyen_id]').val();
                if (!v){
                    $(this).prop("disabled", true)
                }
                else{
                    $(this).prop("disabled", false)
                }
            });

            $('select[name=quan_huyen_id]').select2({
                width: "100%",
                placeholder: '{!! __('Chọn quận/huyện') !!}',
                ajax: {
                    url: '{!! route('info.quan_huyen.list') !!}',
                    dataType: 'json',
                    data: function (params) {
                        var v = $('select[name=tinh_thanh_pho_id]').val();
                        var query = {
                            search: params.term,
                            page: params.page || 1,
                            matp: v
                        };
                        return query;
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        var items = [];
                        $.each(data.data, function () {
                            items.push({
                                id: this.id,
                                text: this.name
                            });
                        });
                        return {
                            results: items,
                            pagination: {
                                more: (params.page * 30) < data.total
                            }
                        };
                    }
                }
            }).on('change', function () {
                var v = $('select[name=tinh_thanh_pho_id]').val();
                if (!v){
                    $(this).prop("disabled", true)
                }
                else{
                    $(this).prop("disabled", false)
                }
                $('select[name=phuong_xa_thi_tran_id]').val(null).trigger('change');
            });

            $('select[name=tinh_thanh_pho_id]').select2({
                width: "100%",
                placeholder: '{!! __('Chọn tỉnh/thành phố') !!}',
                ajax: {
                    url: '{!! route('info.tinh_thanh_pho.list') !!}',
                    dataType: 'json',
                    data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }

                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        var items = [];
                        $.each(data.data, function () {
                            items.push({
                                id: ''+this.id,
                                text: this.name
                            });
                        });
                        return {
                            results: items,
                            pagination: {
                                more: (params.page * 30) < data.total
                            }
                        };
                    }
                }
            }).on('change', function () {
                $('select[name=quan_huyen_id]').val(null).trigger('change');
            }).trigger('change');
        });
    </script>
@endpush