<div class="float-form-login" id="float-form-login">
    <div class="form-wrapper">
        <form id="form-global-login">
            <div class="form-content">
                <div class="field">
                    <input name="login" autocomplete="off" type="text" spellcheck="false" placeholder="Số điện thoại hoặc email">
                </div>
                <div class="field">
                    <input name="password" autocomplete="off" type="password" spellcheck="false" placeholder="Mật khẩu đăng nhập">
                </div>
                <div class="row">
                    <div class="col-6">
                        <label class="checkbox-container remember-checkbox">
                            <input name="remember" value="1" type="checkbox">
                            <span class="checkmark"></span>
                            Lưu đăng nhập
                        </label>
                    </div>
                    <div class="col-6">
                        <div class="reset-password-link">
                            <a href="#" class="show-reset-password-link">Lấy lại mật khẩu</a>
                        </div>
                    </div>
                </div>
                <div class="buttons">
                    <button class="login">ĐĂNG NHẬP</button>
                    <button class="show-register-form-link" type="button" class="register">ĐĂNG KÝ</button>
                    <div class="hoac-tiep-tuc">
                        Hoặc tiếp tục...
                    </div>
                    <button type="button" class="facebook">
                        <i class="fa fa-facebook"></i>
                        <span>Facebook</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@unique('login_form_script')
<script type="text/javascript">
    $(function () {
        $('#form-global-login button.google').click(function () {
            window.location = '{!! route('frontend.login.social', ['provider' => 'google']) !!}';
        });
        $('#form-global-login button.facebook').click(function () {
            window.location = '{!! route('frontend.login.social', ['provider' => 'facebook']) !!}';
        });
        $('#form-global-login').submit(function () {
            var form = $(this);
            var data = $(this).serializeObject();
            $.ajax({
                url: '{!! route('frontend.login.check') !!}',
                type: 'post',
                dataType: 'json',
                data: data,
                beforeSend: function(){

                },
                success: function (json) {
                    {{--return false;--}}
                    {{--var rl = '{!! url('') !!}';--}}
                    {{--if(json.hasOwnProperty('RedirectTo')){--}}
                    {{--    rl = json.RedirectTo;--}}
                    {{--}--}}
                    // window.location = rl;
                    window.location = '{!! Request::url() !!}';
                },
                complete: function(){

                },
                error: function (json) {
                    handleErrorMessage(form, json);
                }
            });
            return false;
        });
        $('body').on('click', function (e) {
            var  l = $('.float-form-login.active').length;
            if(l){
                $('.float-form-login').removeClass('active');
            }
        });
        $('.float-form-login').click(function (e) {
            e.stopPropagation();
        });
        $('.float-form-login .close-btn').click(function (e) {
            e.stopPropagation();
            $(this).parents('.float-form-login').removeClass('active');
        });
    });
</script>
@endunique