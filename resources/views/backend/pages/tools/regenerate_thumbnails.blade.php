@php
/** @var \App\Classes\ThumbnailSize[] $sizes */
@endphp
@enqueueJSByID(config('view.ui.files.js.progressbar.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@extends('layouts.backend')
@section('page_title', __('Công cụ tạo lại thumbnail'))
@section('page_header_title')
    {!! __('Công cụ <strong>tạo lại thumbnail</strong>') !!}
@endsection
@section('page_content_body')
    @component('backend.components.panel', ['classes'=>'panel-default border-top-info'])
        @slot('content')
            <div class="alert alert-info">
                <p class="text-semibold">{!! __('Công cụ này sẽ tạo lại tất cả thumbnail (ảnh thu nhỏ) cho cái file ảnh được hổ trợ bởi hệ thống') !!}</p>
                <p>{!! __('Các kích thước thumbnail đang được hổ trợ bởi hệ thống:') !!}</p>
                <ul>
                    @foreach($sizes as $size)
                        <li class="mt-5 mb-5"><strong>{!! $size->getTitle() !!}</strong>: <span>{!! $size->getWidth() !!}px x {!! $size->getHeight() !!}px</span></li>
                    @endforeach
                </ul>
            </div>
            <div class="progress content-group-sm" id="tool-progresss">
                <div class="progress-bar progress-bar-success" data-transitiongoal-backup="75" data-transitiongoal="75" style="width: 0%">
                    <span class="sr-only">0%</span>
                </div>
            </div>
            <div class="text-center">
                <button type="button" class="btn btn-primary btn-sm btn-start-tool" ><i class="icon-play4 position-left"></i>{!! __('THỰC THI') !!}</button>
                <button type="button" class="btn btn-warning btn-sm btn-stop-tool disabled"><i class="icon-stop2 position-left"></i>{!! __('DỪNG LẠI') !!}</button>
            </div>
        @endslot
    @endcomponent
@endsection
@push('page_footer_js')
    <script type="text/javascript">
        $(function () {
            var stat_btn = $('.btn-start-tool');
            var stop_btn = $('.btn-stop-tool');
            var $total = 0;
            var $done = 0;
            var $queue = [];
            function generateThumbnail(){
                if ($queue.length>0){
                    var file = $queue.pop();
                    var url = '{!! route('backend.tools.regenerate_thumbnails.run', ['file'=>'-999999999']) !!}';
                    url = url.replace('-999999999', file);
                    $.ajax({
                        url: url,
                        type: 'post',
                        beforeSend: function () {
                            $(stat_btn).addClass('disabled');
                            $(stop_btn).removeClass('disabled');
                        },
                        success: function(data){
                            generateThumbnail();
                            $done++;
                        },
                        complete: function(){
                            var $pb = $('#tool-progresss .progress-bar');
                            if ($done == $total){
                                $(stat_btn).removeClass('disabled');
                                $(stop_btn).addClass('disabled');
                                $pb.attr('data-transitiongoal', 100);
                                $pb.progressbar();
                            }
                            else{
                                $pb.attr('data-transitiongoal', $done*100.0/30);
                                $pb.progressbar();
                            }
                        }
                    });
                }
                else{
                    $(stat_btn).removeClass('disabled');
                    $(stop_btn).addClass('disabled');
                }
            }
            var $manager;
            $('.btn-stop-tool').click(function () {
                if ($(this).hasClass('disabled')){
                    return;
                }
                $queue = [];
                $manager.abort();
            });
            $('.btn-start-tool').click(function () {
                if ($(this).hasClass('disabled')){
                    return;
                }
                var stat_btn = this;
                var stop_btn = $('.btn-stop-tool');
                $manager = $.ajax({
                    url: '{!! route('backend.tools.regenerate_thumbnails') !!}',
                    type: 'get',
                    beforeSend: function(){
                        $(stat_btn).addClass('disabled');
                        $(stop_btn).removeClass('disabled');
                    },
                    success: function (data) {
                        console.log(data);
                        if (data.length == 0){
                            $(stat_btn).removeClass('disabled');
                            $(stop_btn).addClass('disabled');
                            return;
                        }
                        $queue = data;
                        $total = data.length;
                        $done = 0;
                        var $pb = $('#tool-progresss .progress-bar');
                        $pb.attr('data-transitiongoal', 0);
                        $pb.progressbar({display_text: 'center',
                            percent_format: function(percent) { return percent + '%'; },
                            amount_format: function(amount_part, amount_total) {
                                var str = '{!! __('Đã xử lý :current/:total file ảnh...') !!}';
                                str = str.replace(':current', $done);
                                str = str.replace(':total', $total);
                                return str;
                            },
                            transition_delay: 0,
                            refresh_speed: 0,
                            use_percentage: false});
                        generateThumbnail();
                    },
                });
            });
        });
    </script>
@endpush