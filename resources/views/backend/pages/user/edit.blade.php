@php
    /** @var \App\User $model */
@endphp
@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.pnotify.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@extends('layouts.backend')
@if($model == null)
@section('page_title', __('Tạo tài khoản'))
@section('page_header_title')
    {!! __('Tạo <strong>tài khoản</strong>') !!}
@endsection
@else
@section('page_title', __('Chỉnh sửa tài khoản'))
@section('page_header_title')
    {!! __('Chỉnh sửa <strong>tài khoản</strong>') !!}
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
    @event(new \App\Events\AfterHtmlBlock('sidebar.groups'))
@endsection
@section('page_content_body')
    @event(new \App\Events\BeforeHtmlBlock('content'))
    <form class="form-horizontal" id="{{jSID('form')}}">
        @event(new \App\Events\BeforeHtmlBlock('content.form'))
        @component('backend.components.panel', ['classes'=>'panel-default'])
            @slot('title')
                <h5 class="panel-title text-teal">{{__('Thông tin tài khoản')}}</h5>
            @endslot
            @slot('content')
                <fieldset>
                    <legend class="text-semibold">
                        <i class="icon-file-text2 position-left"></i>
                        {{__('Thông tin cơ bản')}}
                    </legend>
                    @if($model)
                        @component('backend.components.field', ['field'=>$avatar_field])@endcomponent
                    @endif
                    <div class="form-group">
                        <label class="control-label col-lg-3">{{__('Email')}}<span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input name="email" value="{{$model?$model->email:''}}"
                                   {{$model?'readonly':''}} class="form-control" type="text" spellcheck="false"
                                   placeholder="{{__('Email đăng nhập')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3">{{__('Họ tên')}}<span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input name="name" value="{{$model?$model->name:''}}" class="form-control" type="text"
                                   spellcheck="false" placeholder="{{__('Họ tên chủ tài khoản')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3">{{__('Vai trò')}}<span
                                    class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            @if($model &&  Auth::user()->isMyID($model->id))
                                <select disabled  id="{{jSID('roles')}}" data-width="100%"></select>
                                <input type="hidden" name="role_id" value="{!! $model->role_id !!}">
                            @else
                                <select name="role_id" id="{{jSID('roles')}}" data-width="100%"></select>
                            @endif
                        </div>
                    </div>
                </fieldset>
                @event(new \App\Events\AfterHtmlBlock('content.after_info'))
                <fieldset>
                    <legend class="text-semibold">
                        <i class="icon-key position-left"></i>
                        {{__('Thông tin mật khẩu')}}
                    </legend>
                    <div class="form-group">
                        <label class="control-label col-lg-3">
                            {{__('Mật khẩu')}}
                            @if(!$model)
                                <span class="text-danger">*</span>
                            @endif
                        </label>
                        <div class="col-lg-9">
                            <input name="password" class="form-control" type="password" spellcheck="false"
                                   placeholder="{{__('Mật khẩu đăng nhập')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3">
                            {{__('Xác nhận mật khẩu')}}
                            @if(!$model)
                                <span class="text-danger">*</span>
                            @endif
                        </label>
                        <div class="col-lg-9">
                            <input name="password_confirmation" class="form-control" type="password" spellcheck="false"
                                   placeholder="{{__('Nhập mật khẩu lần nữa')}}">
                        </div>
                    </div>
                </fieldset>
                @event(new \App\Events\AfterHtmlBlock('content.after_password'))
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
                    window.location = '{{route('backend.user.index')}}';
                })
            });

            @if (!$model)
            $('#{{jSID('submit_create')}}').click(function () {
                saveForm(this, function (rs) {
                    window.location = '{{route('backend.user.create')}}';
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
                    url: '{{route('backend.user.update', ['user' => $model])}}',
                    type: 'post',
                    @else
                    url: '{{route('backend.user.store')}}',
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