@extends('layouts.backend_base')
@section('page_body_classes', 'login-container bg-slate-800')
@section('page_title', __('Đăng nhập quản trị'))
@section('page_content')
    <!-- Content area -->
    <div class="content">
        <!-- Advanced login -->
        <form id="backend_login_form" name="backend_login_form" method="post" action="{{route('backend.login.login')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="panel panel-body login-form">
                <div class="text-center">
                    <img src="{{asset('assets/ui/images/walogo.png')}}">
                    <h5 class="content-group-lg">
                        {{{ __('ĐĂNG NHẬP HỆ THỐNG') }}}
                        <small class="display-block">{{__('Vui lòng nhập thông tin đăng nhập') }}</small>
                    </h5>
                </div>
                <div class="login-error"></div>
                @php event(new \App\Events\BackendLoginBeforeFormInputs()) @endphp
                <div class="form-group has-feedback has-feedback-left">
                    <input name="email" type="text" class="form-control input-lg" placeholder="{{__('Email đăng nhập')}}">
                    <div class="form-control-feedback">
                        <i class="icon-user text-muted"></i>
                    </div>
                </div>
                <div class="form-group has-feedback has-feedback-left">
                    <input name="password" type="password" class="form-control input-lg" placeholder="{{__('Mật khẩu đăng nhập')}}">
                    <div class="form-control-feedback">
                        <i class="icon-lock2 text-muted"></i>
                    </div>
                </div>
                @php event(new \App\Events\BackendLoginAfterFormInputs()) @endphp
                <div class="form-group login-options">
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="checkbox-inline">
                                <input value="1" name="remember" type="checkbox" class="styled" checked="checked">{{__('Nhớ đăng nhập')}}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button id="login_submit_btn" type="submit" data-loading-text="<i class='icon-spinner4 spinner position-left'></i> {{__('ĐANG XỬ LÝ...')}}" class="btn bg-primary btn-block btn-lg btn-loading">
                        {{__('ĐĂNG NHẬP')}} <i class="icon-arrow-right14 position-right"></i>
                    </button>
                </div>
                @php event(new \App\Events\BackendLoginAfterFormSubmit()) @endphp
            </div>
        </form>
        <!-- /advanced login -->
    </div>
    <!-- /content area -->
@endsection
@push('page_footer_js')
<!-- form backend login ajax -->
<script type="text/javascript">
    $(function() {
        // Style checkboxes and radios
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.styled').uniform();
        $('#backend_login_form').submit(function () {
            var form = this;
            var data = $(form).serialize();
            $(window).trigger('BackendLoginForm.ajax.data', data, form);
            $.ajax({
                url: '{{route('backend.login.login')}}',
                dataType: 'json',
                method: 'post',
                data: data,
                beforeSend: function () {
                    $('.btn-loading').button('loading');
                    $(window).trigger('BackendLoginForm.ajax.beforeSend', data, form);
                },
                complete: function (response) {
                    $('.btn-loading').button('reset');
                    $(window).trigger('BackendLoginForm.ajax.complete', data, form, response);
                },
                success: function (response) {
                        $(window).trigger('BackendLoginForm.ajax.success', data, form, response);
                        if (response.hasOwnProperty('RedirectTo')){
                            window.location = response.RedirectTo;
                        }
                },
                error: function (response) {
                    $(form).find('.login-error').html('');
                    if (response.hasOwnProperty('responseJSON')){
                        if (response.responseJSON.hasOwnProperty('LoginFail')){
                            $(form).find('.handled-error-label').remove();
                            $(form).find('.login-error').html('<div class="alert alert-danger alert-bordered">'+response.responseJSON.LoginFail+'</div>');
                        }
                    }
                    handleErrorMessage(form, response);
                    $(window).trigger('BackendLoginForm.ajax.error', data, form, response);
                }
            });
            return false;
        });
    });
</script>
<!-- /form backend login ajax -->
@endpush
