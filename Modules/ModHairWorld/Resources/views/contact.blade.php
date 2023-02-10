@enqueueCSS('contact-page', getThemeAssetUrl('libs/styles/contact.css'), 'master-page')
@extends(getThemeViewName('master'))
@section('current_page_title')
    Liên Hệ
@endsection
@php
    $og_img = getNoThumbnailUrl();
     $og_width = 500;
     $og_height = 500;
@endphp
@push('page_meta')
    <meta property="og:title" content="Liên hệ chúng tôi - iSalon"/>
    <meta property="og:image" content="{{ $og_img }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:image:secure_url" content="{{ $og_img }}" />
    <meta property="og:image:width" content="{{$og_width}}" />
    <meta property="og:image:height" content="{{$og_height}}" />
@endpush
@section('page_content')
    @php
    $contact_configs = getSettingsFromPage('contact');
    $contact_configs = collect($contact_configs);
     $headline = $contact_configs->get('theme_contact_headline', '');
    $headline_bg = $contact_configs->get('theme_contact_headline_bg', false);
    if($headline_bg){
        $f = \App\UploadedFile::find($headline_bg);
        if($f){
            $bg = $f->getUrl();
            if($bg){
                $headline_bg = $bg;
            }
        }
    }
    @endphp
    <div class="contact-page-header common-page-header" style="background-image: url('{!! $headline_bg !!}')">
        <div class="container">
            <div class="wrapper">
                <div class="inner">
                    <div class="title">
                        {!! $headline !!}
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
                                    <input spellcheck="false" autocomplete="off" name="name" placeholder="Tên của bạn">
                                </div>
                                <div class="field">
                                    <input spellcheck="false" autocomplete="off" name="email" placeholder="Email liên hệ">
                                </div>
                                <div class="field">
                                    <input spellcheck="false" autocomplete="off" name="phone" placeholder="Số điện thoại">
                                </div>
                                <div class="field">
                                    <textarea name="content" placeholder="Nội dung liên hệ" rows="10"></textarea>
                                </div>
                                <div class="captcha">
                                    <img id="contact_form_captcha" src="{!! url('captcha/flat?') !!}{!! rand() !!}">
                                    <input spellcheck="false" autocomplete="off" placeholder="Nhập mã bảo mật" type="text" spellcheck="false" name="captcha">
                                    <i style="cursor: pointer" class="fa fa-refresh" id="btn_reset_form_contact_captcha"></i>
                                </div>
                                <div class="btn-submit">
                                    <button class="btn">LIÊN HỆ CHÚNG TÔI</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-block">
                        <div class="block-title">Địa chỉ</div>
                        <div class="block-content">
                            @if($contact_configs->get('theme_contact_hotline'))
                            <div class="hot-line">
                                <span class="lbl">Hotline:</span>
                                <span class="text">{!! $contact_configs->get('theme_contact_hotline')!!}</span>
                            </div>
                            @endif
                            @if($contact_configs->get('theme_contact_brands', []))
                            @foreach($contact_configs->get('theme_contact_brands', []) as $item)
                                    <div class="contact-item">
                                            <div class="line name">
                                                {!! $item['title'] !!}
                                            </div>
                                            <div class="line">
                                                <div>Địa chỉ: {!! $item['address'] !!}</div>
                                                @if($item['email'])
                                                <div>Email: {!! $item['email'] !!}</div>
                                                @endif
                                            </div>
                                            @if($item['phone'] || $item['fax'] )
                                            <div class="line">
                                                @if($item['phone'] )
                                                <div>SĐT: {!! $item['phone'] !!}</div>
                                                @endif
                                                @if($item['fax'] )
                                                <div>Fax: {!! $item['fax'] !!}</div>
                                                @endif
                                            </div>
                                            @endif
                                            <div class="map-link">
                                                <a data-lat="{!! $item['location']['lat'] !!}" data-lng="{!! $item['location']['lng'] !!}" data-zoom="{!! $item['location']['zoom'] !!}" href="#" target="_blank">Xem bản đồ chỉ dẫn</a>
                                            </div>
                                        </div>
                            @endforeach
                            @endif
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
    <div id="modal-contact-map" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bản đồ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="map" style="height: 500px">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@include(getThemeViewName('includes.google_map_api'))
@push('page_footer_js')
    <script type="text/javascript">
        $('#btn_reset_form_contact_captcha').click(function () {
            $('#contact_form_captcha').attr('src', '{!! url('captcha/flat') !!}?'+Math.random());
        });
        var $modal_contact_map = $('#modal-contact-map').modal({
            show: false
        });
        var $map_contact;
        var $marker_contact;
        $(window).on('googleMapInit', function(){
            $map_contact = new google.maps.Map(
                $modal_contact_map.find('.map')[0]
                , {
                    center: {lat: -34.397, lng: 150.644},
                    zoom: 8
                });
            $marker_contact = new google.maps.Marker(
                {
                    position: {lat: -34.397, lng: 150.644},
                    map: $map_contact,
                });
        });
        $(function () {
            $('.map-link a').click(function () {
                var location = {
                  lat: $(this).data('lat'),
                  lng:  $(this).data('lng'),
                };
                var zoom = $(this).data('zoom');
                $marker_contact.setPosition(location);
                $map_contact.setCenter(
                    $marker_contact.position
                );
                $map_contact.setZoom(zoom*1.0);
                $modal_contact_map.modal('show');
                return false;
            });
            $('#btn_reset_form_contact_captcha').click(function () {
                $('#contact_form_captcha').attr('src', '{!! url('captcha/flat?') !!}' + Math.random());
            });
            $('#form-contact').submit(function () {
                var data = $(this).serializeObject();
                var form = $(this);
                var button = $(this).find('button');
                $.ajax({
                    url: '{!! route('frontend.contact.store') !!}',
                    data: data,
                    type: 'post',
                    dataType: 'json',
                    beforeSend: function () {
                        button.attr('disabled', true);
                    },
                    success: function () {
                        form.find('input').val('');
                        form.find('textarea').val('');
                        swal("Liên hệ", "Cám ơn bạn đã liên hệ với chúng tôi!", "success")
                    },
                    complete: function () {
                        button.attr('disabled', false);
                        $('#btn_reset_form_contact_captcha').click();
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