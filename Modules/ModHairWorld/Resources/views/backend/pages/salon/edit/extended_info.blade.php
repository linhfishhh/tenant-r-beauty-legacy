@php
    /** @var \Modules\ModHairWorld\Entities\Salon $salon */
    /** @var \Modules\ModHairWorld\Entities\DiaPhuongTinhThanhPho $tinh */
    /** @var \Modules\ModHairWorld\Entities\DiaPhuongQuanHuyen $quan */
    /** @var \Modules\ModHairWorld\Entities\DiaPhuongXaPhuongThiTran $xa */
@endphp
@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.pnotify.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@extends('layouts.backend')
@section('page_title', $salon->name)
@section('page_header_title')
    <strong>{{$salon->name}}</strong>
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
                <a href="{!! route('backend.slider.index') !!}" data-loading-text="<i class='icon-spinner4 spinner position-left'></i> {{__('ĐANG XỬ LÝ...')}}"
                   class="btn bg-warning btn-block btn-back btn-save">
                    {{__('QUAY LẠI')}}
                </a>
                @event(new \App\Events\AfterHtmlBlock('sidebar.actions'))
            </div>
        </div>
    </div>
    @include('modhairworld::backend.pages.salon.edit.includes.sidebar_items', ['salon'=>$salon])
@endsection
@section('page_content_body')
    @event(new \App\Events\BeforeHtmlBlock('content'))
    <form action="#" id="{{jSID('form')}}">
        <div class="panel panel-white">
            <div class="panel-heading">
                <h6 class="panel-title text-teal text-semibold">{!! __('THÔNG TIN MỞ RỘNG') !!}</h6>
            </div>
            <div class="panel-body">
                @component('backend.components.field', ['field' => $field, 'horizontal' => false])
                @endcomponent
            </div>
        </div>
    </form>
    @event(new \App\Events\AfterHtmlBlock('content'))
@endsection
@push('page_footer_js')
    <script type="text/javascript">
        $(function () {
            function saveForm(form, btn, after_success){
                var data = $(form).serializeObject();
                data._method = 'put';
                $.ajax({
                    url: '{!! route('backend.salon.extended_info.update', ['salon' => $salon]) !!}',
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
                        loadSalonSidebarItemCount();
                    }
                });
            }
            $('#{{jSID('submit')}}').click(function () {
                var form = $('#{{jSID('form')}}')[0];
                var btn = this;
                saveForm(form, btn, function () {
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
                });
            });
            $('#{{jSID('submit_return')}}').click(function () {
                var form = $('#{{jSID('form')}}')[0];
                var btn = this;
                saveForm(form, btn, function () {
                    window.location = '{!! route('backend.salon.index') !!}';
                });
            });
            $('#{{jSID('form')}}').submit(function () {
                return false;
            });
        });
    </script>
@endpush