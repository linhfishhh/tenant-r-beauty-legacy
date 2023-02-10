@php
/** @var \Modules\ModHairWorld\Entities\SalonService $service */
$service->load(['logos', 'logos.image']);
@endphp
<div class="service-detail-header">
    <div class="service-title">{!! $service->name !!}</div>
    <div class="rating-block">
        <div class="number">
            {!! number_format($service->rating, 1, '.', '.') !!}
        </div>
        <div class="star-text">
            <div class="stars">
                @component(getThemeViewName('components.rating_stars'), ['score' => $service->rating])
                @endcomponent
            </div>
            <div class="text">
                @if($service->rating_count)
                {!! $service->rating_count !!} đánh giá & nhận xét
                @else
                Chưa có đánh giá nào
                @endif
            </div>
        </div>
        <div class="write-review-link d-none">
            <a href="#">Đánh giá dịch vụ</a>
        </div>
    </div>
    @php
        $images = [];
        if($service->logos){
            foreach ($service->logos as $logo){
                if($logo->image){
                    $url = $logo->image->getThumbnailUrl('small_ka', false);
                    if($url){
                        $images[] = $url;
                    }
                }
            }
        }
    @endphp
    @if($images)
        <div class="service-logo-list-title">Các thương hiệu sử dụng</div>
        <div class="service-logo-list">
            <div class="row">
                @foreach($images as $image)
                    <div class="col-3 col-xs-2 logo">
                        <img src="{!! $image !!}"/>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
<div class="service-detail-content">
    <div class="common-content-block">
        {!! $service->description !!}
    </div>
</div>
<div class="service-detail-review">
    <div class="block-title">Đánh giá & nhận xét</div>
    <div class="review-block">
        @component(getThemeViewName('components.review_items'), [
            'id' => 'service_quick_view_review_box'
        ])
        @endcomponent
    </div>
</div>