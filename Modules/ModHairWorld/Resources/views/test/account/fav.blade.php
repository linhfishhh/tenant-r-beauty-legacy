@enqueueCSS('fancybox', getThemeAssetUrl('libs/fancybox/jquery.fancybox.min.css'), 'master-page')
@enqueueJS('fancybox', getThemeAssetUrl('libs/fancybox/jquery.fancybox.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueCSS('fav-page', getThemeAssetUrl('libs/styles/fav.css'), 'account-menu')
@extends(getThemeViewName('test.account.master'))
@section('content')
    <div class="content-box">
        <div class="content-title">Danh sách yêu thích</div>
        <div class="content-body">
            <div class="page-nav">
                <div class="item {!! $salon?'active':'' !!}">
                    <a href="{!! route('test.account.fav_Salon') !!}">Salon</a>
                </div>
                <div class="item {!! !$salon?'active':'' !!}">
                    <a href="{!! route('test.account.fav_showcase') !!}">Tác phẩm</a>
                </div>
            </div>
            <div class="fav-content">
                @if($salon)
                    <div class="fav-salon-list">
                        @php
                            $items = [
                                [
                                    'img' => getThemeAssetUrl('img/salon1.jpg'),
                                    'title' => 'I Hair Salon Bà Triệu',
                                    'location' => '10 Ngõ Bà Triệu, Bà Triệu, P. Lê Đại Hành, Q. Hoàn Kiếm, Hà Nội',
                                    'rating' => '4.5',
                                    'rating_count' => random_int(10, 50),
                                ],
                                [
                                    'img' => getThemeAssetUrl('img/salon2.jpg'),
                                    'title' => 'Thanh Sài Gòn Hair Salon',
                                    'location' => 'Hàng Nón, Q. Hoàn Kiếm, Hà Nội',
                                    'rating' => '4.5',
                                    'rating_count' => random_int(10, 50),
                                ],
                                [
                                    'img' => getThemeAssetUrl('img/salon3.jpg'),
                                    'title' => 'Hair System',
                                    'location' => 'Lò Sũ, Q. Hoàn Kiếm, Hà Nội',
                                    'rating' => '4.5',
                                    'rating_count' => random_int(10, 50),
                                ],
                                [
                                    'img' => getThemeAssetUrl('img/salon4.jpg'),
                                    'title' => 'Salon Quốc Việt',
                                    'location' => 'Trần Quốc Toản, Q.Hoàn Kiếm, Hà Nội',
                                    'rating' => '4.5',
                                    'rating_count' => random_int(10, 50),
                                ],
                                [
                                    'img' => getThemeAssetUrl('img/salon5.jpg'),
                                    'title' => 'Nam Hair Salon',
                                    'location' => '17 Hàng Mành, Q. Hoàn Kiếm, Hà Nội',
                                    'rating' => '4.5',
                                    'rating_count' => random_int(10, 50),
                                ],
                            ];
                        @endphp
                        @foreach($items as $item)
                            <div class="salon">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="img">
                                            <a href="{!! route('test.salon.post') !!}">
                                                <img src="{!! $item['img'] !!}">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="info">
                                            <div class="title">
                                                <a href="{!! route('test.salon.post') !!}">{!! $item['title'] !!}</a>
                                            </div>
                                            <div class="location">
                                                <i class="fa fa-map-marker"></i>
                                                <span>{!! $item['location'] !!}</span>
                                            </div>
                                            <div class="rating">
                                                <div class="stars">
                                                    @component(getThemeViewName('components.rating_stars'), ['score'=>$item['rating']])
                                                    @endcomponent
                                                </div>
                                                <div class="total">{!! $item['rating_count'] !!} đánh giá nhận xét</div>
                                            </div>
                                            <div class="view">
                                                <a href="{!! route('test.salon.post') !!}">Xem chi tiết</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="remove">
                                    <a href="#"><i class="fa fa-remove"></i></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="fav-showcase">
                        <div class="row">
                            @php
                                $items = [
                                    [
                                        'title' => 'Tác phẩm "Cuốn theo chiều gió"',
                                        'img_list' => [
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase1.png'),
                                                'img' => getThemeAssetUrl('img/showcase1.png')
                                            ],
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase_i1.jpg'),
                                                'img' => getThemeAssetUrl('img/showcase_i1.jpg'),
                                            ],
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase_i2.jpg'),
                                                'img' => getThemeAssetUrl('img/showcase_i2.jpg'),
                                            ],
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase_i3.jpg'),
                                                'img' => getThemeAssetUrl('img/showcase_i3.jpg'),
                                            ],
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase_i4.jpg'),
                                                'img' => getThemeAssetUrl('img/showcase_i4.jpg'),
                                            ],
                                        ]
                                    ],
                                    [
                                        'title' => 'Tác phẩm "Tóc mây thôi bay"',
                                        'img_list' => [
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase2.png'),
                                                'img' => getThemeAssetUrl('img/showcase2.png'),
                                            ],
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase_i1.jpg'),
                                                'img' => getThemeAssetUrl('img/showcase_i1.jpg'),
                                            ],
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase_i2.jpg'),
                                                'img' => getThemeAssetUrl('img/showcase_i2.jpg'),
                                            ],
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase_i3.jpg'),
                                                'img' => getThemeAssetUrl('img/showcase_i3.jpg'),
                                            ],
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase_i4.jpg'),
                                                'img' => getThemeAssetUrl('img/showcase_i4.jpg'),
                                            ],
                                        ]
                                    ],
                                    [
                                        'title' => 'Tác phẩm "Sư cô chảy tóc"',
                                        'img_list' => [
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase3.png'),
                                                'img' => getThemeAssetUrl('img/showcase3.png'),
                                            ],
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase_i1.jpg'),
                                                'img' => getThemeAssetUrl('img/showcase_i1.jpg'),
                                            ],
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase_i2.jpg'),
                                                'img' => getThemeAssetUrl('img/showcase_i2.jpg'),
                                            ],
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase_i3.jpg'),
                                                'img' => getThemeAssetUrl('img/showcase_i3.jpg'),
                                            ],
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase_i4.jpg'),
                                                'img' => getThemeAssetUrl('img/showcase_i4.jpg'),
                                            ],
                                        ]
                                    ],
                                    [
                                        'title' => 'Tác phẩm "Cuốn theo chiều gió"',
                                        'img_list' => [
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase1.png'),
                                                'img' => getThemeAssetUrl('img/showcase1.png')
                                            ],
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase_i1.jpg'),
                                                'img' => getThemeAssetUrl('img/showcase_i1.jpg'),
                                            ],
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase_i2.jpg'),
                                                'img' => getThemeAssetUrl('img/showcase_i2.jpg'),
                                            ],
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase_i3.jpg'),
                                                'img' => getThemeAssetUrl('img/showcase_i3.jpg'),
                                            ],
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase_i4.jpg'),
                                                'img' => getThemeAssetUrl('img/showcase_i4.jpg'),
                                            ],
                                        ]
                                    ],
                                    [
                                        'title' => 'Tác phẩm "Tóc mây thôi bay"',
                                        'img_list' => [
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase2.png'),
                                                'img' => getThemeAssetUrl('img/showcase2.png'),
                                            ],
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase_i1.jpg'),
                                                'img' => getThemeAssetUrl('img/showcase_i1.jpg'),
                                            ],
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase_i2.jpg'),
                                                'img' => getThemeAssetUrl('img/showcase_i2.jpg'),
                                            ],
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase_i3.jpg'),
                                                'img' => getThemeAssetUrl('img/showcase_i3.jpg'),
                                            ],
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase_i4.jpg'),
                                                'img' => getThemeAssetUrl('img/showcase_i4.jpg'),
                                            ],
                                        ]
                                    ],
                                    [
                                        'title' => 'Tác phẩm "Sư cô chảy tóc"',
                                        'img_list' => [
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase3.png'),
                                                'img' => getThemeAssetUrl('img/showcase3.png'),
                                            ],
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase_i1.jpg'),
                                                'img' => getThemeAssetUrl('img/showcase_i1.jpg'),
                                            ],
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase_i2.jpg'),
                                                'img' => getThemeAssetUrl('img/showcase_i2.jpg'),
                                            ],
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase_i3.jpg'),
                                                'img' => getThemeAssetUrl('img/showcase_i3.jpg'),
                                            ],
                                            [
                                                'thumb' => getThemeAssetUrl('img/showcase_i4.jpg'),
                                                'img' => getThemeAssetUrl('img/showcase_i4.jpg'),
                                            ],
                                        ]
                                    ],
                                ];
                            @endphp
                            @foreach($items as $gk=>$item)
                                <div class="col-lg-4 col-6">
                                    <div class="item">
                                        <a data-caption="{{$item['title'] }}" data-fancybox="gallery_{!! $gk !!}" href="{!! $item['img_list'][0]['img'] !!}" class="d-block wrapper">
                                            <div class="cover" style="background-image: url('{!! $item['img_list'][0]['thumb'] !!}')">
                                                <div class="like{!! random_int(0, 1)?' liked':'' !!}"></div>
                                            </div>
                                            <div class="title">{!! $item['title'] !!}</div>
                                        </a>
                                        <div class="d-none">
                                            @foreach($item['img_list'] as $k=>$i)
                                                @if($k==0)
                                                    @continue
                                                @endif
                                                <a data-caption="{{$item['title'] }}" data-fancybox="gallery_{!! $gk !!}" href="{!! $i['img'] !!}"></a>
                                            @endforeach
                                        </div>
                                        <div class="remove">
                                            <i class="fa fa-remove"></i>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection