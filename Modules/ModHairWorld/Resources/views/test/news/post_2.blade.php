@enqueueCSS('news-page', getThemeAssetUrl('libs/styles/news.css'), 'master-page')
@enqueueJS('grid-a-licious', getThemeAssetUrl('libs/jquery.grid-a-licious.min.js'), JS_LOCATION_HEAD, 'jquery')
@extends(getThemeViewName('master'))
@section('page_content')
    <div class="news-page-header">
        <div class="container">
            <div class="title">Liệu HD Brows có phải là câu trả lời cho các vòm tự nhiên?</div>
            <div class="date">19/04/2018</div>
        </div>
    </div>
    <div class="news-page-content-2">
        <div class="container">
            <div class="main-img">
                <img src="{!! getThemeAssetUrl('img/news_post_a_01.jpg') !!}">
            </div>
            <div class="main-content">
                <div class="wrapper">
                    <div class="content">
                        <p>Have we missed a memo or something? At London Fashion Week (And NYFW) you’d be forgiven for thinking that a pink rinse was part of the dress code. Editors and models took a surprise  step away from their signature no statement hair colour rule (better to let the clothes do the talking right?) and showed off a rainbow of candy floss, champagne and rose pink hues. Not to be outdone by the fashion pack, regular beauty chameleons Kylie Jenner and Lady Gaga joined the pretty in pink club with a slightly more glamorous take on this fast-emerging hair colour trend.</p>
                    </div>
                </div>
            </div>
            @php
                $items = [
                    [
                        'title' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed',
                        'img' => getThemeAssetUrl('img/news_post_a_02.jpg'),
                        'text' => '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse</p>'
                    ],
                    [
                        'title' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed',
                        'img' => getThemeAssetUrl('img/news_post_a_03.jpg'),
                        'text' => '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse</p>'
                    ],
                    [
                        'title' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed',
                        'img' => getThemeAssetUrl('img/news_post_a_04.jpg'),
                        'text' => '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse</p>'
                    ],
                    [
                        'title' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed',
                        'img' => getThemeAssetUrl('img/news_post_a_05.jpg'),
                        'text' => '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse</p>'
                    ],
                    [
                        'title' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed',
                        'img' => getThemeAssetUrl('img/news_post_a_06.jpg'),
                        'text' => '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse</p>'
                    ],
                ];
            @endphp
            <div class="news-step-wrapper">
                <div class="news-steps">
                    <div class="item item-p">
                        1
                    </div>
                    @foreach($items as $k=>$item)
                        <div class="item">
                            <div class="item-wrapper">
                                <div class="img">
                                    <img src="{!! $item['img'] !!}">
                                </div>
                                <div class="content">
                                    <div class="content-wrapper">
                                        <div class="number">0{!! $k+1 !!}</div>
                                        <div class="title">{!! $item['title'] !!}</div>
                                        <div class="text">{!! $item['text'] !!}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="item">
                        <div class="book-button">
                            <a href="#">Book now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-sharer">
        <div class="wrapper">
            <div class="container">
                <span class="text">Hãy chia sẽ bài viết này tới bạn bè của bạn</span>
                <div class="items d-inline-block">
                    <a href="#" target="_blank" class="d-inline-block item" style="background-color: #00ace8">
                        <i class="fa fa-facebook"></i>
                        <span class="name">Facebook</span>
                    </a>
                    <a href="#" target="_blank" class="d-inline-block item" style="background-color: #e57945">
                        <i class="fa fa-google-plus"></i>
                        <span class="name">Google</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="page-bread-cumber">
        <div class="container">
            <a href="#">Trang chủ</a> / <a href="#">Xu hướng tóc</a>
        </div>
    </div>
@endsection
@push('page_footer_js')
    <script type="text/javascript">
        $(function () {
            $(".news-steps").gridalicious({width: 500,gutter: 100});
        })
    </script>
@endpush