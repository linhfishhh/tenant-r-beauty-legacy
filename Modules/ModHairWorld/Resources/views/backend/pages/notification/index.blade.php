@enqueueJS('exceljs', getThemeAssetUrl('libs/excel/xlsx.full.min.js'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.blockui.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.moment.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.sweet_alert.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.pnotify.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@extends('layouts.backend')
@section('page_title',__('Push notification'))
@section('page_header_title')
    <strong>{{__('Push notification')}}</strong>
@endsection
@section('sidebar_second')
    @event(new \App\Events\BeforeHtmlBlock('sidebar.groups'))
    <div class="sidebar-category" data-group-id="menu-contact">
        <div class="sidebar-category">
            <div class="category-title">
                <span>{{__('HÀNH ĐỘNG')}}</span>
                <ul class="icons-list">
                    <li><a href="#" data-action="collapse"></a></li>
                </ul>
            </div>

            <div class="category-content text-center">
                <div class="mb-10">
                    <button data-loading-text="<i class='icon-spinner4 spinner position-left'></i> {{__('ĐANG XỬ LÝ...')}}"
                            id="{{jSID('send')}}" type="button" class="btn bg-primary btn-block btn-send">
                        {{__('GỬI THÔNG BÁO')}}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @event(new \App\Events\AfterHtmlBlock('sidebar.groups'))
@endsection
@section('page_content_body')
    <form id="form-send-notify">
        <fieldset class="mb-3">
            <legend class="text-uppercase text-bold">ĐỐI TƯỢNG</legend>
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="control-label col-lg-3">Nhóm thiết bị</label>
                    <div class="col-lg-9">
                        <select class="form-control handled" name="target">
                            <option value="web">Người dùng web browser</option>
                            <option value="booking">Người dùng app booking</option>
                            <option value="manager">Người dùng app manager</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3">Nhóm địa phương</label>
                    <div class="col-lg-9">
                        <select class="form-control handled" multiple name="location[]">
                            @foreach(\Modules\ModHairWorld\Entities\DiaPhuongTinhThanhPho::all(['id', 'name']) as $dp)
                                <option value="{!! $dp['id'] !!}">{!! $dp['name'] !!}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </fieldset>
        <fieldset class="mb-3">
            <legend class="text-uppercase text-bold">NỘI DUNG</legend>
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="control-label col-lg-3">Tiêu đề thông báo</label>
                    <div class="col-lg-9">
                        <input class="form-control" type="text" spellcheck="false" autocomplete="off" autocapitalize="none" name="title" value="isalon.vn">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3">Nội dung thông báo</label>
                    <div class="col-lg-9">
                        <textarea name="content" class="form-control" rows="3">Hi, my good man!</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3">Loại link</label>
                    <div class="col-lg-9">
                        <div class="link-type link_type_web">
                            <select class="form-control link-type handled" name="link_type_web">
                                <option value="web-link">Địa chỉ web</option>
                            </select>
                        </div>
                        <div class="link-type link_type_booking">
                            <select class="form-control handled" name="link_type_booking">
                                <option value="web-link">Địa chỉ web</option>
                                <option value="home">App booking - Màn hình home</option>
                                <option value="history">App booking - Màn hình Đặt chỗ sẽ làm</option>
                                <option value="salon">App booking - Màn hình chi tiết salon</option>
                                <option value="salon-list">App booking - Màn hình danh sách salon</option>
                            </select>
                        </div>
                        <div class="link-type link_type_manager">
                            <select class="form-control link-type handled" name="link_type_manager">
                                <option value="web-link">Địa chỉ web</option>
                                <option value="news">App manager - Màn hình tin tức</option>
                                <option value="rating">App manager - Màn hình xếp hạng đánh giá</option>
                                <option value="month-income">App manager - Màn hình thu nhập tháng</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group web-link-url link-value">
                    <label class="control-label col-lg-3">Link đối tượng - web link</label>
                    <div class="col-lg-9">
                        <input name="web-link-url" class="form-control" value="https://isalon.vn">
                    </div>
                </div>

                <div class="form-group booking-link-url link-value">
                    <label class="control-label col-lg-3">Link đối tượng - web link</label>
                    <div class="col-lg-9">
                        <input name="booking-link-url" class="form-control" value="https://isalon.vn">
                    </div>
                </div>

                <div class="form-group booking-list-query link-value">
                    <label class="control-label col-lg-3">Link đối tượng - query link</label>
                    <div class="col-lg-9">
                        <input name="booking-list-query" class="form-control" value="" placeholder="Link copy từ trang tìm kiếm">
                    </div>
                </div>

                <div class="form-group booking-salon-id link-value">
                    <label class="control-label col-lg-3">Link đối tượng - salon</label>
                    <div class="col-lg-9">
                        <select name="booking-salon-id" class="form-control handled"></select>
                    </div>
                </div>

                <div class="form-group booking-list-url link-value">
                    <label class="control-label col-lg-3">Link đối tượng - web link</label>
                    <div class="col-lg-9">
                        <input name="booking-list-url" class="form-control" value="https://isalon.vn">
                    </div>
                </div>

                <div class="form-group manager-link-url link-value">
                    <label class="control-label col-lg-3">Link đối tượng - web link</label>
                    <div class="col-lg-9">
                        <input name="manager-link-url" class="form-control" value="https://isalon.vn">
                    </div>
                </div>

                <div class="form-group link-value manager-news-id link-value">
                    @component('backend.components.field', [
                             'field' => new \App\Classes\FieldInput\FieldInputPost(
                             'manager-news-id',
                             [0],
                            'Link đối tượng - tin tức',
                             '',
                             true,
                             \App\Classes\FieldInput\FieldInputPost::buildConfigs(false, false, \Modules\ModHairWorld\Entities\PostTypes\MobileNews::class)
                             ),
                             'horizontal' => true,
                         ])
                    @endcomponent
                </div>

            </div>
        </fieldset>
        <fieldset class="mb-3">
            <legend class="text-uppercase text-bold">GIỚI HẠN GỬI (DÙNG ĐỂ TEST)</legend>
            @component('backend.components.field', [
                             'field' => new \App\Classes\FieldInput\FieldInputUser(
                             'limit_user_id',
                             [0],
                            'Chỉ gửi đến tài khoản này',
                             '',
                             true,
                             \App\Classes\FieldInput\FieldInputUser::buildConfigs(false)
                             ),
                             'horizontal' => true,
                         ])
            @endcomponent
        </fieldset>
    </form>
@endsection
@push('page_footer_js')
    <script type="text/javascript">
        $(function () {
            var options = {
              link_type: '',
              link_value: '',
                link_value_el: null
            };

           $('#form-send-notify select.handled').select2({
                minimumResultsForSearch: Infinity,
                placeholder: 'Tất cả'
            });

            $('#form-send-notify .link-type select[name=link_type_web]').change(function () {
                $('#form-send-notify .link-value').hide();
                var value = $(this).val();
                options.link_type = value;
                options.link_value_el = null;
                switch (value) {
                    case 'web-link':
                        $('#form-send-notify .web-link-url').show();
                        options.link_value_el = $('#form-send-notify input[name=web-link-url]');
                        break;
                }
            });

            $('#form-send-notify .link-type select[name=link_type_booking]').change(function () {
                $('#form-send-notify .link-value').hide();
                var value = $(this).val();
                options.link_type = value;
                options.link_value_el = null;
                switch (value) {
                    case 'web-link':
                        $('#form-send-notify .booking-link-url').show();
                        options.link_value_el = $('#form-send-notify input[name=booking-link-url]');
                        break;
                    case 'salon':
                        $('#form-send-notify .booking-salon-id').show();
                        options.link_value_el = $('#form-send-notify select[name=booking-salon-id]');
                        break;
                    case 'salon-list':
                        $('#form-send-notify .booking-list-query').show();
                        options.link_value_el = $('#form-send-notify input[name=booking-list-query]');
                        break;
                }
            });

            $('#form-send-notify .link-type select[name=link_type_manager]').change(function () {
                $('#form-send-notify .link-value').hide();
                var value = $(this).val();
                options.link_type = value;
                options.link_value_el = null;
                switch (value) {
                    case 'web-link':
                        $('#form-send-notify .manager-link-url').show();
                        options.link_value_el = $('#form-send-notify input[name=manager-link-url]');
                        break;
                    case 'news':
                        $('#form-send-notify .manager-news-id').show();
                        options.link_value_el = $('#form-send-notify select[name=manager-news-id\\[posts\\]]');
                        break;
                }
            });

            $('#form-send-notify select[name=target]').change(function () {
                $('#form-send-notify .link-type').hide();
                var value = $(this).val();
                options = {
                    link_type: '',
                    link_value: '',
                    link_value_el: null
                };
                switch (value) {
                    case 'web':
                        $('#form-send-notify .link-type.link_type_web').show();
                        $('#form-send-notify .link-type.link_type_web select').trigger('change');
                        break;
                    case 'booking':
                        $('#form-send-notify .link-type.link_type_booking').show();
                        $('#form-send-notify .link-type.link_type_booking select').trigger('change');
                        break;
                    case 'manager':
                        $('#form-send-notify .link-type.link_type_manager').show();
                        $('#form-send-notify .link-type.link_type_manager select').trigger('change');
                        break;
                }
            }).trigger('change');

            $('#form-send-notify select[name=booking-salon-id]').select2({
                width: '100%',
                placeholder: '{{__('Chọn salon')}}',
                id: function (item) {
                    return item.id;
                },
                escapeMarkup: function (markup) {
                    return markup;
                },
                templateResult: function(repo) {
                    if (repo.loading) {
                        return repo.text;
                    }

                    var markup = '<div class="text-semibold">'+repo.text + '</div>' +
                        '<div class="text-muted">' + repo.address + '</div>'
                    ;

                    return markup;
                },
                templateSelection: function(repo) {
                    return repo.full_name || repo.text;
                },
                ajax: {
                    url: '{!! route('backend.promo_salons.salons') !!}',
                    dataType: 'json',
                    data: function (params) {
                        var rs = {
                            search: params.term,
                        };
                        return rs;
                    },
                    processResults: function (data) {
                        var items = [];
                        $.each(data, function (i, v) {
                            items.push(
                                {
                                    id: v.id,
                                    text: v.name,
                                    address: v.address_cache
                                }
                            );
                        });
                        return {
                            results: items
                        };
                    }
                },
                minimumInputLength: 2,
            });
            $('#form-send-notify select[name=booking-news-id]').select2({
                width: '100%',
                placeholder: '{{__('Chọn tin tức')}}',
                id: function (item) {
                    return item.id;
                },
                escapeMarkup: function (markup) {
                    return markup;
                },
                templateResult: function(repo) {
                    if (repo.loading) {
                        return repo.text;
                    }

                    var markup = '<div class="text-semibold">'+repo.text + '</div>' +
                        '<div class="text-muted">' + repo.address + '</div>'
                    ;

                    return markup;
                },
                templateSelection: function(repo) {
                    return repo.full_name || repo.text;
                },
                ajax: {
                    url: '{!! route('backend.promo_salons.salons') !!}',
                    dataType: 'json',
                    data: function (params) {
                        var rs = {
                            search: params.term,
                        };
                        return rs;
                    },
                    processResults: function (data) {
                        var items = [];
                        $.each(data, function (i, v) {
                            items.push(
                                {
                                    id: v.id,
                                    text: v.name,
                                    address: v.address_cache
                                }
                            );
                        });
                        return {
                            results: items
                        };
                    }
                },
                minimumInputLength: 2,
            });
            $('#form-send-notify select[name=manager-news-id]').select2({
                width: '100%',
                placeholder: '{{__('Chọn tin tức')}}',
                id: function (item) {
                    return item.id;
                },
                escapeMarkup: function (markup) {
                    return markup;
                },
                templateResult: function(repo) {
                    if (repo.loading) {
                        return repo.text;
                    }

                    var markup = '<div class="text-semibold">'+repo.text + '</div>' +
                        '<div class="text-muted">' + repo.address + '</div>'
                    ;

                    return markup;
                },
                templateSelection: function(repo) {
                    return repo.full_name || repo.text;
                },
                ajax: {
                    url: '{!! route('backend.promo_salons.salons') !!}',
                    dataType: 'json',
                    data: function (params) {
                        var rs = {
                            search: params.term,
                        };
                        return rs;
                    },
                    processResults: function (data) {
                        var items = [];
                        $.each(data, function (i, v) {
                            items.push(
                                {
                                    id: v.id,
                                    text: v.name,
                                    address: v.address_cache
                                }
                            );
                        });
                        return {
                            results: items
                        };
                    }
                },
                minimumInputLength: 2,
            });

            $('#form-send-notify select[name=manager-news-id]').select2({
                width: '100%',
                placeholder: '{{__('Chọn tin tức')}}',
                id: function (item) {
                    return item.id;
                },
                escapeMarkup: function (markup) {
                    return markup;
                },
                templateResult: function(repo) {
                    if (repo.loading) {
                        return repo.text;
                    }

                    var markup = '<div class="text-semibold">'+repo.text + '</div>' +
                        '<div class="text-muted">' + repo.address + '</div>'
                    ;

                    return markup;
                },
                templateSelection: function(repo) {
                    return repo.full_name || repo.text;
                },
                ajax: {
                    url: '{!! route('backend.promo_salons.salons') !!}',
                    dataType: 'json',
                    data: function (params) {
                        var rs = {
                            search: params.term,
                        };
                        return rs;
                    },
                    processResults: function (data) {
                        var items = [];
                        $.each(data, function (i, v) {
                            items.push(
                                {
                                    id: v.id,
                                    text: v.name,
                                    address: v.address_cache
                                }
                            );
                        });
                        return {
                            results: items
                        };
                    }
                },
                minimumInputLength: 2,
            });

           function send(form, btn){
               var data = $(form).serializeObject();
               data.link_type = options.link_type;
               data.link_value = null;
               if(options.link_value_el){
                   data.link_value = options.link_value_el.val();
               }
               $.ajax({
                   url: '{!! route('backend.notification.send') !!}',
                   method: 'post',
                   data: data,
                   beforeSend: function () {
                       cleanErrorMessage(form);
                       $(btn).button('loading');
                       $('.btn-send').not(btn).prop('disabled', true);
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
                   success: function(rs){
                       console.log(rs);
                       new PNotify({
                           title: '{{'GỬI THÀNH CÔNG'}}',
                           text: '{{__('Yêu cầu gửi thông báo đã được gửi đến nhà cung cấp dịch vụ Onesignal thành công!')}}',
                           addclass: 'bg-success stack-bottom-right',
                           stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25},
                           buttons: {
                               sticker: false
                           },
                           delay: 10000
                       });
                   },
                   complete: function () {
                       $(btn).button('reset');
                       $('.btn-send').not(btn).prop('disabled', false);
                   }
               });
           };
           $("#{{jSID('send')}}").click(function () {
               var btn = $(this);
               var form = $('#form-send-notify');
               send(form, btn);
           });
            $('#form-send-notify').submit(function () {
                return false;
            });
        });
    </script>
@endpush