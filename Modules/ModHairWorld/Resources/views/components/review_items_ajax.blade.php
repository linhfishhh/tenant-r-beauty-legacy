@php
/** @var \Modules\ModHairWorld\Entities\SalonServiceReview[]|\Illuminate\Pagination\LengthAwarePaginator $items */
@endphp
@if($items->count()>0)
@foreach($items as $item)
    <div class="review" data-id="{!! $item->id !!}">
        <div class="avatar" style="background-image: url('{!! $item->user->avatar?$item->user->avatar->getThumbnailUrl('default', getNoAvatarUrl()):getNoAvatarUrl() !!}')"></div>
        <div class="author">{!! $item->user->name !!}</div>
        <div class="date">{!! $item->created_at->format('H:i d/m/Y') !!}</div>
        <div class="rating-like">
            <div class="row">
                <div class="col-6">
                    <div class="rating">
                        @component(getThemeViewName('components.rating_stars'), ['score' => $item->rating])
                        @endcomponent
                    </div>
                </div>
                <div class="col-6">
                    <div class="like @auth {!! $item->liked_by_me?'liked':'' !!} @endif ">
                        <div class="wrapper clearfix">
                            <div class="count-block">
                                {!! $item->likes_count !!}
                            </div>
                            <div class="button-block">
                                <i class="fa fa-thumbs-up"></i>
                                <span>Hữu ích</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="title">
            "{!! $item->title !!}"
        </div>
        <div class="content">
            {!! $item->content !!}
        </div>
        @if($item->images)
            <div class="row review-images">
                @foreach($item->images as $image)
                    <div class="col-sm-3 col-6">
                        <a
                                data-fancybox="review-{!! $item->id !!}"
                                class="review-image" href="{!!  $image->image?$image->image->getUrl():'#' !!}" style="background-image: url('{!! $image->image?$image->image->getThumbnailUrl('medium', getNoThumbnailUrl()):getNoThumbnailUrl() !!}')">
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
        {{--<div class="info">--}}
            {{--Khách hàng đã sử dụng dịch vụ <span class="service-name">"{!! $item->service->name !!}"</span>--}}
        {{--</div>--}}
    </div>
@endforeach
@else
{{--    <div class="no-review-found">--}}
{{--        <i class="fa fa-frown-o" aria-hidden="true"></i>--}}
{{--        <div>Không tìm thấy đánh giá nhận xét tương ứng</div>--}}
{{--    </div>--}}
<div class="text-center p-5 no-comment-salon">
    <i class="fa fa-frown-o fa-5x" aria-hidden="true"></i>
    <div>Hiện chưa có nhận xét nào cho Salon</div>
    <div>Cho người khác biết ý kiên của bạn và trở thành người đầu tiên
        nhận xét sản phẩm này
    </div>
</div>
@endif