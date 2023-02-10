@php
/** @var \Modules\ModHairWorld\Entities\SalonShowcaseLike[]|\Illuminate\Database\Eloquent\Collection $items */
@endphp
@if($from==-1 && $items->count()==0)
    <div class="no-fav-found">
        <div>Chưa có yêu thích nào</div>
    </div>
@else
    @foreach($items as $gk=>$item)
        @php
        /** @var \Modules\ModHairWorld\Entities\SalonShowcaseItem $cover */
        $cover = $item->showcase->cover();
        @endphp
        <div class="col-lg-4 col-6">
            <div class="item" data-id="{!! $item->id !!}">
                <a data-caption="{{ $item->showcase->name  }}" data-fancybox="gallery_{!! $gk !!}" href="{!! $cover?($cover->image?$cover->image->getUrl():'#'):'#' !!}" class="d-block wrapper">
                    <div class="cover" style="background-image: url('{!! $cover?($cover->image?$cover->image->getThumbnailUrl('medium_sq', getNoThumbnailUrl()):getNoThumbnailUrl()):getNoThumbnailUrl() !!}')">
                    </div>
                    <div class="title">{!! $item->showcase->name !!}</div>
                </a>
                <div class="d-none">
                    @foreach($item->showcase->items as $k=>$i)
                        @if($k==0)
                            @continue
                        @endif
                        @php
                        /** @var \Modules\ModHairWorld\Entities\SalonShowcaseItem $i */
                        @endphp
                        <a data-caption="{{$item->showcase->name }}" data-fancybox="gallery_{!! $gk !!}" href="{!! $i->image?$i->image->getUrl():getNoThumbnailUrl() !!}"></a>
                    @endforeach
                </div>
                <div class="remove" data-id="{!! $item->id !!}">
                    <i class="fa fa-remove"></i>
                </div>
            </div>
        </div>
    @endforeach
@endif