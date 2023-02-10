@php
/** @var array $permission_info */
@endphp
@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.pnotify.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@extends('layouts.backend')
@section('page_title', __('Tài khoản cá nhân'))
@section('page_header_title')
    {!! __('Tài khoản <strong>cá nhân</strong>') !!}
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
                        id="{{jSID('submit')}}" type="button" class="btn bg-warning btn-block btn-save">
                    {{__('LƯU THAY ĐỔI')}}
                </button>
                @event(new \App\Events\AfterHtmlBlock('sidebar.actions'))
            </div>
        </div>
    </div>
    @event(new \App\Events\BeforeHtmlBlock('sidebar.groups'))
    <div class="sidebar-category">
        <div class="category-title">
            <span>{{__('Quyền hạn')}}</span>
            <ul class="icons-list">
                <li><a href="#" data-action="collapse"></a></li>
            </ul>
        </div>
        <?php if (count($permission_info)>0):?>
        <div class="category-content">
            <div class="mb-15">
                <div class="media-heading text-semibold">{{Auth::user()->getRoleTitle()}}</div>
                <div class="text-muted">{{Auth::user()->getRoleDesc()}}</div>
            </div>
            <ul class="media-list">
                @foreach($permission_info as $group)
                    @php
                    $color = 'warning';
                    @endphp
                    <li class="media">
                        <div class="media-left">
                            <a class="btn border-{{$color}} text-{{$color}} btn-flat btn-rounded btn-icon btn-sm">
                                <i class="{{$group['group']->icon}}"></i>
                            </a>
                        </div>

                        <div class="media-body">
                            <span class="media-heading text-semibold">{{$group['group']->title}}</span>
                            @foreach($group['permissions'] as $permission)
                                <div class="mt-5 text-size-small text-slate-300"><i class="icon-circle-small"></i> {{$permission->title}}</div>
                            @endforeach
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <?php endif;?>
    </div>
    @event(new \App\Events\AfterHtmlBlock('sidebar.groups'))
@endsection
@section('page_content_body')
    @event(new \App\Events\BeforeHtmlBlock('content'))
    <form class="" id="{{jSID('form')}}">
        @event(new \App\Events\BeforeHtmlBlock('content.form'))
        @component('backend.components.panel', ['classes'=>'panel-default'])
            @slot('title')
                <h5 class="panel-title text-teal">{{__('Thông tin tài khoản')}}</h5>
            @endslot
            @slot('content')
                <div class="form-horizontal">
                    <fieldset>
                        <legend class="text-semibold">
                            <i class="icon-file-text2 position-left"></i>
                            {{__('Thông tin cơ bản')}}
                        </legend>
                        @component('backend.components.field', ['field'=>$avatar_field])@endcomponent
                        <div class="form-group">
                            <label class="control-label col-lg-3">{{__('Email')}}<span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="email" value="{{Auth::user()->email}}"
                                       readonly class="form-control" type="text" spellcheck="false"
                                       placeholder="{{__('Email đăng nhập')}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-3">{{__('Họ tên')}}<span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="name" value="{{Auth::user()->name}}" class="form-control" type="text"
                                       spellcheck="false" placeholder="{{__('Họ tên chủ tài khoản')}}">
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
                            </label>
                            <div class="col-lg-9">
                                <input name="password" class="form-control" type="password" spellcheck="false"
                                       placeholder="{{__('Mật khẩu đăng nhập')}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-3">
                                {{__('Xác nhận mật khẩu')}}
                            </label>
                            <div class="col-lg-9">
                                <input name="password_confirmation" class="form-control" type="password" spellcheck="false"
                                       placeholder="{{__('Nhập mật khẩu lần nữa')}}">
                            </div>
                        </div>
                    </fieldset>
                    @event(new \App\Events\AfterHtmlBlock('content.after_password'))
                </div>
            @endslot
        @endcomponent
        @event(new \App\Events\AfterHtmlBlock('content.form'))
    </form>
    @event(new \App\Events\AfterHtmlBlock('content'))
@endsection
@push('page_footer_js')
    <script type="text/javascript">
        $(function () {
            $('#{{jSID('submit')}}').click(function () {
                saveForm(this, function (rs) {
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
                })
            });

            function saveForm(btn, after_success) {
                var form = $('#{{jSID('form')}}');
                var data = $(form).serializeObject();
                data._method = 'put'
                $.ajax({
                    url: '{{route('backend.profile.update')}}',
                    type: 'post',
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