@php
    /** @var \Modules\ModHairWorld\Entities\Salon $salon */
    /** @var \Modules\ModHairWorld\Entities\SalonService[] $services */
@endphp
@enqueueJSByID(config('view.ui.files.js.datatables.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.datatables_fixed_header.id'), JS_LOCATION_DEFAULT, 'datatables')
@enqueueJSByID(config('view.ui.files.js.datatables_select.id'), JS_LOCATION_DEFAULT, 'datatables')
@enqueueJSByID(config('view.ui.files.js.datatables_buttons.id'), JS_LOCATION_DEFAULT, 'datatables')
@enqueueJSByID(config('view.ui.files.js.editable.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.sweet_alert.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.pnotify.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@extends('layouts.backend')
@section('page_title', $salon->name)
@section('page_header_title')
    <strong>{{$salon->name}}</strong>
@endsection
@section('sidebar_second')
    <div class="sidebar-category">
        <div class="category-title">
            <span>{{__('HÀNH ĐỘNG')}}</span>
            <ul class="icons-list">
                <li><a href="#" data-action="collapse"></a></li>
            </ul>
        </div>

        <div class="category-content text-center">
            <div class="mb-10">
                @event(new \App\Events\BeforeHtmlBlock('sidebar.actions'))
                <button data-loading-text="<i class='icon-spinner4 spinner position-left'></i> {{__('ĐANG XỬ LÝ...')}}"
                        id="{{jSID('add_manager')}}" type="button" class="btn bg-primary btn-block btn-add">
                    {{__('THÊM KHUYẾN MÃI')}}
                </button>
                <button data-loading-text="<i class='icon-spinner4 spinner position-left'></i> {{__('ĐANG XỬ LÝ...')}}"
                        id="{{jSID('remove_manager')}}" type="button" class="btn bg-orange btn-block btn-remove">
                    {{__('XOÁ KHUYẾN MÃI')}}
                </button>
                <a href="{!! route('backend.salon.index') !!}" data-loading-text="<i class='icon-spinner4 spinner position-left'></i> {{__('ĐANG XỬ LÝ...')}}"
                   class="btn bg-warning btn-block btn-back btn-save">
                    {{__('QUAY LẠI')}}
                </a>
                @event(new \App\Events\AfterHtmlBlock('sidebar.actions'))
            </div>
        </div>
    </div>
    @include('modhairworld::backend.pages.salon.edit.includes.sidebar_items', ['salon'=>$salon])
@endsection
@section('page_content_body')
    @event(new \App\Events\BeforeHtmlBlock('content'))
    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title text-teal text-semibold">{!! __('CÁC KHUYẾN MÃI') !!}</h6>
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
                <form method="post" data-edit-mode="">
                    <div class="modal-header bg-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title"></h6>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="control-label text-semibold">Loại khuyến mãi</label>
                                    <select name="sale_type" class="form-control">
                                        <option value="1">Dịch vụ đơn lẻ</option>
                                        <option value="2">Dịch vụ theo danh mục</option>
                                        <option value="3">Tất cả dịch vụ</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group" id="sale_cats_group">
                                    <label class="control-label text-semibold">Danh mục khuyến mãi</label>
                                    <select name="sale_cat" class="form-control">
                                        @foreach($service_groups as $group_id=>$group)
                                            <option value="{!! $group_id !!}">
                                                {!! $group['name'] !!}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" id="sale_services_group">
                                    <label class="control-label text-semibold">Dịch vụ khuyến mãi</label>
                                    <select name="service_id" class="form-control">
                                        @foreach($service_groups as $group)
                                            <optgroup label="{!! $group['name'] !!}">
                                                @foreach($group['services'] as $service)
                                                    @php
                                                        /** @var \Modules\ModHairWorld\Entities\SalonService $service */
                                                    @endphp
                                                    <option value="{!! $service->id !!}">
                                                        {!! $service->name !!} / {!! number_format($service->getOrgPriceFrom()/1000.0, 0, '.', '.') !!}K
                                                        @if($service->ranged_price)
                                                             - {!! number_format($service->getOrgPriceTo()/1000.0, 0, '.', '.') !!}K
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="control-label text-semibold">Cách tính</label>
                                    <select name="amount_type" class="form-control">
                                        <option value="1">Số tiền</option>
                                        <option value="2">Phần trăm</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div id="cal_amount_group">
                                    @component('backend.components.field', [
                                                'field' => $sale_amount_field,
                                                'horizontal' => 0
                                            ])
                                    @endcomponent
                                </div>
                                <div id="cal_percent_group">
                                    @component('backend.components.field', [
                                                'field' => $sale_percent_field,
                                                'horizontal' => 0
                                            ])
                                    @endcomponent
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label text-semibold">Mô tả ghi chú</label>
                            <input spellcheck="false" autocomplete="off" name="description" class="form-control" placeholder="{!! __('Nhập mô tả, ghi chú cho khuyến mãi') !!}">
                        </div>
                        <div class="alert alert-info text-center">
                            Nếu khuyến mãi đã tồn tại thì sẽ được cập nhật thông tin mới
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">{!! __('ĐÓNG') !!}</button>
                        <button type="submit" class="btn btn-primary">{!! __('LƯU DỮ LIỆU') !!}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="modal-delete-manager" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header bg-warning">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title">XOÁ KHUYẾN MÃI</h6>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <select class="form-control" name="remove_sale_type">
                                <option value="-1">
                                    Tất cả khuyến mãi
                                </option>
                                @foreach($service_groups as $group_id=>$group)
                                    <option value="{!! $group_id !!}">
                                        {!! $group['name'] !!}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="_method" value="delete">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">{!! __('ĐÓNG') !!}</button>
                        <button type="submit" class="btn btn-primary">{!! __('XOÁ KHUYẾN MÃI') !!}</button>
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
            $modal_add = $('#modal-add-manager').modal({
                show: false,
                backdrop: 'static'
            });
            $modal_remove = $('#modal-delete-manager').modal({
                show: false,
                backdrop: 'static'
            });
            $modal_add.find('select[name=service_id]').select2({
            });
            $modal_add.find('select[name=sale_cat]').select2({
            });
            $modal_remove.find('select[name=remove_sale_type]').select2({
            });
            $modal_add.find('select[name=amount_type]').select2({
                minimumResultsForSearch: Infinity
            }).on('select2:select', function () {
                var sl = $(this).val();
                if(sl == 1){
                    $('#cal_amount_group').show();
                    $('#cal_percent_group').hide();
                }
                else{
                    $('#cal_amount_group').hide();
                    $('#cal_percent_group').show();
                }
            }).trigger('select2:select');
            $modal_add.find('select[name=sale_type]').select2({
                minimumResultsForSearch: Infinity
            }).on('select2:select', function () {
                var sl = $(this).val();
                if(sl == 1){
                    $('#sale_services_group').show();
                    $('#sale_cats_group').hide();
                }
                else if(sl == 2){
                    $('#sale_services_group').hide();
                    $('#sale_cats_group').show();
                }
                else{
                    $('#sale_services_group').hide();
                    $('#sale_cats_group').hide();
                }
            }).trigger('select2:select');

            $('#{{jSID('add_manager')}}').click(function () {
                addEditSale(null);
            });

            $('#{{jSID('remove_manager')}}').click(function () {
                $modal_remove.modal('show');
            });
            $modal_remove.find('form').submit(function(){
                var form = this;
                var data = $(form).serializeObject();
                var url = '{!! route('backend.salon.sales.destroy', ['salon'=>$salon]) !!}';
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    beforeSend: function(){
                        cleanErrorMessage(form);
                        $modal_remove.find('button').prop('disabled', true);
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
                        $modal_remove.modal('hide');
                        new PNotify({
                            title: '{{'XOÁ DỮ LIỆU'}}',
                            text: '{{__('Xoá dữ liệu thành công')}}',
                            addclass: 'bg-success stack-bottom-right',
                            stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25},
                            buttons: {
                                sticker: false
                            },
                            delay: 2000
                        });
                        loadSalonSidebarItemCount();
                        $table.draw('page');
                    },
                    complete: function () {
                        $modal_remove.find('button').prop('disabled', false);
                    }
                });
                return false;
            });
            $modal_add.find('form').submit(function(){
                var form = this;
                var data = $(form).serializeObject();
                var edit_mode = $(form).data('edit-mode');
                var url = '{!! route('backend.salon.sale.store', ['salon'=>$salon]) !!}';
                if (edit_mode) {
                    url = '{!! route('backend.salon.sale.update', ['salon'=>$salon]) !!}';
                    data._method = 'put';
                    data.id = $(form).data('edit-target');
                }
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
                        if (!edit_mode){
                            loadSalonSidebarItemCount();
                        }
                        $table.draw('page');
                    },
                    complete: function () {
                        $modal_add.find('button').prop('disabled', false);
                    }
                });
                return false;
            });

            function addEditSale($sale){
                cleanErrorMessage($modal_add.find('form'));
                if ($sale){
                    console.log($sale);
                    $modal_add.find('input[name=description]').val($sale.description);
                    $modal_add.find('input[name=sale_amount]').val($sale.sale_amount);
                    $modal_add.find('input[name=sale_percent]').val($sale.sale_percent);
                    $modal_add.find('select[name=sale_type]').val('1').trigger('change').trigger('select2:select');
                    $modal_add.find('select[name=amount_type]').val($sale.sale_type).trigger('change').trigger('select2:select');
                    $modal_add.find('select[name=service_id]').val($sale.service_id).trigger('change');
                    $modal_add.find('select[name=sale_type]').prop("disabled", true);
                    $modal_add.find('select[name=service_id]').prop("disabled", true);
                    $modal_add.find('.modal-title').html('{!! __('SỬA KHUYẾN MÃI') !!}');
                    $modal_add.find('form').data('edit-mode', true);
                    $modal_add.find('form').data('edit-target', $sale.id);
                    $modal_add.find('.alert').hide();
                }
                else{
                    $modal_add.find('input[name=description]').val('');
                    $modal_add.find('select[name=sale_type]').prop("disabled", false);
                    $modal_add.find('select[name=amount_type]').val(2).trigger('change').trigger('select2:select');
                    $modal_add.find('input[name=sale_percent]').val(0);
                    $modal_add.find('input[name=sale_amount]').val(0);
                    $modal_add.find('select[name=service_id]').prop("disabled", false);
                    $modal_add.find('.modal-title').html('{!! __('THÊM KHUYẾN MÃI') !!}');
                    $modal_add.find('form').data('edit-mode', false);
                    $modal_add.find('.alert').show();
                }
                $modal_add.modal('show');
            }

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
                    data: 'id',
                    name: 'id',
                    title_html: '<th>{!! __('Tên dịch vụ') !!}</th>',
                    cell_define: {
                        render: function (data, type, row) {
                            var html = '<div><a href="#" class="text-semibold text-teal edit-brand">'+row.service.name+'</a></div>';
                            if(row.description){
                                html += '<div class="text-muted">'+row.description+'</div>';
                            }
                            return html;
                        }
                    }
                },
                {
                    data: 'id',
                    name: 'id',
                    title_html: '<th>{!! __('Giá gốc') !!}</th>',
                    cell_define: {
                        render: function (data, type, row) {
                            var html = '<span class="text-semibold text-warning">'+row.price_html+'</span>';
                            return html;
                        }
                    }
                },

                {
                    data: 'id',
                    name: 'id',
                    title_html: '<th>{!! __('Giá KM') !!}</th>',
                    cell_define: {
                        render: function (data, type, row) {
                            var html = '<div>' +
                                '<span class="text-semibold text-success">'+row.sale_price_html+'</span>' +
                                '<div class="text-semibold text-danger"> Giảm '+row.sale_amount_desc+'</div>' +
                                '</div>';
                            return html;
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
                        width: "150px",
                        render: function (data, type, row) {
                            var html = '<a data-id="'+row.id+'" class="label label-warning label-icon cursor-pointer delete-brand mr-10"><i class="icon-trash"></i></a>';
                            html += '<a data-id="'+row.id+'" class="edit-brand label label-success label-icon cursor-pointer edit-brand"><i class="icon-pencil5"></i></a>';
                            return html;
                        }
                    }
                },
            ];
            var $options = {
                select: false,
                autoWidth: false,
                ajax: '{!! route('backend.salon.sale.edit', ['salon' => $salon]) !!}',
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
                $(e.target).find('.delete-brand').click(function () {
                    var ids = [];
                    var id = $(this).data('id');
                    ids.push(id);
                    deleteBrand(ids);
                });
                $(e.target).find('.edit-brand').click(function () {
                    var items = $table.rows(':eq(' + $(this).parents('tr').index() + ')', {page: 'current'});
                    addEditSale(items.data()[0]);
                });
            });
            function deleteBrand(ids){
                if (ids.length == 0) {
                    swal({
                        title: "{{__('Không thể thực thi')}}",
                        text: "{!! __('Vui lòng chọn 1 khuyến mãi để xóa') !!}",
                        confirmButtonColor: "#EF5350",
                        confirmButtonText: "{{__('Đã hiểu')}}",
                        type: "error"
                    });
                    return;
                }
                swal({
                        title: "{!! __('Xóa khuyến mãi') !!}",
                        text: "{!! __('Bạn có chắc chắn muốn xóa khuyến mãi này không?') !!}",
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
                                url: '{!! route('backend.salon.sale.destroy', ['salon' => $salon]) !!}',
                                dataType: 'json',
                                type: 'post',
                                data: {
                                    ids: ids,
                                    _method: 'delete'
                                },
                                success: function () {
                                    new PNotify({
                                        title: '{{'XỬ LÝ THÀNH CÔNG'}}',
                                        text: "{!! __('Xóa khuyến mãi thành công') !!}",
                                        addclass: 'bg-success stack-bottom-right',
                                        stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25},
                                        buttons: {
                                            sticker: false
                                        },
                                        delay: 2000
                                    });
                                    loadSalonSidebarItemCount();
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