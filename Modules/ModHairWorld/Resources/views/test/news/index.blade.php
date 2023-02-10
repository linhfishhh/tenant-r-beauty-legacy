@enqueueCSS('new-cat-page', getThemeAssetUrl('libs/styles/news_cat.css'), 'master-page')
@enqueueJS('grid-a-licious', getThemeAssetUrl('libs/jquery.grid-a-licious.min.js'), JS_LOCATION_HEAD, 'jquery')
@extends(getThemeViewName('master'))
@section('page_content')
    <div class="page-headline">
        <div class="container">
            <div class="page-title">
                Cập nhật xu hướng tóc
            </div>
        </div>
    </div>
    <div class="first-news">
        <div class="wrapper">
            <div class="img-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="img" style="background-image: url('{!! getThemeAssetUrl('img/newscat01.jpg') !!}')"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8 d-none d-lg-block">

                        </div>
                        <div class="col-lg-4">
                            <div class="content">
                                <div class="inner news-item">
                                    <div class="title">
                                        <a href="{!! route('test.news.post.1') !!}">
                                            Liệu HD Brows có phải là câu trả lời cho các vòm tự nhiên?
                                        </a>
                                    </div>
                                    <div class="date">19/04/2018</div>
                                    <div class="desc">
                                        HD Brows được coi là cấp độ chăm sóc
                                        sắc đẹp tiếp theo khi nói đến lông mày
                                        của bạn, có nhiều bước ...
                                    </div>
                                    <div class="more"><a href="#">XEM THÊM</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="news-grid">
        <div class="container">
            <div class="news-grid-wrapper">
                @php
                    $items = [
                        [
                            'title' => 'Hướng dẫn để đi tóc vàng platinum mà không hoàn toàn làm hỏng tóc của bạn',
                            'img' => getThemeAssetUrl('img/newscat02.jpg'),
                            'date' => '19/04/2018',
                            'desc' => 'Da đầu ngứa, sự tái phát rễ nhanh, màu da cam, bạn đã nghe thấy kinh dị...'
                        ],
                        [
                            'title' => 'Pinch, xoắn và căng: Những gì massage Trung Quốc di chuyển thực sự làm',
                            'img' => getThemeAssetUrl('img/newscat03.jpg'),
                            'date' => '20/04/2018',
                            'desc' => 'Cho dù bạn đang cảm thấy cần tăng năng lượng hoặc bạn đang cảm thấy một chút nhấn mạnh...'
                        ],
                        [
                            'title' => 'Beyoncé đã không thay đổi màu sắc móng tay của cô ở giữa của Coachella, nhưng đây là cách bạn có thể',
                            'img' => getThemeAssetUrl('img/newscat04.jpg'),
                            'date' => '21/04/2018',
                            'desc' => 'Trong khi chúng tôi đấu tranh để tìm được chiếc vớ phù hợp vào buổi sáng, Beyoncé đã...'
                        ],
                        [
                            'title' => 'Beyoncé đã không thay đổi màu sắc móng tay của cô ở giữa của Coachella, nhưng đây là cách bạn có thể',
                            'img' => getThemeAssetUrl('img/newscat04.jpg'),
                            'date' => '21/04/2018',
                            'desc' => 'Trong khi chúng tôi đấu tranh để tìm được chiếc vớ phù hợp vào buổi sáng, Beyoncé đã...'
                        ],
                        [
                            'title' => 'Hướng dẫn để đi tóc vàng platinum mà không hoàn toàn làm hỏng tóc của bạn',
                            'img' => getThemeAssetUrl('img/newscat02.jpg'),
                            'date' => '19/04/2018',
                            'desc' => 'Da đầu ngứa, sự tái phát rễ nhanh, màu da cam, bạn đã nghe thấy kinh dị...'
                        ],
                        [
                            'title' => 'Pinch, xoắn và căng: Những gì massage Trung Quốc di chuyển thực sự làm',
                            'img' => getThemeAssetUrl('img/newscat03.jpg'),
                            'date' => '20/04/2018',
                            'desc' => 'Cho dù bạn đang cảm thấy cần tăng năng lượng hoặc bạn đang cảm thấy một chút nhấn mạnh...'
                        ],
                        [
                            'title' => 'Pinch, xoắn và căng: Những gì massage Trung Quốc di chuyển thực sự làm',
                            'img' => getThemeAssetUrl('img/newscat03.jpg'),
                            'date' => '20/04/2018',
                            'desc' => 'Cho dù bạn đang cảm thấy cần tăng năng lượng hoặc bạn đang cảm thấy một chút nhấn mạnh...'
                        ],
                        [
                            'title' => 'Beyoncé đã không thay đổi màu sắc móng tay của cô ở giữa của Coachella, nhưng đây là cách bạn có thể',
                            'img' => getThemeAssetUrl('img/newscat04.jpg'),
                            'date' => '21/04/2018',
                            'desc' => 'Trong khi chúng tôi đấu tranh để tìm được chiếc vớ phù hợp vào buổi sáng, Beyoncé đã...'
                        ],
                        [
                            'title' => 'Hướng dẫn để đi tóc vàng platinum mà không hoàn toàn làm hỏng tóc của bạn',
                            'img' => getThemeAssetUrl('img/newscat02.jpg'),
                            'date' => '19/04/2018',
                            'desc' => 'Da đầu ngứa, sự tái phát rễ nhanh, màu da cam, bạn đã nghe thấy kinh dị...'
                        ],
                    ];
                @endphp
                @foreach($items as $k=>$item)
                    <div class="news-item item">
                        <div class="img">
                            <img src="{!! $item['img'] !!}">
                        </div>
                        <div class="content">
                            <div class="content-wrapper">
                                <div class="title">
                                    <a href="{!! ($k%2==0)?route('test.news.post.1'):route('test.news.post.2')  !!}">{!! $item['title'] !!}</a>
                                </div>
                                <div class="date">{!! $item['date'] !!}</div>
                                <div class="desc">
                                    {!! $item['desc'] !!}
                                </div>
                                <div class="more"><a href="{!! ($k%2==0)?route('test.news.post.1'):route('test.news.post.2')  !!}">XEM THÊM</a></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@push('page_footer_js')
<script type="text/javascript">
    $(function () {
        $(".news-grid-wrapper").gridalicious({width: 300,gutter: 30, animate: true,});
    })
</script>
@endpush