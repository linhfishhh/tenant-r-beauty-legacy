@php
/** @var \Modules\ModHairWorld\Entities\SalonService[] $items */
$cart_items = Request::session()->get('wa_cart_items', []);
$cart_items = collect($cart_items);
$can_book = isset($can_book)?$can_book:false;
$settings = getSettingsFromPage('promo_configs');
$settings = collect($settings);
$promo_cat = $salon->isInPromo() ? $settings->get('promo_cats') : null;
$promo_percent = $settings->get('promo_percent');
$promo_limit = $settings->get('promo_limit');
/** @var \Modules\ModHairWorld\Entities\Salon $salon */
$deal_done = $salon->countPromoOrder();
$deal_remain = $promo_limit - $deal_done;
@endphp
<div class="service-list">
    @foreach($items as $index => $item)
        @php
        $logo_images = [];
        if($item->logos){
            foreach ($item->logos as $logo){
                if($logo->image){
                    $url = $logo->image->getThumbnailUrl('small_ka', false);
                    if($url){
                        $logo_images[] = $url;
                    }
                }
            }
        }
        $is_promo = $promo_cat == $item->category_id;
        $service_logo =  $item->cover;
        if ($service_logo){
            $service_logo = $service_logo->getThumbnailUrl('default', false);
        } else{
            $service_logo = getNoThumbnailUrl();
        }
        $images = [];
        if ($item->images){
            foreach ($item->images as $image){
                $url = $image->image;
                    if($url){
                        $images[] = $url;
                    }
            }
        }

        @endphp
        <div class="service d-none card mb-3 {!! $cart_items->has($item->id)?'added':'' !!}{!! $is_promo?' has-promo':'' !!}" data-cat="{!! $item->category_id !!}"
                data-is-sale="{!! $is_promo || $item->sale_percent_cache ? '1' : '0' !!}">
            <div class="card-body">
                <div class="title">
                   <div class="row">
                        <div class="col-12 col-md-8 d-flex align-items-center">
                            <img class="service-logo" src="{{ $service_logo }}" alt="">
                           <div class="">
                               <a class="d-block" href="#" onclick="showServiceQuickView({!! $item->id !!}); return false">{!! $item->name !!}</a>
                               <small class="d-block">
                                    <span class="times">
                                        {!! $item->timeText() !!}
                                    </span>
                               </small>
                           </div>
                        </div>
                       <div class="col-12 col-md-4 text-center text-md-right">
                           <div class="prices">
                               @if($item->sale_off || $is_promo)

                                   @if($is_promo)
                                       <span class="current">
                                    @php
                                        $price = $item->getOrgPriceFrom();
                                        $price = $price - ($price * $promo_percent/100);
                                        $price = \Modules\ModHairWorld\Entities\SalonService::formatPrice($price);
                                    @endphp
                                           {!! $price !!}
                                    </span>
                                   @else
                                       <span class="current">
                                        {!! $item->finalPriceHtml() !!}
                                    </span>
                                   @endif
                                  <div>
                                      @php
                                          //$sale_off = $item->price?(100 - floor($item->getFinalPrice()*100/$item->price)):0;
                                      @endphp
                                      @if($is_promo)
                                          <div class="sale_off">
                                              Deal còn lại {!! $deal_remain !!}/{!! $promo_limit !!}
                                          </div>
                                      @endif
                                      @if($item->sale_percent_cache > 0 && !$is_promo)
                                          <span class="sale_off">Giảm {!! $item->sale_percent_cache !!}%</span>
                                      @endif
                                      <span class="old">
                                        {!! $item->priceHtml() !!}
                                      </span>
                                  </div>
                               @else
                                   <span class="current">
                                    {!! $item->finalPriceHtml() !!}
                                </span>
                               @endif
                           </div>
                       </div>
                   </div>
                </div>
                <div class="detail">
                    <div class="row no-gutters">
                        @if($images)
                            <div class="col-12 col-md-9 d-flex">
                                <div class="service-image-list text-center text-md-left w-100">
                                    <div class="row no-gutters">
                                        @foreach($images as $key => $image)
{{--                                            @if($key < 3)--}}
                                                <a data-caption="{{ $item->name }}" data-fancybox="service_{!! $index !!}" href="{{ $image->getUrl() }}"
                                                   class="col-6 col-md-3 p-1 {{ $key > 3 ? 'd-none' : ''}}{{ $key == 3 && (count($images) > 4) ? 'more-image' : ''   }}">
                                                    @if($key == 3 && count($images) > 4)
                                                        <div class="count">{{  '+'.(count($images) - 4) }}</div>
                                                    @endif
                                                    <img src="{!! $image->getThumbnailUrl('default', false) !!}"/>
                                                </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="{{ $images ? 'col-md-3 pl-2 button-group mt-2 mt-md-0' : 'col-12 col-md-4 d-inline-flex'  }}">
                            <div class="add-button {{ $images ? 'text-right' : 'col-6 p-0 order-2' }}">
                                <button class="icon btn btn-submit btn-block" data-id="{!! $item->id !!}">
                                    <span class="icon-text">Đặt chỗ</span>
                                </button>
                            </div>
                            <div class="times-link {{ $images ? '' : 'col-6 p-0 order-1' }}">
                                <button onclick="showServiceQuickView({!! $item->id !!}); return false" class="link btn btn-block btn-info {{ $images ? 'ml-auto' :'' }}">Xem chi tiết</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
<div class="show-all-service row no-gutters">
    <a class="btn btn-link col-12 col-md-4" href="#">Xem tất cả <span class="service-count"></span> dịch vụ</a>
</div>
@include(getThemeViewName('includes.service_quickview', ['can_book'=>$can_book]))