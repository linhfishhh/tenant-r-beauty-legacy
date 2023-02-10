@php
/** @var \Modules\ModHairWorld\Entities\Salon[]|\Illuminate\Database\Eloquent\Collection $salons */
@endphp
@enqueueJSByID(config('view.ui.files.js.jquery_ui.id'), JS_LOCATION_DEFAULT, 'jquery')
@extends('layouts.backend')
@section('page_title', __('Danh sách salon của tôi'))
@section('page_header_title')
    <strong>{{__('Danh sách salon của tôi')}}</strong>
@endsection
@section('page_content_body')
    @event(new \App\Events\BeforeHtmlBlock('content'))
    <div class="row salons">
        @foreach($salons as $salon)
            <div data-id="{!! $salon->id !!}" class="col-lg-6 salon">
                <div class="panel panel-flat blog-horizontal blog-horizontal-2">
                    <div data-action=move class="panel-body">
                        <div class="thumb">
                            <a href="{!! route('backend.my_salon.manage', ['salon'=>$salon->id]) !!}" data-toggle="modal">
                                <img src="{!! getNoThumbnailUrl() !!}" class="img-responsive img-rounded" alt="">
                            </a>
                        </div>

                        <div class="blog-preview">
                            <div class="content-group-sm media blog-title stack-media-on-mobile text-left">
                                <div class="media-body">
                                    <h5 class="text-semibold"><a href="#" class="text-default">{!! $salon->name !!}</a></h5>

                                    <ul class="list-inline list-inline-separate no-margin text-muted">
                                        <li class="{!! $salon->open?'text-success':'text-danger-300' !!}"><i class="icon-{!! $salon->open?'checkmark':'blocked' !!}"></i> {!! $salon->open?__('Đang hoạt động'):__('Ngưng hoạt động') !!}</li>
                                    </ul>
                                </div>
                                @if($salon->certified)
                                <div class="text-success media-right no-margin-bottom">
                                    <i data-popup="tooltip" data-original-title="{!! __('Đã được chứng nhận')!!}" style="font-size: 25px" class="icon-shield-check"></i>
                                </div>
                                @else
                                    <div class="text-grey-300 media-right no-margin-bottom">
                                        <i data-popup="tooltip" data-original-title="{!! __('Chưa được chứng nhận')!!}" style="font-size: 25px" class="icon-shield-check"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="salon-info mb-15">
                                <div><label class="text-muted"><i class="position-left icon-location3"></i>{!! __('Địa chỉ') !!}:</label></div>
                                <div class="text-primary">138A Trần Phú, Phường 2, TP. Trà Vinh</div>
                            </div>
                            <div class="salon-info mb-15">
                                <div><label class="text-muted"><i class="position-left icon-users"></i>{!! __('Các quản lý salon') !!}:</label></div>
                                <div>
                                    @foreach($salon->managers as $manager)
                                        <span data-popup="tooltip" data-original-title="{!! $manager->email !!}" class="label bg-orange cursor-pointer">{!! $manager->name !!}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer panel-footer-condensed"><a class="heading-elements-toggle"><i class="icon-more"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline list-inline-separate heading-text">
                                <li><a href="#"><i class="icon-cart5 position-left"></i> 382 {!! __('Đơn hàng chờ xử lý') !!}</a></li>
                                <li><a href="#" class="text-primary">
                                    <i class="icon-star-full2 text-size-base text-warning-300"></i>
                                    <i class="icon-star-full2 text-size-base text-warning-300"></i>
                                    <i class="icon-star-full2 text-size-base text-warning-300"></i>
                                    <i class="icon-star-full2 text-size-base text-warning-300"></i>
                                    <i class="icon-star-full2 text-size-base text-warning-300"></i>
                                    <span class="text-muted position-right">({!! $salon->reviews_count*1 !!})</span>
                                    </a>
                                </li>
                                <li><a href="#" class="text-warning"><i class="icon-heart5 position-left"></i>{!! $salon->likes_count !!} {!! __('Yêu thích') !!}</a></li>
                            </ul>

                            <a href="{!! route('backend.my_salon.manage', ['salon'=>$salon->id]) !!}" class="heading-text pull-right">{!! __('Quản lý') !!} <i class="icon-arrow-right14 position-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @event(new \App\Events\AfterHtmlBlock('content'))
@endsection
@push('page_footer_js')
    <script type="text/javascript">
        $(function () {
            $(".salons").sortable({
                items: '.salon',
                helper: 'original',
                cursor: 'move',
                handle: '[data-action=move]',
                revert: 100,
                containment: '.content-wrapper',
                forceHelperSize: true,
                placeholder: 'sortable-placeholder-fix col-lg-6',
                forcePlaceholderSize: true,
                tolerance: 'pointer',
                start: function(e, ui){
                    ui.placeholder.height(ui.item.outerHeight() - 20);
                },
                stop: function( event, ui ) {
                    var data = [];
                    $('.salon').each(function (i, v) {
                        data.push({
                            id: $(this).data('id'),
                            order: i
                        });
                    });
                    $.ajax({
                        url: '{!! route('backend.my_salon.reorder') !!}',
                        type: 'post',
                        data:{
                            data: data
                        },
                        dataType: 'json',
                        success: function (json) {
                        }
                    });
                }
            });
        });
    </script>
@endpush