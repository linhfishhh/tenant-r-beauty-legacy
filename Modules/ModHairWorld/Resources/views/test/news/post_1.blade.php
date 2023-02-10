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
    <div class="news-page-content-1">
        <div class="container">
            <div class="main-content">
                <div class="wrapper">
                    <div class="content">
                        <p>Have we missed a memo or something? At London Fashion Week (And NYFW) you’d be forgiven for thinking that a pink rinse was part of the dress code. Editors and models took a surprise  step away from their signature no statement hair colour rule (better to let the clothes do the talking right?) and showed off a rainbow of candy floss, champagne and rose pink hues. Not to be outdone by the fashion pack, regular beauty chameleons Kylie Jenner and Lady Gaga joined the pretty in pink club with a slightly more glamorous take on this fast-emerging hair colour trend.</p>
                        <p>Now don’t be too quick to write this off as fashion week madness. Far from being a flash in the pan, pink tints are slowly becoming a modern hair colour classic in the same way that ombre and grey did. And with this, comes improved colouring techniques</p>
                    </div>
                    <div class="book-button">
                        <a href="#">Book now</a>
                    </div>
                </div>
            </div>
            @php
            $items = [
                [
                    'thumb' => getThemeAssetUrl('img/news_post_01.jpg'),
                    'img' => getThemeAssetUrl('img/news_post_01.jpg')
                ],
                [
                    'thumb' => getThemeAssetUrl('img/news_post_02.jpg'),
                    'img' => getThemeAssetUrl('img/news_post_02.jpg')
                ],
                [
                    'thumb' => getThemeAssetUrl('img/news_post_03.jpg'),
                    'img' => getThemeAssetUrl('img/news_post_03.jpg')
                ],
                [
                    'thumb' => getThemeAssetUrl('img/news_post_04.jpg'),
                    'img' => getThemeAssetUrl('img/news_post_04.jpg')
                ],
                [
                    'thumb' => getThemeAssetUrl('img/news_post_05.jpg'),
                    'img' => getThemeAssetUrl('img/news_post_05.jpg')
                ],
                [
                    'thumb' => getThemeAssetUrl('img/news_post_06.jpg'),
                    'img' => getThemeAssetUrl('img/news_post_06.jpg')
                ],
                [
                    'thumb' => getThemeAssetUrl('img/news_post_07.jpg'),
                    'img' => getThemeAssetUrl('img/news_post_07.jpg')
                ],
                [
                    'thumb' => getThemeAssetUrl('img/news_post_08.jpg'),
                    'img' => getThemeAssetUrl('img/news_post_08.jpg')
                ],
                [
                    'thumb' => getThemeAssetUrl('img/news_post_09.jpg'),
                    'img' => getThemeAssetUrl('img/news_post_09.jpg')
                ],
                [
                    'thumb' => getThemeAssetUrl('img/news_post_10.jpg'),
                    'img' => getThemeAssetUrl('img/news_post_10.jpg')
                ],
                [
                    'thumb' => getThemeAssetUrl('img/news_post_11.jpg'),
                    'img' => getThemeAssetUrl('img/news_post_11.jpg')
                ],
            ];
            @endphp
            <div class="news-gallery">
                <div class="item item-p">
                </div>
                @foreach($items as $item)
                    <div class="item">
                        <a href="{!! $item['img'] !!}">
                            <img src="{!! $item['thumb'] !!}">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@push('page_footer_js')
    <script type="text/javascript">
        $(function () {
            $(".news-gallery").gridalicious({width: 300,gutter: 40});
        })
    </script>
@endpush