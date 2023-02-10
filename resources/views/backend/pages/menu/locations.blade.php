@php
/** @var \App\Menu[] $menus */
@endphp
@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.pnotify.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@extends('layouts.backend')
@section('page_title', __('Menu trên giao diện'))
@section('page_header_title')
    {!! __('Menu trên <strong>giao diện</strong>') !!}
@endsection
@section('sidebar_second')
    @if(count($locations) > 0)
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
                        id="{{jSID('submit')}}" type="button" class="btn bg-warning btn-block btn-save">
                    {{__('LƯU THAY ĐỔI')}}
                </button>
                @event(new \App\Events\AfterHtmlBlock('sidebar.actions'))
            </div>
        </div>
    </div>
    @endif
    @include('backend.pages.menu.includes.menu_items')
@endsection
@section('page_content_body')
    @event(new \App\Events\BeforeHtmlBlock('content'))
    <form class="" id="{{jSID('form')}}">
        @event(new \App\Events\BeforeHtmlBlock('content.form'))
        @component('backend.components.panel', ['classes'=>'panel-default'])
            @slot('title')
                <h5 class="panel-title text-teal">{{__('Thiết lập các menu lên giao diện')}}</h5>
            @endslot
            @slot('content')
                @if(count($locations) == 0)
                    <div class="alert alert-warning alert-bordered text-center">
                        @if(\App\Classes\Theme::getCurrentTheme())
                            {{__('Mẫu giao diện bạn đang sử dụng không hỗ trợ gán menu lên giao diện')}}
                        @else
                            {{__('Bạn chưa kích hoạt mẫu giao diện nào')}}
                        @endif
                    </div>
                @else
                    <div class="form-horizontal">
                        @foreach(config('app.locales', []) as $lang_code => $lang_title)
                            @if(count(config('app.locales')) > 1)
                                <fieldset class="content-group">
                                    <legend class="text-bold">
                                        <span class="label label-warning">{{$lang_code}}</span> {{$lang_title}}
                                    </legend>
                                    @endif
                                    @foreach($locations as $location_id=>$location_title)
                                        <div class="form-group">
                                            <label class="control-label col-lg-3">{{$location_title}}</label>
                                            <div class="col-lg-9">
                                                <select data-location="{{$location_id}}" data-lang="{{$lang_code}}" class="menu_selector" data-width="100%"></select>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if(count(config('app.locales')) > 1)
                                </fieldset>
                            @endif
                        @endforeach
                    </div>
                @endif
            @endslot
        @endcomponent
        @event(new \App\Events\AfterHtmlBlock('content.form'))
    </form>
    @event(new \App\Events\AfterHtmlBlock('content'))
@endsection
@push('page_footer_js')
    <script type="text/javascript">
        $(function () {
            $('#{{jSID('form')}} .menu_selector').select2({
                minimumResultsForSearch: Infinity,
                placeholder: '{{__('Chọn menu cho vị trí này')}}',
                allowClear: true,
                data: [
                    @foreach($menus as $menu)
                    {
                      id: '{{$menu->id}}',
                      text: '{{$menu->title}}'
                    },
                    @endforeach
                ]
            });

            var $load_menus = {!! json_encode($load_menus)  !!};

            $('#{{jSID('form')}} .menu_selector').each(function () {
                var location = $(this).data('location');
                var lang = $(this).data('lang');
                var sl = this;
                $(this).val('').change();
                $.each($load_menus, function (i, v) {
                    if(location == v.location && lang == v.language){
                        console.log(v.menu_id);
                        $(sl).val(v.menu_id).change();
                        return false;
                    }
                });
            });

            $('#{{jSID('submit')}}').click(function () {
                saveForm(this, function (rs) {
                    new PNotify({
                        title: '{{'LƯU DỮ LIỆU'}}',
                        text: '{{__('Menu đã được lưu thành công!')}}',
                        addclass: 'bg-success stack-bottom-right',
                        stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25},
                        buttons: {
                            sticker: false
                        },
                        delay: 2000
                    });
                })
            });

            function saveForm(btn, after_success) {
                var form = $('#{{jSID('form')}}');
                var data = [];
                $('#{{jSID('form')}} .menu_selector').each(function (i, v) {
                    var location = $(v).data('location');
                    var lang = $(v).data('lang');
                    data.push(
                        {
                            language: lang,
                            location: location,
                            menu_id: $(v).val()
                        }
                    );
                });
                data = {
                    menus: data
                };
                $.ajax({
                    url: '{{route('backend.menu.location.save')}}',
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    beforeSend: function () {
                        cleanErrorMessage(form);
                        $(btn).button('loading');
                        $('.btn-save').not(btn).prop('disabled', true);
                    },
                    success: function (rs) {
                        console.log(rs);
                        after_success(rs);
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
                    complete: function () {
                        $(btn).button('reset');
                        $('.btn-save').not(btn).prop('disabled', false);
                    }
                });
            }
        });
    </script>
@endpush