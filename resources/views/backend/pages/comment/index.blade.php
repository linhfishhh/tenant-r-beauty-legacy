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
@section('page_title', $comment_type::getMenuTitle())
@section('page_header_title')
    <strong>{{$comment_type::getMenuTitle()}}</strong>
@endsection
@section('sidebar_second')
    @include('backend.pages.comment.includes.sidebar_items')
@endsection
@section('page_content_body')
    @event(new \App\Events\BeforeHtmlBlock('content'))
    @component('backend.components.panel', ['classes'=>'panel-default', 'has_body' => false])
        @slot('content')
            <ul id="{!! jSID('view_mode') !!}" class="nav nav-tabs nav-tabs-highlight no-margin-bottom">
                <li data-value="-1" class="active"><a data-toggle="tab">{{__('Tất cả')}} <span class="badge badge-primary position-right">0</span></a></li>
                <li data-value="1"><a data-toggle="tab">{{__('Đang hiển thị')}} <span class="badge badge-success position-right">0</span></a></li>
                <li data-value="0"><a data-toggle="tab">{{__('Đang tắt')}} <span class="badge badge-danger position-right">0</span></a></li>
            </ul>
            <table data-table-name="{{jSID()}}" id="datatable_{{jSID()}}" class="table table-condensed table-hover">
                <thead><tr></tr></thead>
            </table>
            <template id="{{jSID('more_filter_tpl')}}">
                <div class="dataTables_filter">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <input style="width: 200px" placeholder="{{__('Ngày bình luận')}}" id="{!! jSID('date_filter') !!}" data-start="" data-end=""  type="text" class="form-control date_filter text-size-mini" value="">
                    </div>
                </div>
                <div class="dataTables_filter">
                    <select class="filter-author"></select>
                </div>
            </template>
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
            <div id="{{jSID()}}_modal_detail" class="modal fade" data-comment>
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h5 class="modal-title">{{__('Chi tiết bình luận')}}</h5>
                        </div>

                        <div class="modal-body">
                            <form onsubmit="return false">
                                <div class="mb-15"><a class="post-title text-size-large text-bold text-teal"></a></div>
                                <div class="form-group">
                                    <label class="control-label"><i class="icon-comment position-left text-slate-300"></i><span class="author-name-1 text-semibold text-teal"></span> {{__('đã bình luận')}}</label>
                                    <textarea name="content" spellcheck="false" class="comment-1 form-control" rows="5"></textarea>
                                </div>
                                <div class="form-group parent-comment">
                                    <label class="control-label"><i class="icon-comment position-left text-slate-300"></i><span>{{__('Trả lời cho bình luận của')}}</span> <span class="author-name-2 text-semibold text-teal"></span></label>
                                    <blockquote class="comment-2 text-slate text-italic"></blockquote>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer">
                            @if ($post_type::isPublic())
                            <a class="bg-orange btn frontend-link" target="_blank" href="#">{!! __('Xem trang') !!}</a>
                            @endif
                            <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('Đóng')}}</button>
                            <button type="button" class="btn btn-primary btn-save">{{__('Lưu thay đổi')}}</button>
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
            var $detail_modal = $('#{{jSID()}}_modal_detail').modal({
                show: false
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
                    data: '',
                    name: 'title',
                    title_html: '<th>{!! __('Bình luận') !!}</th>',
                    cell_define: {
                        orderable: false,
                        render: function (data, type, row) {
                            //console.log(row);
                            var html = '';
                            if (row.user){
                                var author = '<a data-id="'+row.id+'" class="text-orange text-semibold view-detail">'+(row.user.name)+'</a>';
                            }
                            else{
                                var author = '<a data-id="'+row.id+'" class="text-info text-semibold view-detail">'+(row.name)+'</a>';
                            }
                            if (row.parent == null){
                                html += '<div>'+author+' <span class="text-slate">{{__('bình luận tại')}}:</span></div>';
                            }
                            else{
                                if (row.parent.user){
                                    var author2 = '<a data-id="'+row.id+'" class="text-orange text-semibold view-detail">'+(row.parent.user.name)+'</a>';
                                }
                                else{
                                    var author2 = '<a data-id="'+row.id+'" class="text-info text-semibold view-detail">'+(row.parent.name)+'</a>';
                                }
                                html += '<div>'+author+' <span class="text-slate">{{__('đã trả lời')}}</span> '+author2+' <span class="text-slate">{{__('tại')}}:</span></div>';
                            }
                            var count = row.post.comments_count;
                            count = '<span class="ml-5 text-warning text-size-mini"><i class="icon-comment"></i> '+count+'</span>';
                            html += '<div><a data-id="'+row.id+'" class="text-teal text-semibold view-detail">'+row.post.title+'</a>'+count+'</div>';
                            html += '<div class="mt-5 mb-10"><span class="label bg-grey-300">{{__('Từ IP')}}: '+row.ip+'</span></div>';
                            html += '<blockquote class="text-italic text-slate"><div class="dotdotdot" style="max-height: 50px; overflow: hidden">'+row.content+'</div><div class="show-more hide mt-5" style="display:inline-block"><a class="text-teal" style="font-style:normal"><i class="icon-arrow-down22"></i> {!! __('Xem đầy đủ') !!}</a></div></blockquote>'
                            return html;
                        },
                    },

                },

                {
                    data: 'created_at',
                    name: 'created_at',
                    title_html: '<th>{!! __('Ngày đăng') !!}</th>',
                    cell_define: {
                        width: "180px",
                        className: 'text-center',
                    }
                },
                {
                    data: 'published',
                    name: 'published',
                    title_html: '<th>{!! __('Trang thái') !!}</th>',
                    cell_define: {
                        width: "150px",
                        render: function (data, type, row) {
                            if (row.published) {
                                var clss = 'label-success';
                                var title = '{{__('Hiển thị')}}';
                            }
                            else {
                                var clss = 'label-warning';
                                var title = '{{__('Tạm tắt')}}';
                            }
                            if (row.trashed) {
                                return '<span class="label ' + clss + '"></i> <span>' + title + '</span></span>'
                            }
                            return '<span data-pk="' + row.id + '" data-value="' + row.published + '" class="cursor-pointer label ' + clss + ' editable-published"><i class="icon-pencil5 text-size-mini"></i> <span>' + title + '</span></span>'
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
                    url: '{!! route('backend.comment.index', ['post_type'=>$post_type::getTypeSlug()]) !!}',
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
                data.user_id = $('.datatable-header select.filter-author').val();
                var view_mode = $('#{!! jSID('view_mode') !!} li.active').data('value');
                data.published = view_mode;
                var date_start = $('#{!! jSID('date_filter') !!}').data('start');
                var date_end = $('#{!! jSID('date_filter') !!}').data('end');
                data.created_at = [date_start, date_end];
            } );

            $('#{!! jSID('view_mode') !!} li').on('shown.bs.tab', function (e) {
                $table.draw('page');
            });

            $table.on( 'draw', function (e, settings) {
                var data = settings.json.counts;
                $('#{!! jSID('view_mode') !!} li[data-value=-1] .badge').html(data['-1']);
                $('#{!! jSID('view_mode') !!} li[data-value=1] .badge').html(data['1']);
                $('#{!! jSID('view_mode') !!} li[data-value=0] .badge').html(data['0']);

                $(e.target).find('.action-delete').click(function () {
                    var items = $table.rows(':eq('+$(this).parents('tr').index()+')', { page: 'current' })
                    delete_rows(items);
                });
                $($detail_modal).find('.btn-save').click(function () {

                    var form = $($detail_modal).find('form');
                    var comment = $($detail_modal).data('comment');
                    $.ajax({
                        url: '{!! route('backend.comment.content', ['post_type'=>$post_type::getTypeSlug()]) !!}',
                        type: 'post',
                        data: {
                            id: comment.id,
                            content: $($detail_modal).find('textarea.comment-1').val(),
                            _method: 'put'
                        },
                        beforeSend: function () {
                            $($detail_modal).find('.btn').prop('disabled', true);
                        },
                        complete: function () {
                            $($detail_modal).find('.btn').prop('disabled', false);
                        },
                        success: function () {
                            $detail_modal.modal('hide');
                            $table.draw('page');
                        },
                        error: function (rs) {
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
                            handleErrorMessage(form, rs);
                        }
                    });
                });
                $('#datatable_{{jSID()}} .view-detail').click(function (e) {
                    e.stopPropagation();
                    var items = $table.rows(':eq('+$(this).parents('tr').index()+')', { page: 'current' })
                    var comment = items.data()[0];
                    @if ($post_type::isPublic())
                    var post_url = '{!! $post_type::getPublicDetailUrl('???') !!}';
                    post_url = post_url.replace('???', comment.post.slug);
                    @endif
                    $($detail_modal).find('.modal-footer .frontend-link').attr('href', post_url);
                    $($detail_modal).data('comment', comment);
                    $($detail_modal).find('textarea.comment-1').val(comment.content);
                    $($detail_modal).find('.post-title').html(comment.post.title);
                    $($detail_modal).find('.author-name-1').html(comment.user?comment.user.name:comment.name);
                    if (comment.parent){
                        $($detail_modal).find('.parent-comment').show();
                        $($detail_modal).find('.comment-2').html(comment.parent.content);
                        $($detail_modal).find('.author-name-2').html(comment.parent.user?comment.parent.user.name:comment.parent.name);
                    }
                    else{
                        $($detail_modal).find('.parent-comment').hide();
                    }
                    $detail_modal.modal('show');
                });

                $('#datatable_{{jSID()}} .editable-published').editable({
                    type: 'select2',
                    highlight: false,
                    source: [
                        {id: 1, text: '{{__('Hiển thị')}}'},
                        {id: 0, text: '{{__('Tạm tắt')}}'},
                    ],
                    display: function (value, sourceData) {
                        $(this).removeClass('label-success');
                        $(this).removeClass('label-warning');
                        if (value == 1) {
                            $(this).find('span').html('{{__('Hiển thị')}}');
                            $(this).addClass('label-success');
                        }
                        else {
                            $(this).find('span').html('{{__('Tạm tắt')}}')
                            $(this).addClass('label-warning');
                        }
                    },
                    select2: {
                        minimumResultsForSearch: Infinity,
                        width: 200
                    },
                    name: 'published',
                    send: 'always',
                    params: function(params) {
                        params._method = 'put';
                        return params;
                    },
                    ajaxOptions: {
                        type: 'post',
                        dataType: 'json'
                    },
                    url: '{!! route('backend.comment.published', ['post_type'=>$post_type::getTypeSlug()]) !!}',
                    error: function (response) {
                        return xEditableResponseHandle(response, '{!! __('Lỗi không xác định!') !!}');
                    },
                    success: function () {
                        $table.draw('page');
                    }
                });

                $('#datatable_{{jSID()}}').find('.dotdotdot').dotdotdot({
                    height: 'watch',
                });
                $('#datatable_{{jSID()}}').find('.ddd-truncated').each(function ()
                {
                    $(this).next().removeClass('hide');
                    var t = this;
                    $(this).next().click(function (e)
                    {
                        e.stopPropagation();
                        if ($(t).hasClass('full')){
                            $(t).css('max-height', '50px');
                            $(t).data('dotdotdot').truncate();
                            $(t).removeClass('full');
                            $(t).next().find('a').html('<i class="icon-arrow-down22"></i> {!! __('Xem đầy đủ') !!}');
                        }
                        else{
                            $(t).data('dotdotdot').restore();
                            $(t).css('max-height', 'none');
                            $(t).addClass('full');
                            $(t).next().find('a').html('<i class="icon-arrow-up22"></i> {!! __('Thu gọn lại') !!}');
                        }
                    });
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
                                url: '{!! route('backend.comment.published', ['post_type'=>$post_type::getTypeSlug()]) !!}',
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
                                url: '{!! route('backend.comment.destroy', ['post_type'=>$post_type::getTypeSlug()]) !!}',
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