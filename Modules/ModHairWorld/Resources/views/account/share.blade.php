@enqueueCSS('share-page', getThemeAssetUrl('libs/styles/share.css'), 'account-menu')
@extends(getThemeViewName('account.master'))
@section('content')
    <div class="content-box">
        <div class="content-title">Giới thiệu chúng tôi đến bạn bè bạn</div>
        <div class="content-body">
            @php
                $items = [

                    [
                        'icon' => getThemeAssetUrl('img/share_email.png'),
                        'link' => '<a href="mailto:?&subject=Website book chỗ làm tóc cực hay&body=Website%20book%20ch%E1%BB%97%20l%C3%A0m%20t%C3%B3c%20c%E1%BB%B1c%20hay%3A%0A'.url('').'" target="_blank">Qua Email</a>'
                    ],
                    [
                        'icon' => getThemeAssetUrl('img/share_email.png'),
                        'link' => '<a href="https://plus.google.com/share?url='.url('').'" target="_blank">Qua Google Plus</a>'
                    ],
                    [
                        'icon' => getThemeAssetUrl('img/share_email.png'),
                        'link' => '<a href="https://twitter.com/home?status=Website%20book%20ch%E1%BB%97%20l%C3%A0m%20t%C3%B3c%20c%E1%BB%B1c%20hay%3A%0A'.url('').'" target="_blank">Qua Twitter</a>'
                    ],
                    [
                        'icon' => getThemeAssetUrl('img/share_face.png'),
                        'link' => '<a href="https://www.facebook.com/sharer/sharer.php?u='.url('').'" target="_blank">Qua Facebook</a>'
                    ],
                    [
                        'icon' => getThemeAssetUrl('img/share_zalo.png'),
                        'link' => '<a class="zalo-share-button" data-href="'.url('').'" data-oaid="579745863508352884" data-layout="2" data-color="blue" data-customize=true>Qua zalo</a><script src="https://sp.zalo.me/plugins/sdk.js"></script>'
                    ],
                ];
            @endphp
            <div id="share-list">
                @foreach($items as $item)
                    <div class="share-item">
                        <img src="{!! $item['icon'] !!}">
                        {!! $item['link'] !!}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection