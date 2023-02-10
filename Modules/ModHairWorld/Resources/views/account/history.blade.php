@enqueueCSS('select2', getThemeAssetUrl('libs/select2/css/select2.min.css'), 'bootstrap')
@enqueueCSS('datepicker', getThemeAssetUrl('libs/datepicker/css/bootstrap-datepicker.standalone.min.css'), 'bootstrap')
@enqueueJS('datepicker', getThemeAssetUrl('libs/datepicker/js/bootstrap-datepicker.min.js'), JS_LOCATION_HEAD, 'bootstrap')
@enqueueJS('datepicker-language', getThemeAssetUrl('libs/datepicker/js/locales/bootstrap-datepicker.vi.min.js'), JS_LOCATION_HEAD, 'datepicker')
@enqueueJS('select2', getThemeAssetUrl('libs/select2/js/select2.full.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueCSS('cart-step-3-page', getThemeAssetUrl('libs/styles/cart-step-3.css'), 'bootstrap')
@enqueueCSS('history-page', getThemeAssetUrl('libs/styles/history.css'), 'account-menu')
@php
$status = request('status', -99);
@endphp
@extends(getThemeViewName('account.master'))
@section('content')
    <div class="content-box">
        <div class="content-title">Lịch sử đặt chỗ</div>
        <div class="content-body" id="main-zone">
            <form id="history-filter-form">
                <div class="filter-bar">
                    <div class="row">
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="field">
                                        <input name="start_date" class="pickdate" spellcheck="false" autocomplete="off" placeholder="Từ ngày">
                                        <i class="fa fa-calendar-o"></i>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="field">
                                        <input name="end_date" class="pickdate" spellcheck="false" autocomplete="off" placeholder="Đến ngày">
                                        <i class="fa fa-calendar-o"></i>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="field">
                                        <select name="status" id="history-status-filter">
                                            <option value="-99">Tất cả loại giao dịch</option>
                                            @foreach(\Modules\ModHairWorld\Entities\SalonOrder::getStatusList() as $key=>$value)
                                                <option {!! $status==$key?'selected="selected"':'' !!} value="{!! $key !!}">{!! $value !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="submit">
                                <button>Tìm kiếm</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="history-table table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Mã</th>
                            <th>Ngày đặt</th>
                            <th>Phục vụ bởi</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <div class="load-more d-none">
                    <i class="fa fa-caret-down"></i>
                    Tải thêm
                </div>
            </div>
        </div>
    </div>
@endsection
@push('page_footer_js')
    <div class="modal modal-register-step" tabindex="-1" role="dialog" id="modal-view-bill">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="dismiss-modal">
                        <div data-dismiss="modal" class="btn-close"><i class="fa fa-close"></i></div>
                    </div>
                    <div class="size-wrapper">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">

        $('.filter-bar select').select2({
            width: '100%',
            minimumResultsForSearch: -1
        });


        $(function () {
            $('.history-table .load-more').click(function () {
                var next = $(this).data('next');
                loadOrderItem(next);
            });

            function loadOrderItem($from) {
                var data = $('#history-filter-form').serializeObject();
                data.from = $from;
                console.log(data);
                $.ajax({
                    url: '{!! route('frontend.account.history') !!}',
                    type:'get',
                    dataType: 'json',
                    data: data,
                    beforeSend: function () {
                        $('#main-zone').addClass('loading');
                    },
                    complete: function () {
                        $('#main-zone').removeClass('loading');
                    },
                    success: function (json) {
                        if($from == -1){
                            $('.history-table tbody').html('');
                        }
                        var html = json.html;
                        $('.history-table tbody').append(html);
                        if(json.last){
                            $('.history-table .load-more').addClass('d-none');
                        }
                        else{
                            $('.history-table .load-more').removeClass('d-none');
                            $('.history-table .load-more').data('next', json.next);
                        }
                    }
                });
            }

            loadOrderItem(-1);

            $('#history-filter-form').submit(function () {
                loadOrderItem(-1)
                return false;
            });

            $('.pickdate').datepicker({
               language: 'vi',
                format: 'yyyy-mm-dd'
            });
            var modal = $('#modal-view-bill').modal({
                show: false
            });
            $('.content-box').on('click', '.service a.view-detail', function () {
                modal.find('.size-wrapper').html(
                    '<div class="loading-icon"><i class="fa fa-spinner fa-spin"></i></div>'
                );
                var id = $(this).parents('.service').data('id');
                var url = '{!! route('frontend.account.history.detail', ['order' => '???']) !!}';
                url = url.replace('???', id);
                console.log(url);
                modal.modal('show');
                $.ajax({
                    url: url,
                    type: 'get',
                    dataType: 'json',
                    success: function (json) {
                        modal.find('.size-wrapper').html(json);
                    }
                });
                return false;
            });
        });
    </script>
@endpush