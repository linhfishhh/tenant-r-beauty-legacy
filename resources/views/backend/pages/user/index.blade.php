@php
/** @var \App\Role[] $roles */
@endphp
@enqueueJSByID(config('view.ui.files.js.datatables.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.datatables_fixed_header.id'), JS_LOCATION_DEFAULT, 'datatables')
@enqueueJSByID(config('view.ui.files.js.datatables_select.id'), JS_LOCATION_DEFAULT, 'datatables')
@enqueueJSByID(config('view.ui.files.js.editable.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.sweet_alert.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.pnotify.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@section('page_title', __('Tài khoản người dùng'))
@section('page_header_title')
    <span class="text-semibold">Tài khoản</span> người dùng
@endsection
@extends('layouts.backend')
@section('sidebar_second')
    @event(new \App\Events\BeforeHtmlBlock('sidebar.groups'))
    @hasPermission('manage_users')
    <div class="sidebar-category">
        <div class="category-title">
            <span>{{__('Tài khoản')}}</span>
            <ul class="icons-list">
                <li><a href="#" data-action="collapse" class=""></a></li>
            </ul>
        </div>

        <div class="category-content no-padding" style="display: block;">
            <ul class="navigation navigation-alt navigation-accordion datatable-users-actions">
                @event(new \App\Events\BeforeHtmlBlock('sidebar.groups.account'))
                <li><a href="{{route('backend.user.create')}}"><i class="icon-user-plus"></i> {{__('Thêm tài khoản')}}</a></li>
                <li class="delete-users"><a><i class="icon-user-minus"></i> {{__('Xóa tài khoản')}}</a></li>
                @event(new \App\Events\AfterHtmlBlock('sidebar.groups.account'))
            </ul>
        </div>
    </div>
    @endif
    @hasPermission('manage_roles')
    <div class="sidebar-category">
        <div class="category-title">
            <span>{{__('Vai trò')}}</span>
            <ul class="icons-list">
                <li><a href="#" data-action="collapse" class=""></a></li>
            </ul>
        </div>

        <div class="category-content no-padding" style="display: block;">
            <ul class="navigation navigation-alt navigation-accordion">
                @event(new \App\Events\BeforeHtmlBlock('sidebar.groups.role'))
                <li><a href="{{route('backend.role.create')}}"><i class="icon-users2"></i> {{__('Thêm vai trò')}}</a></li>
                <li><a href="{{route('backend.role.index')}}"><i class="icon-users"></i> {{__('Danh sách vai trò')}}</a></li>
                @event(new \App\Events\AfterHtmlBlock('sidebar.groups.role'))
            </ul>
        </div>
    </div>
    @endif
    @event(new \App\Events\AfterHtmlBlock('sidebar.groups'))
@endsection
@section('page_content_body')
    @event(new \App\Events\BeforeHtmlBlock('content'))
    @component('backend.components.panel', ['classes'=>'panel-default', 'has_body' => false])
        @slot('content')
            <ul id="{!! jSID('view_mode') !!}" class="nav nav-tabs nav-tabs-highlight no-margin-bottom">
                <li data-value="-1" class="active"><a data-toggle="tab">{{__('Tất cả')}} <span class="badge badge-success position-right">0</span></a></li>
                @foreach($roles as $role)
                    <li data-value="{!! $role->id !!}"><a data-toggle="tab">{!! $role->title !!} <span class="badge bg-orange position-right">0</span></a></li>
                @endforeach
            </ul>
            <table data-table-name="{{Route::currentRouteName()}}" id="datatable_{{jSID()}}" class="table table-condensed table-hover">
                <thead>
                    <tr></tr>
                </thead>
            </table>
            <template id="{{str_slug(Route::currentRouteName())}}_tpl">
                <ul class="icons-list">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-menu9"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a class="action-edit" href="{link}"><i class="icon-pencil5 text-blue"></i> {{__('Sửa')}}</a></li>
                            <li><a class="action-delete"><i class="icon-trash text-danger"></i> {{__('Xóa')}}</a></li>
                        </ul>
                    </li>
                </ul>
            </template>
            @push('page_footer_js')
                @include('backend.settings.datatable.default')
                @include('backend.settings.xeditable.default')
                <script type="text/javascript">
                    $(document).ready(function () {
                        $(function () {
                            $(window).trigger({
                                type: 'wa.datatable.init.{{Route::currentRouteName()}}',
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
                                    data: 'avatar',
                                    name: 'avatar_id',
                                    title_html: '<th>{!! __('Avatar') !!}</th>',
                                    cell_define: {
                                        className: 'text-center',
                                        orderable: false,
                                        searchable: false,
                                        width: '65px',
                                        render: function (data, type, row) {
                                            return '<img width="50" src="'+data+'" />';
                                        },
                                    }
                                },
                                {
                                    data: 'email',
                                    name: 'email',
                                    title_html: '<th>{!! __('Email') !!}</th>',
                                    cell_define: {
                                        render: function (data, type, row) {
                                            if(row.is_ultimate_user && !row.is_me){
                                                var html =  '<a class="text-danger text-semibold">'+data+'</a>';
                                            }
                                            else{
                                                var html = '<a class="text-teal text-semibold" href="'+row.link+'">'+data+'</a>';
                                            }
                                            if(row.is_me){
                                                html += '<div><span class="label bg-orange mt-5">{{__('Tài khoản của bạn')}}</span></div>'
                                            }
                                            if(row.is_ultimate_user){
                                                html += '<div><span class="label bg-danger mt-5">{{__('Tài khoản nhà phát triển')}}</span></div>'
                                            }
                                            return html;
                                        },
                                    }
                                },
                                {
                                    data: 'phone',
                                    name: 'phone',
                                    title_html: '<th>{!! __('Phone') !!}</th>',
                                    cell_define: {
                                        render: function (data, type, row) {
                                            return '<span class="text-primary-800 text-semibold">'+data+'</span>';
                                        },
                                    }
                                },
                                {
                                    data: 'name',
                                    name: 'name',
                                    title_html: '<th>{!! __('Tên') !!}</th>',
                                    cell_define: {
                                        render: function (data, type, row) {
                                            if(row.is_ultimate_user && !row.is_me){
                                                return '<a class="text-danger">'+data+'</a>';
                                            }
                                            else{
                                                return '<a data-pk="'+row.id+'" href="#" class="editable editable-click editable-name">'+data+'</a>';
                                            }
                                        },
                                    }
                                },
                                {
                                    data: 'role_id',
                                    name: 'role_id',
                                    title_html: '<th>{!! __('Vai trò') !!}</th>',
                                    cell_define: {
                                        render: function (data, type, row) {
                                            if(row.is_ultimate_user && !row.is_me){
                                                return '<a class="text-danger">'+row.role+'</a>';
                                            }
                                            else if(row.is_me){
                                                return '<a class="text-orange">'+row.role+'</a>';
                                            }
                                            else{
                                                return '<a data-pk="'+row.id+'" data-value="'+row.role_id+'" href="#" class="editable editable-click editable-role">'+row.role+'</a>';
                                            }
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
                                            var html = $('#{{str_slug(Route::currentRouteName())}}_tpl').html();
                                            html = html.replace(/{link}/, row.link);
                                            return html;
                                        },
                                    }
                                },
                            ];

                            var $options = {
                                select: true,
                                autoWidth: false,
                                ajax: '{!! route('backend.user.index') !!}',
                                columns: [],
                                columnDefs: []
                            };
                            var event = $.Event('wa.datatable.options.{{Route::currentRouteName()}}');
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
                            $table.on('preXhr.dt', function (e, settings, data) {
                                var view_mode = $('#{!! jSID('view_mode') !!} li.active').data('value');
                                data.role_id = view_mode;
                            });
                            $table.on( 'draw', function (e, settings) {
                                var counts = settings.json.counts;
                                $('#{!! jSID('view_mode') !!} li[data-value=-1] .badge').html(counts['-1']);
                                @foreach($roles as $role)
                                    $('#{!! jSID('view_mode') !!} li[data-value={!! $role->id !!}] .badge').html(counts['{!! $role->id !!}']);
                                @endforeach
                                $('#datatable_{{jSID()}} .no-self-edit').click(function(){
                                    swal({
                                        title: "{{__('Không thể thực thi')}}",
                                        text: "{{__('Bạn không thể thay đổi vai trò của chính bạn')}}",
                                        confirmButtonColor: "#EF5350",
                                        confirmButtonText: "{{__('Đã hiểu')}}",
                                        type: "error"
                                    });
                                    return false;
                                });
                                $('#datatable_{{jSID()}} .no-ultimate-edit').click(function(){
                                    swal({
                                        title: "{{__('Không thể thực thi')}}",
                                        text: "{{__('Bạn không thể chỉnh sửa thông tin của người này')}}",
                                        confirmButtonColor: "#EF5350",
                                        confirmButtonText: "{{__('Đã hiểu')}}",
                                        type: "error"
                                    });
                                    return false;
                                });
                                $('#datatable_{{jSID()}} .editable-name').editable({
                                    tpl: '<input type="text" spellcheck="false">',
                                    type: 'text',
                                    name: 'name',
                                    send: 'always',
                                    params: function(params) {
                                        params._method = 'put';
                                        return params;
                                    },
                                    ajaxOptions: {
                                        type: 'post',
                                        dataType: 'json',
                                    },
                                    url: '{!! route('backend.user.put') !!}',
                                    error: function(response, newValue) {
                                        return xEditableResponseHandle(response, '{!! __('Lỗi không xác định!') !!}');
                                    }
                                });
                                $('#datatable_{{jSID()}} .editable-role').editable({
                                    type: 'select2',
                                    //tpl: '<select>',
                                    source: {!! json_encode(\App\Role::getHtmlSelectData()) !!},
                                    select2: {
                                        minimumResultsForSearch: Infinity,
                                        width: 200
                                    },
                                    name: 'role_id',
                                    send: 'always',
                                    params: function(params) {
                                        params._method = 'put';
                                        return params;
                                    },
                                    ajaxOptions: {
                                        type: 'post',
                                        dataType: 'json'
                                    },
                                    url: '{!! route('backend.user.put') !!}',
                                    error: function(response) {
                                        return xEditableResponseHandle(response, '{!! __('Lỗi không xác định!') !!}');
                                    }
                                });
                                $(e.target).find('.action-delete').click(function () {
                                    var items = $table.rows(':eq('+$(this).parents('tr').index()+')', { page: 'current' })
                                    delete_users(items);
                                });

                            });

                            $('#{!! jSID('view_mode') !!} li').on('shown.bs.tab', function (e) {
                                $table.draw('page');
                            });

                            $(window).trigger({
                                type: 'wa.datatable.ran.{{Route::currentRouteName()}}',
                                table_object: $table,
                                table_html: $('#datatable_{{jSID()}}')[0]
                            });

                            $('.datatable-users-actions .delete-users').click(function () {
                                var items = $table.rows( { selected: true } );
                                delete_users(items);
                            });

                            function delete_users(items) {
                                if (items.count() == 0){
                                    swal({
                                        title: "{{__('Không thể thực thi')}}",
                                        text: "{{__('Vui lòng chọn ít nhất 1 tài khoản để xóa')}}",
                                        confirmButtonColor: "#EF5350",
                                        confirmButtonText: "{{__('Đã hiểu')}}",
                                        type: "error"
                                    });
                                    return;
                                }

                                swal({
                                        title: "{{__('Xóa tài khoản')}}",
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
                                                url: '{!! route('backend.user.destroy') !!}',
                                                dataType: 'json',
                                                type: 'post',
                                                data: {
                                                    ids: ids,
                                                    _method: 'delete'
                                                },
                                                success: function () {
                                                    new PNotify({
                                                        title: '{{'XỬ LÝ THÀNH CÔNG'}}',
                                                        text: "{{__('Tài khoản được chọn đã xóa thành công.')}}",
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