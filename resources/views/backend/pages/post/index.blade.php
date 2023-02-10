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
@php
    /** @var \App\Classes\PostType $post_type */
@endphp
@extends('layouts.backend')
@section('page_title', $post_type::getMenuIndexTitle())
@section('page_header_title')
    <strong>{{$post_type::getMenuIndexTitle()}}</strong>
@endsection
@section('sidebar_second')
    @include('backend.pages.post.includes.menu_items')
@endsection
@section('page_content_body')
    @event(new \App\Events\BeforeHtmlBlock('content'))
    @yield('post_type_index_before_content')
    @component('backend.components.panel', ['classes'=>'panel-default', 'has_body' => false])
        @slot('content')
            @yield('post_type_index_before_table')
            <ul id="{!! jSID('view_mode') !!}" class="nav nav-tabs nav-tabs-highlight no-margin-bottom">
                <li data-value="all" class="active"><a data-toggle="tab">{{__('Khả dụng')}} <span
                                class="badge badge-success position-right">0</span></a></li>
                <li data-value="mine"><a data-toggle="tab">{{__('Của tôi')}} <span
                                class="badge bg-orange position-right">0</span></a></li>
                @if(me()->hasAnyPermissions([
                    $post_type::getDeletePermissionID(),
                    $post_type::getTrashPermissionID()
                ]))
                    <li data-value="trashed"><a data-toggle="tab">{{__('Thùng rác')}} <span
                                    class="badge badge-danger position-right">0</span></a>
                    </li>
                @endif
            </ul>
            <table data-table-name="{{jSID()}}" id="datatable_{{jSID()}}" class="table table-condensed table-hover">
                <thead>
                <tr></tr>
                </thead>
            </table>
            <template id="{{jSID()}}_row_action_tpl">
                <ul class="icons-list">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-menu9"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a class="action-edit" href="{link}"><i class="icon-pencil5 text-blue"></i> {{__('Sửa')}}</a></li>
                            @if($post_type::isPublic())
                            <li><a class="action-view" href="{public_link}" target="_blank"><i class="icon-eye text-primary"></i> {{__('Xem')}}</a></li>
                            @endif
                            <li class="divider"></li>
                            <li><a class="action-restore"><i class="icon-redo2 text-green"></i> {{__('Phục hồi')}}
                                </a></li>
                            <li class="divider"></li>
                            <li><a class="action-trash"><i class="icon-trash-alt text-warning"></i> {{__('Tạm xóa')}}
                                </a></li>
                            <li><a class="action-delete"><i class="icon-trash text-danger"></i> {{__('Xóa vĩnh viễn')}}
                                </a></li>
                        </ul>
                    </li>
                </ul>
            </template>
            <template id="{{jSID()}}_users_count_tpl">
                <span class="badge badge-info">{count}</span>
            </template>
            @if(isMultipleLanguage())
            <template id="{{jSID('lang_filter')}}">
                <div id="{{jSID('lang_filter')}}" class="dataTables_filter lang_filter">
                    @component('backend.components.language_seletor')
                        @slot('name', jSID('language_filter'))
                        @slot('id', jSID('language_filter'))
                        @slot('has_none', true)
                    @endcomponent
                </div>
            </template>
            @endif
            <template id="{{jSID('visible_filter')}}">
                <div class="dataTables_filter visible_filter">
                    <select>
                        <option value="-1">{{__('Tất cả trạng thái')}}</option>
                        <option value="1">{{__('Hiển thị')}}</option>
                        <option value="0">{{__('Tạm tắt')}}</option>
                    </select>
                </div>
            </template>
            <template id="{{jSID('advanced_filter_btn')}}">
                <div class="dataTables_filter">
                    <button class="btn bg-warning"><i class="icon-cog3 position-left"></i> {{__('Nâng cao')}}</button>
                </div>
            </template>
            <template id="{{jSID('advanced_filter_default')}}">
                <div class="dataTables_filter full-width no-margin-left form-group-lg">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                        <input placeholder="{{__('Ngày đăng')}}" id="{!! jSID('date_filter') !!}" data-start="" data-end="" readonly type="text" class="form-control date_filter text-size-mini" value="">
                    </div>
                </div>
                <div class="dataTables_filter full-width no-margin-left form-group-lg">
                    <select id="{!! jSID('user_filter') !!}" class="user_filter"></select>
                </div>
            </template>
            @yield('post_type_index_after_table')
        @endslot
    @endcomponent
    @yield('post_type_index_after_content')
    @event(new \App\Events\AfterHtmlBlock('content'))
@endsection
@push('page_footer_js')
    @include('backend.settings.datatable.default')
    @include('backend.settings.xeditable.default')
    @include('backend.settings.language')
    @yield('post_type_index_before_footer_js')
    <script type="text/javascript">
        $(function () {
            function showPostCount(data) {
                $('#{!! jSID('view_mode') !!} li[data-value=all] .badge').html(data.all);
                $('#{!! jSID('view_mode') !!} li[data-value=mine] .badge').html(data.mine);
                $('#{!! jSID('view_mode') !!} li[data-value=trashed] .badge').html(data.trashed);
            }

            function recal_post_count() {
                $.ajax({
                    url: '{!! route('backend.post.counts', ['post_type'=>$post_type::getTypeSlug()]) !!}',
                    type: 'get',
                    success: function (data) {
                        showPostCount(data);
                    }
                });
            }

            $(window).trigger({
                type: 'wa.datatable.init.{{jSID()}}',
                table_html: $('#datatable_{{jSID()}}')[0]
            });

            function toggleSidebarActions(view_mode) {
                $('.datatable-post-actions .action-restore').parent().removeClass('disabled');
                $('.datatable-post-actions .action-trashed').parent().removeClass('disabled');
                switch (view_mode){
                    case 'all':
                        $('.datatable-post-actions .action-restore').parent().addClass('disabled');
                        break;
                    case 'mine':
                        $('.datatable-post-actions .action-restore').parent().addClass('disabled');
                        break;
                    case 'trashed':
                        $('.datatable-post-actions .action-trashed').parent().addClass('disabled');
                        break;
                }
            }

            toggleSidebarActions('all');

            $('#{!! jSID('view_mode') !!} li').on('shown.bs.tab', function (e) {
                var view_mode = $(e.target).parent().data('value');
                toggleSidebarActions(view_mode);
                $table.draw('page');
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
                    title_html: '<th>{!! __('Tiêu đề') !!}</th>',
                    cell_define: {
                        render: function (data, type, row) {
                            var html;
                            if (row.trashed) {
                                html = '<div><span class="text-teal text-semibold text-size-large">' + data + '</span></div>';
                                html += '<div class="mt-5 text-slate"><span>{{__('Tạo bởi:')}}</span> <a  data-popup="tooltip" data-tooltip-title="' + row.user_email + ' (' + row.user_role_title + ')" class="">' + row.user_name + '</a></div>';
                            }
                            else{
                                @if (me()->hasPermission($post_type::getEditPermissionID()))
                                    html = '<div><a class="text-teal text-semibold text-size-base" href="' + row.link + '">' + data + '</a></div>';
                                @else
                                    html = '<div><a class="text-teal text-semibold text-size-base">' + data + '</a></div>';
                                @endif

                                @if (me()->hasPermission($post_type::getChangeAuthorPermissionID()))
                                    html += '<div class="mt-5 text-slate"><span>{{__('Tạo bởi:')}}</span> <i class="icon-pencil5 text-size-mini text-primary"></i> <a data-pk="' + row.id + '" data-value="' + row.user_id + '"  data-popup="tooltip" data-tooltip-title="' + row.user_email + ' (' + row.user_role_title + ')" class="editable-tag-link editable-user_id">' + row.user_name + '</a></div>';
                                @else
                                    html += '<div class="mt-5 text-slate"><span>{{__('Tạo bởi:')}}</span> <a  data-popup="tooltip" data-tooltip-title="' + row.user_email + ' (' + row.user_role_title + ')" class="editable-tag-link">' + row.user_name + '</a></div>';
                                @endif
                            }
                            @foreach($taxonomies as $taxonomy=>$rel)
                                @php
                                /** @var \App\Classes\Taxonomy $taxonomy */
                                @endphp
                                @if ($taxonomy::getShowInAdminTable())
                                var taxs = '';
                                var val = '';
                                if (row.taxonomies['{{$taxonomy::getTaxSlug()}}'].length > 0){
                                    $.each(row.taxonomies['{{$taxonomy::getTaxSlug()}}'], function (i, v) {
                                        if (val != ''){
                                            val = val + ',';
                                        }
                                        val = val + v.id;
                                        if (i>0){
                                            taxs += ', ';
                                        }
                                        taxs += v.title;
                                    });
                                }
                                if (taxs.length>0){
                                    taxs = '<a class="">'+taxs+'</a>';
                                }
                                else{
                                    taxs = '<a class="text-italic">{{__('Chưa phân loại')}}</a>';
                                }
                                @hasPermission($post_type::getCatalogizePermissionID())
                                    taxs = '<span data-language="'+row.language+'" data-pk="'+row.id+'"  data-taxonomy="{!! $post_type::getTypeSlug() !!}_{!! $taxonomy::getTaxSlug() !!}" data-value="'+val+'" data-hierarchy="{!! $taxonomy::isHierarchy()?1:0 !!}" data-single="{!! $taxonomy::isSingle()?1:0 !!}" class="editable editable-click editable-tag-link editable-term_id">'+taxs+'</span>';
                                @endif
                                html += '<div class="mt-5 text-slate">' +
                                    '<span>{{$taxonomy::getSingular()}}:</span> '+taxs+
                                '</div>';
                                @endif
                            @endforeach
                            return html;
                        }
                    }
                },
                {
                    data: 'published_at',
                    name: 'published_at',
                    title_html: '<th>{!! __('Ngày đăng') !!}</th>',
                    cell_define: {
                        width: "200px",
                        render: function (data, type, row) {
                            if (row.trashed) {
                                return '<span>' + data + '</span>';
                            }
                            @if (me()->hasPermission($post_type::getPublishPermissionID()))
                                return '<i class="icon-pencil5 text-size-mini text-primary"></i> <a data-pk="' + row.id + '" data-value="' + row.published_at + '"  class="editable-tag-link editable-published_at">' + data + '</a>';
                            @else
                                return '<a>' + data + '</a>';
                            @endif
                        }
                    }
                },
                    @if(isMultipleLanguage())
                {
                    data: 'language_title',
                    name: 'language',
                    title_html: '<th>{!! __('Ngôn ngữ') !!}</th>',
                    cell_define: {
                        width: "150px",
                        className: 'text-center',
                        render: function (data, type, row) {
                            var clss = $language_tool.isSupported(row.language) ? $language_tool.getMeta(row.language).color_class : 'bg-warning';
                            if (row.trashed) {
                                return '<span class="label ' + clss + '"><span>' + data + '</span></span>';
                            }
                            @if (me()->hasPermission($post_type::getEditPermissionID()))
                                return '<a data-old-class="' + clss + '" data-pk="' + row.id + '" data-value="' + row.language + '" class="label ' + clss + ' editable-language"><i class="icon-pencil5 text-size-mini"></i> <span>' + data + '</span></a>';
                            @else
                                return '<a class="label ' + clss + '"><span>' + data + '</span></a>';
                            @endif
                        },
                    }
                },
                    @endif
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
                            @if (me()->hasPermission($post_type::getPublishPermissionID()))
                                return '<span data-pk="' + row.id + '" data-value="' + row.published + '" class="cursor-pointer label ' + clss + ' editable-published"><i class="icon-pencil5 text-size-mini"></i> <span>' + title + '</span></span>'
                            @else
                                return '<span class="cursor-pointer label ' + clss + '"></i> <span>' + title + '</span></span>'
                            @endif
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
                            html = html.replace(/{link}/, row.link);
                            html = html.replace(/{public_link}/, row.public_link);
                            return html;
                        },
                    }
                },
            ];

            var $options = {
                select: true,
                autoWidth: false,
                ajax: '{!! route('backend.post.index', ['post_type'=>$post_type::getTypeSlug()]) !!}',
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

            $table.on('preXhr.dt', function (e, settings, data) {
                var view_mode = $('#{!! jSID('view_mode') !!} li.active').data('value');
                data.view_mode = view_mode;
                @if (isMultipleLanguage())
                    var lang = $(e.target).parents('.dataTables_wrapper').find('.lang_filter select').val();
                    data.language = lang;
                @endif
                var published = $(e.target).parents('.dataTables_wrapper').find('.visible_filter select').val();
                data.published = published;
                @foreach($taxonomies as $tax=>$rel)
                    @if ($tax::getShowInAdminTable())
                        var ids = $('#{!! jSID('taxonomy-filter-'.$tax::getTaxSlug()) !!}').val();
                        data['{!! $tax::getTaxSlug() !!}'] = ids?ids:[];
                    @endif
                @endforeach
                var date_start = $('#{!! jSID('date_filter') !!}').data('start');
                var date_end = $('#{!! jSID('date_filter') !!}').data('end');
                data.published_date = [date_start, date_end];
                var user_ids = $('#{!! jSID('user_filter') !!}').val();
                data.user_ids = user_ids;
            });

            $table.on('preInit.dt', function (e) {
                var node = $('#{{jSID('trashed_filter_tpl')}}').html();
                node = $(node);
                $(e.target).parents('.dataTables_wrapper').find('.datatable-header').append(node);
                @if(isMultipleLanguage())
                    var node = $('#{{jSID('lang_filter')}}').html();
                    node = $(node);
                    $(e.target).parents('.dataTables_wrapper').find('.datatable-header').append(node);
                    $(node).find('select').select2({
                        minimumResultsForSearch: Infinity,
                        width: "150px",
                    });
                    $(node).find('select').on('select2:select', function (e) {
                        $table.draw();
                    });
                @endif
                node = $('#{{jSID('visible_filter')}}').html();
                node = $(node);
                $(e.target).parents('.dataTables_wrapper').find('.datatable-header').append(node);
                $(node).find('select').select2({
                    minimumResultsForSearch: Infinity,
                    width: "150px",
                });
                $(node).find('select').on('select2:select', function (e) {
                    $table.draw();
                });
                var node = $('#{{jSID('advanced_filter_btn')}}').html();
                node = $(node);
                $(e.target).parents('.dataTables_wrapper').find('.datatable-header').append(node);
                //$(e.target).parents('.dataTables_wrapper').find('.datatable-header').addClass('no-border-bottom');
                var advanced_filter_node = $('<div class="datatable-header no-border-top advanced-filters"></div>')
                    .insertAfter($(e.target)
                    .parents('.dataTables_wrapper').find('.datatable-header')[0]);
                advanced_filter_node.hide();
                $(node).find('button').click(function () {
                    advanced_filter_node.slideToggle();
                });
                var default_adv_filters = $('#{!! jSID('advanced_filter_default') !!}').html();
                default_adv_filters = $(default_adv_filters).appendTo(advanced_filter_node);
                $(default_adv_filters).find('.date_filter').daterangepicker({
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
                $(default_adv_filters).find('.date_filter').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
                    $(this).data('start', picker.startDate.format('YYYY-MM-DD 00:00:00'));
                    $(this).data('end', picker.endDate.format('YYYY-MM-DD 23:59:59'));
                    $table.draw('page');
                });
                $(default_adv_filters).find('.date_filter').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    $(this).data('start', '');
                    $(this).data('end', '');
                    $table.draw('page');
                });
                $(default_adv_filters).find('.user_filter').select2({
                    containerCssClass: 'select-lg',
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
                    templateSelection: function(repo) {
                        return repo.full_name || repo.text;
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
                    multiple: true
                });
                $(default_adv_filters).find('.user_filter').on('change', function () {
                    $table.draw('page');
                });
                @if (count($taxonomies)>0)
                    @foreach($taxonomies as $tax=>$rel)
                        @if ($tax::getShowInAdminTable())
                            var filter_node = $('<div class="dataTables_filter full-width no-margin-left"><select id="{!! jSID('taxonomy-filter-'.$tax::getTaxSlug()) !!}"></select></div>').appendTo(advanced_filter_node);
                            $(filter_node).find('select').select2({
                                multiple: true,
                                width: "100%",
                                minimumInputLength: 2,
                                placeholder: '{{__(':Singular', ['singular'=>mb_strtolower($tax::getSingular())])}}',
                                escapeMarkup: function (markup) { return markup; },
                                templateResult: function (tax) {
                                    if (tax.loading) return tax.text;
                                    markup = '<div class="pt-5 pb-5">';
                                    @if (isMultipleLanguage())
                                    var clss = $language_tool.isSupported(tax.language) ? $language_tool.getMeta(tax.language).color_class : 'bg-warning';
                                        markup += '<span class="label bg-'+clss+' mr-5">'+$language_tool.getTitle(tax.language)+'</span>';
                                    @endif
                                    markup += '<span>'+tax.text+'</span></div>';
                                    return markup;
                                },
                                templateSelection: function (tax) {
                                    return tax.text;
                                },
                                ajax:{
                                    url: '{!! route('backend.taxonomy.search',['post_type'=>$post_type::getTypeSlug(),'taxonomy'=>$tax::getTaxSlug()]) !!}',
                                    dataType: 'json',
                                    delay: 250,
                                    data: function (params) {
                                        return {
                                            keyword: params.term, // search term
                                            page: params.page
                                        };
                                    },
                                    processResults: function (data, params) {
                                        params.page = params.page || 1;
                                        var items = [];
                                        $.each(data, function (i, v) {
                                            items.push(
                                                {
                                                    id: v.id,
                                                    text: v.title,
                                                    language: v.language,
                                                }
                                            );
                                        });
                                        return {
                                            results: items,
                                            pagination: {
                                                more: (params.page * 50) < data.total_count
                                            }
                                        };
                                    },
                                }
                            });
                            $(filter_node).find('select').on('change', function () {
                                $table.draw('page');
                            });
                        @endif
                    @endforeach
                @endif
            });
            var $last_term_select = null;
            $table.on('draw', function (e, settings) {
                $last_term_select = null;
                showPostCount(settings.json.counts);
                var view_mode = $('#{!! jSID('view_mode') !!} li.active').data('value');
                @if (me()->hasPermission($post_type::getTrashPermissionID()))
                    if(view_mode !== 'trashed'){
                        $(e.target).find('.action-trash').click(function () {
                            var items = $table.rows(':eq(' + $(this).parents('tr').index() + ')', {page: 'current'});
                            delete_rows(items, true);
                        });
                    }
                @else
                    $(e.target).find('.action-trash').parent().addClass('disabled');
                @endif

                if (view_mode == 'trashed'){
                    $(e.target).find('.dropdown-menu>li>a').not('.action-delete,.action-restore').parent().addClass('disabled');
                    $(e.target).find('.action-edit').parent().addClass('disabled');
                    $(e.target).find('.action-edit').removeAttr('href');
                    @if (!me()->hasPermission($post_type::getTrashPermissionID()))
                    $(e.target).find('.action-restore').parent().addClass('disabled');
                    @else
                    $(e.target).find('.action-restore').click(function () {
                        var items = $table.rows(':eq(' + $(this).parents('tr').index() + ')', {page: 'current'});
                        restore_rows(items);
                    });
                    @endif
                }
                else{
                    $(e.target).find('.action-restore').parent().addClass('disabled');
                }

                @if (!me()->hasPermission($post_type::getEditPermissionID()))
                    $(e.target).find('.action-edit').parent().addClass('disabled');
                    $(e.target).find('.action-edit').removeAttr('href');
                @endif

                @if (me()->hasPermission($post_type::getDeletePermissionID()))
                    $(e.target).find('.action-delete').click(function () {
                        var items = $table.rows(':eq(' + $(this).parents('tr').index() + ')', {page: 'current'});
                        delete_rows(items);
                    });
                @else
                    $(e.target).find('.action-delete').parent().addClass('disabled');
                @endif

                $(e.target).find('[data-popup="tooltip"]').tooltip({
                    title: function () {
                        var t = $(this).data('tooltip-title');
                        return t;
                    }
                });

                @if (isMultipleLanguage() && me()->hasPermission($post_type::getEditPermissionID()))
                    $('#datatable_{{jSID()}} .editable-language').editable({
                    type: 'select2',
                    highlight: false,
                    source: $language_tool.getSelect2Format(),
                    display: function (value, sourceData) {
                        var html = $language_tool.getTitle(value);
                        $(this).find('span').html(html);
                        var old_class = $(this).data('old-class');
                        $(this).removeClass(old_class);
                        if ($language_tool.isSupported(value)) {
                            var meta = $language_tool.getMeta(value);
                            old_class = 'bg-'+meta.color_class;
                            $(this).data('old-class', old_class);
                            $(this).addClass(old_class);
                        }
                        else {
                            old_class = 'label-warning';
                            $(this).data('old-class', old_class);
                            $(this).addClass(old_class);
                        }
                    },
                    select2: {
                        minimumResultsForSearch: Infinity,
                        width: 200
                    },
                    name: 'language',
                    send: 'always',
                    params: function(params) {
                        params._method = 'put';
                        return params;
                    },
                    ajaxOptions: {
                        type: 'post',
                        dataType: 'json'
                    },
                    url: '{!! route('backend.post.put', ['post_type'=>$post_type::getTypeSlug()]) !!}',
                    error: function (response) {
                        return xEditableResponseHandle(response, '{!! __('Lỗi không xác định!') !!}');
                    },
                    success: function () {
                        $table.draw('page');
                    }
                });
                @endif

                @if (me()->hasPermission($post_type::getPublishPermissionID()))
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
                    ajaxOptions: {
                        type: 'post',
                        dataType: 'json'
                    },
                    params: function(params) {
                        params._method = 'put';
                        return params;
                    },
                    url: '{!! route('backend.post.put', ['post_type'=>$post_type::getTypeSlug()]) !!}',
                    error: function (response) {
                        return xEditableResponseHandle(response, '{!! __('Lỗi không xác định!') !!}');
                    },
                    success: function () {
                        $table.draw('page');
                    }
                });
                    $('#datatable_{{jSID()}} .editable-published_at').editable({
                        type: 'combodate',
                        highlight: false,
                        name: 'published_at',
                        format: 'YYYY-MM-DD HH:mm:ss',
                        viewformat: 'YYYY-MM-DD HH:mm:ss',
                        template: 'YYYY-MM-DD HH:mm:ss',
                        combodate: {
                            firstItem: 'name',
                            maxYear: 2099
                        },
                        showbuttons: 'bottom',
                        send: 'always',
                        params: function(params) {
                            params._method = 'put';
                            return params;
                        },
                        ajaxOptions: {
                            type: 'post',
                            dataType: 'json'
                        },
                        url: '{!! route('backend.post.put', ['post_type'=>$post_type::getTypeSlug()]) !!}',
                        error: function (response) {
                            return xEditableResponseHandle(response, '{!! __('Lỗi không xác định!') !!}');
                        }
                    });
                    @if (me()->hasPermission($post_type::getChangeAuthorPermissionID()))
                        var $users = {};
                        $('#datatable_{{jSID()}} .editable-user_id').editable({
                            type: 'select2',
                            highlight: false,
                            tpl: '<select>',
                            onblur: 'ignore',
                            success: function () {
                                recal_post_count();
                            },
                            select2: {
                                width: 250,
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
                                templateSelection: function(repo) {
                                    return repo.full_name || repo.text;
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
                                placeholder: '{{__('Chọn tài khoản')}}',
                                minimumInputLength: 2,
                            },
                            name: 'user_id',
                            send: 'always',
                            params: function(params) {
                                params._method = 'put';
                                return params;
                            },
                            ajaxOptions: {
                                type: 'post',
                                dataType: 'json'
                            },
                            url: '{!! route('backend.post.put', ['post_type'=>$post_type::getTypeSlug()]) !!}',
                            error: function (response) {
                                return xEditableResponseHandle(response, '{!! __('Lỗi không xác định!') !!}');
                            },
                            display: function (value) {
                                if ($users.hasOwnProperty(value)) {
                                    var item = $users[value];
                                    $(this).html(item.name);
                                    $(this).data('tooltip-title', item.email + '(' + item.role.title + ')');
                                }
                            }
                        });
                    @endif
                @endif

                if (view_mode != 'trashed'){
                        @if (me()->hasPermission($post_type::getCatalogizePermissionID()))
                        @foreach($post_type::getTaxonomies() as $taxonomy=>$rel)
                        $(e.target).find('.editable-term_id[data-taxonomy="{!! $post_type::getTypeSlug() !!}_{!! $taxonomy::getTaxSlug() !!}"]').editable({
                            type: 'select2',
                            tpl: '<select>',
                            onblur: 'ignore',
                            highlight: false,
                            params: function(params) {
                                params.taxonomy = '{!! $taxonomy::getTaxSlug() !!}';
                                return params;
                            },
                            display: function (value) {
                            },
                            ajaxOptions: {
                                type: 'post',
                                dataType: 'json'
                            },
                            url: '{!! route('backend.post.update_term', ['post_type'=>$post_type::getTypeSlug()]) !!}',
                            error: function (response) {
                                return xEditableResponseHandle(response, '{!! __('Lỗi không xác định!') !!}');
                            },
                            success: function () {
                                $table.draw('page');
                            },
                            select2: {
                                @if (!$taxonomy::isSingle())
                                multiple: true,
                                @endif
                                        @if ($taxonomy::isHierarchy())
                                escapeMarkup: function (markup) { return markup; },
                                templateResult: function (tax) {
                                    if (tax.loading) return tax.text;
                                    var title = tax.text;
                                    for(var k=1; k<=tax.level; k++){
                                        title = '├─'+title;
                                    }
                                    markup = title;
                                    return markup;
                                },
                                templateSelection: function (tax) {
                                    return tax.text;
                                },
                                @endif
                                width: "250px",
                                @if ($taxonomy::isSingle())
                                allowClear: true,
                                @endif
                                placeholder: '{!! $taxonomy::getSingular() !!}',
                                @if ($taxonomy::isHierarchy())
                                minimumResultsForSearch: -1,
                                @endif
                                ajax:{
                                    url: '{!! route('backend.taxonomy.select',['post_type'=>$post_type::getTypeSlug(),'taxonomy'=>$taxonomy::getTaxSlug()]) !!}',
                                    dataType: 'json',
                                    delay: 250,
                                    data: function (params) {
                                        var m = $(e.target).find('.editable-term_id[data-taxonomy="{!! $post_type::getTypeSlug() !!}_{!! $taxonomy::getTaxSlug() !!}"]');
                                        var language = $(m).data('language');
                                        return {
                                            keyword: params.term, // search term
                                            @if (!$taxonomy::isHierarchy())
                                            page: params.page,
                                            @endif
                                            language: language
                                        };
                                    },
                                    processResults: function (data, params) {
                                        @if (!$taxonomy::isHierarchy())
                                            params.page = params.page || 1;
                                                @endif
                                        var items = [];
                                        $last_term_select = [];
                                        $.each(data, function (i, v) {
                                            $last_term_select.push(
                                                {
                                                    id: v.id,
                                                    text: v.title,
                                                    level: v.level
                                                }
                                            );
                                            items.push(
                                                {
                                                    id: v.id,
                                                    text: v.title,
                                                    level: v.level
                                                }
                                            );
                                        });
                                        return {
                                            results: items,
                                            @if (!$taxonomy::isHierarchy())
                                            pagination: {
                                                more: (params.page * 50) < data.total_count
                                            }
                                            @endif
                                        };
                                    },
                                }
                            },
                        });
                        @endforeach

                        @endif
                }
            });

            function restore_rows(items){
                if (items.count() == 0) {
                    swal({
                        title: "{{__('Không thể thực thi')}}",
                        text: "{{__('Vui lòng chọn ít nhất một :singular để phục hồi', ['singular'=>mb_strtolower($post_type::getSingular())])}}",
                        confirmButtonColor: "#EF5350",
                        confirmButtonText: "{{__('Đã hiểu')}}",
                        type: "error"
                    });
                    return;
                }

                swal({
                        title: "{{__('Phục hồi :singular', ['singular'=>mb_strtolower($post_type::getSingular())])}}",
                        text: "Bạn có chắc chắn muốn phục hồi hay không?",
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
                            var ids = [];
                            $.each(items.data(), function () {
                                ids.push(this.id);
                            });
                            var url = '{!! route('backend.post.restore', ['post_type'=>$post_type::getTypeSlug()]) !!}';
                            $.ajax({
                                url: url,
                                dataType: 'json',
                                type: 'post',
                                data: {
                                    ids: ids,
                                    _method: 'delete'
                                },
                                success: function () {
                                    new PNotify({
                                        title: '{{'XỬ LÝ THÀNH CÔNG'}}',
                                        text: "{{__(':plural được chọn đã phục hồi thành công.', ['plural'=>$post_type::getPlural()])}}",
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
                                complete: function (data) {
                                    $table.draw('page');
                                }
                            });
                        }
                    });
            }

            function delete_rows(items, trashed) {
                if (items.count() == 0) {
                    swal({
                        title: "{{__('Không thể thực thi')}}",
                        text: "{{__('Vui lòng chọn ít nhất một :singular để xóa', ['singular'=>mb_strtolower($post_type::getSingular())])}}",
                        confirmButtonColor: "#EF5350",
                        confirmButtonText: "{{__('Đã hiểu')}}",
                        type: "error"
                    });
                    return;
                }

                swal({
                        title: trashed?"{{__('Tạm xóa :singular', ['singular'=>mb_strtolower($post_type::getSingular())])}}":"{{__('Xóa :singular', ['singular'=>mb_strtolower($post_type::getSingular())])}}",
                        text: trashed?"Bạn có chắc chắn muốn tạm xóa hay không?":"Bạn có chắc chắn muốn xóa vĩnh viễn hay không?",
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
                            var ids = [];
                            $.each(items.data(), function () {
                                ids.push(this.id);
                            });
                            if (trashed) {
                                var url = '{!! route('backend.post.trash', ['post_type'=>$post_type::getTypeSlug()]) !!}';
                            }
                            else {
                                var url = '{!! route('backend.post.destroy', ['post_type'=>$post_type::getTypeSlug()]) !!}';
                            }
                            $.ajax({
                                url: url,
                                dataType: 'json',
                                type: 'post',
                                data: {
                                    ids: ids,
                                    _method: 'delete'
                                },
                                success: function () {
                                    new PNotify({
                                        title: '{{'XỬ LÝ THÀNH CÔNG'}}',
                                        text: "{{__(':plural được chọn đã xóa thành công.', ['plural'=>$post_type::getPlural()])}}",
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

            $('.datatable-post-actions .action-trashed').click(function () {
                var items = $table.rows( { selected: true } );
                delete_rows(items, true);
            });
            $('.datatable-post-actions .action-delete').click(function () {
                var items = $table.rows( { selected: true } );
                delete_rows(items, false);
            });
            $('.datatable-post-actions .action-restore').click(function () {
                var items = $table.rows( { selected: true } );
                restore_rows(items, true);
            });
        });
    </script>
    @yield('post_type_index_after_footer_js')
@endpush