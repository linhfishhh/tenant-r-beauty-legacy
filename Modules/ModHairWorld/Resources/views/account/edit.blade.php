@enqueueCSS('select2', getThemeAssetUrl('libs/select2/css/select2.min.css'), 'bootstrap')
@enqueueJS('select2', getThemeAssetUrl('libs/select2/js/select2.full.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueCSS('cropper', getThemeAssetUrl('libs/cropper/cropper.min.css'), 'bootstrap')
@enqueueJS('cropper', getThemeAssetUrl('libs/cropper/cropper.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueCSS('datepicker', getThemeAssetUrl('libs/datepicker/css/bootstrap-datepicker.standalone.min.css'), 'bootstrap')
@enqueueJS('datepicker', getThemeAssetUrl('libs/datepicker/js/bootstrap-datepicker.min.js'), JS_LOCATION_HEAD, 'bootstrap')
@enqueueJS('datepicker-language', getThemeAssetUrl('libs/datepicker/js/locales/bootstrap-datepicker.vi.min.js'), JS_LOCATION_HEAD, 'datepicker')
@enqueueCSS('edit-profile-page', getThemeAssetUrl('libs/styles/edit-profile.css'), 'account-menu')
@extends(getThemeViewName('account.master'))
@section('content')
    @php
    $avatar = me()->avatar?me()->avatar->getThumbnailUrl('medium_sq', false):false;
    /** @var \Modules\ModHairWorld\Entities\UserExtra $info */
    @endphp
    <div class="content-box box-avatar">
        <div class="content-title">Ảnh đại diện</div>
        <div class="content-body">
            <div class="row">
                <div class="col-5 col-md-3">
                    <div class="avatar" id="profile-avatar">
                        <img class="img" src="{!! $avatar?$avatar:getNoAvatarUrl() !!}">
                        <img class="mask" src="{!! getThemeAssetUrl('img/amask.png') !!}">
                        <div class="remove d-none"><i class="fa fa-minus"></i></div>
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
                        <button id="btn-upload-avatar">Tải ảnh từ máy tính</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-box box-info">
        <div class="content-title">Thông tin cá nhân</div>
        <div class="content-body">
            <form id="form-basic-info">
                <div class="field">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="lbl">
                                Họ tên
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="input">
                                <input value="{!! me()->name?me()->name:'' !!}" name="name" spellcheck="false" autocomplete="off" placeholder="Họ tên">
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
                                <select name="gender">
                                    <option {!! $info->gender==0?'selected="selected"':'' !!} value="0">Nữ</option>
                                    <option {!! $info->gender==1?'selected="selected"':'' !!}  value="1">Nam</option>
                                    <option {!! $info->gender==2?'selected="selected"':'' !!}  value="2">Khác</option>
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
                                <input value="{!! $info->birthday?$info->birthday->format('d/m/Y'):'' !!}" name="birthday" spellcheck="false" autocomplete="off" placeholder="Ngày sinh">
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
                                <input value="{!! me()->email !!}" name="email" spellcheck="false" autocomplete="off" placeholder="Email liên hệ">
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
                                <input name="phone" value="{!! me()->phone !!}" spellcheck="false" autocomplete="off" placeholder="Số điện thoại cá nhân">
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
                    <div class="addresses" id="my-address-list">
                        @php
                        /** @var \Modules\ModHairWorld\Entities\UserAddress[] $addresses */
                        @endphp
                        @foreach($addresses as $address)
                            <div class="address" data-id="{!! $address->id !!}">
                                <div class="info">
                                    {!! $address->getAddressLine() !!}
                                </div>
                                <div class="edit">
                                    <a href="#" class="change">sửa</a>
                                    <a href="#" class="remove">Xóa</a>
                                </div>
                                <div class="d-none">
                                    <div class="data-id">{!! $address->id !!}</div>
                                    <div class="data-road">{!! $address->address !!}</div>
                                    <div class="data-lv1">{!! $address->address_lv1 !!}</div>
                                    <div class="data-lv2">{!! $address->address_lv2 !!}</div>
                                    <div class="data-lv3">{!! $address->address_lv3 !!}</div>
                                    <div class="data-lv1-text">{!! $address->lv1?$address->lv1->name:'' !!}</div>
                                    <div class="data-lv2-text">{!! $address->lv2?$address->lv2->name:'' !!}</div>
                                    <div class="data-lv3-text">{!! $address->lv3?$address->lv3->name:'' !!}</div>
                                </div>
                            </div>
                        @endforeach
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
    <template id="address-template">
        <div class="address" data-id="{id}">
            <div class="info">
                {text}
            </div>
            <div class="edit">
                <a href="#" class="change">sửa</a>
                <a href="#" class="remove">Xóa</a>
            </div>
            <div class="d-none">
                <div class="data-id">{id}</div>
                <div class="data-road">{address}</div>
                <div class="data-lv1">{lv1}</div>
                <div class="data-lv2">{lv2}</div>
                <div class="data-lv3">{lv3}</div>
                <div class="data-lv1-text">{lv1-text}</div>
                <div class="data-lv2-text">{lv2-text}</div>
                <div class="data-lv3-text">{lv3-text}</div>
            </div>
        </div>
    </template>
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
                                <a class="phone">0956589456</a>
                            </div>
                            <div class="check">Vui lòng kiểm tra tin nhắn trên điện thoại và nhập 6 chữ số được cung cấp trong tin nhắn để xác nhận số điện thoại</div>
                            <form class="block-form">
                                <div class="field">
                                    <input name="code" type="text" spellcheck="false" autocomplete="off" placeholder="Nhập mã số gồm 6 chữ số">
                                </div>
                                <button class="block-button submit">
                                    <span>Xác nhận</span>
                                </button>
                            </form>
                        </div>
                        <div class="block-note">
                            <span>Chưa nhận được tin nhắn? <a class="refresh-verify-code" href="#">Gửi xác minh lại</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-register-step" role="dialog" id="modal-account-address">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="dismiss-modal">
                        <div data-dismiss="modal" class="btn-close"><i class="fa fa-close"></i></div>
                    </div>
                    <div class="size-wrapper">
                        <div class="block-title">Địa chỉ liên hệ</div>
                        <div class="block-content">
                            <form class="block-form" id="modal-address-edit">
                                <div class="field">
                                    <input name="address" type="text" spellcheck="false" autocomplete="off" placeholder="Số nhà, ngỏ, đường...">
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
    <div class="modal fade" id="modal-cropper" tabindex="-1" role="dialog" aria-labelledby="modal-cropper" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Cắt ảnh đại diện</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <img id="upload-avatar" src="https://avatars0.githubusercontent.com/u/3456749">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Bỏ</button>
                    <button type="button" class="btn btn-primary" id="btn-crop-finish">Crop</button>
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

            $('#form-basic-info').submit(function () {
                var data = $(this).serializeObject();
                var form = $(this);
                $.ajax({
                    url: '{!! route('frontend.account.edit.basic_info.save') !!}',
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    beforeSend: function () {
                        form.addClass('loading');
                    },
                    complete: function () {
                        form.removeClass('loading');
                    },
                    success: function (json) {
                        cleanErrorMessage(form);
                    },
                    error: function (json) {
                        handleErrorMessage(form, json);
                    }
                });
                return false;
            });

            var $modal_cropper = $('#modal-cropper').modal({
                show: false,
                backdrop: 'static'
            });

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
               var form = $(this);
               var data = $(this).serializeObject();
                //$modal_change_phone.modal('show');
               $.ajax({
                   url: '{!! route('frontend.account.phone.check') !!}',
                   type: 'post',
                   dataType: 'json',
                   data: data,
                   beforeSend: function () {
                       form.addClass('loading');
                   },
                   complete: function () {
                       form.removeClass('loading');
                   },
                   success: function (json) {
                       console.log(json);
                       cleanErrorMessage(form);
                       $modal_change_phone.find('.phone').html(data.phone);
                       $modal_change_phone.find('form').data('data', data);
                       $modal_change_phone.modal('show');
                   },
                   error: function (json) {
                       handleErrorMessage(form, json);
                   }
               });
               return false;
           });
            $('#modal-account-change-phone form').submit(function () {
                //$modal_change_phone.modal('hide');
                var data = $(this).serializeObject();
                var form = $(this);
                var send_data = form.data('data');
                send_data.code = data.code;
                $.ajax({
                    url: '{!! route('frontend.account.phone.save') !!}',
                    type: 'post',
                    dataType: 'json',
                    data: send_data,
                    beforeSend: function () {
                        $modal_change_phone.addClass('loading');
                    },
                    complete: function () {
                        $modal_change_phone.removeClass('loading');
                    },
                    success: function (json) {
                        cleanErrorMessage(form);
                        $modal_change_phone.modal('hide');
                        swal({
                            title: "{!! __('Đổi số điện thoại') !!}",
                            text: "{!! __('Số điện thoại mới đã được cập nhật thành công!') !!}",
                            type: "success",
                            confirmButtonColor: "#EF5350",
                            confirmButtonText: "{{__('Đã hiểu')}}",
                            closeOnConfirm: true,
                        });
                    },
                    error: function (json) {
                        if(json.status === 400){
                            handleErrorMessage(form, {
                                responseJSON: {
                                    errors: {
                                        code: [
                                            json.responseJSON.message
                                        ]
                                    }
                                }
                            });
                        }
                        else{
                            handleErrorMessage(form, json);
                        }
                    }
                });
                return false;
            });

            $modal_address.find('form').submit(function () {
                var data = $(this).serializeObject();
                var form = $(this);
                var id =  $modal_address.data('id');
                data['id'] = id;
                $.ajax({
                    url: '{!! route('frontend.account.edit.addresses.save') !!}',
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    beforeSend: function () {
                        $modal_address.addClass('loading');
                    },
                    complete: function () {
                        $modal_address.removeClass('loading');
                    },
                    success: function (json) {
                        cleanErrorMessage(form);
                        $modal_address.modal('hide');
                        var tpl = $('#address-template').html();
                        tpl = tpl.replace(/{text}/g, json.text);
                        tpl = tpl.replace(/{id}/g, json.id);
                        tpl = tpl.replace(/{address}/g, json.address);
                        tpl = tpl.replace(/{lv1}/g, json.address_lv1);
                        tpl = tpl.replace(/{lv2}/g, json.address_lv2);
                        tpl = tpl.replace(/{lv3}/g, json.address_lv3);
                        tpl = tpl.replace(/{lv1-text}/g, json.lv1_text);
                        tpl = tpl.replace(/{lv2-text}/g, json.lv2_text);
                        tpl = tpl.replace(/{lv3-text}/g, json.lv3_text);
                        if(id){
                            $('#my-address-list .address[data-id='+id+']').replaceWith(tpl);
                        }
                        else{
                            $('#my-address-list').append(tpl);
                        }
                        console.log(json);
                    },
                    error: function (json) {
                        handleErrorMessage(form, json);
                    }
                });
                return false;
            });

            function addEditAddress(data){
                $modal_address.find('input[name=address]').val('');
                $modal_address.find('select[name=tinh_thanh_pho_id]').val(null).trigger('change');
                $modal_address.find('select[name=quan_huyen_id]').val(null).trigger('change');
                $modal_address.find('select[name=phuong_xa_thi_tran_id]').val(null).trigger('change');
                $modal_address.data('id', null);
                if(data){
                    $modal_address.data('id', data.id);
                    $modal_address.find('input[name=address]').val(data.address);
                    var op;
                    op = new Option(data.lv1_text, data.lv1, false, true);
                    $modal_address.find('select[name=tinh_thanh_pho_id]').append(op).trigger('change');
                    op = new Option(data.lv2_text, data.lv2, false, true);
                    $modal_address.find('select[name=quan_huyen_id]').append(op).trigger('change');
                    op = new Option(data.lv3_text, data.lv3, false, true);
                    $modal_address.find('select[name=phuong_xa_thi_tran_id]').append(op).trigger('change');
                }
                $modal_address.modal('show');
            }

            $('body').on('click', '.box-address .address a.remove', function () {
                var address = $(this).parents('.address');
                var id  = address.data('id');
                swal({
                        title: "Xóa địa chỉ",
                        text: "Bạn thật sự muốn xóa địa chỉ này?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        cancelButtonText: "Hủy",
                        confirmButtonText: "Xóa",
                        closeOnConfirm: true
                    },
                    function(){
                        var url = '{!! route('frontend.account.edit.addresses.delete', ['address'=>'???']) !!}';
                        url = url.replace('???', id);
                        $.ajax({
                            url: url,
                            type: 'post',
                            dataType: 'json',
                            data: {
                                _method: 'delete'
                            },
                            beforeSend: function () {
                                $('.box-address').addClass('loading');
                            },
                            complete: function () {
                                $('.box-address').removeClass('loading');
                            },
                            success: function (json) {
                                if(json){
                                    address.remove();
                                }
                            }
                        });
                    });
                    return false;
            });

            $('.box-address .add-address').click(function () {
                addEditAddress(null);
                return false;
            });

            $('body').on('click', '.box-address .address a.change', function () {
                var data = {
                    id: $(this).parents('.address').find('.data-id').html(),
                    address: $(this).parents('.address').find('.data-road').html(),
                    lv1: $(this).parents('.address').find('.data-lv1').html(),
                    lv2: $(this).parents('.address').find('.data-lv2').html(),
                    lv3: $(this).parents('.address').find('.data-lv3').html(),
                    lv1_text: $(this).parents('.address').find('.data-lv1-text').html(),
                    lv2_text: $(this).parents('.address').find('.data-lv2-text').html(),
                    lv3_text: $(this).parents('.address').find('.data-lv3-text').html(),
                };
                addEditAddress(data);
                return false;
            });
            $('.box-avatar .upload button').click(function () {
                $('.box-avatar .upload input[type=file]').click();
            });

            var avatar = $('.box-avatar .avatar img')[0];
            var image = $('#upload-avatar')[0];
            var input = $('.box-avatar .upload input[type=file]')[0];
            var cropper;
            var processing_file;
            $('.box-avatar .upload input[type=file]').change(function (e) {

                var files = e.target.files;
                var size = files[0].size/1024.0/1024.0;
                processing_file = files[0];

                if(size>1){
                    swal("Tải ảnh", "Ảnh đại diện phải nhỏ hơn 1MB", "danger")
                    return;
                }

                var done = function (url) {
                    input.value = '';
                    image.src = url;
                    $modal_cropper.modal('show');
                };
                var reader;
                var file;
                var url;

                if (files && files.length > 0) {
                    file = files[0];

                    if (URL) {
                        done(URL.createObjectURL(file));
                    } else if (FileReader) {
                        reader = new FileReader();
                        reader.onload = function (e) {
                            done(reader.result);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });

            $modal_cropper.on('shown.bs.modal', function () {
                cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 3,
                });
            }).on('hidden.bs.modal', function () {
                cropper.destroy();
                cropper = null;
            });

            $('#btn-crop-finish').click(function () {
                var initialAvatarURL;
                var canvas;

                if (cropper) {
                    canvas = cropper.getCroppedCanvas({
                        width: 160,
                        height: 160,
                    });
                    initialAvatarURL = avatar.src;
                    canvas.toBlob(function (blob) {
                        var formData = new FormData();
                        formData.append('avatar', blob);
                        formData.append('filename', processing_file.name);
                        $.ajax({
                            url: '{!! route('frontend.account.edit.avatar.save') !!}',
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            beforeSend: function(){
                                $modal_cropper.addClass('loading');
                            },
                            success: function (rs) {
                                avatar.src = canvas.toDataURL();
                                $modal_cropper.modal('hide');
                            },
                            complete: function () {
                                $modal_cropper.removeClass('loading');
                            }
                        });
                    });
                }
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