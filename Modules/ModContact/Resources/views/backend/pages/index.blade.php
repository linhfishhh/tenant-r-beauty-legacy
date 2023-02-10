@enqueueJSByID(config('view.ui.files.js.datatables.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.datatables_fixed_header.id'), JS_LOCATION_DEFAULT, 'datatables')
@enqueueJSByID(config('view.ui.files.js.datatables_select.id'), JS_LOCATION_DEFAULT, 'datatables')
@enqueueJSByID(config('view.ui.files.js.datatables_buttons.id'), JS_LOCATION_DEFAULT, 'datatables')
@enqueueJSByID(config('view.ui.files.js.editable.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.sweet_alert.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.pnotify.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.dotdotdot.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.moment.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.daterangepicker.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@php

        @endphp
@extends('layouts.backend')
@section('page_title',__('Liên hệ của khách'))
@section('page_header_title')
    <strong>{{__('Liên hệ của khách')}}</strong>
@endsection
@section('sidebar_second')
    @event(new \App\Events\BeforeHtmlBlock('sidebar.groups'))
    <div class="sidebar-category" data-group-id="menu-contact">
        <div class="category-title">
            <span>{{__('Hành động')}}</span>
            <ul class="icons-list">
                <li><a href="#" data-action="collapse" class=""></a></li>
            </ul>
        </div>
        <div class="category-content no-padding" style="display: block;">
            <ul class="navigation navigation-alt navigation-accordion datatable-comment-actions">
                @event(new \App\Events\BeforeHtmlBlock('sidebar.groups.actions'))
                <li>
                    <a class="action-delete"><i class="icon-trash"></i> {{__('Xóa bỏ')}}</a>
                    <a class="action-handle" data-handled="1"><i class="icon-checkmark"></i> {{__('Đánh dấu đã xử lý')}}</a>
                    <a class="action-handle" data-handled="0"><i class="icon-blocked"></i> {{__('Đánh dấu chưa xử lý')}}</a>
                    <a class="action-emails" data-handled="0"><i class="icon-cog2"></i> {{__('Cấu hình thông báo mail')}}</a>
                </li>
                @event(new \App\Events\AfterHtmlBlock('sidebar.groups.actions'))
            </ul>
        </div>
    </div>
    @event(new \App\Events\AfterHtmlBlock('sidebar.groups'))
@endsection
@section('page_content_body')
    @event(new \App\Events\BeforeHtmlBlock('content'))
    @component('backend.components.panel', ['classes'=>'panel-default', 'has_body' => false])
        @slot('content')
            <table data-table-name="{{jSID()}}" id="datatable_{{jSID()}}" class="table table-condensed table-hover">
                <thead><tr></tr></thead>
            </table>
            <template id="{!! jSID('filter_tpl') !!}">
                <div class="dataTables_filter date_filter">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <input placeholder="{{__('Thống kê theo ngày')}}" id="{!! jSID('date_filter') !!}" data-start="" data-end="" readonly type="text" class="form-control date_filter text-size-mini" value="">
                    </div>
                </div>
                <div class="dataTables_filter handled_filter">
                    <div class="input-group">
                        <select>
                            <option value="-1">{{__('Tất cả')}}</option>
                            <option value="0">{!! __('Chưa xử lý') !!}</option>
                            <option value="1">{!! __('Đã xử lý') !!}</option>
                        </select>
                    </div>
                </div>
            </template>
            <template id="{{jSID()}}_row_action_tpl">
                <a title="{{__('Chi tiết')}}" href="#" class="label label-success label-icon label-rounded action-view"><i class="icon-eye"></i></a>
                <a title="{{__('Xóa')}}" href="#" class="label label-warning label-icon label-rounded action-delete"><i class="icon-trash"></i></a>
            </template>
            <div id="view_detail_modal" class="modal fade">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h5 class="modal-title">{!! __('CHI TIẾT LIÊN HỆ') !!}</h5>
                        </div>

                        <div class="modal-body">
                            <form onsubmit="return false">
                                <div class="row">
                                    <div class="col-md-4 mb-10">
                                        <div class="text-semibold text-teal"><i class="icon-user position-left"></i> Tên khách:</div>
                                        <div class="name-placeholder"></div>
                                    </div>
                                    <div class="col-md-4 mb-10">
                                        <div class="text-semibold text-teal"><i class="icon-envelop position-left"></i> Địa chỉ email:</div>
                                        <div class="email-placeholder"></div>
                                    </div>
                                    <div class="col-md-4 mb-10">
                                        <div class="text-semibold text-teal"><i class="icon-phone position-left"></i> Điện thoại liên hệ:</div>
                                        <div class="phone-placeholder"></div>
                                    </div>
                                </div>
                                <div class="mb-10">
                                    <div class="text-semibold text-teal"><i class="icon-comment position-left"></i> Nội dung liên hệ:</div>
                                    <div class="content-placeholder"></div>
                                </div>
                                <div>
                                    @component('backend.components.field', [
                                    'unhandled' => false,
                                    'horizontal' => 0,
                                    'field' =>
                                    new \App\Classes\FieldInput\FieldInputTinyMCE
                                    ('content',
                                    __('Nhập nội dung mail cần gửi'),
                                    '<div class="text-semibold text-teal mb-5"><i class="icon-comments position-left"></i> Trả lời khách:</div>','',
                                    true,
                                    \App\Classes\FieldInput\FieldInputTinyMCE::buildConfigs([
                                        'height' => 150
                                    ])
                                    )
                                ])

                                    @endcomponent
                                </div>
                                <input type="hidden" class="contact-id" name="id">
                            </form>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-warning" data-dismiss="modal">
                                <i class="icon-close2 position-left"></i>
                                {!! __('ĐÓNG LẠI') !!}
                            </button>
                            <button type="button" class="btn btn-primary send_mail_btn">
                                <i class="icon-comment position-left"></i>
                                {!! __('TRẢ LỜI') !!}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="view_admin_email" class="modal fade">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h5 class="modal-title">{!! __('EMAIL NHẬN THÔNG BÁO') !!}</h5>
                        </div>

                        <div class="modal-body">
                            <form onsubmit="return false">
                                <textarea spellcheck="false" name="emails" rows="5" placeholder="{!! __('Nhập danh sách email') !!}" class="form-control"></textarea>
                                <div class="help-block">Nhập danh sách các email sẽ nhận thông báo khi có một khách liên hệ (mỗi email một dòng)</div>
                            </form>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-warning" data-dismiss="modal">
                                <i class="icon-close2 position-left"></i>
                                {!! __('ĐÓNG LẠI') !!}
                            </button>
                            <button type="button" class="btn btn-primary btn-save">
                                <i class="icon-checkmark position-left"></i>
                                {!! __('LƯU THAY ĐỔI') !!}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endslot
    @endcomponent
    @event(new \App\Events\AfterHtmlBlock('content'))
@endsection
@push('page_footer_js')
    @include('backend.settings.datatable.default')
    @include('backend.settings.xeditable.default')
    <script type="text/javascript">
        $(function () {
            var $modal = $('#view_detail_modal').modal({
                show: false,
                backdrop: 'static'
            });

            $modal.find('.send_mail_btn').click(function () {
                var $form = $modal.find('form');
                var id = $modal.find('.contact-id').val();
                var data = {
                    content: $form.find('textarea[name=content]').val(),
                };
                var url = '{!! route('backend.contact.reply', ['contact' => '???']) !!}';
                url = url.replace('???', id);
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    beforeSend: function () {
                        cleanErrorMessage($form);
                        $modal.find('.modal-footer button').prop('disabled', true);
                    },
                    success: function () {
                        cleanErrorMessage($form);
                        $modal.modal('hide');
                        new PNotify({
                            title: '{{'XỬ LÝ THÀNH CÔNG'}}',
                            text: "{{__('Đã trả lời liên hệ thành công.')}}",
                            addclass: 'bg-success stack-bottom-right',
                            stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25},
                            buttons: {
                                sticker: false
                            },
                            delay: 2000
                        });
                        $table.draw('page');
                    },
                    error: function(json){
                        handleErrorMessage($form, json);
                        new PNotify({
                            title: '{{'LỖI XẢY RA'}}',
                            text: '{!! __('Có lỗi xảy ra trong quá trình gửi mail') !!}',
                            addclass: 'bg-danger stack-bottom-right',
                            stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25},
                            buttons: {
                                sticker: false
                            },
                            delay: 2000
                        });
                    },
                    complete: function () {
                        $modal.find('.modal-footer button').prop('disabled', false);
                    }
                });
            });

            var $email_modal = $('#view_admin_email').modal({
                show: false,
                backdrop: 'static'
            });

            $('#view_admin_email .btn-save').click(function () {
                $.ajax({
                   url: '{!! route('backend.contact.mail_list.set') !!}',
                   type: 'post',
                   dataType: 'json',
                   data: {
                       mail_list: $('#view_admin_email form textarea[name=emails]').val()
                   },
                    success: function () {
                        $email_modal.modal('hide');
                    },
                    error: function () {

                    }
                });
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
                        width: "80px",
                    }
                },
                {
                    data: 'name',
                    name: 'name',
                    title_html: '<th>{!! __('Tên khách') !!}</th>',
                    cell_define: {
                        render: function (data, type, row) {
                            return '<a href="#" class="text-semibold text-teal action-view">'+data+'</a>'
                        }
                    }
                },
                {
                    data: '',
                    name: 'info',
                    title_html: '<th>{!! __('Liên hệ') !!}</th>',
                    cell_define: {
                        render: function (data, type, row) {
                            var rs = '';
                            rs += '<div class="mb-5"><i class="icon-envelop position-left text-grey-300"></i><span>'+row.email+'</span></div>';
                            rs += '<div><i class="icon-phone position-left text-grey-300"></i><span>'+row.phone+'</span></div>';
                            return rs;
                        }
                    }
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    title_html: '<th>{!! __('Gửi lúc') !!}</th>',
                    cell_define: {
                        width: "150px",
                        className: 'text-center'
                    }
                },
                {
                    data: 'handled',
                    name: 'handled',
                    title_html: '<th>{!! __('Xử lý') !!}</th>',
                    cell_define: {
                        width: "150px",
                        render: function (data, type, row) {
                            if (row.published) {
                                var clss = 'label-success';
                                var title = '{{__('Rồi')}}';
                            }
                            else {
                                var clss = 'label-warning';
                                var title = '{{__('Chưa')}}';
                            }
                            if (row.trashed) {
                                return '<span class="label ' + clss + '"></i> <span>' + title + '</span></span>'
                            }
                            return '<span data-pk="' + row.id + '" data-value="' + data + '" class="cursor-pointer label ' + clss + ' editable-handled"><i class="icon-pencil5 text-size-mini"></i> <span>' + title + '</span></span>'
                        }
                    }
                },
                {
                    data: '',
                    name: 'action',
                    title_html: '<th>{!! __('Hành động') !!}</th>',
                    cell_define: {
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                        width: "120px",
                        render: function (data, type, row) {
                            var html = $('#{{jSID()}}_row_action_tpl').html();
                            html = html.replace(/{id}/, row.id);
                            return html;
                        },
                    }
                },
            ];

            var $options = {
                select: true,
                autoWidth: false,
                ajax: {
                    url: '{!! route('backend.contact.index') !!}',
                },
                columns: [],
                columnDefs: [],
            };
            var event = $.Event('wa.datatable.options.{{jSID()}}');
            event.table_options = $options;
            event.table_html= $('#datatable_{{jSID()}}')[0];
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
            $table.on('preXhr.dt', function ( e, settings, data ) {
                data.handled = $('#datatable_{!! jSID() !!}_wrapper .handled_filter select').val();
                var date_start = $('#{!! jSID('date_filter') !!}').data('start');
                var date_end = $('#{!! jSID('date_filter') !!}').data('end');
                if (date_start && date_end){
                    data.date_start = date_start;
                    data.date_end = date_end;
                }
            } );


            $table.on( 'draw', function (e, settings) {
                $(e.target).find('.action-delete').click(function () {
                    var items = $table.rows(':eq('+$(this).parents('tr').index()+')', { page: 'current' })
                    delete_rows(items);
                });

                $('#datatable_{{jSID()}} .editable-handled').editable({
                    type: 'select2',
                    highlight: false,
                    source: [
                        {id: 1, text: '{{__('Rồi')}}'},
                        {id: 0, text: '{{__('Chưa')}}'},
                    ],
                    display: function (value, sourceData) {
                        $(this).removeClass('label-success');
                        $(this).removeClass('label-warning');
                        if (value == 1) {
                            $(this).find('span').html('{{__('Rồi')}}');
                            $(this).addClass('label-success');
                        }
                        else {
                            $(this).find('span').html('{{__('Chưa')}}')
                            $(this).addClass('label-warning');
                        }
                    },
                    select2: {
                        minimumResultsForSearch: Infinity,
                        width: 200
                    },
                    name: 'handled',
                    send: 'always',
                    params: function(params) {
                        params._method = 'put';
                        params.handled = params.value;
                        params.ids = [params.pk];
                        return params;
                    },
                    ajaxOptions: {
                        type: 'post',
                        dataType: 'json'
                    },
                    url: '{!! route('backend.contact.handle') !!}',
                    error: function (response) {
                        return xEditableResponseHandle(response, '{!! __('Lỗi không xác định!') !!}');
                    },
                    success: function () {
                        $table.draw('page');
                    }
                });

                $('#datatable_{{jSID()}} .action-view').click(function () {
                    e.stopPropagation();
                    var items = $table.rows(':eq('+$(this).parents('tr').index()+')', { page: 'current' })
                    var contact = items.data()[0];
                    $('#view_detail_modal .name-placeholder').html(contact.name);
                    $('#view_detail_modal .email-placeholder').html(contact.email);
                    $('#view_detail_modal .phone-placeholder').html(contact.phone);
                    $('#view_detail_modal .content-placeholder').html(contact.content);
                    $('#view_detail_modal .contact-id').val(contact.id);
                    $('#view_detail_modal').modal('show');
                    return false;
                });
            });

            $table.on('preInit.dt', function (e) {
                var node = $('#{{jSID('filter_tpl')}}').html();
                node = $(node);
                $(e.target).parents('.dataTables_wrapper').find('.datatable-header').append(node);
                $(node).find('select').select2({
                    minimumResultsForSearch: Infinity,
                    width: "150px",
                }).on('change', function () {
                    $table.draw('page');
                });
                $(node).find('.date_filter').daterangepicker({
                    autoUpdateInput: false,
                    applyClass: 'bg-slate-600',
                    cancelClass: 'btn-default',
                    locale: {
                        format: 'YYYY/MM/DD',
                        applyLabel: '{{__('Đồng ý')}}',
                        cancelLabel: '{{__('Hủy chọn')}}',
                        startLabel: '{{__('Bắt đầu')}}',
                        endLabel: '{{__('Kết thúc')}}',
                        customRangeLabel: '{{__('Chọn khoản thời gian')}}',
                        daysOfWeek: ['{{__('CN')}}', '{{__('T2')}}', '{{__('T3')}}', '{{__('T4')}}', '{{__('T5')}}', '{{__('T6')}}','{{__('T7')}}'],
                        monthNames: ['{{__('THÁNG 01')}} ', '{{__('THÁNG 02')}} ', '{{__('THÁNG 03')}} ', '{{__('THÁNG 04')}} ', '{{__('THÁNG 05')}} ', '{{__('THÁNG 06')}} ', '{{__('THÁNG 07')}} ', '{{__('THÁNG 08')}} ', '{{__('THÁNG 09')}} ', '{{__('THÁNG 10')}} ', '{{__('THÁNG 11')}} ', '{{__('THÁNG 12')}} '],
                    }
                });
                $(node).find('.date_filter').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
                    $(this).data('start', picker.startDate.format('YYYY-MM-DD 00:00:00'));
                    $(this).data('end', picker.endDate.format('YYYY-MM-DD 23:59:59'));
                    $table.draw('page');
                });
                $(node).find('.date_filter').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    $(this).data('start', '');
                    $(this).data('end', '');
                    $table.draw('page');
                });
            });
            $('.datatable-comment-actions .action-delete').click(function () {
                var items = $table.rows( { selected: true } );
                delete_rows(items);
            });
            
            
            $('.datatable-comment-actions .action-handle').click(function () {
                var items = $table.rows( { selected: true } );
                var handled = $(this).data('handled');
                handle_rows(items, handled);
            });

            $('.datatable-comment-actions .action-emails').click(function () {
                var mail_list = [];
                $.ajax({
                    url: '{!! route('backend.contact.mail_list.get') !!}',
                    type: 'get',
                    success: function (json) {
                        $('#view_admin_email textarea').val(json);
                        $email_modal.modal('show');
                    },
                    error: function () {

                    }
                });
            });

            function handle_rows(items, handled) {
                if (items.count() == 0){
                    swal({
                        title: "{{__('Không thể thực thi')}}",
                        text: "{{__('Vui lòng chọn ít nhất 1 liên hệ để xử lý')}}",
                        confirmButtonColor: "#EF5350",
                        confirmButtonText: "{{__('Đã hiểu')}}",
                        type: "error"
                    });
                    return;
                }

                var ids = [];
                $.each(items.data(), function () {
                    ids.push(this.id);
                });

                $.ajax({
                    url: '{!! route('backend.contact.handle') !!}',
                    dataType: 'json',
                    type: 'post',
                    data: {
                        ids: ids,
                        _method: 'put',
                        handled: handled
                    },
                    success: function () {
                        new PNotify({
                            title: '{{'XỬ LÝ THÀNH CÔNG'}}',
                            text: "{{__('Liên hệ được chọn đã xử lý thành công.')}}",
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
                            text: data.responseJSON,
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

            function delete_rows(items) {
                if (items.count() == 0){
                    swal({
                        title: "{{__('Không thể thực thi')}}",
                        text: "{{__('Vui lòng chọn ít nhất 1 liên hệ để xóa')}}",
                        confirmButtonColor: "#EF5350",
                        confirmButtonText: "{{__('Đã hiểu')}}",
                        type: "error"
                    });
                    return;
                }

                swal({
                        title: "{{__('Xóa liên hệ')}}",
                        text: "Bạn có chắc chắn muốn xóa hay không?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#EF5350",
                        confirmButtonText: "{{__('Đồng ý')}}",
                        cancelButtonText: "{{__('Hủy bỏ')}}",
                        closeOnConfirm: true,
                        closeOnCancel: true
                    },
                    function(isConfirm){
                        if (isConfirm){
                            var ids = [];
                            $.each(items.data(), function () {
                                ids.push(this.id);
                            });
                            $.ajax({
                                url: '{!! route('backend.contact.destroy') !!}',
                                dataType: 'json',
                                type: 'post',
                                data: {
                                    ids: ids,
                                    _method: 'delete'
                                },
                                success: function () {
                                    new PNotify({
                                        title: '{{'XỬ LÝ THÀNH CÔNG'}}',
                                        text: "{{__('Liên hệ được chọn đã xóa thành công.')}}",
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
                                        text: data.responseJSON,
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