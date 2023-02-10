@enqueueCSS('contact-page', getThemeAssetUrl('libs/styles/contact.css'), 'master-page')
@extends(getThemeViewName('master'))
@section('page_content')
    <div class="contact-page-header common-page-header" style="background-image: url('{!! getThemeAssetUrl('img/contact_header_bg.jpg') !!}')">
        <div class="container">
            <div class="wrapper">
                <div class="inner">
                    <div class="title">
                        Liên hệ với chúng tôi để được hổ trợ
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-block">
                        <div class="block-title">Cho chúng tôi biết bạn cần gì</div>
                        <div class="block-content">
                            <form id="form-contact">
                                <div class="field">
                                    <input name="name" placeholder="Tên của bạn">
                                </div>
                                <div class="field">
                                    <input name="email" placeholder="Email liên hệ">
                                </div>
                                <div class="field">
                                    <input name="phone" placeholder="Số điện thoại">
                                </div>
                                <div class="field">
                                    <textarea name="content" placeholder="Nội dung liên hệ" rows="10"></textarea>
                                </div>
                                <div class="btn-submit">
                                    <button>LIÊN HỆ CHÚNG TÔI</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-block">
                        <div class="block-title">Địa chỉ</div>
                        <div class="block-content">
                            <div class="hot-line">
                                <span class="lbl">Hotline:</span>
                                <span class="text">1800-588-883</span>
                            </div>
                            <div class="contact-item">
                                <div class="line name">
                                    Trụ sở chính
                                </div>
                                <div class="line">
                                    <div>Địa chỉ: Tầng 3, D2 Giảng Võ, Phường Giảng Võ, Quận Ba Đình, Hà Nội</div>
                                    <div>Email: nfo@salonzo.com</div>
                                </div>
                                <div class="line">
                                    <div>SĐT: 0243.5190242 / 0243.5190244</div>
                                    <div>Fax: +84  4 3519 0217</div>
                                </div>
                                <div class="map-link"><a href="#" target="_blank">Xem bản đồ chỉ dẫn</a></div>
                            </div>
                            <div class="contact-item">
                                <div class="line name">
                                    Chi nhánh Miền Nam
                                </div>
                                <div class="line">
                                    <div>Địa chỉ: Tầng 3, D2 Giảng Võ, Phường Giảng Võ, Quận Ba Đình, Hà Nội</div>
                                    <div>Email: nfo@salonzo.com</div>
                                </div>
                                <div class="line">
                                    <div>SĐT: 0243.5190242 / 0243.5190244</div>
                                    <div>Fax: +84  4 3519 0217</div>
                                </div>
                                <div class="map-link"><a href="#" target="_blank">Xem bản đồ chỉ dẫn</a></div>
                            </div>
                            <div class="contact-item">
                                <div class="line name">
                                    Chi nhánh Đà Nẵng
                                </div>
                                <div class="line">
                                    <div>Địa chỉ: Tầng 3, D2 Giảng Võ, Phường Giảng Võ, Quận Ba Đình, Hà Nội</div>
                                    <div>Email: nfo@salonzo.com</div>
                                </div>
                                <div class="line">
                                    <div>SĐT: 0243.5190242 / 0243.5190244</div>
                                    <div>Fax: +84  4 3519 0217</div>
                                </div>
                                <div class="map-link"><a href="#" target="_blank">Xem bản đồ chỉ dẫn</a></div>
                            </div>
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
            $('#form-contact').submit(function () {
                var data = $(this).serializeObject();
                var form = $(this);
                $.ajax({
                    url: '{!! route('frontend.contact.store') !!}',
                    data: data,
                    type: 'post',
                    dataType: 'json',
                    beforeSend: function () {

                    },
                    success: function () {

                    },
                    complete: function () {

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