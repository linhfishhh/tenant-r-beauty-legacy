@enqueueJSByID(config('view.ui.files.js.datatables.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.datatables_fixed_header.id'), JS_LOCATION_DEFAULT, 'datatables')
@enqueueJSByID(config('view.ui.files.js.datatables_select.id'), JS_LOCATION_DEFAULT, 'datatables')
@enqueueJSByID(config('view.ui.files.js.editable.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.sweet_alert.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.pnotify.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@extends('layouts.backend')
@section('page_title', __('Thư viện menu'))
@section('page_header_title')
    {!! __('Thư viện <strong>menu</strong>') !!}
@endsection
@section('sidebar_second')
    @include('backend.pages.menu.includes.menu_items')
@endsection
@section('page_content_body')
    @event(new \App\Events\BeforeHtmlBlock('content'))
    @component('backend.components.panel', ['classes'=>'panel-default', 'has_body' => false])
        @slot('content')
            <table data-table-name="{{str_slug(Route::currentRouteName())}}" id="datatable_{{str_slug(Route::currentRouteName())}}" class="table table-condensed table-hover">
                <thead>
                <tr></tr>
                </thead>
            </table>
            <template id="{{str_slug(Route::currentRouteName())}}_row_action_tpl">
                <a title="{{__('Sửa')}}" href="{link}" class="label label-success label-icon label-rounded"><i class="icon-pencil"></i></a>
                <a title="{{__('Xóa')}}" class="label label-warning label-icon label-rounded action-delete"><i class="icon-trash"></i></a>
            </template>
            <template id="{{str_slug(Route::currentRouteName())}}_users_count_tpl">
                <span class="badge badge-info">{count}</span>
            </template>
        @endslot
    @endcomponent
    @event(new \App\Events\AfterHtmlBlock('content'))
@endsection
@push('page_footer_js')
    @include('backend.settings.datatable.default')
    @include('backend.settings.xeditable.default')
    <script type="text/javascript">
        $(function () {
            $(window).trigger({
                type: 'wa.datatable.init.{{str_slug(Route::currentRouteName())}}',
                table_html: $('#datatable_{{str_slug(Route::currentRouteName())}}')[0]
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
                    data: 'title',
                    name: 'title',
                    title_html: '<th>{!! __('Tên') !!}</th>',
                    cell_define: {
                        render: function (data, type, row) {
                            return '<a class="text-teal text-semibold" href="'+row.link+'">'+data+'</a>';
                        },
                    }
                },
                {
                    data: 'items_count',
                    name: 'items_count',
                    title_html: '<th>{!! __('Item con') !!}</th>',
                    cell_define: {
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                        width: "100px",
                        render: function (data, type, row) {
                            var html = $('#{{str_slug(Route::currentRouteName())}}_users_count_tpl').html();
                            html = html.replace(/{count}/, row.items_count);
                            return html;
                        },
                    }
                }
                ,
                {
                    data: '',
                    name: 'actions',
                    title_html: '<th>{!! __('Hành động') !!}</th>',
                    cell_define: {
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                        width: "200px",
                        render: function (data, type, row) {
                            var html = $('#{{str_slug(Route::currentRouteName())}}_row_action_tpl').html();
                            html = html.replace(/{link}/, row.link);
                            return html;
                        },
                    }
                }
            ];
            var $options = {
                select: true,
                autoWidth: false,
                ajax: '{!! route('backend.menu.library.index') !!}',
                columns: [],
                columnDefs: []
            };
            var event = $.Event('wa.datatable.options.{{str_slug(Route::currentRouteName())}}');
            event.table_options = $options;
            event.table_html= $('#datatable_{{str_slug(Route::currentRouteName())}}')[0];
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
            var $table = $('#datatable_{{str_slug(Route::currentRouteName())}}').DataTable(event.table_options);

            $table.on( 'draw', function (e) {
                $(e.target).find('.action-delete').click(function () {
                    var items = $table.rows(':eq('+$(this).parents('tr').index()+')', { page: 'current' })
                    delete_rows(items);
                });
            });

            function delete_rows(items) {
                if (items.count() == 0){
                    swal({
                        title: "{{__('Không thể thực thi')}}",
                        text: "{{__('Vui lòng chọn ít nhất 1 menu để xóa')}}",
                        confirmButtonColor: "#EF5350",
                        confirmButtonText: "{{__('Đã hiểu')}}",
                        type: "error"
                    });
                    return;
                }

                swal({
                        title: "{{__('Xóa menu')}}",
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
                                url: '{!! route('backend.menu.destroy') !!}',
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
                type: 'wa.datatable.ran.{{str_slug(Route::currentRouteName())}}',
                table_object: $table,
                table_html: $('#datatable_{{str_slug(Route::currentRouteName())}}')[0]
            });
        });
    </script>
@endpush