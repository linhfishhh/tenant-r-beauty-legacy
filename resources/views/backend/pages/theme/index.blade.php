@php
/** @var \Nwidart\Modules\Laravel\Module[] $themes */
@endphp
@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.pnotify.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@extends('layouts.backend')
@section('page_title', __('Các mẫu giao diện'))
@section('page_header_title')
    {!! __('Các mẫu <strong>giao diện</strong>') !!}
@endsection
@section('page_content_body')
    <div class="row" id="{{jSID('theme_list_placeholder')}}">

    </div>
    <template id="{{jSID('theme_list')}}">
        <div class="col-md-3 {{jSID('theme_item')}}">
            <div class="thumbnail">
                <div class="thumb">
                    <img src="{cover}" alt="">
                </div>
                <div class="caption text-center">
                    <h6 class="text-semibold no-margin-top">{title}</h6>
                    <ul class="icons-list icons-list-extended mb-15">
                        <li><a class="text-semibold text-grey" data-popup="tooltip" title="" data-original-title="{{__('Menu')}}"><i class="icon-{support_menu_icon} {support_menu}"></i> {{__('Menu')}}</a></li>
                        <li><a class="text-semibold text-grey" data-popup="tooltip" title="" data-original-title="{{__('Sidebar')}}"><i class="icon-{support_sidebar_icon} {support_sidebar}"></i> {{__('Sidebar')}}</a></li>
                    </ul>
                    <p style="height: 38px; overflow: hidden">
                        {description}
                    </p>
                    <a data-id="{id}" class="btn btn-block bg-{class}"><i class="icon-star-{icon} position-left"></i> {label}</a>
                </div>
            </div>
        </div>
    </template>
@endsection
@push('page_footer_js')
    <script type="text/javascript">
        $(function () {
            $(document).on('click', '.{{jSID('theme_item')}} .btn.bg-success', function () {
                var active = $(this).data('id');
                loadThemeList(active);
            });
            function loadThemeList(active) {
                $.ajax({
                    url: '{{route('backend.theme.index')}}',
                    type: 'get',
                    dataType: 'json',
                    data:{
                      active: active
                    },
                    beforeSend: function () {
                        $('#{{jSID('theme_list_placeholder')}}').block(
                            {
                                message: '<i class="icon-spinner4 spinner"></i>',
                                overlayCSS: {
                                    backgroundColor: '#fff',
                                    opacity: 0.8,
                                    cursor: 'wait'
                                },
                                css: {
                                    border: 0,
                                    padding: 0,
                                    backgroundColor: 'none'
                                }
                            }
                        );
                    },
                    error: function () {
                        new PNotify({
                            title: '{{'KÍCH HOẠT GIAO DIỆN'}}',
                            text: '{{__('Có lỗi xảy ra trong quá trình kích hoạt giao diện!')}}',
                            addclass: 'bg-danger stack-bottom-right',
                            stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25},
                            buttons: {
                                sticker: false
                            },
                            delay: 2000
                        });
                    },
                    success: function (rs) {
                        console.log(rs);
                        var tpl = $('#{{jSID('theme_list')}}').html();
                        var html = '';
                        $.each(rs, function (i, v) {
                            var add = tpl;
                            add = add.replace(/{cover}/, v.cover);
                            add = add.replace(/{title}/, v.title);
                            add = add.replace(/{description}/, v.description);
                            add = add.replace(/{id}/, v.id);
                            if (v.active){
                                add = add.replace(/{class}/, 'danger');
                                add = add.replace(/{icon}/, 'full2');
                                add = add.replace(/{label}/, '{{__('Đang dùng')}}');
                            }
                            else {
                                add = add.replace(/{class}/, 'success');
                                add = add.replace(/{icon}/, 'empty3');
                                add = add.replace(/{label}/, '{{__('Kích hoạt')}}');
                            }
                            if (v.support_menu){
                                add = add.replace(/{support_menu}/, 'text-success');
                                add = add.replace(/{support_menu_icon}/, 'checkmark2');
                            }
                            else{
                                add = add.replace(/{support_menu}/, 'text-warning');
                                add = add.replace(/{support_menu_icon}/, 'cross3');
                            }
                            if (v.support_sidebar){
                                add = add.replace(/{support_sidebar}/, 'text-success');
                                add = add.replace(/{support_sidebar_icon}/, 'checkmark2');
                            }
                            else{
                                add = add.replace(/{support_sidebar}/, 'text-warning');
                                add = add.replace(/{support_sidebar_icon}/, 'cross3');
                            }
                            html = html + add;
                        });
                        $('#{{jSID('theme_list_placeholder')}}').html(html);
                        $('#{{jSID('theme_list_placeholder')}}').find('[data-popup="tooltip"]').tooltip();
                        if (active){
                            {{--new PNotify({--}}
                                {{--title: '{{'KÍCH HOẠT GIAO DIỆN'}}',--}}
                                {{--text: '{{__('Giao diện bạn chọn đã được kích hoạt!')}}',--}}
                                {{--addclass: 'bg-success stack-bottom-right',--}}
                                {{--stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25},--}}
                                {{--buttons: {--}}
                                    {{--sticker: false--}}
                                {{--},--}}
                                {{--delay: 2000--}}
                            {{--});--}}
                            window.location = '{!! route(Route::currentRouteName())!!}';
                        }
                    },
                    complete: function () {
                        $('#{{jSID('theme_list_placeholder')}}').unblock();
                    }
                });
            }
            $(document).ready(function () {
                loadThemeList(null);
            });
        });
    </script>
@endpush