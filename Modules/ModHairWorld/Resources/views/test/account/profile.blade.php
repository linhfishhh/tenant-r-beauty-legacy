@enqueueCSS('profile-page', getThemeAssetUrl('libs/styles/profile.css'), 'master-page')
@php
    $dark_theme = 1;
@endphp
@extends(getThemeViewName('master'))
@section('page_content')
    <div class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="left-sec">
                        <div class="avatar">
                            <img src="{!! getThemeAssetUrl('img/avatar_profile.jpg') !!}">
                        </div>
                        <div class="block phone-numbers">
                            <div class="block-title">
                                Các số điện thoại
                            </div>
                            <div class="block-content">
                                <ul>
                                    <li class="default">
                                        <a>093569875</a>
                                        <div class="default action" title="Đã chọn làm mặc định"><i class="fa fa-check-circle"></i></div>
                                        <div class="delete action" title="Xóa bỏ"><i class="fa fa-minus-circle"></i></div>
                                    </li>
                                    <li>
                                        <a>0125896325</a>
                                        <div class="default action" title="Đã chọn làm mặc định"><i class="fa fa-check-circle"></i></div>
                                        <div class="delete action" title="Xóa bỏ"><i class="fa fa-minus-circle"></i></div>
                                    </li>
                                </ul>
                                <div class="add-more">
                                    <a href="#">Thêm số điện thoại</a>
                                </div>
                            </div>
                        </div>
                        <div class="block basic-info">
                            <div class="block-title">
                                Thông tin cơ bản
                            </div>
                            <div class="block-content">
                                <div class="info">
                                    <div class="lbl">Giới tính:</div>
                                    <div class="val">Nam</div>
                                </div>
                                <div class="info">
                                    <div class="lbl">Ngày sinh:</div>
                                    <div class="val">30/04/1980</div>
                                </div>
                                <div class="info">
                                    <div class="lbl">Email:</div>
                                    <div class="val">Thạch Minh Trang</div>
                                </div>
                                <div class="info">
                                    <div class="lbl">Địa chỉ:</div>
                                    <div class="val">56A, Lý Tự Trọng, Ninh Kiều, Cần Thơ</div>
                                </div>
                                <div class="info">
                                    <div class="lbl">Phương thức thanh toán mặc định:</div>
                                    <div class="val">Nganluong.vn</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="right-sec">
                        <div class="welcome">Chào, Thạch Minh Trang!</div>
                        <div class="address-join-date">
                            <span class="address">Ninh Kiều, Cần Thơ</span>
                            <i class="fa fa-circle"></i>
                            <span class="join-date">Gia nhập ngày 30/04/2017</span>
                        </div>
                        <div class="edit-links">
                            <a class="edit-profile-link" href="{!! route('test.account.edit') !!}">Chỉnh sửa thông tin cá nhân</a>
                            <a class="edit-password-link" href="{!! route('test.account.reset_password') !!}">Thay đổi mật khẩu</a>
                        </div>
                        <div class="info-blocks">
                            <div class="row">
                                <div class="col-md-4">
                                    <a href="#" class="info-block d-table orders">
                                        <div class="wrapper">
                                            <div class="block-title">ĐƠN HÀNG</div>
                                            <div class="block-number">16</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="#" class="info-block d-table waiting">
                                        <div class="wrapper">
                                            <div class="block-title">ĐANG CHỜ</div>
                                            <div class="block-number">02</div>
                                            <div class="block-text">13:30 - 05/05/2018</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="#" class="info-block d-table favs">
                                        <div class="wrapper">
                                            <div class="block-icon">
                                                <i class="fa fa-heart-o"></i>
                                            </div>
                                            <div class="block-title">YÊU THÍCH</div>
                                            <div class="block-number">09</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="coupons">
                            <div class="block-title">Bạn có 3 mã giảm giá</div>
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="name">YSWEZA</td>
                                        <td class="desc">Giảm 50% tại salon Tóc Tây</td>
                                        <td class="date">Hết hạn: 30/04/2018</td>
                                    </tr>
                                    <tr>
                                        <td class="name">GHJTRD</td>
                                        <td class="desc">Giảm 30% tại salon Tóc Tây</td>
                                        <td class="date">Hết hạn: 30/03/2018</td>
                                    </tr>
                                    <tr>
                                        <td class="name">RT4YFD</td>
                                        <td class="desc">Giảm 10% tại salon Tóc Tây</td>
                                        <td class="date">Hết hạn: 30/01/2018</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-bread-cumber">
        <div class="container">
            <a href="#">Trang chủ</a> / <a href="#">Xu hướng tóc</a>
        </div>
    </div>
@endsection
@push('page_footer_js')
    <script type="text/javascript">
        $(function () {

        });
    </script>
@endpush