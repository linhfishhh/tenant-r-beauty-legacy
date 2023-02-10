@php

@endphp
@enqueueJSByID(config('view.ui.files.js.datatables.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.datatables_fixed_header.id'), JS_LOCATION_DEFAULT, 'datatables')
@enqueueJSByID(config('view.ui.files.js.datatables_select.id'), JS_LOCATION_DEFAULT, 'datatables')
@enqueueJSByID(config('view.ui.files.js.datatables_buttons.id'), JS_LOCATION_DEFAULT, 'datatables')
@enqueueJSByID(config('view.ui.files.js.editable.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.sweet_alert.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.pnotify.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.moment.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.daterangepicker.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@extends('layouts.backend')
@section('page_title', 'Danh sách đơn đặt chỗ')
@section('page_header_title')
    <strong>Danh sách đơn đặt chỗ</strong>
@endsection
@section('page_content_body')
    @event(new \App\Events\BeforeHtmlBlock('content'))
    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title text-teal text-semibold">{!! __('DANH SÁCH ĐẶT CHỖ') !!}</h6>
        </div>
        <table data-table-name="{{jSID()}}" id="datatable_{{jSID()}}" class="table table-condensed table-hover">
            <thead>
            <tr></tr>
            </thead>
        </table>
    </div>
    <div id="modal-add-manager" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header bg-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title">{!! __('Chi tiết đặt chỗ') !!}</h6>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">Trạng thái đơn hàng</label>
                            
                            <select name="status">
                                @foreach(\Modules\ModHairWorld\Entities\SalonOrder::getStatusList() as $value=>$title)
                                    <option value="{!! $value !!}">
                                        {!! $title !!}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tên dịch vụ</th>
                                    <th style="text-align: right">Số lượng</th>
                                    <th style="text-align: right">Giá</th>
                                </tr>
                            </thead>
                            <tbody id="booking-detail-items">

                            </tbody>
                        </table>
                        <hr>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Tên dịch vụ kèm theo</th>
                                <th style="text-align: right">Số lượng</th>
                                <th style="text-align: right">Đơn giá</th>
                            </tr>
                            </thead>
                            <tbody id="booking-detail-included-items">

                            </tbody>
                        </table>
                        <div class="text-left text-nowrap">
                            <span style="font-weight: bold;">Tổng xu: </span>
                            <span id="booking-detail-total-coin"></span>
                        </div>
                        <div class="text-left text-nowrap" id="booking-detail-total-price">
                            <span style="font-weight: bold;">Tổng tiền cần thanh toán: </span>
                            <input name="amount_money" id="booking-detail-total-price-input"></input>
                            <span> VNĐ</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">{!! __('ĐÓNG') !!}</button>
                        <button type="submit" class="btn btn-primary" id="booking-update-btn">{!! __('CẬP NHẬT') !!}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @event(new \App\Events\AfterHtmlBlock('content'))
@endsection
@push('page_footer_js')
    @include('backend.settings.datatable.default')
    @include('backend.settings.xeditable.default')
    <script type="text/javascript">
        $(function () {
            function currencyFormat(str) {
                return str ? `${String(Math.abs(str)).replace(/(.)(?=(\d{3})+$)/g, '$1,')}` : '0';
            }

            $modal_add = $('#modal-add-manager').modal({
                show: false,
                backdrop: 'static'
            });

            $('#modal-add-manager select').select2(
                {
                    minimumResultsForSearch: Infinity,
                }
            );

            $('#{{jSID('add_manager')}}').click(function () {
                $modal_add.modal('show');
            });

            $modal_add.find('form').submit(function(){
                var id = $(this).data('id');
                var form = this;
                var data = $(form).serializeObject();
                data._method = 'put';
                var url = '{!! route('backend.bookings.update', ['order'=>'???']) !!}';
                url = url.replace('???', id);
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    beforeSend: function(){
                        cleanErrorMessage(form);
                        $modal_add.find('button').prop('disabled', true);
                    },
                    error: function(json){
                        new PNotify({
                            title: '{{'LƯU DỮ LIỆU'}}',
                            text: '{{__('Có lỗi xảy ra, vui lòng kiểm tra lại!')}}',
                            addclass: 'bg-danger stack-bottom-right',
                            stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25},
                            buttons: {
                                sticker: false
                            },
                            delay: 2000
                        });
                        handleErrorMessage(form, json);
                    },
                    success: function () {
                        cleanErrorMessage(form);
                        $modal_add.modal('hide');
                        new PNotify({
                            title: '{{'LƯU DỮ LIỆU'}}',
                            text: '{{__('Lưu dữ liệu thành công')}}',
                            addclass: 'bg-success stack-bottom-right',
                            stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25},
                            buttons: {
                                sticker: false
                            },
                            delay: 2000
                        });
                        $table.draw('page');
                    },
                    complete: function () {
                        $modal_add.find('button').prop('disabled', false);
                    }
                });
                return false;
            });

            $(window).trigger({
                type: 'wa.datatable.init.{{jSID()}}',
                table_html: $('#datatable_{{jSID()}}')[0]
            });
            var $table_column_handles = [
                {
                    data: 'id',
                    name: 'id',
                    title_html: '<th>{!! __('ID') !!}</th>',
                    cell_define: {
                        width: "100px",
                    }
                },
                {
                    data: 'salon_name',
                    name: 'salon_name',
                    title_html: '<th>{!! __('Salon') !!}</th>',
                    cell_define: {
                        render: function (data, type, row) {
                            return '<div class="text-teal text-semibold">'+row.salon_name+'</div><div>'+row.address_line+'</div>';
                        }
                    }
                },
                {
                    data: 'name',
                    name: 'name',
                    title_html: '<th>{!! __('Khách hàng') !!}</th>',
                    cell_define: {
                        render: function (data, type, row) {
                            if(!row.user){
                                return '<div class="text-muted text-semibold">Người dùng không tồn tại</div>';
                            }
                            else{
                                return '<div class="text-teal text-semibold">'+row.user.name+'</div><div class="text-warning text-semibold">'+row.user.phone+'</div><div>'+row.user.email+'</div>';
                            }
                        }
                    }
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    title_html: '<th>{!! __('Đặt lúc') !!}</th>',
                    cell_define: {
                        width: "120px",
                    }
                },
                {
                    data: 'service_time',
                    name: 'service_time',
                    title_html: '<th>{!! __('Thực hiện') !!}</th>',
                    cell_define: {
                        width: "120px",
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    title_html: '<th>{!! __('Trạng thái') !!}</th>',
                    cell_define: {
                        width: "120px",
                        render: function (data, type, row) {
                            return '<div class="text-semibold">'+row.status_text+'</div>';
                        }
                    }
                },
                {
                    data: 'sum',
                    name: 'sum',
                    title_html: '<th>{!! __('Tổng tiền') !!}</th>',
                    cell_define: {
                        width: "150px",
                        render: function (data, type, row) {
                            return '<div class="text-danger text-semibold">'+row.sum+'</div>';
                        }
                    }
                },
                {
                    data: 'needToPay',
                    name: 'needToPay',
                    title_html: '<th>{!! __('Số tiền cần thanh toán') !!}</th>',
                    cell_define: {
                        width: "150px",
                        render: function (data, type, row) {
                            return '<div class="text-danger text-semibold">'+row.needToPay+'</div>';
                        }
                    }
                },
                {
                    data: '',
                    name: 'action',
                    title_html: '<th>{!! __('Hành động') !!}</th>',
                    cell_define: {
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: "100px",
                        render: function (data, type, row) {
                            var html = '<a data-id="'+row.id+'" class="label label-success label-icon cursor-pointer edit-manager mr-5"><i class="icon-pencil"></i></a>';
                            /*html +=  '<a data-id="'+row.id+'" class="label label-warning label-icon cursor-pointer delete-manager"><i class="icon-trash"></i></a>';*/
                            return html;
                        }
                    }
                },
            ];
            var $options = {
                select: false,
                autoWidth: false,
                ajax: '{!! route('backend.bookings.index') !!}',
                columns: [],
                columnDefs: []
            };
            var event = $.Event('wa.datatable.options.{{jSID()}}');
            event.table_options = $options;
            event.table_html = $('#datatable_{{jSID()}}')[0];
            event.table_column_handles = $table_column_handles;
            $(window).trigger(event);
            $.each(event.table_column_handles, function (i, v) {
                $('#datatable_{{jSID()}}>thead>tr').append(v.title_html);
                $('#datatable_{{jSID()}}>tfoot>tr').append(v.title_html);
                $options.columns.push({
                    name: v.name,
                    data: v.data
                });
                var h = v.cell_define;
                h.targets = i;
                $options.columnDefs.push(h);
            });
            var $table = $('#datatable_{{jSID()}}').DataTable(event.table_options);
            $table.on('preXhr.dt', function (e, settings, data) {});
            $table.on('preInit.dt', function (e) {});
            $table.on('draw', function (e, settings) {
                $(e.target).find('[data-popup="tooltip"]').tooltip({
                    title: function () {
                        var t = $(this).data('tooltip-title');
                        return t;
                    }
                });
                /*
                $(e.target).find('.delete-manager').click(function () {
                    var ids = [];
                    var id = $(this).data('id');
                    ids.push(id);
                    deleteManagers(ids);
                });
                */

                $(e.target).find('.edit-manager').click(function () {
                    var ids = [];
                    var id = $(this).data('id');
                    var data = $table.data()[$(this).parents('tr').index()];
                    var html = '';
                    var html1 = '';
                    // console.log(data);
                    $(data.items).each(function (index, item) {
                        html += '<tr>' +
                                '<td>'+item.name+'</td>'+
                            '<td style="text-align: right">'+item.qty+'</td>'+
                            '<td style="text-align: right">'+item.sum+'</td>'+
                            '</tr>'

                    });
                    if(data.included_items.length > 0){
                        $(data.included_items).each(function (index, item) {
                            html1 += '<tr>'+
                                '<td ">'+item.name+'</td>'+
                                '<td style="text-align: right">'+item.qty+'</td>'+
                                '<td style="text-align: right">'+item.sum+'</td>'+
                                '</tr>'
                        });
                    }else {
                        html1 = '<tr><td colspan="3" class="text-center">Không có dịch vụ kèm theo</td></tr>'
                    }

                    if (data.amount_coin !== undefined && data.amount_coin !== null) {
                        $('#booking-detail-total-coin').html(currencyFormat(data.amount_coin)+' xu').css('color', 'red');
                    } else {
                        $('#booking-detail-total-coin').html('');
                    }
                    $displayAmountMoney = data.sum;
                    if (data.needToPay !== undefined && data.needToPay !== null) {
                        $displayAmountMoney = data.needToPay;
                    }
                    $displayAmountMoney = $displayAmountMoney.replace(/\D/g,'');
                    $('#booking-detail-total-price-input').val($displayAmountMoney);
                    $('#booking-detail-total-price-input').prop("disabled", data.disable_change_status);
                    $('#booking-update-btn').prop("disabled", data.disable_change_status);

                    $('#booking-detail-items').html(html);
                    $('#booking-detail-included-items').html(html1);
                    $('#modal-add-manager select').val(data.status).trigger('change');
                    $('#modal-add-manager select').prop("disabled", data.disable_change_status);
                    $('#modal-add-manager form').data('id', id);
                    $modal_add.modal('show');
                });
            });
            function deleteManagers(ids){
                if (ids.length == 0) {
                    swal({
                        title: "{{__('Không thể thực thi')}}",
                        text: "{!! __('Vui lòng chọn 1 đơn hàng để xóa') !!}",
                        confirmButtonColor: "#EF5350",
                        confirmButtonText: "{{__('Đã hiểu')}}",
                        type: "error"
                    });
                    return;
                }
                swal({
                        title: "{!! __('Xoá đơn hàng') !!}",
                        text: "{!! __('Bạn có chắc muốn xoá đơn đặt chỗ này không?') !!}",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#EF5350",
                        confirmButtonText: "{{__('Đồng ý')}}",
                        cancelButtonText: "{{__('Hủy bỏ')}}",
                        closeOnConfirm: true,
                        closeOnCancel: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: '{!! route('backend.bookings.destroy') !!}',
                                dataType: 'json',
                                type: 'post',
                                data: {
                                    ids: ids,
                                    _method: 'delete'
                                },
                                success: function () {
                                    new PNotify({
                                        title: '{{'XỬ LÝ THÀNH CÔNG'}}',
                                        text: "{!! __('Đã xoá đơn đặt chỗ thành công') !!}",
                                        addclass: 'bg-success stack-bottom-right',
                                        stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25},
                                        buttons: {
                                            sticker: false
                                        },
                                        delay: 2000
                                    });
                                },
                                error: function (data) {
                                    new PNotify({
                                        title: '{{'LỖI XẢY RA'}}',
                                        text: getJSONErrorMessage(data, '{{__('Lỗi không xác định')}}'),
                                        addclass: 'bg-danger stack-bottom-right',
                                        stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25},
                                        buttons: {
                                            sticker: false
                                        },
                                        delay: 2000
                                    });
                                },
                                complete: function () {
                                    $table.draw('page');
                                }
                            });
                        }
                    });
            }
            $(window).trigger({
                type: 'wa.datatable.ran.{{jSID()}}',
                table_object: $table,
                table_html: $('#datatable_{{jSID()}}')[0]
            });
        });
    </script>
@endpush