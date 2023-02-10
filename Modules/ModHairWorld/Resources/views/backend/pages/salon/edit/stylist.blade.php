@php
    /** @var \Modules\ModHairWorld\Entities\Salon $salon */
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
                    {{__('THÊM STYLIST')}}
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
            <h6 class="panel-title text-teal text-semibold">{!! __('ĐỘI NGŨ STYLIST') !!}</h6>
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
                        <div class="form-group">
                            <label class="control-label text-semibold">{!! __('Tên stylist') !!}</label>
                            <input class="form-control" spellcheck="false" name="name" value="" placeholder="{!! __('Nhập tên stylist') !!}">
                        </div>
                        <div class="form-group">
                            <label class="control-label text-semibold">{!! __('Ảnh avatar stylist') !!}</label>
                            <div class="avatar-picker"></div>
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
    <template id="{!! jSID('avatar_field') !!}">
        @component('backend.components.field', [
            'field' => $avatar_field,
            'unhandled' => true
        ])
        @endcomponent
    </template>
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
            $('#{{jSID('add_manager')}}').click(function () {
                addEditStylist(null);
            });
            $modal_add.find('form').submit(function(){
                var form = this;
                var data = {
                    'name' : $(form).find('input[name=name]').val(),
                    'avatar_id' : $(form).find('input[name=avatar_id]').val(),
                };
                var edit_mode = $(form).data('edit-mode');
                var url = '{!! route('backend.salon.stylist.store', ['salon'=>$salon]) !!}';
                if (edit_mode) {
                    url = '{!! route('backend.salon.stylist.update', ['salon'=>$salon]) !!}';
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

            function addEditStylist($stylist){
                var picker = $modal_add.find('.avatar-picker');
                picker.html('');
                var html = $('#{!! jSID('avatar_field') !!}').html();
                picker.html(html);
                var avatar_id = '';
                if ($stylist){
                    $modal_add.find('.modal-title').html('{!! __('SỬA STYLIST') !!}');
                    $modal_add.find('form').data('edit-mode', true);
                    $modal_add.find('form input[name=name]').val($stylist.name);
                    avatar_id = $stylist.avatar_id;
                    $modal_add.find('form').data('edit-target', $stylist.id);
                }
                else{
                    $modal_add.find('form input[name=name]').val('');
                    $modal_add.find('.modal-title').html('{!! __('THÊM STYLIST') !!}');
                    $modal_add.find('form').data('edit-mode', false);
                }
                {!! $avatar_field->getHtmlTemplateHandleID() !!}(picker, avatar_id);
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
                    data: 'avatar',
                    name: 'avatar',
                    title_html: '<th>{!! __('Avatar') !!}</th>',
                    cell_define: {
                        width: "100px",
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            var html ='<img src="'+data+'" class="full-width thumb-rounded no-margin edit-stylist cursor-pointer" />';
                            return html;
                        }
                    }
                },
                {
                    data: 'name',
                    name: 'name',
                    title_html: '<th>{!! __('Tên stylist') !!}</th>',
                    cell_define: {
                        render: function (data, type, row) {
                            return '<div><span class="edit-stylist cursor-pointer text-semibold text-teal">'+data+'</div>';
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
                            var html = '<a data-id="'+row.id+'" class="label label-warning label-icon cursor-pointer delete-stylist mr-10"><i class="icon-trash"></i></a>';
                            html += '<a data-id="'+row.id+'" class="edit-stylist label label-success label-icon cursor-pointer edit-stylist"><i class="icon-pencil5"></i></a>';
                            return html;
                        }
                    }
                },
            ];
            var $options = {
                select: false,
                autoWidth: false,
                ajax: '{!! route('backend.salon.stylist.edit', ['salon' => $salon]) !!}',
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
                $(e.target).find('.delete-stylist').click(function () {
                    var ids = [];
                    var id = $(this).data('id');
                    ids.push(id);
                    deleteStylist(ids);
                });
                $(e.target).find('.edit-stylist').click(function () {
                    var items = $table.rows(':eq(' + $(this).parents('tr').index() + ')', {page: 'current'});
                    addEditStylist(items.data()[0]);
                });
            });
            function deleteStylist(ids){
                if (ids.length == 0) {
                    swal({
                        title: "{{__('Không thể thực thi')}}",
                        text: "{!! __('Vui lòng chọn 1 stylist để xóa') !!}",
                        confirmButtonColor: "#EF5350",
                        confirmButtonText: "{{__('Đã hiểu')}}",
                        type: "error"
                    });
                    return;
                }
                swal({
                        title: "{!! __('Xóa stylist') !!}",
                        text: "{!! __('Bạn có chắc chắn muốn xóa stylist này không?') !!}",
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
                                url: '{!! route('backend.salon.stylist.destroy', ['salon' => $salon]) !!}',
                                dataType: 'json',
                                type: 'post',
                                data: {
                                    ids: ids,
                                    _method: 'delete'
                                },
                                success: function () {
                                    new PNotify({
                                        title: '{{'XỬ LÝ THÀNH CÔNG'}}',
                                        text: "{!! __('Xóa stylist thành công') !!}",
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