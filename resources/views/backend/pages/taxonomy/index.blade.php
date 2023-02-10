@enqueueJSByID(config('view.ui.files.js.datatables.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.datatables_fixed_header.id'), JS_LOCATION_DEFAULT, 'datatables')
@enqueueJSByID(config('view.ui.files.js.datatables_select.id'), JS_LOCATION_DEFAULT, 'datatables')
@enqueueJSByID(config('view.ui.files.js.datatables_buttons.id'), JS_LOCATION_DEFAULT, 'datatables')
@enqueueJSByID(config('view.ui.files.js.editable.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.sweet_alert.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.pnotify.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@php
/** @var \App\Classes\Taxonomy $taxonomy */
/** @var \App\Classes\PostType $post_type */
@endphp
@extends('layouts.backend')
@section('page_title', $taxonomy::getMenuTitle())
@section('page_header_title')
    <strong>{{$taxonomy::getMenuTitle()}}</strong>
@endsection
@section('sidebar_second')
    @include('backend.pages.taxonomy.includes.menu_items')
@endsection
@section('page_content_body')
    @event(new \App\Events\BeforeHtmlBlock('content'))
    @yield('taxonomy_index_before_content')
    @component('backend.components.panel', ['classes'=>'panel-default', 'has_body' => false])
        @slot('content')
            @yield('taxonomy_index_before_table')
            <table data-table-name="{{jSID()}}" id="datatable_{{jSID()}}" class="table table-condensed table-hover">
                <thead>
                <tr></tr>
                </thead>
            </table>
            <template id="{{jSID()}}_row_action_tpl">
                <a title="{{__('Sửa')}}" href="{link}" class="label label-success label-icon label-rounded"><i class="icon-pencil"></i></a>
                @if($taxonomy::isPublic())
                <a href="{public_link}" target="_blank" title="{{__('Xem')}}" class="label label-primary label-icon label-rounded action-view"><i class="icon-eye"></i></a>
                @endif
                <a title="{{__('Xóa')}}" class="label label-warning label-icon label-rounded action-delete"><i class="icon-trash"></i></a>
            </template>
            <template id="{{jSID()}}_users_count_tpl">
                <span class="badge badge-info">{count}</span>
            </template>
            <template id="{{jSID('lang_filter')}}">
                <div id="{{jSID('lang_filter')}}" class="dataTables_filter lang_filter @if($taxonomy::isHierarchy()) no-margin-left @endif">
                    @component('backend.components.language_seletor')
                        @slot('name', jSID('language_filter'))
                        @slot('id', jSID('language_filter'))
                        @slot('has_none', true)
                    @endcomponent
                </div>
            </template>
            @yield('taxonomy_index_after_table')
        @endslot
    @endcomponent
    @yield('taxonomy_index_after_content')
    @event(new \App\Events\AfterHtmlBlock('content'))
@endsection
@push('page_footer_js')
    @include('backend.settings.datatable.default')
    @include('backend.settings.xeditable.default')
    @yield('taxonomy_index_before_footer_js')
    <script type="text/javascript">
        $(function () {
            @if (isMultipleLanguage())
            var $language_tool = {
                languages: {!! json_encode(config('app.locales')) !!},
                language_metas: {!! json_encode(config('app.locale_metas')) !!},
                getCodes: function (return_string) {
                    var rs = [];
                    $.each(this.languages, function (code, title) {
                        rs.push(code);
                    });
                    if (return_string){
                        rs = rs.join(',');
                    }
                    return rs;
                },
                isSupported: function (lang_code) {
                    return this.languages.hasOwnProperty(lang_code);
                },
                getMeta: function (lang_code) {
                    return this.language_metas[lang_code];
                }
            };
            @endif
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
                    data: 'title',
                    name: 'title',
                    title_html: '<th>{!! __('Tiêu đề') !!}</th>',
                    cell_define: {
                        render: function (data, type, row) {
                            var html = '<a class="text-teal text-semibold" href="'+row.link+'">'+data+'</a>';
                            if (row.level > 0){
                                var add = '';
                                for(var i=0; i<= row.level-1; i++){
                                    add = add + '├─';
                                }
                                html = '<span class="text-grey-300">'+add +'</span>&nbsp;' + html;
                            }
                            return html;
                        },
                    }
                },
                {{--{--}}
                    {{--data: 'slug',--}}
                    {{--name: 'slug',--}}
                    {{--title_html: '<th>{!! __('Định danh/slug') !!}</th>',--}}
                    {{--cell_define: {--}}
                        {{--className: 'text-center',--}}
                    {{--}--}}
                {{--},--}}
                @if(isMultipleLanguage())
                {
                    data: 'language_title',
                    name: 'language',
                    title_html: '<th>{!! __('Ngôn ngữ') !!}</th>',
                    cell_define: {
                        width: "150px",
                        className: 'text-center',
                        render: function (data, type, row) {
                            var clss = $language_tool.isSupported(row.language) ? $language_tool.getMeta(row.language).color_class: 'bg-warning';
                            return '<span class="label bg-'+clss+'">'+data+'</span>';
                        },
                    }
                },
                @endif
                {
                    data: 'posts_count',
                    name: 'posts_count',
                    title_html: '<th>{{$post_type::getSingular()}}</th>',
                    cell_define: {
                        width: "150px",
                        className: 'text-center',
                        render: function (data, type, row) {
                            return '<span class="badge badge-info">'+data+'</span>';
                        },
                    }
                },
                {
                    data:'',
                    name:'actions',
                    title_html: '<th class="text-center">{!! __('Hành động') !!}</th>',
                    cell_define: {
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                        width: "200px",
                        render: function (data, type, row) {
                            var html = $('#{{jSID()}}_row_action_tpl').html();
                            html = html.replace(/{link}/, row.link);
                            @if($taxonomy::isPublic())
                            html = html.replace(/{public_link}/, row.public_link);
                            @endif
                            return html;
                        },
                    }
                }
            ];

            var $options = {
                select: true,
                autoWidth: false,
                @if ($taxonomy::isHierarchy())
                "paging": false,
                "searching": false,
                @endif
                ajax: {
                    url: '{!! route('backend.taxonomy.index', ['post_type'=>$post_type::getTypeSlug(), 'taxonomy'=>$taxonomy::getTaxSlug()]) !!}',
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
                @if (isMultipleLanguage())
                    var lang = $(e.target).parents('.dataTables_wrapper').find('.lang_filter select').val();
                    data.language = lang;
                @endif
            } );
            $table.on( 'draw', function (e) {
                $(e.target).find('.action-delete').click(function () {
                    var items = $table.rows(':eq('+$(this).parents('tr').index()+')', { page: 'current' })
                    delete_rows(items);
                });
            });
            $table.on('preInit.dt', function (e) {
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
                @else
                    //$(e.target).parents('.dataTables_wrapper').find('.datatable-header').hide();
                @endif
            });

            $('.datatable-taxonomy-actions .action-delete').click(function () {
                var items = $table.rows( { selected: true } );
                delete_rows(items);
            });

            function delete_rows(items) {
                if (items.count() == 0){
                    swal({
                        title: "{{__('Không thể thực thi')}}",
                        text: "{{__('Vui lòng chọn ít nhất 1 :taxonomy để xóa', ['taxonomy'=>$taxonomy::getSingular()])}}",
                        confirmButtonColor: "#EF5350",
                        confirmButtonText: "{{__('Đã hiểu')}}",
                        type: "error"
                    });
                    return;
                }

                swal({
                        title: "{{__('Xóa :taxonomy', ['taxonomy' => $taxonomy::getSingular()])}}",
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
                                url: '{!! route('backend.taxonomy.destroy', ['post_type'=>$post_type::getTypeSlug(), 'taxonomy'=>$taxonomy::getTaxSlug()]) !!}',
                                dataType: 'json',
                                type: 'post',
                                data: {
                                    ids: ids,
                                    _method: 'delete'
                                },
                                success: function () {
                                    new PNotify({
                                        title: '{{'XỬ LÝ THÀNH CÔNG'}}',
                                        text: "{{__(':taxonomy được chọn đã xóa thành công.', ['taxonomy' => $taxonomy::getSingular()])}}",
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
    @yield('taxonomy_index_after_footer_js')
@endpush