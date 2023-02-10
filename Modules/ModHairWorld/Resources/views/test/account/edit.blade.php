@enqueueCSS('select2', getThemeAssetUrl('libs/select2/css/select2.min.css'), 'bootstrap')
@enqueueJS('select2', getThemeAssetUrl('libs/select2/js/select2.full.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueCSS('datepicker', getThemeAssetUrl('libs/datepicker/css/bootstrap-datepicker.standalone.min.css'), 'bootstrap')
@enqueueJS('datepicker', getThemeAssetUrl('libs/datepicker/js/bootstrap-datepicker.min.js'), JS_LOCATION_HEAD, 'bootstrap')
@enqueueJS('datepicker-language', getThemeAssetUrl('libs/datepicker/js/locales/bootstrap-datepicker.vi.min.js'), JS_LOCATION_HEAD, 'datepicker')
@enqueueCSS('edit-profile-page', getThemeAssetUrl('libs/styles/edit-profile.css'), 'account-menu')
@extends(getThemeViewName('test.account.master'))
@section('content')
    <div class="content-box box-avatar">
        <div class="content-title">Ảnh đại diện</div>
        <div class="content-body">
            <div class="row">
                <div class="col-5 col-md-3">
                    <div class="avatar">
                        <img class="img" src="{!! getThemeAssetUrl('img/avatar_profile2.png') !!}">
                        <img class="mask" src="{!! getThemeAssetUrl('img/amask.png') !!}">
                        <div class="remove"><i class="fa fa-minus"></i></div>
                    </div>
                </div>
                <div class="col-7 col-md-9">
                    <div class="message">
                        Ảnh mặt mặt trước là một cách quan trọng để chủ salon và khách hàng tìm hiểu về nhau.
                        Nó không phải là nhiều niềm vui để lưu trữ một phong cảnh! Hãy chắc chắn sử dụng một bức
                        ảnh thể hiện rõ khuôn mặt của bạn và không bao gồm bất kỳ thông tin cá nhân hoặc nhạy
                        cảm nào mà bạn không muốn có bạn bè xem.
                    </div>
                    <div class="upload">
                        <input type="file" style="display: none" accept="image/*">
                        <button>Tải ảnh từ máy tính</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-box box-info">
        <div class="content-title">Thông tin cá nhân</div>
        <div class="content-body">
            <form>
                <div class="field">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="lbl">
                                Họ tên
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="input">
                                <input spellcheck="false" autocomplete="off" placeholder="Họ tên">
                            </div>
                            <div class="note">
                                Tiểu sử công khai của bạn chỉ hiển thị tên của bạn. Khi bạn bình luận hay chia sẻ, chủ salon của
                                bạn sẽ thấy tên và họ của bạn.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="lbl">
                                Giới tính <i class="fa fa-lock"></i>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="input">
                                <select>
                                    <option value="0">Nữ</option>
                                    <option value="1">Nam</option>
                                    <option value="2">Khác</option>
                                </select>
                            </div>
                            <div class="note">
                                Chúng tôi sử dụng dữ liệu này để phân tích và không bao giờ chia sẻ nó với người
                                dùng khác.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="lbl">
                                Ngày sinh
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="input">
                                <input name="birthday" spellcheck="false" autocomplete="off" placeholder="Ngày sinh">
                            </div>
                            <div class="note">
                                Chúng tôi sẽ không chia sẻ ngày sinh của bạn với những người
                                dùng hệ thống khác.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="lbl">
                                Email <i class="fa fa-lock"></i>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="input">
                                <input spellcheck="false" autocomplete="off" placeholder="Email liên hệ">
                            </div>
                            <div class="note">
                                Chúng tôi sẽ không chia sẻ địa chỉ email cá nhân của bạn với những người
                                dùng hệ thống khác.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="submit">
                    <button>Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
    <div class="content-box box-info box-phone">
        <div class="content-title">Số điện thoại</div>
        <div class="content-body">
            <form>
                <div class="field">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="lbl">
                                Số điện thoại <i class="fa fa-lock"></i>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="input">
                                <input spellcheck="false" autocomplete="off" placeholder="Số điện thoại cá nhân">
                            </div>
                            <div class="note">
                                Chúng tôi sẽ không chia sẻ số điện thoại cá nhân của bạn với những người
                                dùng hệ thống khác. Mỗi lần thay đổi số điện thoại bạn cần phải xác minh số điện thoại mới.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="submit">
                    <button>Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
    <div class="content-box box-address">
        <div class="content-title">Địa chỉ liên hệ</div>
        <div class="content-body">
            <div class="row">
                <div class="col-lg-3">

                </div>
                <div class="col-lg-8">
                    <div class="addresses">
                        <div class="address">
                            <div class="info">
                                48A, Lý Tự Trong, Phường Xuân Khánh, Quận Ninh Kiều, Cần Thơ.
                            </div>
                            <div class="edit">
                                <a href="#" class="change">sửa</a>
                                <a href="#" class="remove">Xóa</a>
                            </div>
                        </div>
                        <div class="address">
                            <div class="info">
                                48A, Lý Tự Trong, Phường Xuân Khánh, Quận Ninh Kiều, Cần Thơ.
                            </div>
                            <div class="edit">
                                <a href="#" class="change">sửa</a>
                                <a href="#" class="remove">Xóa</a>
                            </div>
                        </div>
                        <div class="address">
                            <div class="info">
                                48A, Lý Tự Trong, Phường Xuân Khánh, Quận Ninh Kiều, Cần Thơ.
                            </div>
                            <div class="edit">
                                <a href="#" class="change">sửa</a>
                                <a href="#" class="remove">Xóa</a>
                            </div>
                        </div>
                    </div>
                    <div class="add-address">
                        <i class="fa fa-plus"></i>
                        Thêm địa chỉ
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('page_footer_js')
    <div class="modal modal-register-step" tabindex="-1" role="dialog" id="modal-account-change-phone">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="dismiss-modal">
                        <div data-dismiss="modal" class="btn-close"><i class="fa fa-close"></i></div>
                    </div>
                    <div class="img-account-valid">
                        <img src="{!! getThemeAssetUrl('img/account_val.png') !!}">
                    </div>
                    <div class="size-wrapper">
                        <div class="block-title">Xác minh số điện thoại</div>
                        <div class="block-content">
                            <div class="send">
                                <span>Một tin nhắn đã gửi đến</span>
                                <a>0956589456</a>
                            </div>
                            <div class="check">Vui lòng kiểm tra tin nhắn trên điện thoại và nhập 6 chữ số được cung cấp trong tin nhắn để xác nhận số điện thoại</div>
                            <form class="block-form">
                                <div class="field">
                                    <input type="text" spellcheck="false" autocomplete="off" placeholder="Nhập mã số gồm chữ số">
                                </div>
                                <button class="block-button submit">
                                    <span>Xác nhận</span>
                                </button>
                            </form>
                        </div>
                        <div class="block-note">
                            <span>Chưa nhận được tin nhắn? <a href="#">Gửi xác minh lại</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-register-step" tabindex="-1" role="dialog" id="modal-account-address">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="dismiss-modal">
                        <div data-dismiss="modal" class="btn-close"><i class="fa fa-close"></i></div>
                    </div>
                    <div class="size-wrapper">
                        <div class="block-title">Địa chỉ liên hệ</div>
                        <div class="block-content">
                            <form class="block-form">
                                <div class="field">
                                    <input type="text" spellcheck="false" autocomplete="off" placeholder="Số nhà, ngỏ, đường...">
                                </div>
                                <div class="field">
                                    <select data-width="100%" name="tinh_thanh_pho_id" class="form-control"></select>                                </div>
                                <div class="field">
                                    <select data-width="100%" name="quan_huyen_id" class="form-control"></select>
                                </div>
                                <div class="field">
                                    <select data-width="100%" name="phuong_xa_thi_tran_id" class="form-control"></select>
                                </div>
                                <button class="block-button submit">
                                    <span>Lưu thông tin</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('.box-info select').select2({
            width: 100,
            minimumResultsForSearch: -1
        });
        $(function () {
            $('.box-info form input[name=birthday]').datepicker({
                language: 'vi',
                endDate: '0d'
            });

           var $modal_change_phone = $('#modal-account-change-phone').modal({
               show: false,
               backdrop: 'static'
           });
            var $modal_address = $('#modal-account-address').modal({
                show: false,
                backdrop: 'static'
            });
           $('.box-phone form').submit(function () {
                $modal_change_phone.modal('show');
               return false;
           });
            $('#modal-account-change-phone form').submit(function () {
                $modal_change_phone.modal('hide');
                return false;
            });
            $('.box-address .add-address').click(function () {
                $modal_address.modal('show');
                return false;
            });
            $('.box-address .address a.change').click(function () {
                $modal_address.modal('show');
                return false;
            });
            $('.box-avatar .upload button').click(function () {
                $('.box-avatar .upload input[type=file]').click();
            });
            $('select[name=phuong_xa_thi_tran_id]').select2({
                width: "100%",
                placeholder: '{!! __('Chọn phường/xã/thị trấn') !!}',
                ajax: {
                    url: '{!! route('info.phuong_xa.list') !!}',
                    dataType: 'json',
                    data: function (params) {
                        var v = $('select[name=quan_huyen_id]').val();
                        var query = {
                            search: params.term,
                            page: params.page || 1,
                            maqh: v
                        };
                        return query;
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        var items = [];
                        $.each(data.data, function () {
                            items.push({
                                id: this.id,
                                text: this.name
                            });
                        });
                        return {
                            results: items,
                            pagination: {
                                more: (params.page * 30) < data.total
                            }
                        };
                    }
                }
            }).on('change', function () {
                var v = $('select[name=quan_huyen_id]').val();
                if (!v){
                    $(this).prop("disabled", true)
                }
                else{
                    $(this).prop("disabled", false)
                }
            });

            $('select[name=quan_huyen_id]').select2({
                width: "100%",
                placeholder: '{!! __('Chọn quận/huyện') !!}',
                ajax: {
                    url: '{!! route('info.quan_huyen.list') !!}',
                    dataType: 'json',
                    data: function (params) {
                        var v = $('select[name=tinh_thanh_pho_id]').val();
                        var query = {
                            search: params.term,
                            page: params.page || 1,
                            matp: v
                        };
                        return query;
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        var items = [];
                        $.each(data.data, function () {
                            items.push({
                                id: this.id,
                                text: this.name
                            });
                        });
                        return {
                            results: items,
                            pagination: {
                                more: (params.page * 30) < data.total
                            }
                        };
                    }
                }
            }).on('change', function () {
                var v = $('select[name=tinh_thanh_pho_id]').val();
                if (!v){
                    $(this).prop("disabled", true)
                }
                else{
                    $(this).prop("disabled", false)
                }
                $('select[name=phuong_xa_thi_tran_id]').val(null).trigger('change');
            });

            $('select[name=tinh_thanh_pho_id]').select2({
                width: "100%",
                placeholder: '{!! __('Chọn tỉnh/thành phố') !!}',
                ajax: {
                    url: '{!! route('info.tinh_thanh_pho.list') !!}',
                    dataType: 'json',
                    data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }

                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        var items = [];
                        $.each(data.data, function () {
                            items.push({
                                id: ''+this.id,
                                text: this.name
                            });
                        });
                        return {
                            results: items,
                            pagination: {
                                more: (params.page * 30) < data.total
                            }
                        };
                    }
                }
            }).on('change', function () {
                $('select[name=quan_huyen_id]').val(null).trigger('change');
            }).trigger('change');
        });
    </script>
@endpush