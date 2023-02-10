@enqueueCSS('share-page', getThemeAssetUrl('libs/styles/share.css'), 'account-menu')
@extends(getThemeViewName('test.account.master'))
@section('content')
    <div class="content-box">
        <div class="content-title">Giới thiệu chúng tôi đến bạn bè bạn</div>
        <div class="content-body">
            @php
                $items = [
                    [
                        'icon' => getThemeAssetUrl('img/share_sms.png'),
                        'link' => '<a href="#" target="_blank">Qua SMS</a>'
                    ],
                    [
                        'icon' => getThemeAssetUrl('img/share_email.png'),
                        'link' => '<a href="#" target="_blank">Qua Email</a>'
                    ],
                    [
                        'icon' => getThemeAssetUrl('img/share_face.png'),
                        'link' => '<a href="#" target="_blank">Qua Facebook</a>'
                    ],
                    [
                        'icon' => getThemeAssetUrl('img/share_zalo.png'),
                        'link' => '<a class="zalo-share-button" data-href="http://developers.zalo.me" data-oaid="579745863508352884" data-layout="2" data-color="blue" data-customize=true>Qua zalo</a><script src="https://sp.zalo.me/plugins/sdk.js"></script>'
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