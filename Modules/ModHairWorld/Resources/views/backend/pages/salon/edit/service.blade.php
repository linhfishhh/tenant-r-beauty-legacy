@php
    /** @var \Modules\ModHairWorld\Entities\Salon $salon */
    /** @var \Modules\ModHairWorld\Entities\SalonServiceCategory[] $service_cats */
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
                    {{__('THÊM DỊCH VỤ')}}
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
            <h6 class="panel-title text-teal text-semibold">{!! __('DANH SÁCH DỊCH VỤ') !!}</h6>
        </div>
        <table data-table-name="{{jSID()}}" id="datatable_{{jSID()}}" class="table table-condensed table-hover">
            <thead>
            <tr></tr>
            </thead>
        </table>
    </div>
    <div id="modal-add-manager" class="modal fade" style="overflow-y: auto">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="post" data-edit-mode="">
                    <div class="modal-header bg-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title"></h6>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label text-semibold">{!! __('Tên dịch vụ') !!}</label>
                                    <input class="form-control" spellcheck="false" name="name" value="" placeholder="{!! __('Nhập tên dịch vụ') !!}">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                @component('backend.components.field', [
                                        'field' => $color_field,
                                        'horizontal' => 0
                                    ])
                                @endcomponent
                            </div>
                            <div class="col-lg-3">
                                @component('backend.components.field', [
                                        'field' => $text_color_field,
                                        'horizontal' => 0
                                    ])
                                @endcomponent
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label text-semibold">{!! __('Danh mục dịch vụ') !!}</label>
                                    <select class="form-control" name="service_cat_id">
                                        @foreach($service_cats as $cat)
                                            <option value="{!! $cat->id !!}">{!! $cat->title !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                @component('backend.components.field', [
                                        'field' => $price_field,
                                        'horizontal' => 0
                                    ])
                                @endcomponent
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                @component('backend.components.field', [
                                    'field' => $time_from_field,
                                    'horizontal' => 0
                                ])
                                @endcomponent
                            </div>
                            <div class="col-lg-6">
                                @component('backend.components.field', [
                                    'field' => $time_to_field,
                                    'horizontal' => 0
                                ])
                                @endcomponent
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label text-semibold">{!! __('Ảnh đại diện dịch vụ') !!}</label>
                            <div class="cover-picker"></div>
                        </div>
                        <div class="form-group">
                            <label class="control-label text-semibold">{!! __('Logo các thương hiệu sử dụng') !!}</label>
                            <div class="logo-picker"></div>
                        </div>
                        <div class="form-group">
                            <label class="control-label text-semibold">{!! __('Hình ảnh dịch vụ') !!}</label>
                            <div class="image-picker"></div>
                        </div>
                        @component('backend.components.field', [
                                        'field' => $description_field,
                                        'horizontal' => 0
                                    ])
                        @endcomponent
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">{!! __('ĐÓNG') !!}</button>
                        <button type="submit" class="btn btn-primary">{!! __('LƯU DỮ LIỆU') !!}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="modal-add-option" class="modal fade" style="overflow-y: auto">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" data-edit-mode="">
                    <div class="modal-header bg-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title">QUẢN LÝ TUỲ CHỌN</h6>
                    </div>
                    <div class="modal-body">
                        <div class="option_manager">

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
    <div id="modal-add-included-option" class="modal fade" style="overflow-y: auto">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" data-edit-mode="">
                    <div class="modal-header bg-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title">QUẢN LÝ DỊCH VỤ KÈM THEO</h6>
                    </div>
                    <div class="modal-body">
                        <div class="option_manager">

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
    <template id="{!! jSID('logo_field') !!}">
        @component('backend.components.field', [
            'field' => $logo_field,
            'unhandled' => true
        ])
        @endcomponent
    </template>
    <template id="{!! jSID('image_field') !!}">
        @component('backend.components.field', [
            'field' => $image_field,
            'unhandled' => true
        ])
        @endcomponent
    </template>
    <template id="{!! jSID('option_field') !!}">
        @component('backend.components.field', [
            'field' => $option_field,
            'unhandled' => true
        ])
        @endcomponent
    </template>
    <template id="{!! jSID('included_option_field') !!}">
        @component('backend.components.field', [
            'field' => $included_option_field,
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
            $modal_option = $('#modal-add-option').modal({
                show: false,
                backdrop: 'static'
            });
            $modal_included_option = $('#modal-add-included-option').modal({
                show: false,
                backdrop: 'static'
            });
            $('#{{jSID('add_manager')}}').click(function () {
                addEditStylist(null);
            });
            $modal_add.find('select[name=service_cat_id]').select2({

            });
            $modal_add.find('form').submit(function(){
                var form = this;
                var data = {
                    'name' : $(form).find('input[name=name]').val(),
                    'cover_id' : $(form).find('input[name=cover_id]').val(),
                    'price': $(form).find('input[name=price]').val(),
                    'description': $(form).find('textarea[name=description]').val(),
                    'service_cat_id' : $(form).find('select[name=service_cat_id]').val(),
                    'time_from' : $(form).find('input[name=time_from]').val(),
                    'time_to' : $(form).find('input[name=time_to]').val(),
                    'color' : $(form).find('input[name=color]').val(),
                    'text_color' : $(form).find('input[name=text_color]').val(),
                };
                var ip = 'logos[]';
                var logo_i = $(form).find('input[name="'+ip+'"]');
                $(logo_i).each(function (index, logo_f) {
                    data['logos['+index+']'] = $(logo_f).val();
                });
                var service_image_ip = 'images[]';
                var service_image_i = $(form).find('input[name="'+service_image_ip+'"]');
                $(service_image_i).each(function (index, service_image_f) {
                    data['images['+index+']'] = $(service_image_f).val();
                });
                var edit_mode = $(form).data('edit-mode');
                var url = '{!! route('backend.salon.service.store', ['salon'=>$salon]) !!}';
                if (edit_mode) {
                    url = '{!! route('backend.salon.service.update', ['salon'=>$salon]) !!}';
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

            $modal_option.find('form').submit(function(){
                var form = this;
                var data = $(form).serializeObject();
                var url = '{!! route('backend.salon.service.options.update', ['salon' => $salon, 'service' => '???']) !!}';
                var service_id = $(form).data('id');
                url = url.replace('???', service_id);
                //console.log(data);
                $.ajax({
                    url: url,
                    method: 'post',
                    data: data,
                    beforeSend: function(){
                        cleanErrorMessage(form);
                        $modal_option.find('button').prop('disabled', true);
                    },
                    success: function (json) {
                        console.log(json);
                        $modal_option.modal('hide');
                        $table.draw('page');
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
                    complete: function () {
                        $modal_option.find('button').prop('disabled', false);
                    }
                });
                return false;
            });

            $modal_included_option.find('form').submit(function(){
                var form = this;
                var data = $(form).serializeObject();
                console.log(data);
                var url = '{!! route('backend.salon.service.includedoptions.update', ['salon' => $salon, 'service' => '???']) !!}';
                var service_id = $(form).data('id');
                url = url.replace('???', service_id);
                // console.log(data);
                $.ajax({
                    url: url,
                    method: 'post',
                    data: data,
                    beforeSend: function(){
                        cleanErrorMessage(form);
                        $modal_included_option.find('button').prop('disabled', true);
                    },
                    success: function (json) {
                        // console.log(json);
                        $modal_included_option.modal('hide');
                        $table.draw('page');
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
                    complete: function () {
                        $modal_included_option.find('button').prop('disabled', false);
                    }
                });
                return false;
            });

            function addEditStylist($service){
                cleanErrorMessage($modal_add.find('form'));
                var picker = $modal_add.find('.cover-picker');
                var logo_picker = $modal_add.find('.logo-picker');
                var image_picker = $modal_add.find('.image-picker');
                picker.html('');
                logo_picker.html('');
                var html = $('#{!! jSID('avatar_field') !!}').html();
                picker.html(html);
                html = $('#{!! jSID('logo_field') !!}').html();
                logo_picker.html(html);
                html = $('#{!! jSID('image_field') !!}').html();
                image_picker.html(html);
                var cover_id = '';
                var logo_ids = [];
                var image_ids = [];
                if ($service){
                    $modal_add.find('.modal-title').html('{!! __('SỬA DỊCH VỤ') !!}');
                    $modal_add.find('form').data('edit-mode', true);
                    $modal_add.find('form input[name=name]').val($service.name);
                    cover_id = $service.cover_id;
                    $modal_add.find('form').data('edit-target', $service.id);
                    $modal_add.find('form input[name=price]').val($service.price);
                    $modal_add.find('form input[name=time_from]').val($service.time_from);
                    $modal_add.find('form input[name=time_to]').val($service.time_to);
                    $modal_add.find('form input[name=color]').spectrum("set", $service.color);
                    $modal_add.find('form input[name=text_color]').spectrum("set", $service.text_color);
                    console.log($service);
                    var elem = document.createElement('textarea');
                    elem.innerHTML = $service.description;
                    var decoded = elem.value;
                    tinymce.get('description').setContent(decoded);
                    $modal_add.find('form textarea[name=description]').val(decoded);
                    $modal_add.find('form select[name=service_cat_id]').val($service.category_id).trigger('change');
                    $.each($service.logos, function (index, logo) {
                        logo_ids.push(logo.logo_id);
                    });
                    $.each($service.images, function (index, image) {
                        image_ids.push(image.image_id);
                    });
                }
                else{
                    $modal_add.find('form input[name=name]').val('');
                    $modal_add.find('form input[name=price]').val(100000);
                    $modal_add.find('form input[name=time_from]').val(30);
                    $modal_add.find('form input[name=time_to]').val(30);
                    $modal_add.find('form input[name=color]').spectrum("set", '#F2F2F2');
                    $modal_add.find('form input[name=text_color]').spectrum("set", '#21232C');
                    tinymce.get('description').setContent('');
                    $modal_add.find('form textarea[name=description]').val('');
                    $modal_add.find('form select[name=service_cat_id]').val(null).trigger('change');
                    $modal_add.find('.modal-title').html('{!! __('THÊM DỊCH VỤ') !!}');
                    $modal_add.find('form').data('edit-mode', false);
                }
                {!! $avatar_field->getHtmlTemplateHandleID() !!}(picker, cover_id);
                {!! $logo_field->getHtmlTemplateHandleID() !!}(logo_picker, logo_ids);
                console.log('{!! $image_field->getHtmlTemplateHandleID() !!}');
                console.log(image_picker);
                {!! $image_field->getHtmlTemplateHandleID() !!}(image_picker, image_ids);
                $modal_add.modal('show');
            }

            function addEditOptions($service){
                $modal_option.find('form').data('id', $service.id);
                var option_manager = $modal_option.find('.option_manager');
                var html = $('#{!! jSID('option_field') !!}').html();
                option_manager.html(html);
                {!! $option_field->getHtmlTemplateHandleID() !!}(option_manager, $service.options);
                $modal_option.modal('show');
            }

            function addEditIncludedOptions($service){
                $modal_included_option.find('form').data('id', $service.id);
                var option_manager = $modal_included_option.find('.option_manager');
                var html = $('#{!! jSID('included_option_field') !!}').html();
                option_manager.html(html);
                {!! $included_option_field->getHtmlTemplateHandleID() !!}(option_manager, $service.included_options);
                $modal_included_option.modal('show');
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
                    data: 'cover',
                    name: 'cover',
                    title_html: '<th>{!! __('Cover') !!}</th>',
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
                    title_html: '<th>{!! __('Tên dịch vụ') !!}</th>',
                    cell_define: {
                        width: "250px",
                        render: function (data, type, row) {
                            var html = '<div><span class="edit-stylist cursor-pointer text-semibold text-teal">'+data+'</div>';
                            var time = row.time_from+'';
                            if(row.time_from < row.time_to){
                                time += ' - '+row.time_to
                            }
                            time += ' phút';
                            html += '<div class="mt-5"><span class="text-warning text-semibold mr-15"><i class="icon-coin-dollar"></i> '+row.price_format+'</span>' +
                                '<span class="text-orange text-semibold"><i class="icon-watch2"></i> '+time+'</span></div>';
                            return html;
                        }
                    }
                },
                {
                    data: 'cat',
                    name: 'cat',
                    title_html: '<th>{!! __('Danh mục') !!}</th>',
                    orderable: false,
                    cell_define: {
                        width: "250px",
                        render: function (data, type, row) {
                            return '<div class="text-primary"><span class="">'+data+'</div>';
                        }
                    }
                },
                {
                    data: '',
                    name: '',
                    title_html: '<th>{!! __('Các tuỳ chọn') !!}</th>',
                    cell_define: {
                        render: function (data, type, row) {
                            var html = '';
                            if(row.options.length === 0){
                                html += '<div class="text-muted mb-5">Chưa có tuỳ chọn nào!</div>';
                            }
                            else{
                                html += '<div class="text-warning mb-5">Có '+row.options.length+' tuỳ chọn</div>';
                            }
                            return html;
                        }
                    }
                },
                {
                    data: '',
                    name: '',
                    title_html: '<th>{!! __('Dịch vụ kèm theo') !!}</th>',
                    cell_define: {
                        render: function (data, type, row) {
                            var html = '';
                            if(row.included_options.length === 0){
                                html += '<div class="text-muted mb-5">Chưa có dịch vụ nào!</div>';
                            }
                            else{
                                html += '<div class="text-warning mb-5">Có '+row.included_options.length+' dịch vụ</div>';
                            }
                            return html;
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
                        width: "200px",
                        render: function (data, type, row) {
                            var html = '<a data-id="'+row.id+'" class="label label-warning label-icon cursor-pointer delete-stylist mr-10"><i class="icon-trash"></i></a>';
                            html += '<a data-id="'+row.id+'" class="label bg-orange label-icon cursor-pointer btn-add-option mr-10"><i class="icon-add-to-list"></i></a>';
                            html += '<a data-id="'+row.id+'" class="label bg-blue label-icon cursor-pointer btn-add-included-option mr-10"><i class="icon-add-to-list"></i></a>';
                            html += '<a data-id="'+row.id+'" class="edit-stylist label label-success label-icon cursor-pointer edit-stylist"><i class="icon-pencil5"></i></a>';
                            return html;
                        }
                    }
                },
            ];
            var $options = {
                select: false,
                autoWidth: false,
                ajax: '{!! route('backend.salon.service.edit', ['salon' => $salon]) !!}',
                columns: [],
                columnDefs: []
            };
            var $included_options = {
                select: false,
                autoWidth: false,
                ajax: '{!! route('backend.salon.service.edit', ['salon' => $salon]) !!}',
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
                $included_options.columns.push({
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

                $(e.target).find('.btn-add-option').click(function(){
                    var items = $table.rows(':eq(' + $(this).parents('tr').index() + ')', {page: 'current'});
                    addEditOptions(items.data()[0]);
                });

                $(e.target).find('.btn-add-included-option').click(function(){
                    var items = $table.rows(':eq(' + $(this).parents('tr').index() + ')', {page: 'current'});
                    addEditIncludedOptions(items.data()[0]);
                });
            });
            function deleteStylist(ids){
                if (ids.length == 0) {
                    swal({
                        title: "{{__('Không thể thực thi')}}",
                        text: "{!! __('Vui lòng chọn 1 dịch vụ để xóa') !!}",
                        confirmButtonColor: "#EF5350",
                        confirmButtonText: "{{__('Đã hiểu')}}",
                        type: "error"
                    });
                    return;
                }
                swal({
                        title: "{!! __('Xóa dịch vụ') !!}",
                        text: "{!! __('Bạn có chắc chắn muốn xóa dịch vụ này không?') !!}",
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
                                url: '{!! route('backend.salon.service.destroy', ['salon' => $salon]) !!}',
                                dataType: 'json',
                                type: 'post',
                                data: {
                                    ids: ids,
                                    _method: 'delete'
                                },
                                success: function () {
                                    new PNotify({
                                        title: '{{'XỬ LÝ THÀNH CÔNG'}}',
                                        text: "{!! __('Xóa dịch vụ thành công') !!}",
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