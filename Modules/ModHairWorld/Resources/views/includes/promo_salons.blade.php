@if($salons['items'])
    @foreach($salons['items'] as $item)
        <a href="{!! $item['url'] !!}?service_cat={!!  $salons['cat']['id'] !!}" style="display: block" class="promo-salon">
            <div class="img" style="background-image: url('{!! $item['image'] !!}')">
                <div class="service-sale-max"><div class="lbl">Giảm đến</div><div class="svl">{!! $salons['percent'] !!}%</div></div>
            </div>
            <div class="info">
                <div class="salon-name">{!! $item['name'] !!}</div>
                <div class="cat-name">{!! $salons['cat']['name'] !!}</div>
                <div class="address"><i class="fa fa-map-marker"></i><span>{!! $item['address'] !!}</span></div>
                <div class="rating">
                    <div class="number">{!! number_format($item['rating'], 1) !!}</div>
                    <div class="stars">
                        @component(getThemeViewName('components.rating_stars'), ['score' => $item['rating']])
                        @endcomponent
                    </div>
                    <div class="total">({!! $item['rating_count'] !!})</div>
                </div>
            </div>
            <div class="stats">
                <div class="percent-full">
                    <div class="percent-current" style="width: {!! 100 - $item['deal_done_percent'] !!}%"></div>
                </div>
                <div class="stats-text">Deal còn lại {!! $salons['limit'] - $item['deal_done'] !!}/{!! $salons['limit'] !!}</div>
            </div>
        </a>
    @endforeach
@endif