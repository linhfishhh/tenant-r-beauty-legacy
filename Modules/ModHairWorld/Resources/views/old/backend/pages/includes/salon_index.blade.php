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
    /** @var \App\Classes\PostType $post_type */
    /** @var \App\Classes\Comment $comment_type */
@endphp
@extends('layouts.backend')
@if($admin_mode)
    @section('page_title', __('Danh sách salon'))
@section('page_header_title')
    <strong>{{__('Danh sách salon')}}</strong>
@endsection
@else
    @section('page_title', __('Danh sách salon của tôi'))
@section('page_header_title')
    <strong>{{__('Danh sách salon của tôi')}}</strong>
@endsection
@endif
@if($admin_mode)
@section('sidebar_second')
    @event(new \App\Events\BeforeHtmlBlock('sidebar.groups'))
    <div class="sidebar-category" data-group-id="menu-lib">
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
                </li>
                <li>
                    <a class="action-published-on"><i class="icon-eye"></i> {{__('Cho phép hiển thị')}}</a>
                </li>
                <li>
                    <a class="action-published-off"><i class="icon-eye-blocked"></i> {{__('Không phép hiển thị')}}</a>
                </li>
                @event(new \App\Events\AfterHtmlBlock('sidebar.groups.actions'))
            </ul>
        </div>
    </div>
    @event(new \App\Events\AfterHtmlBlock('sidebar.groups'))
@endsection
@endif
@section('page_content_body')
    @event(new \App\Events\BeforeHtmlBlock('content'))
    @component('backend.components.panel', ['classes'=>'panel-default', 'has_body' => false])
        @slot('content')
            <table data-table-name="{{jSID()}}" id="datatable_{{jSID()}}" class="table table-condensed table-hover">
                <thead><tr></tr></thead>
            </table>
            <template id="{{jSID()}}_row_action_tpl">
                <ul class="icons-list">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-menu9"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a data-id="{id}" class="view-detail"><i class="icon-eye text-blue"></i> {{__('Xem chi tiết')}}</a></li>
                            <li><a class="action-delete"><i class="icon-trash text-danger"></i> {{__('Xóa bỏ')}}
                                </a></li>
                        </ul>
                    </li>
                </ul>
            </template>
            <template id="{{jSID()}}_row_main_block_tpl">
                <div class="media stack-media-on-mobile">
                    <div class="media-left">
                        <div class="thumb">
                            <a href="#">
                                <img src="{img}" class="img-responsive img-rounded media-preview" alt="">
                                <span class="zoom-image"><i class="icon-pencil7"></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading"><a href="#">{name}</a></h6>
                        <ul class="list-inline list-inline-separate">
                            <li class="{status_color}"><i class="{status_icon} position-left"></i> {status_text}</li>
                            <li class="text-muted">Hoạt động từ {date}</li>
                        </ul>
                        The him father parish looked has sooner. Attachment frequently gay terminated son. You greater nay use prudent placing passage to so distant behaved natural between do talking...
                    </div>
                </div>
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
                            var html = $('#{{jSID()}}_row_main_block_tpl').html();
                            html = html.replace('{img}', '{!! getNoThumbnailUrl() !!}');
                            html = html.replace('{name}', data);
                            if (row.open){
                                html = html.replace('{status_icon}', 'icon-checkmark');
                                html = html.replace('{status_text}', '{!! __('Đang hoạt động') !!}');
                                html = html.replace('{status_color}', 'text-success');
                            }
                            else{
                                html = html.replace('{status_icon}', 'icon-blocked');
                                html = html.replace('{status_text}', '{!! __('Tạm dừng hoạt động') !!}');
                                html = html.replace('{status_color}', 'text-danger');
                            }
                            html = html.replace('{date}', row.date);
                            return html;
                        }
                    }
                },
            ];

            var $options = {
                select: true,
                autoWidth: false,
                ajax: {
                    url: '{!! route('backend.salon.index') !!}',
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
                {{--data.user_id = $('.datatable-header select.filter-author').val();--}}
                {{--var view_mode = $('#{!! jSID('view_mode') !!} li.active').data('value');--}}
                {{--data.published = view_mode;--}}
                {{--var date_start = $('#{!! jSID('date_filter') !!}').data('start');--}}
                {{--var date_end = $('#{!! jSID('date_filter') !!}').data('end');--}}
                {{--data.created_at = [date_start, date_end];--}}
            } );

            $('#{!! jSID('view_mode') !!} li').on('shown.bs.tab', function (e) {
                $table.draw('page');
            });

            $table.on( 'draw', function (e, settings) {
                var data = settings.json.counts;
                {{--$('#{!! jSID('view_mode') !!} li[data-value=-1] .badge').html(data['-1']);--}}
                {{--$('#{!! jSID('view_mode') !!} li[data-value=1] .badge').html(data['1']);--}}
                {{--$('#{!! jSID('view_mode') !!} li[data-value=0] .badge').html(data['0']);--}}

                $(e.target).find('.action-delete').click(function () {
                    var items = $table.rows(':eq('+$(this).parents('tr').index()+')', { page: 'current' })
                    delete_rows(items);
                });
            });

            $table.on('preInit.dt', function (e) {
                var node = $('#{{jSID('more_filter_tpl')}}').html();
                node = $(node);
                $(e.target).parents('.dataTables_wrapper').find('.datatable-header').append(node);
                $(node).find('.filter-author').select2({
                    width: '200px',
                    allowClear: true,
                    placeholder: '{{__('Tác giả')}}',
                    id: function (item) {
                        return item.id;
                    },
                    escapeMarkup: function (markup) {
                        return markup;
                    },
                    templateResult: function(repo) {
                        if (repo.loading) {
                            return repo.text;
                        }

                        var markup = '<div class="text-semibold">' + repo.text + '</div>' +
                            '<div class="text-grey">' + repo.role + '</div>' +
                            '<div class="text-size-mini">' + repo.email + '</div>'
                        ;

                        return markup;
                    },
                    ajax: {
                        url: '{!! route('backend.user.select') !!}',
                        dataType: 'json',
                        data: function (params) {
                            var rs = {
                                search: params.term,
                            };
                            return rs;
                        },
                        processResults: function (data) {
                            $users = {};
                            var items = [];
                            $.each(data, function (i, v) {
                                $users[v.id] = v;
                                items.push(
                                    {
                                        id: v.id,
                                        text: v.name,
                                        email: v.email,
                                        role: v.role.title
                                    }
                                );
                            });
                            return {
                                results: items
                            };
                        }
                    },
                    minimumInputLength: 2,
                    //multiple: true
                });
                $(node).find('select').on('change', function () {
                    $table.draw('page');
                });
                var date_filter_node = $(node).find('.date_filter');
                $(date_filter_node).daterangepicker({
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
                $(date_filter_node).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
                    $(this).data('start', picker.startDate.format('YYYY-MM-DD 00:00:00'));
                    $(this).data('end', picker.endDate.format('YYYY-MM-DD 23:59:59'));
                    $table.draw('page');
                });
                $(date_filter_node).on('cancel.daterangepicker', function(ev, picker) {
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

            $('.datatable-comment-actions .action-published-off').click(function () {
                var items = $table.rows( { selected: true } );
                publish_rows(items, 0);
            });

            $('.datatable-comment-actions .action-published-on').click(function () {
                var items = $table.rows( { selected: true } );
                publish_rows(items, 1);
            });


            function publish_rows(items, value) {
                if (items.count() == 0){
                    swal({
                        title: "{{__('Không thể thực thi')}}",
                        text: "{{__('Vui lòng chọn ít nhất 1 bình luận để thao tác')}}",
                        confirmButtonColor: "#EF5350",
                        confirmButtonText: "{{__('Đã hiểu')}}",
                        type: "error"
                    });
                    return;
                }
                swal({
                        title: value?"{{__('Bật bình luận')}}":"{{__('Tắt bình luận')}}",
                        text: "Bạn có chắc chắn muốn thực thi hay không?",
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
                                url: '',
                                dataType: 'json',
                                type: 'post',
                                data: {
                                    ids: ids,
                                    value: value,
                                    _method: 'put'
                                },
                                success: function () {
                                    new PNotify({
                                        title: '{{'XỬ LÝ THÀNH CÔNG'}}',
                                        text: value?"{{__('Bình luận được chọn đã bật thành công.')}}":"{{__('Bình luận được chọn đã tắt thành công.')}}",
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
            function delete_rows(items) {
                if (items.count() == 0){
                    swal({
                        title: "{{__('Không thể thực thi')}}",
                        text: "{{__('Vui lòng chọn ít nhất 1 bình luận để xóa')}}",
                        confirmButtonColor: "#EF5350",
                        confirmButtonText: "{{__('Đã hiểu')}}",
                        type: "error"
                    });
                    return;
                }

                swal({
                        title: "{{__('Xóa bình luận')}}",
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
                                url: '',
                                dataType: 'json',
                                type: 'post',
                                data: {
                                    ids: ids,
                                    _method: 'delete'
                                },
                                success: function () {
                                    new PNotify({
                                        title: '{{'XỬ LÝ THÀNH CÔNG'}}',
                                        text: "{{__('Bình luận được chọn đã xóa thành công.')}}",
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