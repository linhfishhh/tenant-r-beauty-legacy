@enqueueCSS('notification-page', getThemeAssetUrl('libs/styles/notification.css'), 'account-menu')
@extends(getThemeViewName('test.account.master'))
@section('content')
    <div class="content-box">
        <div class="content-title">Thông báo của bạn</div>
        <div class="content-body">
            <div class="notifications">
                @php
                $items = [
                    [
                        'img' => getThemeAssetUrl('img/navatar1.png'),
                        'title' => '<a href="#">Giám giá 50% khi thanh toán online nhân dịp 30/4 - 1/5</a>',
                        'read' => 0,
                        'date' => '30/04/2018',
                        'link' => '#',
                        'color' => '#F05D3E',
                    ],
                    [
                        'img' => getThemeAssetUrl('img/navatar2.png'),
                        'title' => '<a href="#">Huyền Sài Gòn mới khai trương chi nhánh thứ 3 ưu đãi gấp 3 lần</a>',
                        'read' => 0,
                        'date' => '25/04/2018',
                        'link' => '#',
                        'color' => '#F05D3E',
                    ],
                    [
                        'img' => '',
                        'title' => 'Bạn vừa tạo thành công đơn hàng COD 12345678',
                        'read' => 1,
                        'date' => '20/04/2018',
                        'link' => '#',
                        'color' => '#F05D3E',
                    ],
                    [
                        'img' => '',
                        'title' => '<span>Bạn đã thanh toán thành công đơn hàng 12345678</span>',
                        'read' => 1,
                        'date' => '18/04/2018',
                        'link' => '#',
                        'color' => '#F05D3E',
                    ],
                    [
                        'img' => '',
                        'title' => '<a href="#">Hân</a> đã thích  bài đánh giá của bạn',
                        'read' => 1,
                        'date' => '17/04/2018',
                        'link' => '#',
                        'color' => '#00A69C',
                    ],
                    [
                        'img' => '',
                        'title' => '<span>Bạn có một mã Coupon code quy đổi từ việc huỷ dịch vụ của bạn</span>',
                        'read' => 1,
                        'date' => '10/04/2018',
                        'link' => '#',
                        'color' => '#00A69C',
                    ],
                ];
                @endphp
                @foreach($items as $item)
                    <div class="notification clearfix">
                        <div class="img" style="{!! $item['img']?'background-image: url(\''.$item['img'].'\')':'background-color:'.$item['color'] !!}">
                            @if(!$item['read'])
                                <div class="unread"></div>
                            @endif
                        </div>
                        <div class="content">
                            <div class="title">{!! $item['title'] !!}</div>
                            <div class="meta">
                                <span class="date">{!! $item['date'] !!}</span>
                                @if($item['link'])
                                    <i class="fa fa-circle"></i>
                                    <a class="view" href="{!! $item['link'] !!}">Chi tiết</a>
                                @endif
                                @if(!$item['read'])
                                <i class="fa fa-circle"></i>
                                <a class="read" href="#">Đã đọc</a>
                                @endif
                            </div>
                        </div>
                        <div class="delete"><i class="fa fa-remove"></i></div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection