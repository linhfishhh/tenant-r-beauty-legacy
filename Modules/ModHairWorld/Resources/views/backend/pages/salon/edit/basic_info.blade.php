@php
    /** @var \Modules\ModHairWorld\Entities\Salon $salon */
    /** @var \Modules\ModHairWorld\Entities\DiaPhuongTinhThanhPho $tinh */
    /** @var \Modules\ModHairWorld\Entities\DiaPhuongQuanHuyen $quan */
    /** @var \Modules\ModHairWorld\Entities\DiaPhuongXaPhuongThiTran $xa */
@endphp
@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.pnotify.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@extends('layouts.backend')
@section('page_title', $salon?$salon->name:__('Tạo salon mới'))
@section('page_header_title')
    <strong>{{$salon?$salon->name:__('Tạo salon mới')}}</strong>
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
                        id="{{jSID('submit')}}" type="button" class="btn bg-primary btn-block btn-save">
                    {{__('LƯU THAY ĐỔI')}}
                </button>
                <button data-loading-text="<i class='icon-spinner4 spinner position-left'></i> {{__('ĐANG XỬ LÝ...')}}"
                        id="{{jSID('submit_return')}}" type="button" class="btn bg-orange btn-block btn-save">
                    {{__('LƯU VÀ QUAY LẠI')}}
                </button>
                <a href="{!! route('backend.salon.index') !!}" data-loading-text="<i class='icon-spinner4 spinner position-left'></i> {{__('ĐANG XỬ LÝ...')}}"
                   class="btn bg-warning btn-block btn-back btn-save">
                    {{__('QUAY LẠI')}}
                </a>
                @event(new \App\Events\AfterHtmlBlock('sidebar.actions'))
            </div>
        </div>
    </div>
    @if($salon)
        @include('modhairworld::backend.pages.salon.edit.includes.sidebar_items', ['salon'=>$salon])
    @endif
@endsection
@section('page_content_body')
    @event(new \App\Events\BeforeHtmlBlock('content'))
    <form action="#" id="{{jSID('form')}}">
        <div class="panel panel-white">
            <div class="panel-heading">
                <h6 class="panel-title text-teal text-semibold">{!! __('THÔNG TIN CƠ BẢN') !!}</h6>
            </div>

            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label text-semibold">{!! __('Tên salon') !!}<span class="text-danger">*</span></label>
                    <input value="{!! $salon?$salon->name:'' !!}" class="form-control" type="text" spellcheck="false" name="name" placeholder="{!! __('Nhập tên salon của bạn') !!}">
                </div>
                @component('backend.components.field', [
                    'field' => $cover_field,
                    'horizontal' => 0
                ])
                @endcomponent
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label text-semibold">{!! __('Tình trạng chứng thực') !!}</label>
                            <select name="certified" class="form-control">
                                <option value="0">{!! __('Chưa chứng thực') !!}</option>
                                <option value="1">{!! __('Đã chứng thực') !!}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label text-semibold">{!! __('Trạng thái hoạt động') !!}</label>
                            <select name="open" class="form-control">
                                <option value="1">{!! __('Đang hoạt động') !!}</option>
                                <option value="0">{!! __('Tạm ngưng hoạt động') !!}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label text-semibold">{!! __('Địa chỉ') !!}<span class="text-danger">*</span></label>
                    <input value="{!! $salon?$salon->address:'' !!}" class="form-control" type="text" spellcheck="false" name="address" placeholder="{!! __('Nhập tên đường, khu vực') !!}">
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-semibold">{!! __('Tỉnh/thành phố') !!}<span class="text-danger">*</span></label>
                            <select name="tinh_thanh_pho_id" class="form-control"></select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-semibold">{!! __('Quận/Huyện') !!}<span class="text-danger">*</span></label>
                            <select name="quan_huyen_id" class="form-control"></select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-semibold">{!! __('Phường/xã/thị trấn') !!}<span class="text-danger">*</span></label>
                            <select name="phuong_xa_thi_tran_id" class="form-control"></select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label text-semibold">{!! __('Vị trí salon trên bản đồ')!!}</label>
                    <div class="mb-10"><button id="auto_pick_location" class="btn btn-sm btn-success full-width" type="button"><i class="position-left icon-move-down2"></i>{!! __('Chọn vị trí từ địa chỉ bên trên') !!}</button></div>
                    <div id="map" style="height: 450px"></div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-semibold">{!! __('Vĩ độ') !!}</label>
                            <input name="lat" readonly type="text" spellcheck="false" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-semibold">{!! __('Kinh độ') !!}</label>
                            <input name="lng" readonly type="text" spellcheck="false" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-semibold">{!! __('Zoom') !!}</label>
                            <input name="zoom" readonly type="text" spellcheck="false" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="alert alert-success text-center">
                    {!! __('Kéo thả điểm đánh dấu bản đồ (màu đỏ) đến vị trí chính xác của salon<br/>sử dụng tổ hợp phím CTRL và cuộn nút giữa chuột để zoom bảng đồ') !!}
                </div>
                @component('backend.components.field', [
                    'field' => $info_field,
                    'horizontal' => 0
                ])
                @endcomponent
                @component('backend.components.field', [
                  'field' => $training_info_field,
                  'horizontal' => 0
                ])
                @endcomponent

                @component('backend.components.field', [
                    'field' => $meta_keyword_field,
                    'horizontal' => 0
                ])
                @endcomponent
            </div>
        </div>
    </form>
    @event(new \App\Events\AfterHtmlBlock('content'))
@endsection
@push('page_footer_js')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDQWT3fahmtocJ9UKL4ChXkVzwKYGuNlj8&language=vi"></script>
    <script type="text/javascript">
        $(function () {
            $('#{{jSID('form')}} select[name=open], #{{jSID('form')}} select[name=certified]').select2({
                minimumResultsForSearch: Infinity,
            });
            $('#{{jSID('form')}} select[name=open]').val('{!! $salon?$salon->open:0 !!}').trigger('change');
            $('#{{jSID('form')}} select[name=certified]').val('{!! $salon?$salon->certified:0 !!}').trigger('change');
            function saveForm(form, btn, after_success){
                var data = $(form).serializeObject();
                @if($salon)
                    var url = '{!! route('backend.salon.basic_info.update', ['salon' => $salon]) !!}';
                    data._method = 'put';
                @else
                    var url = '{!! route('backend.salon.store') !!}';
                @endif
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    beforeSend: function () {
                        cleanErrorMessage(form);
                        $(btn).button('loading');
                        $('.btn-save').not(btn).prop('disabled', true);
                    },
                    complete: function () {
                        $(btn).button('reset');
                        $('.btn-save').not(btn).prop('disabled', false);
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
                            delay: 5000
                        });
                        handleErrorMessage(form, rs);
                    },
                    success: function (rs) {
                        if (after_success){
                            after_success(rs);
                        }
                    }
                });
            }
            $('#{{jSID('submit')}}').click(function () {
                var form = $('#{{jSID('form')}}')[0];
                var btn = this;
                saveForm(form, btn, function (rs) {
                    @if($salon)
                        new PNotify({
                            title: '{{'LƯU DỮ LIỆU'}}',
                            text: '{{__('Thông tin đã được lưu thành công!')}}',
                            addclass: 'bg-success stack-bottom-right',
                            stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25},
                            buttons: {
                                sticker: false
                            },
                            delay: 2000
                        });
                    @else
                        window.location = rs;
                    @endif
                });
            });
            $('#{{jSID('submit_return')}}').click(function () {
                var form = $('#{{jSID('form')}}')[0];
                var btn = this;
                saveForm(form, btn, function (rs) {
                    window.location = '{!! route('backend.salon.index') !!}';
                });
            });
            $('#{{jSID('form')}}').submit(function () {
                return false;
            });
            $('#auto_pick_location').click(function () {
                var tp = $('select[name=tinh_thanh_pho_id] option:selected').html();
                if (!tp){
                    return;
                }
                var address = tp;
                var qh = $('select[name=quan_huyen_id] option:selected').html();
                if (qh){
                    address = qh + ', '+address;
                }
                var tx = $('select[name=phuong_xa_thi_tran_id] option:selected').html();
                if (tx){
                    address = tx + ', '+address;
                }
                var road = $('input[name=address]').val();
                road = road.trim();
                if (road){
                    address = road + ', '+address;
                }
                mapToAddress(address);
            });
            var marker, geocoder, map;
            function mapToAddress(address) {
                geocoder.geocode( { 'address': address}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        map.setCenter(results[0].geometry.location);
                        marker.setPosition(results[0].geometry.location);
                    } else {
                        console.log('Geocode was not successful for the following reason: ' + status);
                    }
                });
            }
            function initMap() {
                geocoder = new google.maps.Geocoder();
                map = new google.maps.Map(document.getElementById('map'), {
                    zoom: {!! $zoom !!},
                    center: {lat: {!! $lat !!}, lng: {!! $lng !!}}
                });

                marker = new google.maps.Marker({
                    map: map,
                    draggable: true,
                    animation: google.maps.Animation.DROP,
                    position: {lat: {!! $lat !!}, lng: {!! $lng !!}}
                });
                google.maps.event.addListener(marker, 'dragend', function () {
                    map.panTo(marker.getPosition());
                    //map.setCenter(marker.getPosition()); // sets center without animation
                });
                google.maps.event.addListener(marker, "position_changed", function() {
                    getMapInfoForInput();
                });
                google.maps.event.addListener(map, 'zoom_changed', function() {
                    getMapInfoForInput();
                });
            }
            initMap();
            function getMapInfoForInput(){
                var lat = marker.getPosition().lat();
                var lng = marker.getPosition().lng();
                var zoom = map.zoom;
                $('input[name=lat]').val(lat);
                $('input[name=lng]').val(lng);
                $('input[name=zoom]').val(zoom);
            }
            getMapInfoForInput();
            $('select[name=phuong_xa_thi_tran_id]').select2({
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

                    @if ($tinh)
            var $option = new Option('{!! $tinh->name !!}', {!! $tinh->id !!}, true, true);
            $('select[name=tinh_thanh_pho_id]').append($option).trigger('change');
                    @if ($quan)
            var $option = new Option('{!! $quan->name !!}', {!! $quan->id !!}, true, true);
            $('select[name=quan_huyen_id]').append($option).trigger('change');
                    @if ($xa)
            var $option = new Option('{!! $xa->name !!}', {!! $xa->id !!}, true, true);
            $('select[name=phuong_xa_thi_tran_id]').append($option).trigger('change');
            @endif
            @endif
            @endif
        });
    </script>
@endpush