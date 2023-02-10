@enqueueCSS('select2', getThemeAssetUrl('libs/select2/css/select2.min.css'), 'bootstrap')
@enqueueCSS('datepicker', getThemeAssetUrl('libs/datepicker/css/bootstrap-datepicker.standalone.min.css'), 'bootstrap')
@enqueueJS('datepicker', getThemeAssetUrl('libs/datepicker/js/bootstrap-datepicker.min.js'), JS_LOCATION_HEAD, 'bootstrap')
@enqueueJS('datepicker-language', getThemeAssetUrl('libs/datepicker/js/locales/bootstrap-datepicker.vi.min.js'), JS_LOCATION_HEAD, 'datepicker')
@enqueueJS('select2', getThemeAssetUrl('libs/select2/js/select2.full.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueCSS('history-page', getThemeAssetUrl('libs/styles/history.css'), 'account-menu')
@extends(getThemeViewName('test.account.master'))
@section('content')
    <div class="content-box">
        <div class="content-title">Lịch sử đặt chổ</div>
        <div class="content-body">
            <div class="filter-bar">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="field">
                                    <input class="pickdate" spellcheck="false" autocomplete="off" placeholder="Từ ngày">
                                    <i class="fa fa-calendar-o"></i>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="field">
                                    <input class="pickdate" spellcheck="false" autocomplete="off" placeholder="Đến ngày">
                                    <i class="fa fa-calendar-o"></i>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="field">
                                    <select>
                                        <option>Tất cả loại giao dịch</option>
                                        <option>Đã hủy</option>
                                        <option>Chờ thanh toán</option>
                                        <option>Chờ xử lý</option>
                                        <option>Chờ thực hiện</option>
                                        <option>Hoàn thành</option>
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
                        @php
                        $items = [
                            [
                                'id' => '#1256',
                                'date' => '09:30 21/05/2018',
                                'sdate' => '09:30 22/05/2018',
                                'salon' => 'Salon Tóc Tây',
                                'sum' => '350.000',
                                'status' => 'Hoàn thành',
                            ],
                            [
                                'id' => '#1255',
                                'date' => '09:30 25/04/2018',
                                'sdate' => '09:30 26/04/2018',
                                'salon' => 'Salon Tóc Tây',
                                'sum' => '350.000',
                                'status' => 'Đợi xử lý',
                            ],
                            [
                                'id' => '#1254',
                                'date' => '09:30 22/03/2018',
                                'sdate' => '09:30 23/03/2018',
                                'salon' => 'Salon Tóc Tây',
                                'sum' => '350.000',
                                'status' => 'Đã hủy',
                            ],
                            [
                                'id' => '#1253',
                                'date' => '09:30 24/02/2018',
                                'sdate' => '09:30 25/02/2018',
                                'salon' => 'Salon Tóc Tây',
                                'sum' => '350.000',
                                'status' => 'Đợi thực hiện',
                            ],
                            [
                                'id' => '#1252',
                                'date' => '09:30 22/01/2018',
                                'sdate' => '09:30 23/01/2018',
                                'salon' => 'Salon Tóc Tây',
                                'sum' => '350.000',
                                'status' => 'Hoàn thành',
                            ],
                            [
                                'id' => '#1251',
                                'date' => '09:30 20/05/2018',
                                'sdate' => '09:30 21/05/2018',
                                'salon' => 'Salon Tóc Tây',
                                'sum' => '350.000',
                                'status' => 'Hoàn thành',
                            ],
                        ];
                        @endphp
                        @foreach($items as $item)
                            <tr class="service">
                                <td><a class="view-detail" href="#">{!! $item['id'] !!}</a></td>
                                <td>{!! $item['date'] !!}</td>
                                <td>
                                    <div class="title">
                                        <a href="{!! route('test.salon.post') !!}">{!! $item['salon'] !!}</a>
                                    </div>
                                    <div class="service-date">
                                        Lúc: {!! $item['sdate'] !!}
                                    </div>
                                    <div class="view"><a class="view-detail" href="#">Chi tiết</a></div>
                                </td>
                                <td>{!! $item['sum'] !!}</td>
                                <td>{!! $item['status'] !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                        @component(getThemeViewName('components.bill'))
                        @endcomponent
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
            $('.pickdate').datepicker({
               language: 'vi',
                endDate: '0d'
            });
            var modal = $('#modal-view-bill').modal({
                show: false
            });
            $('.content-box .service a.view-detail').click(function () {
                modal.modal('show');
                return false;
            });
        });
    </script>
@endpush