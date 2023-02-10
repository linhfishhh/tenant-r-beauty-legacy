@php
    /** @var \Modules\ModHairWorld\Entities\Salon $salon */
@endphp
@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.pnotify.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.picker.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.picker_time.id'), JS_LOCATION_DEFAULT, 'bootstrap')
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
                <h6 class="panel-title text-teal text-semibold">{!! __('GIỜ LÀM VIỆC') !!}</h6>
            </div>
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th style="width: 150px">Ngày làm việc</th>
                            <th>Từ</th>
                            <th>Đến</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i=2; $i<=8; $i++)
                            <tr data-day="{!! $i-1 !!}">
                                <td>
                                    <span class="label label-block weekday pt-10 pb-10 text-size-base">
                                        @if($i == 8)
                                            {!! __('Chủ nhật') !!}
                                        @else
                                            {!! __('Thứ :weekday', ['weekday' => $i]) !!}
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="icon-alarm"></i></span>
                                        <input value="{!! isset($times[($i-1)])?$times[($i-1)]['start']:'' !!}" name="day[{!! $i-1 !!}][start]" type="text" class="form-control pickatime time-start" placeholder="{!! __('Giờ mở cửa') !!}">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="icon-alarm"></i></span>
                                        <input value="{!! isset($times[($i-1)])?$times[($i-1)]['end']:'' !!}" name="day[{!! $i-1 !!}][end]" type="text" class="form-control pickatime time-end" placeholder="{!! __('Giờ đóng cửa') !!}">
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-time-clear"><i class="icon-close2 position-left"></i>{!! __('NGHỈ') !!}</button>
                                    <button type="button" class="btn btn-success btn-time-all"><i class="icon-square-down position-left"></i>{!! __('TẤT CẢ') !!}</button>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
        </div>
    </form>
    @event(new \App\Events\AfterHtmlBlock('content'))
@endsection
@push('page_footer_js')
    <script type="text/javascript">
        $(function () {
            $('#{{jSID('form')}} select').select2({
                minimumResultsForSearch: -1
            });
            $('.btn-time-clear').click(function () {
                $(this).parents('tr').find('.pickatime').val('');
                $(this).parents('tr').find('.pickatime.time-start').trigger('change');
                $(this).parents('tr').find('.pickatime.time-end').trigger('change');
            });
            $('.btn-time-all').click(function () {
                var start = $(this).parents('tr').find('.time-start').val();
                var end = $(this).parents('tr').find('.time-end').val();
                $('.time-start').val(start);
                $('.time-end').val(end);
                $('.pickatime.time-start').trigger('change');
                $('.pickatime.time-end').trigger('change');
            });
            $('.pickatime.time-start').pickatime({
                clear: '{!! __('Xóa') !!}',
                format: 'HH:i',
                interval: 1,
            }).on('change', function () {
                var end = $(this).parents('tr').find('.time-end');
                var cval = $(this).val();
                $(this).parents('tr').find('.weekday').removeClass('bg-primary');
                $(this).parents('tr').find('.weekday').removeClass('bg-slate-300');
                if (cval){
                    if (!end.val() || (end.val()<cval)){
                        end.val(cval);
                    }
                    $(this).parents('tr').find('.weekday').addClass('bg-primary');
                }
                else{
                    $(this).parents('tr').find('.weekday').addClass('bg-slate-300');
                }
            }).trigger('change');
            $('.pickatime.time-end').pickatime({
                clear: '{!! __('Xóa') !!}',
                format: 'HH:i',
                interval: 30,
            }).on('change', function () {
                var start = $(this).parents('tr').find('.time-start');
                var cval = $(this).val();
                $(this).parents('tr').find('.weekday').removeClass('bg-primary');
                $(this).parents('tr').find('.weekday').removeClass('bg-slate-300');
                if (cval){
                    if (!start.val() || (start.val()>cval)){
                        start.val(cval);
                    }
                    $(this).parents('tr').find('.weekday').addClass('bg-primary');
                }
                else{
                    $(this).parents('tr').find('.weekday').addClass('bg-slate-300');
                }
            }).trigger('change');
            function saveForm(form, btn, after_success){
                var data = $(form).serializeObject();
                $.ajax({
                    url: '{!! route('backend.salon.time.store', ['salon' => $salon]) !!}',
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
                    loadSalonSidebarItemCount();
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