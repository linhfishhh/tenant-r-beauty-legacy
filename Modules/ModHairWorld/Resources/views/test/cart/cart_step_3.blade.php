@enqueueCSS('cart-step-3-page', getThemeAssetUrl('libs/styles/cart-step-3.css'), 'cart')
@extends(getThemeViewName('test.cart.master'))
@section('content')
    @php
        $step = 3;
        $cart_title = 'Đặt chỗ hoàn tất!';
        $cart_desc = 'Quá trình đặt chỗ hoàn tất, vui lòng lưu lại thông tin bên dưới để đối chiếu';
    @endphp
@section('cart_content')
    <div class="cart-step-2">
        <div class="row">
            <div class="col-md-6">
                @component(getThemeViewName('components.bill'), ['show_link'=>1])
                @endcomponent
            </div>
        </div>
    </div>
@endsection
@endsection
@push('page_footer_js')
    <script type="text/javascript">
        $(function () {
        });
    </script>
@endpush