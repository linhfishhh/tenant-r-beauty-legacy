@enqueueJSByID(config('view.ui.files.js.datatables.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.datatables_fixed_header.id'), JS_LOCATION_DEFAULT, 'datatables')
@enqueueJSByID(config('view.ui.files.js.datatables_select.id'), JS_LOCATION_DEFAULT, 'datatables')
@enqueueJSByID(config('view.ui.files.js.editable.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.sweet_alert.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.pnotify.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.fileinput_purify.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.fileinput_sortable.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.fileinput.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.nicescroll.id'), JS_LOCATION_DEFAULT, 'jquery')
@extends('layouts.backend_base')
@section('page_content')
    @event(new \App\Events\BeforeHtmlBlock('content'))
    @component('backend.components.panel', ['classes'=>'panel-default no-margin-bottom', 'has_body' => false])
        @slot('content')
            <ul id="{!! jSID('view_mode') !!}" class="nav nav-tabs nav-tabs-highlight no-margin-bottom">
                <li data-value="all" class="active"><a data-toggle="tab">{{__('Tất cả')}} <span
                                class="badge bg-blue position-right">0</span></a></li>
                @foreach($type_groups as $group)
                    @php
                    /** @var \App\Classes\FileTypeGroup $group */
                    @endphp
                    <li data-value="{!! $group->getId() !!}"><a data-toggle="tab">{!! $group->getTitle() !!} <span
                                    class="badge bg-success position-right">0</span></a></li>
                @endforeach
                @if(!$has_limit)
                <li data-value="other"><a data-toggle="tab">{{__('Khác')}} <span
                                class="badge bg-orange position-right">0</span></a></li>
                @endif
            </ul>
            <table data-table-name="{{str_slug(Route::currentRouteName())}}" id="datatable_{{str_slug(Route::currentRouteName())}}" class="table table-condensed table-hover">
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
@include('backend.settings.fileinput.default')
@php
    /** @var \Illuminate\Support\Collection $cats */
    $cats = app('file_categories')->categories;
@endphp
<template id="{!! jSID('actions_tpl') !!}">
    <div class="dataTables_filter">
        <select class="file-cats">
            @if(count($settings['categories'])>1)
                <option value="all">{{__('Tất cả các nhóm file')}}</option>
            @endif
            @foreach($settings['categories'] as $category)
                <option value="{!! $category !!}">{!! $cats->get($category) !!}</option>
            @endforeach
        </select>
    </div>
    @if($settings['select'] == 0)
    <div class="dataTables_filter">
        <div class="form-group">
            <select id="{!! jSID('user_filter') !!}" class="user_filter dbt-fix"></select>
        </div>
    </div>
    @endif

    <div class="dataTables_filter file-actions">
        <div class="btn-group">
            <button type="button" class="btn bg-success btn-labeled dropdown-toggle" data-toggle="dropdown"><b><i class=" icon-magic-wand"></i></b> {!! __('HÀNH ĐỘNG') !!} <span class="caret"></span></button>
            <ul class="dropdown-menu dropdown-menu-left">
                <li><a class="action-delete"><i class="icon-trash text-danger"></i> {{__('Xóa bỏ')}}</a></li>
            </ul>
        </div>
    </div>
    <div class="dataTables_filter">
        <button type="button" class="btn bg-danger btn-labeled show-upload"><b><i class=" icon-upload"></i></b> {!! __('TẢI LÊN') !!}</button>
    </div>
    @if($select_limit!=0)
    <div class="dataTables_filter file-select">
        @if($select_limit == -1)
            <button type="button" class="btn bg-primary btn-labeled select-done disabled"><b><i class="icon-checkmark"></i></b> {!! __('CHỌN ÍT NHẤT 1 FILE') !!}</button>
        @else
            <button type="button" class="btn bg-primary btn-labeled select-done disabled"><b><i class="icon-checkmark"></i></b> {!! __('CHỌN :num FILE', ['num'=>$select_limit]) !!}</button>
        @endif
    </div>
    @endif
</template>
    <template id="{!! jSID('upload_tpl') !!}">
        <div class="datatable-header no-border-top upload-form form-horizontal">
            <div class="form-group row">
                <label class="control-label col-lg-3">{{__('Chọn nhóm cho file sẽ tải lên')}}</label>
                <div class="col-lg-9">
                    <select class="file-cats-upload">
                        @foreach($settings['categories'] as $category)
                            <option value="{!! $category !!}">{!! $cats->get($category) !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <input type="file" name="upload" multiple="multiple" class="">
            </div>
        </div>
    </template>
@push('page_head')
    <style>
        .sweet-overlay{
            background-color: white;
            opacity: 0.6!important;
            -moz-opacity: 0.6!important;
        }
    </style>
@endpush
<script type="text/javascript">
    $(function () {
        $('#{!! jSID('view_mode') !!} li').on('shown.bs.tab', function (e) {
            var view_mode = $(e.target).parent().data('value');
            $table.draw('page');
        });
        $('body').niceScroll({
            smoothscroll: 0
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
                data: 'thumbnail',
                name: 'thumbnail',
                title_html: '<th>{!! __('Ảnh') !!}</th>',
                cell_define: {
                    width: "150px",
                    render: function (data, type, row) {
                        return '<img style="width: 70px" src="'+data+'" />';
                    },
                }
            },
            {
                data: 'name',
                name: 'name',
                title_html: '<th>{!! __('Tên') !!}</th>',
                cell_define: {
                    render: function (data, type, row) {
                        var html = '<span class="text-teal text-semibold text-size-large">'+data+'.'+row.extension+'</span>';
                        html += '<div class="mt-5 text-slate-300"><span class="label bg-orange">'+row.category+'</span></div>';
                        if(row.user){
                            html += '<div class="mt-10"><i class="icon-user-tie position-left text-info"></i><span data-popup="tooltip" data-tooltip-title="'+row.user.email+'">'+row.user.name+'</span></div>'
                        }
                        else{
                            html += '<div class="mt-10"><i class="icon-user-tie position-left text-info"></i><span>Người dùng bị xoá</span></div>'
                        }
                        return html;
                    },
                }
            },

            {
                data: 'size',
                name: 'size',
                title_html: '<th>{!! __('Dung lượng') !!}</th>',
                cell_define: {
                    width: "200px",
                    render: function (data, type, row) {
                        return fileSizeFormat(data);
                    },
                }
            },
            {
                data: 'created_at',
                name: 'created_at',
                title_html: '<th>{!! __('Ngày tạo') !!}</th>',
                cell_define: {
                }
            },
        ];
        var $options = {
            select: true,
            autoWidth: 1,
            ajax: '{!! route('backend.file.manager.index') !!}',
            columns: [],
            columnDefs: [],
            //scrollY: 'calc(90vh - 190px)',
        };
        var event = $.Event('wa.datatable.options.{{jSID()}}');
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

        $table.on( 'draw', function (e, settings) {
            showCounts(settings.json.counts);
            $(e.target).find('[data-popup="tooltip"]').tooltip({
                title: function () {
                    var t = $(this).data('tooltip-title');
                    return t;
                }
            });
        });

        function showCounts(data){
            $('#{!! jSID('view_mode') !!} li[data-value=all] .badge').html(data.all);
            @foreach($type_groups as $group)
            @php
                /** @var \App\Classes\FileTypeGroup $group */
            @endphp
            $('#{!! jSID('view_mode') !!} li[data-value={!! $group->getId() !!}] .badge').html(data['{!! $group->getId() !!}']);
            @endforeach
            $('#{!! jSID('view_mode') !!} li[data-value=other] .badge').html(data.other);
        }

        $table.on('preXhr.dt', function (e, settings, data) {
            var view_mode = $('#{!! jSID('view_mode') !!} li.active').data('value');
            data.view_mode = view_mode;
            data.settings = {!! json_encode($settings) !!};
            data.category = $('select.file-cats').val();
            @if($settings['select'] == 0)
            var user_id = $('#{!! jSID('user_filter') !!}').val();
            if (user_id){
                data.user_id = user_id;
            }
            @endif
        });

        @if ($select_limit!=0)
            $table.on( 'select deselect', function (  e, dt, type, cell, originalEvent ) {
                var items = $table.rows( { selected: true } );
                $('.file-select .select-done').removeClass('disabled');
                var limit = {!! $select_limit !!};
                if (limit == -1){
                    if (items.count() == 0){
                        $('.file-select .select-done').addClass('disabled');
                    }
                }
                else{
                    if (items.count() != {!! $select_limit !!}){
                        $('.file-select .select-done').addClass('disabled');
                    }
                }

            } );
        @endif

        $table.on('preInit.dt', function (e) {

            var container = $(e.target).parents('.dataTables_wrapper').find('.datatable-header');
            var buttons_node = $('#{!! jSID('actions_tpl') !!}').html();
            buttons_node = $(buttons_node).appendTo(container);
            var upload_node = $('#{!! jSID('upload_tpl') !!}').html();
            upload_node = $(upload_node).insertAfter(container);
            $(upload_node).hide();
            $(buttons_node).find('.show-upload').click(function () {
                $(upload_node).slideToggle();
            });
            $(buttons_node).find('.select-done').click(function () {
                if (!$(this).hasClass('disabled')){
                    var items = $table.rows( { selected: true } ).data();
                    var datas = [];
                    $.each(items, function (i,v) {
                        datas.push(v);
                    });
                    parent.wa_get_files(datas);
                }
            });
            $(buttons_node).find('select.file-cats').select2({
                width: '200px',
                minimumResultsForSearch: -1
            });
            $(buttons_node).find('select.file-cats').on('select2:select', function () {
                $table.draw('page');
            });
            $(buttons_node).find('.action-delete').click(function () {
                var items = $table.rows( { selected: true } );
                delete_file(items);
            });
            @if($settings['select'] == 0)
            $(buttons_node).find('.user_filter').select2({
                width: '200px',
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
                multiple: false,
                allowClear: true
            });
            $(buttons_node).find('.user_filter').on('change', function () {
                $table.draw('page');
            });
            @endif
            var $upload_error = 0;
            $(upload_node).find('input[name=upload]').fileinput({
                //showPreview: 0,
                allowedFileExtensions: {!! json_encode($extensions) !!},
                maxFilePreviewSize: 0.00001,
                browseLabel: '{{__('CHỌN FILE...')}}',
                removeLabel: '{{__('XÓA BỎ')}}',
                uploadLabel: '{{__('TẢI LÊN')}}',
                browseIcon: '<i class="icon-file-plus"></i>',
                browseClass: 'btn btn-success',
                uploadIcon: '<i class="icon-file-upload2"></i>',
                removeIcon: '<i class="icon-cross3"></i>',
                layoutTemplates: {
                    icon: '<i class="icon-file-check"></i>',
                    modal: modalTemplate
                },
                initialCaption: "{{__('Chưa có file được chọn')}}",
                previewZoomButtonClasses: previewZoomButtonClasses,
                previewZoomButtonIcons: previewZoomButtonIcons,
                fileActionSettings: {
                    removeIcon: '<i class="icon-bin"></i>',
                    removeClass: 'btn btn-link btn-xs btn-icon',
                    uploadIcon: '<i class="icon-upload"></i>',
                    uploadClass: 'btn btn-link btn-xs btn-icon',
                    zoomIcon: '<i class="icon-zoomin3"></i>',
                    zoomClass: 'btn btn-link btn-xs btn-icon',
                    indicatorNew: '<i class="icon-file-plus text-slate"></i>',
                    indicatorSuccess: '<i class="icon-checkmark3 file-icon-large text-success"></i>',
                    indicatorError: '<i class="icon-cross2 text-danger"></i>',
                    indicatorLoading: '<i class="icon-spinner2 spinner text-muted"></i>',
                },
                uploadUrl: '{!! route('backend.file.upload') !!}',
                uploadAsync: true,
                msgInvalidFileExtension: '{name}: {!! __('Không hổ trợ tải file này lên!') !!}',
                msgSizeTooLarge: '{name}: {!! __('Tập tin vượt quá kích thước cho phép') !!} ({maxSize} KB)',
                maxFileSize: {!! $max_file_size !!},
                uploadExtraData: function () {
                    var obj = {};
                    obj.category = $('select.file-cats-upload').val();
                    obj.owned = {!! $settings['owned'] !!};
                    return obj;
                }
            });
            $(upload_node).find('input[name=upload]').on('filebatchpreupload', function (event, files, extra) {
                $upload_error = 0;
            });
            $(upload_node).find('input[name=upload]').on('fileuploaderror', function (event, files, extra) {
                $upload_error = 1;
            });
            $(upload_node).find('input[name=upload]').on('filebatchuploadcomplete', function (event, files, extra) {
                $table.draw('page');
                if (!$upload_error){
                    $(upload_node).slideToggle();
                    $(this).fileinput('clear');
                }
            });
            $(upload_node).find('.file-cats-upload').select2({
                width: '100%',
                minimumResultsForSearch: -1
            });
            @if (count($settings['categories'])==1)
                $(buttons_node).find('select.file-cats').parent().hide();
                $(upload_node).find('.file-cats-upload').parent().parent().hide();
            @endif
        });

        function delete_file(items) {
            if (items.count() == 0){
                swal({
                    title: "{{__('Không thể thực thi')}}",
                    text: "{{__('Vui lòng chọn ít nhất 1 file để xóa')}}",
                    confirmButtonColor: "#EF5350",
                    confirmButtonText: "{{__('Đã hiểu')}}",
                    type: "error"
                });
                return;
            }
            swal({
                    title: "{{__('Xóa File')}}",
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
                            url: '{!! route('backend.file.destroy') !!}',
                            dataType: 'json',
                            type: 'post',
                            data: {
                                ids: ids,
                                _method: 'delete',
                            },
                            success: function () {
                                new PNotify({
                                    title: '{{'XỬ LÝ THÀNH CÔNG'}}',
                                    text: "{{__('File được chọn đã xóa thành công.')}}",
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

        $(window).trigger({
            type: 'wa.datatable.ran.{{jSID()}}',
            table_object: $table,
            table_html: $('#datatable_{{jSID()}}')[0]
        });
    });
</script>
@endpush