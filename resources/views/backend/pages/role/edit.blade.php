@php
    /** @var \App\Role $model */
@endphp
@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.pnotify.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@extends('layouts.backend')
@if($model == null)
    @section('page_title', __('Tạo vai trò'))
@section('page_header_title')
    {!! __('Tạo <strong>vai trò</strong>') !!}
@endsection
@else
    @section('page_title', __('Chỉnh sửa vai trò'))
@section('page_header_title')
    {!! __('Chỉnh sửa <strong>vai trò</strong>') !!}
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
    @event(new \App\Events\BeforeHtmlBlock('sidebar.groups'))
    @hasPermission('manage_roles')
    <div class="sidebar-category" data-block-id="role">
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
                <li><a href="{{route('backend.role.index')}}"><i class="icon-users"></i> {{__('Danh sách vai trò')}}</a>
                </li>
                @event(new \App\Events\AfterHtmlBlock('sidebar.groups.role'))
            </ul>
        </div>
    </div>
    @endif
    @hasPermission('manage_users')
    <div class="sidebar-category" data-group-id="account">
        <div class="category-title">
            <span>{{__('Tài khoản')}}</span>
            <ul class="icons-list">
                <li><a href="#" data-action="collapse" class=""></a></li>
            </ul>
        </div>
        <div class="category-content no-padding" style="display: block;">
            <ul class="navigation navigation-alt navigation-accordion datatable-users-actions">
                @event(new \App\Events\BeforeHtmlBlock('sidebar.groups.account'))
                <li><a href="{{route('backend.user.create')}}"><i class="icon-user-plus"></i> {{__('Thêm tài khoản')}}
                    </a></li>
                <li><a href="{{route('backend.user.index')}}"><i class="icon-users4"></i> {{__('Danh sách tài khoản')}}
                    </a></li>
                @event(new \App\Events\AfterHtmlBlock('sidebar.groups.account'))
            </ul>
        </div>
    </div>
    @endif
    @event(new \App\Events\AfterHtmlBlock('sidebar.groups'))
@endsection
@section('page_content_body')
    @event(new \App\Events\BeforeHtmlBlock('content'))
    <form class="" id="{{jSID('form')}}">
        @event(new \App\Events\BeforeHtmlBlock('content.form'))
        @component('backend.components.panel', ['classes'=>'panel-default'])
            @slot('title')
                <h5 class="panel-title text-teal">{{__('Thông tin vai trò')}}</h5>
            @endslot
            @slot('content')
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-lg-3">{{__('Tên vai trò')}}<span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input placeholder="{{__('Nhập tên vai trò')}}" class="form-control" type="text" spellcheck="false" name="title" value="{{$model?$model->title:''}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3">{{__('Mô tả vai trò')}}<span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <textarea name="desc" class="form-control" rows="5" spellcheck="false" placeholder="{{__('Nhập mô tả ngắn cho vai trò')}}">{{$model?$model->desc:''}}</textarea>
                        </div>
                    </div>
                </div>
            @endslot
        @endcomponent
        @component('backend.components.panel', ['classes'=>'panel-default'])
            @slot('title')
                <h5 class="panel-title text-teal">{{__('Phân quyền vai trò')}}</h5>
            @endslot
            @slot('content')
                @php
                $e = app('permissions');
                $groups = Auth::user()->getAllowPermissionToSet();
                @endphp
                @foreach($groups as  $group_data)
                        <fieldset data-group-id="{{$group_data['group']->id}}">
                            <legend class="text-semibold">
                                <i class="{{$group_data['group']->icon}} position-left"></i>
                                {{$group_data['group']->title}}
                            </legend>
                            <div class="form-group">
                                @foreach($group_data['permissions'] as $permission)
                                    <div class="checkbox">
                                        <label>
                                            @if($model)
                                            @php
                                                $permissions = $model->permissions();
                                            @endphp
                                            <input {{$model->isUltimateRole()||(Auth::user()->isMyRole($model->id))?'disabled':''}}  {{$model->hasPermission($permission->id)?'checked':''}} name="permissions[]" type="checkbox" class="styled" value="{{$permission->id}}">
                                            {{$permission->title}}
                                            @else
                                                <input name="permissions[]" type="checkbox" class="styled" value="{{$permission->id}}">
                                                {{$permission->title}}
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </fieldset>
                @endforeach
            @endslot
        @endcomponent
        @event(new \App\Events\AfterHtmlBlock('content.form'))
        <input type="hidden" name="is_update" value="{{$model?'1':'0'}}">
    </form>
    @event(new \App\Events\AfterHtmlBlock('content'))
@endsection
@push('page_footer_js')
    <script type="text/javascript">
        $(function () {
            $('.permission').uniform();
            $('#{{jSID('roles')}}').select2({
                minimumResultsForSearch: Infinity,
                placeholder: '{{__('Chọn vai trò cho tài khoản...')}}',
                data: {!! json_encode(\App\Role::getHtmlSelectData()) !!}
            }).val('{{$model?$model->role_id:''}}').trigger('change');

            $('#{{jSID('submit')}}').click(function () {
                saveForm(this, function (rs) {
                    @if ($model)
                    new PNotify({
                        title: '{{'LƯU DỮ LIỆU'}}',
                        text: '{{__('Tài khoản đã được lưu thành công!')}}',
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
                    window.location = '{{route('backend.role.index')}}';
                })
            });

            @if (!$model)
            $('#{{jSID('submit_create')}}').click(function () {
                saveForm(this, function (rs) {
                    window.location = '{{route('backend.role.create')}}';
                })
            });

            @endif

            function saveForm(btn, after_success) {
                var form = $('#{{jSID('form')}}');
                var data = $(form).serializeObject();
                @if ($model)
                    data._method = 'put';
                @endif
                $.ajax({
                    @if ($model)
                    url: '{{route('backend.role.update', ['role' => $model])}}',
                    type: 'post',
                    @else
                    url: '{{route('backend.role.store')}}',
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
        });
    </script>
@endpush