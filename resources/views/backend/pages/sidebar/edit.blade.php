@php
    /** @var \App\Widget $model */
    /** @var \App\Events\WidgetTypeRegister $event */
    /** @var \App\Widget[] $widgets */
@endphp
@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.pnotify.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.jquery_ui.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.fancytree_all.id'), JS_LOCATION_DEFAULT, 'jquery_ui')
@enqueueJSByID(config('view.ui.files.js.fancytree_childcounter.id'), JS_LOCATION_DEFAULT, 'fancytree_all')
@enqueueJSByID(config('view.ui.files.js.dragula.id'), JS_LOCATION_DEFAULT, 'jquery')
@extends('layouts.backend')
@if($model == null)
    @section('page_title', __('Tạo sidebar'))
@section('page_header_title')
    {!! __('Tạo <strong>menu</strong>') !!}
@endsection
@else
    @section('page_title', __('Chỉnh sửa sidebar'))
@section('page_header_title')
    {!! __('Chỉnh sửa <strong>sidebar</strong>') !!}
@endsection
@endif
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
                        id="{{jSID('submit')}}" type="button" class="btn bg-primary btn-block btn-save">
                    {{__('LƯU THAY ĐỔI')}}
                </button>
                <button data-loading-text="<i class='icon-spinner4 spinner position-left'></i> {{__('ĐANG XỬ LÝ...')}}"
                        id="{{jSID('submit_return')}}" type="button" class="btn bg-orange btn-block btn-save">
                    {{__('LƯU VÀ QUAY LẠI')}}
                </button>
                @if(!$model)
                    <button data-loading-text="<i class='icon-spinner4 spinner position-left'></i> {{__('ĐANG XỬ LÝ...')}}"
                            id="{{jSID('submit_create')}}" type="button" class="btn bg-success btn-block btn-save">
                        {{__('LƯU VÀ TẠO TIẾP')}}
                    </button>
                @endif
                @event(new \App\Events\AfterHtmlBlock('sidebar.actions'))
            </div>
        </div>
    </div>
    <div class="sidebar-category">
        <div class="category-title" data-group-id="add-item">
            <span>{{__('THÊM WIDGET')}}</span>
            <ul class="icons-list">
                <li><a href="#" data-action="collapse"></a></li>
            </ul>
        </div>

        <div class="category-content text-center">
            @event(new \App\Events\BeforeHtmlBlock('sidebar.add-item'))
            <div class="form-group">
                <div class="mb-10">
                    @php
                        $groups = $event->getInfo();
                    @endphp
                    <select data-placeholder="{{__('Chọn loại widget...')}}" data-width="100%" id="widget_types">
                        @foreach($groups as $group_data)
                            @php
                                /** @var \App\Classes\MenuTypeGroup $group */
                                $group = $group_data['group'];
                                /** @var \App\Classes\MenuType[] $types */
                                $types = $group_data['types'];
                            @endphp
                            <optgroup label="{{$group->title}}">
                                @foreach($types as $type)
                                    <option value="{{$type->getID()}}" data-icon="{{$type->getIcon()}}">{{$type->getTitle()}}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <button id="bnt_add_item" type="button" class="btn btn-danger btn-block">
                    {{__('THÊM WIDGET')}}
                </button>
            </div>
            @event(new \App\Events\AfterHtmlBlock('sidebar.add-item'))
        </div>
    </div>
    @include('backend.pages.sidebar.includes.sidebar_items')
@endsection
@section('page_content_body')
    @event(new \App\Events\BeforeHtmlBlock('content'))
    <form class="" id="{{jSID('form')}}">
        @event(new \App\Events\BeforeHtmlBlock('content.form'))
        @component('backend.components.panel', ['classes'=>'panel-default'])
            @slot('title')
                <h5 class="panel-title text-teal">{{__('Thông tin sidebar')}}</h5>
            @endslot
            @slot('content')
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-lg-3">{{__('Tên sidebar')}}</label>
                        <div class="col-lg-9">
                            <input name="title" value="{{$model?$model->title:''}}" class="form-control" spellcheck="false" placeholder="{{__('Nhập tên nhận biết sidebar')}}">
                        </div>
                    </div>
                </div>
            @endslot
        @endcomponent
        @component('backend.components.panel', ['classes'=>'panel-default', 'has_body' => true])
            @slot('title')
                <h5 class="panel-title text-teal">{{__('Các widget')}}</h5>
            @endslot
            @slot('content')
                <ul class="media-list media-list-container" id="{{jSID('widget_list')}}">

                </ul>
            @endslot
        @endcomponent
        @event(new \App\Events\AfterHtmlBlock('content.form'))
        <input type="hidden" name="is_update" value="{{$model?'1':'0'}}">
    </form>
    <div id="modal_edit_type" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title"></h5>
                </div>

                <form id="{{jSID('form-option')}}">
                    <div class="modal-body">
                        <fieldset>
                            <legend class="text-semibold"><i class="icon-pencil position-left"></i> {{__('Hiển thị')}}
                                <a class="control-arrow" data-toggle="collapse" data-target="#item_config_info">
                                    <i class="icon-circle-down2"></i>
                                </a>
                            </legend>
                            <div id="item_config_info" class="form-horizontal collapse in">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">{{__('Tiêu đề')}}</label>
                                    <div class="col-lg-9">
                                        <input placeholder="{{__('Tên đề widget')}}" spellcheck="false" name="title" value="" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset id="item_config_link_wrapper">
                            <legend class="text-semibold"><i class="icon-hammer-wrench position-left"></i> {{__('Tùy chỉnh')}}
                                <a class="control-arrow" data-toggle="collapse" data-target="#item_config_link">
                                    <i class="icon-circle-down2"></i>
                                </a>
                            </legend>
                            <div id="item_config_link" class="form-horizontal collapse in">
                                <div class="form-group">
                                    <div id="item_config_link_holder"></div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <legend class="text-semibold"><i class="icon-equalizer position-left"></i> {{__('Nâng cao')}}
                                <a class="control-arrow" data-toggle="collapse" data-target="#item_config_adv">
                                    <i class="icon-circle-down2"></i>
                                </a>
                            </legend>
                            <div id="item_config_adv" class="form-horizontal collapse">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">{{__('Trạng thái đăng nhập')}}</label>
                                    <div class="col-lg-9">
                                        <select name="login_status" class="login_status_selector" data-width="100%">
                                            <option value="all">{{__('Cả đăng nhập và chưa')}}</option>
                                            <option value="logged">{{__('Đã đăng nhập')}}</option>
                                            <option value="guest">{{__('Chưa đăng nhập')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group role_selector_wrapper">
                                    <label class="control-label col-lg-3">{{__('Vai trò yêu cầu')}}</label>
                                    <div class="col-lg-9">
                                        <select name="roles[]" class="role_selector" data-width="100%">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-3">{{__('CSS Classes')}}</label>
                                    <div class="col-lg-9">
                                        <input name="classes" placeholder="{{__('Tùy chọn css class')}}" spellcheck="false" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">{{__('Hủy bỏ')}}</button>
                        <button type="submit" class="btn btn-success">{{__('Thực thi')}}</button>
                    </div>
                    <input type="hidden" name="widget_type">
                </form>
            </div>
        </div>
    </div>
    <template id="{{jSID('widget_item_tpl')}}">
        <li class="media widget-item">
            <div class="media-left media-middle">
                <i class="icon-dots dragula-handle"></i>
            </div>

            <div class="media-body cursor-pointer edit-link">
                <div class="media-heading text-semibold cursor-pointer rp-title">{title}</div>
                <div>
                    <span class="label label-info"><i class="icon-pencil7 text-size-mini"></i> <span class="rp-type">{type}</span></span><span class="rp-permission">{permission}</span>
                </div>
            </div>

            <div class="media-right media-middle">
                <ul class="icons-list text-nowrap widget-action">
                    <li>
                        <span class="label label-warning label-rounded label-icon widget-delete cursor-pointer"><i class="icon-trash"></i></span>
                    </li>
                </ul>
            </div>
        </li>
    </template>
    <template id="{{jSID('no_option_tpl')}}">
        <div class="alert alert-info alert-bordered text-center">
            {{__('Widget này không cần thiết lập tùy chọn')}}
        </div>
    </template>
    @event(new \App\Events\AfterHtmlBlock('content'))
@endsection
@push('page_footer_js')
    @foreach($event->getTypes() as $type)
        @if($type->getHtmlView() != false)
            @php
            $data = $type->getViewData();
            $data['widget_type'] = $type;
            @endphp
            @include($type->getHtmlView(), $data)
        @endif
    @endforeach
    <script type="text/javascript">
        $(function () {
            dragula([document.getElementById('{{jSID('widget_list')}}')], {
                mirrorContainer: document.querySelector('.media-list-container'),
                moves: function (el, container, handle) {
                    return handle.classList.contains('dragula-handle');
                }
            });
            var $roles = {
                items : {!! json_encode(\App\Role::getHtmlSelectData()) !!},
                getByID: function (id) {
                    var rs = null;
                    $.each(this.items, function (i, v) {
                        if(v.id == id){
                            rs = v;
                        }
                    });
                    return rs;
                }
            };

            $('#modal_edit_type .role_selector').select2({
                minimumResultsForSearch: -1,
                placeholder: '{{__('Mặc định tất cả vai trò')}}',
                multiple: true,
                data: $roles.items
            });

            $('#modal_edit_type .login_status_selector').select2({
                minimumResultsForSearch: -1
            });

            $('#modal_edit_type .login_status_selector').on('change', function () {
                var val = $(this).val();
                if (val == 'logged'){
                    $('#modal_edit_type form .role_selector_wrapper').show();
                }
                else{
                    $('#modal_edit_type form .role_selector_wrapper').hide();
                }
            });

            $('#modal_edit_type .login_status_selector').trigger('change');

            function iconFormat(icon) {
                var originalOption = icon.element;
                if (!icon.id) { return icon.text; }
                var $icon = "<i class='" + $(icon.element).data('icon') + "'></i>" + icon.text;

                return $icon;
            }

            $('#widget_types').select2({
                templateResult: iconFormat,
                templateSelection: iconFormat,
                escapeMarkup: function(m) { return m; }
            });

            $('#bnt_add_item').click(function () {
                var type_id = $('#widget_types').val();
                addEditItem(type_id, null);
            });

            var edit_modal = $('#modal_edit_type').modal({
                show: false,
                backdrop: 'static'
            });

            function addEditItem(type_id, item) {
                $('#item_config_link_holder').html('');
                $('#modal_edit_type form [name=title]').val('');
                $('#modal_edit_type form [name="roles[]"]').val(null).trigger("change");
                $('#modal_edit_type form [name=login_status]').val('all').trigger("change");
                $('#modal_edit_type form [name=classes]').val('');
                if (item == null){
                    $('#modal_edit_type .modal-title').html('{{__('THÊM WIDGET')}}');
                    $('#modal_edit_type').data('item', null);
                }
                else {
                    var data = $(item).data('item');
                    $('#modal_edit_type .modal-title').html('{{__('SỬA WIDGET')}}');
                    $('#modal_edit_type form [name=title]').val(data.title);
                    $('#modal_edit_type form [name="roles[]"]').val(data.roles).trigger("change");
                    $('#modal_edit_type form [name=login_status]').val(data.login_status).trigger("change");
                    $('#modal_edit_type form [name=classes]').val(data.classes);
                    $('#modal_edit_type').data('item', item);
                }
                var type = $widget_types.getTypeByID(type_id);
                if (type.has_option == false){
                    $('#item_config_link_wrapper').hide();
                }
                else{
                    var fn = window[type.js_load];
                    fn($('#item_config_link_holder'), item?data:null);
                    $('#item_config_link_wrapper').show()
                }
                var form = $('#{{jSID('form-option')}}');
                cleanErrorMessage(form);
                $(form).find('input[name=widget_type]').val(type_id);
                edit_modal.modal('show')
            }


            $('#{{jSID('submit')}}').click(function () {
                saveForm(this, function (rs) {
                    @if ($model)
                    new PNotify({
                        title: '{{'LƯU DỮ LIỆU'}}',
                        text: '{{__('Sidebar đã được lưu thành công!')}}',
                        addclass: 'bg-success stack-bottom-right',
                        stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25},
                        buttons: {
                            sticker: false
                        },
                        delay: 2000
                    });
                    @else
                        window.location = rs;
                    @endif
                })
            });

            $('#{{jSID('submit_return')}}').click(function () {
                saveForm(this, function (rs) {
                    window.location = '{{route('backend.sidebar.library.index')}}';
                })
            });

            @if (!$model)
            $('#{{jSID('submit_create')}}').click(function () {
                saveForm(this, function (rs) {
                    window.location = '{{route('backend.sidebar.create')}}';
                })
            });

            @endif

            function saveForm(btn, after_success) {
                var form = $('#{{jSID('form')}}');
                var data = $(form).serializeObject();
                var items = [];
                $('#{{jSID('widget-list')}} li .edit-link').each(function () {
                    items.push($(this).data('item'));
                });
                data.widgets = items;
                @if ($model)
                      data._method = 'put';
                @endif
                $.ajax({
                    @if ($model)
                    url: '{{route('backend.sidebar.update', ['sidebar' => $model])}}',
                    type: 'post',
                    @else
                    url: '{{route('backend.sidebar.store')}}',
                    type: 'post',
                    @endif
                    dataType: 'json',
                    data: data,
                    beforeSend: function () {
                        cleanErrorMessage(form);
                        $(btn).button('loading');
                        $('.btn-save').not(btn).prop('disabled', true);
                    },
                    success: function (rs) {
                        after_success(rs);
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
                    },
                    complete: function () {
                        $(btn).button('reset');
                        $('.btn-save').not(btn).prop('disabled', false);
                    }
                });
            }

            function get_permission_tags(item) {
                var permission = '<span class="label label-warning ml-5"><i class="icon-users text-size-mini"></i> ';
                switch (item.login_status){
                    case 'all':
                        permission = permission + '{{__('Tất cả')}}';
                        break;
                    case 'logged':
                        permission = permission + '{{__('Đã đăng nhập')}}';
                        break;
                    case 'guest':
                        permission = permission + '{{__('Chưa đăng nhập')}}';
                        break;
                }
                permission = permission + '</span>';
                if (item.login_status == 'logged'){
                    if (item.roles){
                        if (item.roles.length > 0){
                            permission = '';
                            $.each(item.roles, function (i, v) {
                                var lbl = $roles.getByID(v);
                                permission = permission + '<span class="label label-warning ml-5"><i class="icon-users text-size-mini"></i> '+lbl.text+'</span>';
                            })
                        }
                    }
                }

                return permission;
            }

            function add_new_widget(item) {
                var type = $widget_types.getTypeByID(item.type);
                var html = $('#{{jSID('widget_item_tpl')}}').html();
                html = html.replace(/{title}/, item.title);
                html = html.replace(/{type}/, type.title);
                html = html.replace(/{permission}/, get_permission_tags(item));
                html = $(html);
                $(html).find('.edit-link').data('item', item);
                $('#{{jSID('widget_list')}}').append(html);
                $(html).find('.edit-link').click(function () {
                    addEditItem(type.id, this);
                });
                $(html).find('.widget-action .widget-delete').click(function () {
                    $(this).parents('.widget-item').remove();
                });
            }

            $('#modal_edit_type form').submit(function () {
                var form = this;
                var data = $(form).serializeObject();
                var old_item = $('#modal_edit_type').data('item');
                $.ajax({
                    url: '{{route('backend.widget.option.save')}}',
                    dataType: 'json',
                    data: data,
                    type: 'post',
                    beforeSend: function () {
                        cleanErrorMessage(form);
                        $('#modal_edit_type .modal-content form').block({
                            message: '<i class="icon-spinner4 spinner"></i>',
                            overlayCSS: {
                                backgroundColor: '#fff',
                                opacity: 0.8,
                                cursor: 'wait'
                            },
                            css: {
                                border: 0,
                                padding: 0,
                                backgroundColor: 'none'
                            }
                        });
                    },
                    success: function (rs) {
                        var item = rs;
                        var type = $widget_types.getTypeByID(item.type);

                        if (old_item == null){
                            add_new_widget(item);
                        }
                        else{
                            var node = $('#modal_edit_type').data('item');
                            $(node).find('.rp-title').html(item.title);
                            $(node).find('.rp-type').html(type.title);
                            $(node).find('.rp-permission').html(get_permission_tags(item));
                            $(node).data('item', item);
                        }
                        edit_modal.modal('hide');
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
                    },
                    complete: function () {
                        $('#modal_edit_type .modal-content form').unblock();
                    }
                });

                return false;
            });

            var $widget_types = {
                'items': {
                    @foreach($event->getTypes() as $type)
                    '{{$type->getID()}}' : {
                        'id' : '{{$type->getID()}}',
                        'title': '{{$type->getTitle()}}',
                        'group_id' : '{{$type->getGroupID()}}',
                        'js_id': '{{$type->getJSID()}}',
                        'js_load': '{{$type->getJSLoad()}}',
                        'icon': '{{$type->getIcon()}}',
                        'order': '{{$type->getOrder()}}',
                        'has_option': {{$type->getHtmlView()==false?'0':'1'}}
                    },
                    @endforeach
                },
                getTypeByID: function (id) {
                    return this.items[id];
                }
            };
                    @if ($model)
            var $old_items = [
                            @foreach($model->widgets as $item)
                            @if (!hasWidgetType($item->type))
                                @continue
                            @endif
                    {
                        js:{
                            title: '{{$item->title}}',
                            classes: '{{$item->classes}}',
                            login_status: '{{$item->login_status}}',
                            roles: {!! $item->roles !!},
                            options: {!! $item->options !!},
                            type: '{{$item->type}}',
                        },
                        php:{
                            id: {{$item->id}},
                        }
                    },
                        @endforeach
                ];
            function loadOldItems(){
                if ($old_items.length == 0){
                    return;
                }
                $.each($old_items, function (i, v) {
                    var js = v.js;
                    add_new_widget(js);
                });
            }
            loadOldItems();
            @endif
        });
    </script>
@endpush