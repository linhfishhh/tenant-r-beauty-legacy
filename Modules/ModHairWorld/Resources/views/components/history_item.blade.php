@php
/** @var \Modules\ModHairWorld\Entities\SalonOrder[]|\Illuminate\Database\Eloquent\Collection $items */

@endphp

@foreach($items as $item)
    <tr class="service" data-id="{!! $item->id !!}">
        <td><a class="view-detail" href="#">{!! $item->id !!}</a></td>
        <td>{!! $item->created_at->format('d/m/Y H:m:i') !!}</td>
        <td>
            <div class="title">
                <a href="{!! $item->salon?$item->salon->url():'#' !!}">{!! $item->salon_name !!}</a>
            </div>
            <div class="service-date">
                @if(isset($item->service_time))
                    Lúc: {!! $item->service_time->format('d/m/Y H:i') !!}
                @endif
            </div>
            <div class="view"><a class="view-detail" href="#">Chi tiết</a></div>
        </td>
        <td>{!! number_format($item->getSum()/1000, 0, '.', '.') !!}K</td>
        <td><div>{!! $item->getStatusText() !!}</div>
            {{--@if($item->status == \Modules\ModHairWorld\Entities\SalonOrder::_CHO_THANH_TOAN_)--}}
                {{--<div><a href="{!! $item->getPaymentLink($item->payment_method, $item->getCheckWebCheckLink($item->payment_method)) !!}">[Thanh toán ngay]</a></div>--}}
            {{--@endif--}}
        </td>
    </tr>
@endforeach