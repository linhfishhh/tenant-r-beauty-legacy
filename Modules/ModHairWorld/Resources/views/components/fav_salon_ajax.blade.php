@php
    /** @var \Modules\ModHairWorld\Entities\SalonLike[]|\Illuminate\Database\Eloquent\Collection $items */
@endphp
@if($from==-1 && $items->count()==0)
<div class="no-fav-found">
    <div>Chưa có yêu thích nào</div>
</div>
@else
@foreach($items as $item)
    <div class="salon" data-id="{!! $item->id !!}">
        <div class="row">
            <div class="col-md-4">
                <div class="img">
                    <a href="{!! $item->salon->url() !!}">
                        <img src="{!! $item->salon->cover?$item->salon->cover->getThumbnailUrl('medium', getNoThumbnailUrl()):getNoThumbnailUrl() !!}">
                    </a>
                </div>
            </div>
            <div class="col-md-8">
                <div class="info">
                    <div class="title">
                        <a href="{!! $item->salon->url() !!}">{!! $item->salon->name !!}</a>
                    </div>
                    <div class="location">
                        <i class="fa fa-map-marker"></i>
                        <span>{!! $item->salon->getAddressLine() !!}</span>
                    </div>
                    <div class="rating">
                        <div class="stars">
                            @component(getThemeViewName('components.rating_stars'), ['score'=>$item->salon->rating])
                            @endcomponent
                        </div>
                        @if($item->salon->rating_count)
                        <div class="total">{!! $item->salon->rating_count !!} đánh giá nhận xét</div>
                        @else
                            <div class="total">Chưa có đánh giá nhận xét</div>
                        @endif
                    </div>
                    <div class="view">
                        <a href="{!! $item->salon->url() !!}">Xem chi tiết</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="remove">
            <a href="#" data-id="{!! $item->id !!}"><i class="fa fa-remove"></i></a>
        </div>
    </div>
@endforeach
@endif