@enqueueCSS('reset-password-page', getThemeAssetUrl('libs/styles/reset-password.css'), 'account-menu')
@extends(getThemeViewName('account.master'))
@section('current_page_title')
    Mật Khẩu
@endsection
@section('content')
    <div class="content-box">
        <div class="content-title">Thay đổi mật khẩu</div>
        <div class="content-body">
            <form id="reset-password-page-form">
                <div class="field">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="lbl">
                                Mật khẩu hiện tại
                            </div>
                        </div>
                        <div class="col-md-7">
                            <input name="old_password" type="password" placeholder="Mật khẩu hiện tại">
                        </div>
                    </div>
                </div>
                <div class="field">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="lbl">
                                Mật khẩu mới
                            </div>
                        </div>
                        <div class="col-md-7">
                            <input name="password" type="password" placeholder="Mật khẩu mới">
                        </div>
                    </div>
                </div>
                <div class="field">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="lbl">
                                Nhập lại mật khẩu mới
                            </div>
                        </div>
                        <div class="col-md-7">
                            <input name="password_confirmation" type="password" placeholder="Nhập lại mật khẩu mới">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                    </div>
                    <div class="col-md-7">
                        <div class="submit">
                            <button>Thay đổi</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('page_footer_js')
    <script type="text/javascript">
        $(function () {
            $('#reset-password-page-form').submit(function () {
                var form = $(this);
                var data = $(form).serializeObject();
                $.ajax({
                    url: '{!! route('frontend.account.reset_password.save') !!}',
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    beforeSend: function () {
                        $(form).addClass('loading');
                    },
                    complete: function () {
                        $(form).removeClass('loading');
                    },
                    success: function () {
                        cleanErrorMessage(form);
                        swal("Đổi mật khẩu", "Bạn đã đổi mật khẩu thành công", "success")
                    },
                    error: function (json) {
                        handleErrorMessage(form, json);
                    }
                });
                return false;
            });
        });
    </script>
@endpush