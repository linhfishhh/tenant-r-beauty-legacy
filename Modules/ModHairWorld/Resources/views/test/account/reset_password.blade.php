@enqueueCSS('reset-password-page', getThemeAssetUrl('libs/styles/reset-password.css'), 'account-menu')
@extends(getThemeViewName('test.account.master'))
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
                            <input type="password" placeholder="Mật khẩu hiện tại">
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
                            <input type="password" placeholder="Mật khẩu mới">
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
                            <input type="password" placeholder="Nhập lại mật khẩu mới">
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