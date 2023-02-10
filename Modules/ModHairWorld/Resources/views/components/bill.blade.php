@enqueueCSS('cart-step-3-page', getThemeAssetUrl('libs/styles/cart-step-3.css'))
@php
$show_link = isset($show_link)?$show_link:false;
/** @var \Modules\ModHairWorld\Entities\SalonOrder $order */
@endphp
<div class="booking-bill">
    <div class="bill-header">
        <div class="title">Đơn đặt chỗ của bạn</div>
        <div class="id">#{!! $order->id !!}</div>
    </div>
    <div class="bill-info">
        <div class="item">
            <div class="icon">
                <i class="fa fa-calendar"></i>
            </div>
            <div class="info">
                @if(isset($order->service_time))
                    <div class="title">Ngày {!! $order->service_time->format('d/m/Y') !!}</div>
                @endif
            </div>
        </div>
        <div class="item">
            <div class="icon">
                <i class="fa fa-clock-o"></i>
            </div>
            <div class="info">
                @if(isset($order->service_time))
                    <div class="title">{!! $order->service_time->format('H:i') !!}</div>
                @endif
            </div>
        </div>
        <div class="item">
            <div class="icon">
                <i class="fa fa-map-marker"></i>
            </div>
            <div class="info">
                <div class="title">{!! $order->salon?$order->salon->name:$order->salon_name !!}</div>
                <div class="sub">{!! $order->salon?$order->salon->getAddressLine():$order->salon_address !!}</div>
            </div>
        </div>
        <div class="item">
            <div class="icon">
                <i class="fa fa-dollar"></i>
            </div>
            <div class="info">
                <div class="title">Hình thức thanh toán</div>
                <div class="sub">{!! $order->getPaymentMethodText() !!}</div>
            </div>
        </div>
    </div>
    <div class="bill-detail">
        <table>
            <thead>
            <tr>
                <th>Dịch vụ</th>
                <th>Thành tiền</th>
            </tr>
            </thead>
            <tbody>
            @php
                $sum = $order->items->sum(function ($item){
                    /** @var \Modules\ModHairWorld\Entities\SalonOrderItem $item */
                    return $item->price * $item->quatity;
                });
            @endphp
            @foreach($order->items as $item)
                <tr>
                    <td>
                        <div class="title">{!! $item->service_name !!}</div>
                        <div class="quantity">Số lương: {!! $item->quatity !!}</div>
                    </td>
                    <td style="text-align: right">
                        <div class="price">{!! number_format($item->price/1000, 0, '.', '.') !!}K</div>
                    </td>
                </tr>
            @endforeach
            <tr class="sum">
                <td>
                    <div class="title">Tổng tiền</div>
                </td>
                <td style="text-align: right">
                    <div class="price">{!! number_format($sum/1000, 0, '.', '.') !!}K</div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    @if($show_link)
    <div class="link">
        <a href="{!! route('frontend.account.history') !!}">
            <i class="fa fa-edit"></i>
            Quản lý đặt chỗ
        </a>
    </div>
    @endif
</div>