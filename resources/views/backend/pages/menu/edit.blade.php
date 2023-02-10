@php
    /** @var \App\Menu $model */
    /** @var \App\Events\MenuTypeRegister $event */
@endphp
@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.pnotify.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.jquery_ui.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.fancytree_all.id'), JS_LOCATION_DEFAULT, 'jquery_ui')
@enqueueJSByID(config('view.ui.files.js.fancytree_childcounter.id'), JS_LOCATION_DEFAULT, 'fancytree_all')
@extends('layouts.backend')
@if($model == null)
    @section('page_title', __('Tạo menu'))
@section('page_header_title')
    {!! __('Tạo <strong>menu</strong>') !!}
@endsection
@else
    @section('page_title', __('Chỉnh sửa menu'))
@section('page_header_title')
    {!! __('Chỉnh sửa <strong>menu</strong>') !!}
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
            <span>{{__('THÊM ITEM')}}</span>
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
                    <select data-placeholder="{{__('Chọn loại menu...')}}" data-width="100%" id="menu_types">
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
                    {{__('THÊM ITEM')}}
                </button>
            </div>
            @event(new \App\Events\AfterHtmlBlock('sidebar.add-item'))
        </div>
    </div>
    @include('backend.pages.menu.includes.menu_items')
@endsection
@section('page_content_body')
    @event(new \App\Events\BeforeHtmlBlock('content'))
    <form class="" id="{{jSID('form')}}">
        @event(new \App\Events\BeforeHtmlBlock('content.form'))
        @component('backend.components.panel', ['classes'=>'panel-default'])
            @slot('title')
                <h5 class="panel-title text-teal">{{__('Thông tin menu')}}</h5>
            @endslot
            @slot('content')
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-lg-3">{{__('Tên menu')}}</label>
                        <div class="col-lg-9">
                            <input name="title" value="{{$model?$model->title:''}}" class="form-control" spellcheck="false" placeholder="{{__('Nhập tên nhận biết menu')}}">
                        </div>
                    </div>
                </div>
            @endslot
        @endcomponent
        @component('backend.components.panel', ['classes'=>'panel-default', 'has_body' => false])
            @slot('title')
                <h5 class="panel-title text-teal">{{__('Các item con')}}</h5>
            @endslot
            @slot('content')
                    {{--<div class="table-responsive">--}}
                        <table class="table table-bordered tree-table" id="item-table">
                            <thead>
                            <tr>
                                <th>{{__('Tiêu đề')}}</th>
                                <th style="width: 50px; text-align: center">{{__('Icon')}}</th>
                                <th style="width: 200px; text-align: center">{{__('Quyền xem')}}</th>
                                <th style="width: 200px; text-align: center">{{__('Loại')}}</th>
                                <th style="width: 150px; text-align: center">{{__('Hành động')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align: left"></td>
                                    <td style="text-align: center"></td>
                                    <td style="text-align: center"></td>
                                    <td style="text-align: center"></td>
                                    <td style="text-align: center"></td>
                                </tr>
                            </tbody>
                        </table>
                    {{--</div>--}}
            @endslot
        @endcomponent
        @event(new \App\Events\AfterHtmlBlock('content.form'))
        <input type="hidden" name="is_update" value="{{$model?'1':'0'}}">
    </form>
    <div id="modal_edit_type" class="modal fade">
        <div class="modal-dialog">
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
                                            <input placeholder="{{__('Tên đề link')}}" spellcheck="false" name="title" value="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-3">{{__('Icon')}}</label>
                                        <div class="col-lg-9">
                                            @component('backend.components.icon_selector')
                                                @slot('name', 'icon')
                                                @slot('classes', 'icon_selector'),
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset id="item_config_link_wrapper">
                                <legend class="text-semibold"><i class="icon-link position-left"></i> {{__('Liên kết')}}
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
                                    <label class="control-label col-lg-3">{{__('Link target')}}</label>
                                    <div class="col-lg-9">
                                        <select name="target" class="target_selector" data-width="100%">
                                            <option value="_self">{{__('Mở ở tab/cửa sổ hiện tại')}}</option>
                                            <option value="_blank">{{__('Mở ở tab/cửa sổ mới')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-3">{{__('CSS Classes')}}</label>
                                    <div class="col-lg-9">
                                        <input name="classes" placeholder="{{__('Tùy chọn css class')}}" spellcheck="false" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-3">{{__('Tag attributes')}}</label>
                                    <div class="col-lg-9">
                                        <input name="attributes" placeholder="{{__('Tùy chọn thuộc tính thẻ a')}}" spellcheck="false" class="form-control">
                                    </div>
                                </div>
                            </div>
                            </fieldset>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">{{__('Hủy bỏ')}}</button>
                        <button type="submit" class="btn btn-success">{{__('Thực thi')}}</button>
                    </div>
                    <input type="hidden" name="menu_type">
                </form>
            </div>
        </div>
    </div>
    <template id="{{str_slug(Route::currentRouteName())}}_row_action_tpl">
        <a title="{{__('Sửa')}}" class="label label-success label-icon label-rounded action-edit"><i class="icon-pencil"></i></a>
        <a title="{{__('Xóa')}}" class="label label-warning label-icon label-rounded action-delete"><i class="icon-trash"></i></a>
    </template>
    <template id="{{str_slug(Route::currentRouteName())}}_no_option_tpl">
        <div class="alert alert-info alert-bordered text-center">
            {{__('Menu item này không cần thiết lập tùy chọn link')}}
        </div>
    </template>
    @event(new \App\Events\AfterHtmlBlock('content'))
@endsection
@push('page_footer_js')
    @foreach($event->getTypes() as $type)
        @if($type->getHtmlView() != false)
            @php
                $data = $type->getViewData();
                $data['menu_type'] = $type;
            @endphp
            @include($type->getHtmlView(), $data)
        @endif
    @endforeach
    <script type="text/javascript">
        $(function () {
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
            var $menu = $('#item-table').fancytree({
                extensions: ["table","dnd"],
                icon: false,
                table: {
                    indentation: 20,      // indent 20px per node level
                    nodeColumnIdx: 0,     // render the node title into the 2nd column
                },
                source: [
                ],
                clickFolderMode: 1,
                dnd: {
                    autoExpandMS: 400,
                    focusOnClick: true,
                    preventVoidMoves: true,
                    preventRecursiveMoves: true,
                    dragStart: function(node, data) {
                        return true;
                    },
                    dragEnter: function(node, data) {
                        return true;
                    },
                    dragDrop: function(node, data) {
                        data.otherNode.moveTo(node, data.hitMode);
                    },
                },
                dblclick:function (event) {
                    if (event.originalEvent.target.className == 'fancytree-title'){
                        $(event.originalEvent.target).parent().parent().parent().find('.action-edit').click();
                    }
                },
                renderColumns: function(event, data) {
                    var node = data.node,
                        $tdList = $(node.tr).find(">td");

                    // (index #0 is rendered by fancytree by adding the checkbox)
                    //$tdList.eq(0).css('text-align', 'left');
                    var info = data.node.data.info;
                    var item = data.node;
                    $( $tdList.eq(1)).html('<i class="'+info.icon+'"></i>');
                    var html = '<span class="label label-info label-block">';
                    switch (info.login_status){
                        case 'all':
                            html = html + '{{__('Tất cả')}}';
                            break;
                        case 'logged':
                            html = html + '{{__('Đã đăng nhập')}}';
                            break;
                        case 'guest':
                            html = html + '{{__('Chưa đăng nhập')}}';
                            break;
                    }
                    html = html + '</span>';
                    if (info.login_status == 'logged'){
                        if (info.roles){
                            if (info.roles.length >0){
                                html = '';
                                $.each(info.roles, function (i, v) {
                                    var lbl = $roles.getByID(v);
                                    html = html + '<span class="label label-info label-block mt-5">'+lbl.text+'</span>';
                                });
                            }
                        }
                    }
                    $( $tdList.eq(2)).html(html);
                    $( $tdList.eq(3)).html($menu_types.getTypeByID(data.node.data.type).title);
                    var ra = $('#{{str_slug(Route::currentRouteName())}}_row_action_tpl').html();
                    $( $tdList.eq(4)).html(ra);
                    $($tdList.eq(4)).find('.action-delete').on('click', function () {
                        node.remove();
                    });
                    $($tdList.eq(4)).find('.action-edit').on('click', function () {
                        addEditItem(item.data.type, item);
                    });
                }
            });

            function iconFormat(icon) {
                var originalOption = icon.element;
                if (!icon.id) { return icon.text; }
                var $icon = "<i class='" + $(icon.element).data('icon') + "'></i>" + icon.text;

                return $icon;
            }

            $('#modal_edit_type .icon_selector').select2({
                allowClear: true,
                templateResult: iconFormat,
                templateSelection: iconFormat,
                escapeMarkup: function(m) { return m; }
            });
            $('#modal_edit_type .icon_selector').val("1").trigger("change");
            $('#modal_edit_type .target_selector').select2({
                minimumResultsForSearch: -1
            });

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

            $('#menu_types').select2({
                templateResult: iconFormat,
                templateSelection: iconFormat,
                escapeMarkup: function(m) { return m; }
            });

            $('#bnt_add_item').click(function () {
                var type_id = $('#menu_types').val();
                addEditItem(type_id, null);
            });

            var edit_modal = $('#modal_edit_type').modal({
                show: false,
                backdrop: 'static'
            });

            function addEditItem(type_id, item) {
                $('#item_config_link_holder').html('');
                $('#modal_edit_type form [name=title]').val('');
                $('#modal_edit_type form [name=icon]').val("1").trigger("change");
                $('#modal_edit_type form [name=target]').val("_self").trigger("change");
                $('#modal_edit_type form [name="roles[]"]').val(null).trigger("change");
                $('#modal_edit_type form [name=login_status]').val('all').trigger("change");
                $('#modal_edit_type form [name=classes]').val('');
                $('#modal_edit_type form [name=attributes]').val('');
                if (item == null){
                    $('#modal_edit_type .modal-title').html('{{__('THÊM MENU ITEM')}}');
                    $('#modal_edit_type').data('item', null);
                }
                else {
                    $('#modal_edit_type .modal-title').html('{{__('SỬA MENU ITEM')}}');
                    $('#modal_edit_type form [name=title]').val(item.data.info.title);
                    $('#modal_edit_type form [name=icon]').val(item.data.info.icon).trigger("change");
                    $('#modal_edit_type form [name=target]').val(item.data.info.target).trigger("change");
                    $('#modal_edit_type form [name="roles[]"]').val(item.data.info.roles).trigger("change");
                    $('#modal_edit_type form [name=login_status]').val(item.data.info.login_status).trigger("change");
                    $('#modal_edit_type form [name=classes]').val(item.data.info.classes);
                    $('#modal_edit_type form [name=attributes]').val(item.data.info.attributes);
                    $('#modal_edit_type').data('item', item);
                }
                var type = $menu_types.getTypeByID(type_id);
                if (type.has_option == false){
                    $('#item_config_link_wrapper').hide();
                }
                else{
                    var fn = window[type.js_load];
                    fn($('#item_config_link_holder'), item?item.data:null);
                    $('#item_config_link_wrapper').show()
                }
                var form = $('#{{jSID('form-option')}}');
                cleanErrorMessage(form);
                $(form).find('input[name=menu_type]').val(type_id);
                edit_modal.modal('show')
            }


            $('#{{jSID('submit')}}').click(function () {
                saveForm(this, function (rs) {
                    @if ($model)
                    new PNotify({
                        title: '{{'LƯU DỮ LIỆU'}}',
                        text: '{{__('Menu đã được lưu thành công!')}}',
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
                    window.location = '{{route('backend.menu.library.index')}}';
                })
            });

            @if (!$model)
            $('#{{jSID('submit_create')}}').click(function () {
                saveForm(this, function (rs) {
                    window.location = '{{route('backend.menu.create')}}';
                })
            });

            @endif

            function saveForm(btn, after_success) {
                var form = $('#{{jSID('form')}}');
                var data = $(form).serializeObject();
                @if ($model)
                    data._method = 'put';
                @endif
                var items = [];
                $menu.fancytree("getTree").visit(function (node) {
                    var item = {
                        key: node.key,
                        parent: node.parent.parent==null?0:node.parent.key,
                        data: {
                            title: node.title,
                            type: node.data.type,
                            icon: node.data.info.icon,
                            classes: node.data.info.classes,
                            attributes: node.data.info.attributes,
                            target: node.data.info.target,
                            roles: node.data.info.roles,
                            login_status: node.data.info.login_status,
                            options: node.data.options
                        }
                    };

                    items.push(item);
                });
                console.log(items);
                data.items = items;
                $.ajax({
                    @if ($model)
                    url: '{{route('backend.menu.update', ['menu' => $model])}}',
                    type: 'post',
                    @else
                    url: '{{route('backend.menu.store')}}',
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

            $('#modal_edit_type form').submit(function () {
                var form = this;
                var data = $(form).serializeObject();
                var old_item = $('#modal_edit_type').data('item');
                $.ajax({
                    url: '{{route('backend.menu.option.save')}}',
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
                        if (old_item == null){
                            $menu.fancytree("getRootNode").addChildren(item);
                        }
                        else{
                            var node = $menu.fancytree("getNodeByKey", old_item.key);
                            node.fromDict(item);
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

            var $menu_types = {
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
                    @foreach($model->items as $item)
                    @if (!hasMenuType($item->type))
                        @continue;
                    @endif
                    {
                        js:{
                            title: '{{$item->title}}',
                            data: {
                                info: {
                                    title: '{{$item->title}}',
                                    icon: '{{$item->icon}}',
                                    target: '{{$item->target}}',
                                    classes: '{{$item->classes}}',
                                    attributes: '{{$item->attributes}}',
                                    login_status: '{{$item->login_status}}',
                                    roles: {!! $item->roles !!}
                                },
                                options: {!! $item->options !!},
                                type: '{{$item->type}}',
                            }
                        },
                        php:{
                            id: {{$item->id}},
                            parent_id: {{$item->parent_id}}
                        }
                    },
                    @endforeach
                ];
            function loadOldItems(parent_id, parent_node){
                if ($old_items.length == 0){
                    return;
                }
                $.each($old_items, function (i, v) {
                    var js = v.js;
                    var php = v.php;
                    if (php.parent_id != parent_id){
                        return true;
                    }
                    var node;
                    if (parent_node == null){
                        node = $menu.fancytree("getRootNode").addChildren(js);
                    }
                    else{
                        node = parent_node.addChildren(js);
                    }
                    loadOldItems(php.id, node);
                });
            }
            loadOldItems(0, null);
            $menu.fancytree("getRootNode").visit(function(node){
                node.setExpanded(true);
            });
            @endif
        });
    </script>
@endpush