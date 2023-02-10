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
@section('page_header_title')
    <strong>{!! __('Danh sách salon') !!}</strong>
@endsection
@section('page_title')
    {!! __('Danh sách salon') !!}
@endsection
@section('page_content_body')
    @event(new \App\Events\BeforeHtmlBlock('content'))
    @component('backend.components.panel', ['classes'=>'panel-default', 'has_body' => false])
        @slot('content')
            <table data-table-name="{{jSID()}}" id="datatable_{{jSID()}}" class="table table-condensed table-hover">
                <thead>
                <tr></tr>
                </thead>
            </table>
        @endslot
    @endcomponent
    @event(new \App\Events\AfterHtmlBlock('content'))
@endsection
@push('page_footer_js')
    @include('backend.settings.datatable.default')
    @include('backend.settings.xeditable.default')
    <template id="{!! jSID('salon_main_info_tpl') !!}">
        <div class="name">
            <a class="text-semibold text-teal text-uppercase" href="{link}">
                {certified}
                {title}
            </a>
        </div>
        <div class="address mt-5 text-slate-300">
            <i class="icon-location3 position-left"></i>
            <span class="">{address}</span>
        </div>
        <div class="managers mt-5 text-slate-300">
            <i class="icon-users position-left"></i>
            <span class="">{managers}</span>
        </div>
    </template>
    <template id="{!! jSID('row_action') !!}">
        <ul class="icons-list">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-menu9"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li>
                        <a target="_blank" href="{!! route('frontend.salon', ['salon' => '???id???', 'location_slug' => '???location_slug???', 'slug' => '???slug???']) !!}"><i class="icon-eye text-success"></i> {!! __('Xem web') !!}
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a data-clone-title="???title???" data-clone-id="???id???" class="salon-clone-act"><i class="icon-copy4 text-orange"></i> {!! __('Clone salon') !!}
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="{!! route('backend.salon.basic_info.edit', ['salon' => '???id???']) !!}"><i class="icon-book"></i> {!! __('Thông tin cơ bản') !!}
                        </a>
                    </li>
                    <li>
                        <a href="{!! route('backend.salon.extended_info.edit', ['salon' => '???id???']) !!}"><i class="icon-info22"></i> {!! __('Thông tin mở rộng') !!}
                        </a>
                    </li>
                    <li>
                        <a href="{!! route('backend.salon.managers.edit', ['salon' => '???id???']) !!}"><i class="icon-users"></i> {!! __('Tài khoản quản lý') !!}
                        </a>
                    </li>
                    <li>
                        <a href="{!! route('backend.salon.gallery.edit', ['salon' => '???id???']) !!}"><i class="icon-image2"></i> {!! __('Gallery ảnh') !!}
                        </a>
                    </li>
                    <li>
                        <a href="{!! route('backend.salon.time.edit', ['salon' => '???id???']) !!}"><i class="icon-watch2"></i> {!! __('Giờ làm việc') !!}
                        </a>
                    </li>
                    <li>
                        <a href="{!! route('backend.salon.stylist.edit', ['salon' => '???id???']) !!}"><i class="icon-scissors"></i> {!! __('Đội ngũ stylist') !!}
                        </a>
                    </li>
                    <li>
                        <a href="{!! route('backend.salon.showcase.edit', ['salon' => '???id???']) !!}"><i class="icon-image5"></i> {!! __('Tác phẩm nổi bật') !!}
                        </a>
                    </li>
                    <li>
                        <a href="{!! route('backend.salon.brand.edit', ['salon' => '???id???']) !!}"><i class="icon-bag"></i> {!! __('Thương hiệu') !!}
                        </a>
                    </li>
                    <li>
                        <a href="{!! route('backend.salon.service.edit', ['salon' => '???id???']) !!}"><i class="icon-stars"></i> {!! __('Dịch vụ') !!}
                        </a>
                    </li>
                    <li>
                        <a href="{!! route('backend.salon.sale.edit', ['salon' => '???id???']) !!}"><i class="icon-gift"></i> {!! __('Khuyến mãi') !!}
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a class="salon-delete"><i class="icon-trash text-warning"></i> {!! __('Xóa salon') !!}
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </template>
    <template id="{!! jSID('tool_tpl') !!}">
        <div id="{{jSID('tool')}}" class="dataTables_filter tools">
            <a href="{!! route('backend.salon.create') !!}" class="btn btn-warning btn-labeled btn-sm">
                <b><i class="icon-add-to-list"></i></b> {!! __('Thêm salon') !!}
            </a>
        </div>
    </template>
    <div id="modal_clone_salon" class="modal fade">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">CLONE SALON</h5>
                </div>

                <form id="clone_salon_form">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Tên Salon được tạo</label>
                                    <input spellcheck="false" autocapitalize="off" autocomplete="false" name="clone_salon_name" type="text" placeholder="Nhập tên salon sẽ tạo..." class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <fieldset>
                                    <legend>Tuỳ chọn dữ liệu copy</legend>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="clone_salon_cover" type="checkbox" class="styled" checked="checked">
                                                        Ảnh đại diện
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="clone_salon_status" type="checkbox" class="styled" checked="checked">
                                                        Trạng thái hoạt động
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="clone_salon_verified" type="checkbox" class="styled" checked="checked">
                                                        Trạng thái chứng thực
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="clone_salon_address" type="checkbox" class="styled" checked="checked">
                                                        Địa chỉ salon
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="clone_salon_desc" type="checkbox" class="styled" checked="checked">
                                                        Thông tin mô tả
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="clone_salon_gallery" type="checkbox" class="styled" checked="checked">
                                                        Gallery ảnh
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="clone_salon_times" type="checkbox" class="styled" checked="checked">
                                                        Giờ làm việc
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="clone_salon_stylists" type="checkbox" class="styled" checked="checked">
                                                        Đội ngũ stylist
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="clone_salon_showcases" type="checkbox" class="styled" checked="checked">
                                                        Tác phẩm nổi bật
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="clone_salon_brands" type="checkbox" class="styled" checked="checked">
                                                        Thương hiệu sử dụng
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="clone_salon_services" type="checkbox" class="styled" checked="checked">
                                                        Dịch vụ
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="clone_salon_sales" type="checkbox" class="styled" checked="checked">
                                                        Khuyến mãi
                                                    </label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-6">
                                <fieldset>
                                    <legend>
                                        Tài khoản quản lý
                                    </legend>
                                    <div class="checkbox mb-10">
                                        <label>
                                            <input name="clone_salon_create_manager" id="toggle-manager-account" type="checkbox" class="styled" checked="checked">
                                            Tạo tài khoản quản lý cho salon
                                        </label>
                                    </div>
                                    <div id="create-manager-form">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <input name="clone_salon_manager_name" type="text" placeholder="Nhập tên quản lý" class="form-control">
                                                </div>

                                                <div class="col-sm-6">
                                                    <input name="clone_salon_manager_phone" type="text" placeholder="Nhập số điện thoại quản lý" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <input name="clone_salon_manager_email" type="text" placeholder="Nhập email quản lý" class="form-control">
                                                </div>
                                                <div class="col-sm-6">
                                                    <input name="clone_salon_manager_password" type="text" placeholder="Nhập mật khẩu quản lý" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">ĐÓNG LẠI</button>
                        <button type="button" id="btn_clone_edit" class="btn bg-orange">CLONE RỒI SỬA</button>
                        <button type="button" id="btn_clone" class="btn btn-success">CLONE SALON</button>
                    </div>
                    <input type="hidden" name="clone_salon_id">
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function () {
            var $clone_modal = $('#modal_clone_salon').modal({
                show: false,
                backdrop: 'static'
            });
            function clone(edit) {
                var form = $('#clone_salon_form');
                var data = $(form).serializeObject();
                var id = data.clone_salon_id;
                var url = '{!! route('backend.salon.clone', ['salon'=>'???id???']) !!}';
                url = url.replace(/\?\?\?id\?\?\?/g, id);
                console.log(data);
                $.ajax({
                    method: 'post',
                    url: url,
                    data: data,
                    beforeSend: function(){
                        cleanErrorMessage(form);
                        $clone_modal.find('button').prop('disabled', true);
                    },
                    complete: function () {
                        $clone_modal.find('button').prop('disabled', false);
                    },
                    error: function (json) {
                        handleErrorMessage(form, json);
                    },
                    success: function (json) {
                        $clone_modal.modal('hide');
                        $table.draw('page');
                        if(edit){
                            window.location = json;
                        }
                        else{
                            new PNotify({
                                title: '{{'XỬ LÝ THÀNH CÔNG'}}',
                                text: "{!! __('Salon đã được clone thành công') !!}",
                                addclass: 'bg-success stack-bottom-right',
                                stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25},
                                buttons: {
                                    sticker: false
                                },
                                delay: 2000
                            });
                        }
                    }
                });
            }
            $('#btn_clone_edit').click(function () {
                clone(true);
            });
            $('#btn_clone').click(function () {
                clone(false);
            });
            $('#toggle-manager-account').change(function () {
                var chk = $(this).is(':checked');
                if(chk){
                    $('#create-manager-form').show();
                }
                else{
                    $('#create-manager-form').hide();
                }
            });
            $('#clone_salon_form').submit(function () {
                clone(false);
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
                    data: 'name',
                    name: 'name',
                    title_html: '<th>{!! __('Salon') !!}</th>',
                    cell_define: {
                        render: function (data, type, row) {
                            var html = $('#{!! jSID('salon_main_info_tpl') !!}').html();
                            html = html.replace(/{title}/g, row.name);
                            html = html.replace(/{link}/g, row.link);
                            var certified_class = 'text-slate-300';
                            var certified_text = '{!! __('Chưa chứng thực') !!}';
                            if (row.certified){
                                certified_class= 'text-orange';
                                certified_text = '{!! __('Đã chứng thực') !!}';
                            }
                            var certified_icon = '<span data-popup="tooltip" data-tooltip-title="'+certified_text+'" class="certified">' +
                                '<i class="icon-medal-star position-left '+certified_class+'"></i>' +
                                '</span>';
                            html = html.replace(/{certified}/g, certified_icon);
                            html = html.replace(/{address}/g, row.address_line);
                            var managers_html = '';
                            if (row.managers.length){
                                $.each(row.managers, function (i, v) {
                                    if (i>0){
                                        managers_html += ', ';
                                    }
                                    managers_html += '<span>'+this.name+'</a>'
                                });
                            }
                            else{
                                managers_html += '<span>{!! __('Chưa thiết lập') !!}</span>';
                            }
                            html = html.replace(/{managers}/g, managers_html);
                            return html;
                        }
                    }
                },
                {
                    data: 'open',
                    name: 'open',
                    title_html: '<th>{!! __('Tình trạng') !!}</th>',
                    cell_define: {
                        searchable: false,
                        className: 'text-center',
                        width: "120px",
                        render: function (data, type, row) {
                            var status = 'warning';
                            var status_text = '{!! __('Tạm ngưng') !!}'
                            if (row.open){
                                status = 'success';
                                status_text = '{!! __('Hoạt động') !!}'
                            }
                            var html = '<span class="label label-'+status+'">'+status_text+'</span>';
                            return html;
                        }
                    }
                },
                {
                    data: '',
                    name: 'action',
                    title_html: '<th>{!! __('Quản lý') !!}</th>',
                    cell_define: {
                        searchable: false,
                        orderable: false,
                        className: 'text-center',
                        width: "120px",
                        render: function (data, type, row) {
                            var html = $('#{!! jSID('row_action') !!}').html();
                            html = html.replace(/\?\?\?id\?\?\?/g, row.id);
                            html = html.replace(/\?\?\?slug\?\?\?/g, row.slug);
                            html = html.replace(/\?\?\?title\?\?\?/g, row.name);
                            html = html.replace(/\?\?\?location_slug\?\?\?/g, row.location_slug);
                            return html;
                        }
                    }
                }
            ];
            var $options = {
                select: false,
                autoWidth: false,
                ajax: '{!! route('backend.salon.index') !!}',
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
            $table.on('preInit.dt', function (e) {
                var html = $('#{!! jSID('tool_tpl') !!}').html();
                var append_to = $(e.target).parents('.dataTables_wrapper').find('.datatable-header');
                var button = $(html).appendTo(append_to);
            });

            $table.on('draw', function (e, settings) {
                $(e.target).find('[data-popup="tooltip"]').tooltip({
                    title: function () {
                        var t = $(this).data('tooltip-title');
                        return t;
                    }
                });
                $(e.target).find('.salon-delete').click(function () {
                    var items = $table.rows(':eq(' + $(this).parents('tr').index() + ')', {page: 'current'});
                    deleteSalon(items.data()[0].id);
                });
                $(e.target).find('.salon-clone-act').click(function () {
                    var id = $(this).data('clone-id');
                    var title = $(this).data('clone-title')+' - Copy';
                    $('input[name=clone_salon_name]').val(title);
                    $('input[name=clone_salon_id]').val(id);
                    $('#toggle-manager-account').prop('checked', false).trigger('change');
                    $('input[name=clone_salon_cover]').prop('checked', 'checked').trigger('change');
                    $('input[name=clone_salon_status]').prop('checked', 'checked').trigger('change');
                    $('input[name=clone_salon_verified]').prop('checked', 'checked').trigger('change');
                    $('input[name=clone_salon_address]').prop('checked', 'checked').trigger('change');
                    $('input[name=clone_salon_desc]').prop('checked', 'checked').trigger('change');
                    $('input[name=clone_salon_gallery]').prop('checked', 'checked').trigger('change');
                    $('input[name=clone_salon_times]').prop('checked', 'checked').trigger('change');
                    $('input[name=clone_salon_stylists]').prop('checked', 'checked').trigger('change');
                    $('input[name=clone_salon_showcases]').prop('checked', 'checked').trigger('change');
                    $('input[name=clone_salon_brands]').prop('checked', 'checked').trigger('change');
                    $('input[name=clone_salon_services]').prop('checked', 'checked').trigger('change');
                    $('input[name=clone_salon_sales]').prop('checked', 'checked').trigger('change');
                    $.uniform.update();
                    $clone_modal.modal('show');
                });
            });
            function deleteSalon(id) {
                swal({
                        title: "{!! __('Xóa salon') !!}",
                        text: "{!! __('Bạn có chắc chắn muốn xóa salon này không?') !!}",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#EF5350",
                        confirmButtonText: "{{__('Đồng ý')}}",
                        cancelButtonText: "{{__('Hủy bỏ')}}",
                        closeOnConfirm: true,
                        closeOnCancel: true
                    },
                    function (isConfirm) {
                        if(isConfirm){
                            var url = '{!! route('backend.salon.destroy', ['salon'=>'???']) !!}';
                            url = url.replace('???', id);
                            $.ajax({
                                url: url,
                                type: 'post',
                                data:{
                                    _method: 'delete'
                                },
                                success: function (rs) {
                                    new PNotify({
                                        title: '{{'XỬ LÝ THÀNH CÔNG'}}',
                                        text: "{!! __('Xóa salon thương hiệu thành công') !!}",
                                        addclass: 'bg-success stack-bottom-right',
                                        stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25},
                                        buttons: {
                                            sticker: false
                                        },
                                        delay: 2000
                                    });
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