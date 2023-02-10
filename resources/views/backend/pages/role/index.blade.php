@enqueueJSByID(config('view.ui.files.js.datatables.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.datatables_fixed_header.id'), JS_LOCATION_DEFAULT, 'datatables')
@enqueueJSByID(config('view.ui.files.js.datatables_select.id'), JS_LOCATION_DEFAULT, 'datatables')
@enqueueJSByID(config('view.ui.files.js.editable.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.sweet_alert.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.pnotify.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@extends('layouts.backend')
@section('sidebar_second')
    @event(new \App\Events\BeforeHtmlBlock('sidebar.groups'))
    @hasPermission('manage_roles')
    <div class="sidebar-category">
        <div class="category-title">
            <span>{{__('Vai trò')}}</span>
            <ul class="icons-list">
                <li><a href="#" data-action="collapse" class=""></a></li>
            </ul>
        </div>

        <div class="category-content no-padding" style="display: block;">
            <ul class="navigation navigation-alt navigation-accordion datatable-roles-actions">
                @event(new \App\Events\BeforeHtmlBlock('sidebar.groups.role'))
                <li><a href="{{route('backend.role.create')}}"><i class="icon-users2"></i> {{__('Thêm vai trò')}}</a></li>
                <li class="move-roles"><a><i class="icon-transmission"></i> {{__('Chuyển vai trò')}}</a></li>
                @event(new \App\Events\AfterHtmlBlock('sidebar.groups.role'))
            </ul>
        </div>
    </div>
    @endif
    @hasPermission('manage_users')
    <div class="sidebar-category">
        <div class="category-title">
            <span>{{__('Tài khoản')}}</span>
            <ul class="icons-list">
                <li><a href="#" data-action="collapse" class=""></a></li>
            </ul>
        </div>

        <div class="category-content no-padding" style="display: block;">
            <ul class="navigation navigation-alt navigation-accordion">
                @event(new \App\Events\BeforeHtmlBlock('sidebar.groups.account'))
                <li><a href="{{route('backend.user.create')}}"><i class="icon-user-plus"></i> {{__('Thêm tài khoản')}}</a></li>
                <li><a href="{{route('backend.user.index')}}"><i class="icon-users4"></i> {{__('Danh sách tài khoản')}}</a></li>
                @event(new \App\Events\AfterHtmlBlock('sidebar.groups.account'))
            </ul>
        </div>
    </div>
    @endif
    @event(new \App\Events\AfterHtmlBlock('sidebar.groups'))
@endsection
@section('page_title', __('Vai trò người dùng'))
@section('page_header_title')
    <span class="text-semibold">Vai trò</span> người dùng
@endsection
@section('sidebar_second_items')
    <div class="sidebar-category">
        <div class="category-title">
            <span>Search</span>
            <ul class="icons-list">
                <li><a href="#" data-action="collapse"></a></li>
            </ul>
        </div>

        <div class="category-content">
            <form action="#">
                <div class="has-feedback has-feedback-left">
                    <input type="search" class="form-control" placeholder="Search">
                    <div class="form-control-feedback">
                        <i class="icon-search4 text-size-base text-muted"></i>
                    </div>
                </div>
            </form>
        </div>
    </div>
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
            <template id="{{str_slug(Route::currentRouteName())}}_row_action_tpl">
                <a title="{{__('Sửa')}}" href="{link}" class="label label-success label-icon label-rounded"><i class="icon-pencil"></i></a>
                <a title="{{__('Chuyển vai trò')}}" class="label label-primary label-icon label-rounded action-move"><i class="icon-transmission"></i></a>
                <a title="{{__('Xóa')}}" class="label label-warning label-icon label-rounded action-delete"><i class="icon-trash"></i></a>
            </template>
            <template id="{{str_slug(Route::currentRouteName())}}_users_count_tpl">
                <span class="badge badge-info">{count}</span>
            </template>
            <div id="{{str_slug(Route::currentRouteName())}}_change_role" class="modal fade">
                <div class="modal-dialog modal-xs">
                    <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h5 class="modal-title">{{__('Chuyển vai trò')}}</h5>
                        </div>

                        <div class="modal-body">
                            <div class="form-group">
                                <label class="control-label">{{__('Chọn vai trò mới cho những tài khoản thuộc vai trò hiện tại')}}</label>
                                <select data-width="100%">

                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-link" data-dismiss="modal">{{__('Hủy bỏ')}}</button>
                            <button type="button" class="btn btn-danger execute-action">{{__('Thực thi')}}</button>
                        </div>
                    </div>
                </div>
            </div>
            @push('page_footer_js')
                @include('backend.settings.datatable.default')
                @include('backend.settings.xeditable.default')
                <script type="text/javascript">
                    $(document).ready(function () {
                        $(function () {

                            $(window).trigger({
                                type: 'wa.datatable.init.{{str_slug(Route::currentRouteName())}}',
                                table_html: $('#datatable_{{jSID()}}')[0]
                            });

                            var $table_column_handles = [
                                {
                                    data: 'id',
                                    name: 'id',
                                    title_html: '<th>{!! __('ID') !!}</th>',
                                    cell_define: {
                                        render: function (data, type, row) {
                                            return data;
                                        },
                                    }
                                },
                                {
                                    data: 'title',
                                    name: 'title',
                                    title_html: '<th>{!! __('Tên') !!}</th>',
                                    cell_define: {
                                        render: function (data, type, row) {
                                            var html = '<a class="text-teal text-semibold" href="'+row.link+'">'+data+'</a>';
                                            if(row.is_my_role){
                                                html += '<div><span class="label bg-orange mt-5">{{__('Vai trò của bạn')}}</span></div>';
                                            }
                                            if(row.is_ultimate_role){
                                                html += '<div><span class="label bg-danger mt-5">{{__('Vai trò nhà phát triển')}}</span></div>';
                                            }
                                            return html;
                                        },
                                    }
                                },
                                {
                                    data: 'desc',
                                    name: 'desc',
                                    title_html: '<th>{!! __('Mô tả') !!}</th>',
                                    cell_define: {
                                        render: function (data, type, row) {
                                            return '<a data-type="textarea" data-pk="'+row.id+'" href="#" class="editable editable-click editable-desc">'+data+'</a>';
                                        },
                                    }
                                },
                                {
                                    data: 'users_count',
                                    name: 'users_count',
                                    title_html: '<th>{!! __('Tài khoản') !!}</th>',
                                    cell_define: {
                                        className: 'text-center',
                                        orderable: false,
                                        searchable: false,
                                        render: function (data, type, row) {
                                            var html = $('#{{str_slug(Route::currentRouteName())}}_users_count_tpl').html();
                                            html = html.replace(/{count}/, row.users_count);
                                            return html;
                                        },
                                    }
                                },
                                {
                                    data: '',
                                    name: 'actions',
                                    title_html: '<th>{!! __('Hành động') !!}</th>',
                                    cell_define: {
                                        className: 'text-center',
                                        orderable: false,
                                        searchable: false,
                                        render: function (data, type, row) {
                                            var html = $('#{{str_slug(Route::currentRouteName())}}_row_action_tpl').html();
                                            html = html.replace(/{link}/, row.link);
                                            return html;
                                        },
                                    }
                                },
                            ];

                            var $options = {
                                select: true,
                                autoWidth: false,
                                ajax: '{!! route('backend.role.index') !!}',
                                columns: [],
                                columnDefs: []
                            };
                            var event = $.Event('wa.datatable.options.{{str_slug(Route::currentRouteName())}}');
                            event.table_options = $options;
                            event.table_html= $('#datatable_{{jSID()}}')[0];
                            event.table_column_handles = $table_column_handles;
                            $(window).trigger(event);
                            $.each(event.table_column_handles, function (i, v) {
                                $('#datatable_{{jSID()}}>thead>tr').append(v.title_html);
                                $options.columns.push({
                                    name: v.name,
                                    data: v.data
                                });
                                var h = v.cell_define;
                                h.targets = i;
                                $options.columnDefs.push(h);
                            });
                            var $table = $('#datatable_{{jSID()}}').DataTable(event.table_options);

                            $table.on( 'draw', function (e) {
                                $('#datatable_{{jSID()}} .editable-desc').editable({
                                    tpl: '<textarea spellcheck="false">',
                                    name: 'desc',
                                    send: 'always',
                                    params: function(params) {
                                        params._method = 'put';
                                        return params;
                                    },
                                    ajaxOptions: {
                                        type: 'post',
                                        dataType: 'json',
                                    },
                                    url: '{!! route('backend.role.put') !!}',
                                    error: function(response, newValue) {
                                        return xEditableResponseHandle(response, '{!! __('Lỗi không xác định!') !!}');
                                    }
                                });

                                $(e.target).find('.action-delete').click(function () {
                                    var items = $table.rows(':eq('+$(this).parents('tr').index()+')', { page: 'current' })
                                    delete_roles(items);
                                });

                                $(e.target).find('.action-move').click(function () {
                                    var items = $table.rows(':eq('+$(this).parents('tr').index()+')', { page: 'current' })
                                    move_roles(items);
                                });
                            });

                            $(window).trigger({
                                type: 'wa.datatable.ran.{{str_slug(Route::currentRouteName())}}',
                                table_object: $table,
                                table_html: $('#datatable_{{jSID()}}')[0]
                            });

                            $('.datatable-roles-actions .move-roles').click(function () {
                                var items = $table.rows( { selected: true } );
                                move_roles(items);
                            });

                            $('#{{str_slug(Route::currentRouteName())}}_change_role').modal({
                                show: false,
                                backdrop: 'static',
                                keyboard: false
                            });

                            $('#{{str_slug(Route::currentRouteName())}}_change_role .execute-action').click(function () {
                                var ids = $('#{{str_slug(Route::currentRouteName())}}_change_role').data('ids');
                                var new_role_id = $('#{{str_slug(Route::currentRouteName())}}_change_role select').val();
                                $.ajax({
                                    url: '{{route('backend.role.move')}}',
                                    type: 'post',
                                    dataType: 'json',
                                    data: {
                                        ids: ids,
                                        new_id: new_role_id
                                    },
                                    error: function (data) {
                                        new PNotify({
                                            title: '{{'LỖI XẢY RA'}}',
                                            text: data.responseJSON.message,
                                            addclass: 'bg-danger stack-bottom-right',
                                            stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25},
                                            buttons: {
                                                sticker: false
                                            },
                                            delay: 2000
                                        });
                                    },
                                    complete: function () {
                                        $('#{{str_slug(Route::currentRouteName())}}_change_role').modal('hide');
                                    },
                                    success: function (data) {
                                        $table.draw('page');
                                        new PNotify({
                                            title: '{{'XỬ LÝ THÀNH CÔNG'}}',
                                            text: "{{__('Đã chuyển tài khoản sang vai trò mới thành công')}}",
                                            addclass: 'bg-success stack-bottom-right',
                                            stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25},
                                            buttons: {
                                                sticker: false
                                            },
                                            delay: 2000
                                        });
                                    }
                                })
                            });

                            $('#{{str_slug(Route::currentRouteName())}}_change_role select').select2({
                                minimumInputLength: 0,
                                minimumResultsForSearch: -1
                            });
                            
                            function move_roles(items) {
                                if (items.count() == 0){
                                    swal({
                                        title: "{{__('Không thể thực thi')}}",
                                        text: "{{__('Vui lòng chọn ít nhất 1 vai trò thực thi')}}",
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
                                $('#{{str_slug(Route::currentRouteName())}}_change_role').data('ids', ids);
                                $.ajax({
                                    url: '{{route('backend.role.select')}}',
                                    type: 'get',
                                    dataType: 'json',
                                    complete: function () {
                                    },
                                    success: function (response) {
                                        $('#{{str_slug(Route::currentRouteName())}}_change_role').modal('show');
                                        $('#{{str_slug(Route::currentRouteName())}}_change_role select').empty().trigger("change");
                                        if(response.length > 0){
                                            $.each(response, function (i, v) {
                                                var newOption = new Option(v.text, v.id, false, false);
                                                $('#{{str_slug(Route::currentRouteName())}}_change_role select').append(newOption);
                                            });
                                            $('#{{str_slug(Route::currentRouteName())}}_change_role select').trigger('change');
                                        }
                                    }
                                });
                            }

                            function delete_roles(items) {
                                if (items.count() == 0){
                                    swal({
                                        title: "{{__('Không thể thực thi')}}",
                                        text: "{{__('Vui lòng chọn ít nhất 1 vai trò để xóa')}}",
                                        confirmButtonColor: "#EF5350",
                                        confirmButtonText: "{{__('Đã hiểu')}}",
                                        type: "error"
                                    });
                                    return;
                                }

                                swal({
                                        title: "{{__('Xóa vai trò')}}",
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
                                                url: '{!! route('backend.role.destroy') !!}',
                                                dataType: 'json',
                                                type: 'post',
                                                data: {
                                                    ids: ids,
                                                    _method: 'delete'
                                                },
                                                success: function () {
                                                    new PNotify({
                                                        title: '{{'XỬ LÝ THÀNH CÔNG'}}',
                                                        text: "{{__('Vai trò được chọn đã xóa thành công.')}}",
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
                                                        text: getJSONErrorMessage(data),
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
                        });
                    });
                </script>
            @endpush
        @endslot
    @endcomponent
    @event(new \App\Events\AfterHtmlBlock('content'))
@endsection