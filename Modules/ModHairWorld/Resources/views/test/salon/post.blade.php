@enqueueCSS('salon-page', getThemeAssetUrl('libs/styles/salon.css'), 'master-page')
@enqueueCSS('owl', getThemeAssetUrl('libs/owl/assets/owl.carousel.min.css'), 'master-page')
@enqueueJS('owl', getThemeAssetUrl('libs/owl/owl.carousel.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueCSS('fancybox', getThemeAssetUrl('libs/fancybox/jquery.fancybox.min.css'), 'master-page')
@enqueueJS('fancybox', getThemeAssetUrl('libs/fancybox/jquery.fancybox.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueCSS('select2', getThemeAssetUrl('libs/select2/css/select2.min.css'), 'bootstrap')
@enqueueJS('select2', getThemeAssetUrl('libs/select2/js/select2.full.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueJS('popper', getThemeAssetUrl('libs/popper.min.js'), JS_LOCATION_HEAD, 'jquery')
@extends(getThemeViewName('master'))
@section('page_content')
@component(getThemeViewName('components.float_cart'))
@endcomponent
<div class="salon-page-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-7">
                <div class="salon-name">
                    Salon Tóc Tây Ninh Kiều
                </div>
            </div>
            <div class="col-lg-4 col-md-5 clearfix">
                <a class="salon-rating clearfix d-block" href="#">
                    <div class="rating-score">
                        3.5
                    </div>
                    <div class="rating-detail">
                        <div class="star-block">
                            @component(getThemeViewName('components.rating_stars'), ['score' => 3.5]) @endcomponent
                        </div>
                        <div class="stats-block">
                            250 nhận xét & đánh giá
                        </div>
                    </div>
                    <div class="link">
                        <i class="fa fa-chevron-right"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="salon-gallery">
    <div class="container">
        <div class="salon-share-like">
            <a href="#" id="salon-share"><i class="fa fa-share-alt"></i></a>
            <a href="#"><i class="fa fa-heart"></i></a>
        </div>
        <div class="img-list owl-carousel">
            @php
            $items = [
                [
                    'thumb' => getThemeAssetUrl('img/salon-g-1.jpg'),
                    'link' => getThemeAssetUrl('img/salon-g-1.jpg'),
                    'video' => 0
                ],
                [
                    'thumb' => getThemeAssetUrl('img/salon-g-2.jpg'),
                    'link' => 'https://youtu.be/_sI_Ps7JSEk',
                    'video' => 1
                ],
                [
                    'thumb' => getThemeAssetUrl('img/salon-g-3.jpg'),
                    'link' => getThemeAssetUrl('img/salon-g-3.jpg'),
                    'video' => 0
                ],
                [
                    'thumb' => getThemeAssetUrl('img/salon-g-4.jpg'),
                    'link' => getThemeAssetUrl('img/salon-g-4.jpg'),
                    'video' => 0
                ],
            ];
            @endphp
            @foreach($items as $item)
                    <a data-fancybox="gallery" class="d-block{!! $item['video']?' video':'' !!}" href="{!! $item['link'] !!}" style="background-image: url('{!! $item['thumb'] !!}')">
                    </a>
            @endforeach
        </div>
    </div>
</div>
<div class="salon-detail">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 order-lg-1">
                <div class="salon-map">
                    <div class="map">
                        <img src="{!! getThemeAssetUrl('img/salon_map.png') !!}" style="width: 100%">
                    </div>
                    <div class="info">
                        <div class="item location-working">
                            <div class="location">
                                <i class="fa fa-map-marker"></i>
                                <span>Hàng Nón, Q. Hoàn Kiếm, Hà Nội</span>
                            </div>
                            <div class="work-hours">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="title">Giờ mở cửa</div>
                                        <div class="sub-title">Tất cả ngày trong tuần</div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="hours">
                                            8:30 - 20:00
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item feature">
                            <div class="img"><img src="{!! getThemeAssetUrl('img/salon_verify.png') !!}"></div>
                            <div class="title">Salon được xác minh bởi thegioitoc.vn</div>
                        </div>
                        <div class="item feature">
                            <div class="img"><img src="{!! getThemeAssetUrl('img/salon_card_accept.png') !!}"></div>
                            <div class="title">Cửa hàng này chấp nhận thanh toán thẻ</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 order-lg-0">
                <div class="d-none d-lg-block">
                    <div class="local-nav" id="local-nav">
                        <div class="container">
                            <ul class="nav">
                                <li class="nav-item"><a class="nav-link" href="#hot-services">Dịch vụ hot</a></li>
                                <li class="nav-item"><a class="nav-link" href="#salon-services">Dịch vụ</a></li>
                                <li class="nav-item"><a class="nav-link" href="#info">Thông tin</a></li>
                                <li class="nav-item"><a class="nav-link" href="#showcase">Tác phẩm</a></li>
                                <li class="nav-item"><a class="nav-link" href="#review">Đánh giá</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="hot-service">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="hot-service-title">
                                Các dịch vụ nổi bật của chúng tôi
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="hot-service-block" id="hot-services">
                                @php
                                    $items = [
                                        [
                                            'title' => 'Combo 6 bước cắt tóc nam',
                                            'time' => [
                                                '30',
                                                '40'
                                            ],
                                            'price' => [
                                                '60',
                                                '100'
                                            ]
                                        ],
                                        [
                                            'title' => 'Nhuộm tóc highlight',
                                            'time' => [
                                                '20',
                                                '30'
                                            ],
                                            'price' => [
                                                '40',
                                                '40'
                                            ]
                                        ],
                                        [
                                            'title' => 'Duỗi tóc',
                                            'time' => [
                                                '60',
                                                '80'
                                            ],
                                            'price' => [
                                                '200',
                                                '350'
                                            ]
                                        ],
                                    ];
                                @endphp
                                @component(getThemeViewName('components.salon_services'), ['items' => $items]) @endcomponent
                            </div>
                        </div>
                    </div>
                </div>
                <div class="salon-service" id="salon-services">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="salon-service-title">
                                <img src="{!! getThemeAssetUrl('img/dv_icon.png') !!}">
                                <span>Dịch vụ</span>
                            </div>
                            <div class="salon-service-cats">
                                <ul>
                                    @php
                                        $cats = [
                                            [
                                                'title' => 'Hớt tóc nữ',
                                                'count' => random_int(1, 9)
                                            ],
                                            [
                                                'title' => 'Hớt tóc nam',
                                                'count' => random_int(1, 9)
                                            ],
                                            [
                                                'title' => 'Nhộm tóc',
                                                'count' => random_int(1, 9)
                                            ],
                                            [
                                                'title' => 'Nối tóc',
                                                'count' => random_int(1, 9)
                                            ],
                                            [
                                                'title' => 'Duỗi tóc',
                                                'count' => random_int(1, 9)
                                            ],
                                            [
                                                'title' => 'Tạo kiểu tóc',
                                                'count' => random_int(1, 9)
                                            ],
                                            [
                                                'title' => 'Combo làm tóc',
                                                'count' => random_int(1, 9)
                                            ],
                                        ];
                                    @endphp
                                    @foreach($cats as $k=>$cat)
                                        <li class="{!! $k==0?'active':'' !!}">
                                            <div class="wrapper">
                                                <div class="title">
                                                    {!! $cat['title'] !!}
                                                </div>
                                                <div class="count">
                                                    (0{!! $cat['count'] !!})
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="salon-service-text">Chúng tôi mong muốn mang lại dịch vụ tốt nhất cho bạn!</div>
                            <div class="salon-service-block">
                                @php
                                    $items = [
                                        [
                                            'title' => 'Combo 6 bước cắt tóc nam',
                                            'time' => [
                                                '30',
                                                '40'
                                            ],
                                            'price' => [
                                                '60',
                                                '100'
                                            ]
                                        ],
                                        [
                                            'title' => 'Nhuộm tóc highlight',
                                            'time' => [
                                                '20',
                                                '30'
                                            ],
                                            'price' => [
                                                '40',
                                                '40'
                                            ]
                                        ],
                                        [
                                            'title' => 'Duỗi tóc',
                                            'time' => [
                                                '60',
                                                '80'
                                            ],
                                            'price' => [
                                                '200',
                                                '350'
                                            ]
                                        ],
                                        [
                                            'title' => 'Combo 6 bước cắt tóc nam',
                                            'time' => [
                                                '30',
                                                '40'
                                            ],
                                            'price' => [
                                                '60',
                                                '100'
                                            ]
                                        ],
                                        [
                                            'title' => 'Nhuộm tóc highlight',
                                            'time' => [
                                                '20',
                                                '30'
                                            ],
                                            'price' => [
                                                '40',
                                                '40'
                                            ]
                                        ],
                                        [
                                            'title' => 'Duỗi tóc',
                                            'time' => [
                                                '60',
                                                '80'
                                            ],
                                            'price' => [
                                                '200',
                                                '350'
                                            ]
                                        ],
                                    ];
                                @endphp
                                @component(getThemeViewName('components.salon_services'), ['items' => $items]) @endcomponent
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="salon-info">
    <div class="container">
        <div class="info-block basic-info" id="info">
            <div class="info-block-title clearfix">
                <div class="icon">
                    <img src="{!! getThemeAssetUrl('img/info_icon_1.png') !!}">
                </div>
                <div class="text">Thông tin chung</div>
            </div>
            <div class="info-block-content">
                <div class="main-basic-info">
                    <div class="row">
                    <div class="col-lg-8">
                        <div class="intro-block">
                            <p>Được thành lập vào năm 2013, Head Quarters là một tiệm làm tóc dành cho phụ nữ nằm trong câu lạc bộ giải trí của David Lloyd ở Bushey, Hertfordshire. Nổi tiếng với phương pháp điều trị chất lượng cao và cách tiếp cận chuyên nghiệp, khách hàng của họ, họ cung cấp một loạt các dịch vụ tóc bao gồm cắt, thổi khô, màu sắc nổi bật và mờ.</p>
                            <p>Salon là sáng, thoáng mát và rộng rãi, và bầu không khí yên tĩnh là hoàn hảo để ngồi lại và tận hưởng một khoảnh khắc thư giãn của niềm đam mê. Với 60 năm kinh nghiệm kết hợp, đội ngũ tạo mẫu tài năng của HQ chỉ làm việc với những sản phẩm tốt nhất từ ​​các thương hiệu hàng đầu trong ngành như L'Oreal, Goldwell và Paul Mitchell, và có sự liên lạc và nghệ thuật để biến đổi vẻ ngoài của bạn và mang lại những kết quả nổi bật bạn đang sau.</p>
                            <p>Head Quarters nằm đối diện với Costco Watford, chỉ cách góc đường từ Hilton Bushey và mở cửa bảy ngày một tuần. Tại đây có chỗ đậu xe miễn phí cho khách hàng để thuận tiện cho bạn. Hãy đối xử với ổ khóa của bạn vào một buổi chiều sang trọng với một cuộc hẹn tại Head Quarters Bushey.</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="times-block">
                            <ul>
                                @php
                                $items = [
                                    [
                                        'title' => 'Thứ hai',
                                        'from' => '7:30',
                                        'to' => '18:00'
                                    ],
                                    [
                                        'title' => 'Thứ ba',
                                        'from' => '7:30',
                                        'to' => '18:00'
                                    ],
                                    [
                                        'title' => 'Thứ tư',
                                        'from' => '7:30',
                                        'to' => '18:00'
                                    ],
                                    [
                                        'title' => 'Thứ năm',
                                        'from' => '7:30',
                                        'to' => '18:00'
                                    ],
                                    [
                                        'title' => 'Thứ sáu',
                                        'from' => '7:30',
                                        'to' => '18:00'
                                    ],
                                    [
                                        'title' => 'Thứ bảy',
                                        'from' => '7:30',
                                        'to' => '18:00'
                                    ],
                                    [
                                        'title' => 'Chủ nhật',
                                        'from' => '7:30',
                                        'to' => '18:00'
                                    ],
                                ];
                                @endphp
                                @foreach($items as $item)
                                    <li>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="w-day">
                                                    {!! $item['title'] !!}
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="w-hour">
                                                    {!! $item['from'] !!} - {!! $item['to'] !!}
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                </div>
                <div class="sub-basic-info stylist-info">
                    <div class="sub-info-block">
                        <div class="sub-info-block-title clearfix">
                            <div class="icon">
                                <img src="{!! getThemeAssetUrl('img/sub_icon_1.png') !!}">
                            </div>
                            <div class="text">Stylist</div>
                        </div>
                        <div class="sub-info-block-content">
                            <div class="stylist-list owl-carousel">
                                @php
                                $items = [
                                    [
                                        'img' => getThemeAssetUrl('img/stylist1.jpg'),
                                        'name' => 'Kaly Bùi'
                                    ],
                                    [
                                        'img' => getThemeAssetUrl('img/stylist2.jpg'),
                                        'name' => 'Thái Hà'
                                    ],
                                    [
                                        'img' => getThemeAssetUrl('img/stylist3.jpg'),
                                        'name' => 'Samuel Nguyễn'
                                    ],
                                    [
                                        'img' => getThemeAssetUrl('img/stylist4.jpg'),
                                        'name' => 'Cùi Bắp'
                                    ],
                                    [
                                        'img' => getThemeAssetUrl('img/stylist5.jpg'),
                                        'name' => 'Củ Cải Xanh'
                                    ],
                                    [
                                        'img' => getThemeAssetUrl('img/stylist6.jpg'),
                                        'name' => 'Hai Hợi'
                                    ],
                                ];
                                @endphp
                                @foreach($items as $item)
                                    <div class="stylist">
                                        <div class="wrapper">
                                            <div class="img">
                                                <img src="{!! $item['img'] !!}">
                                            </div>
                                            <div class="name">
                                                {!! $item['name'] !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sub-basic-info brand-info">
                    <div class="sub-info-block">
                        <div class="sub-info-block-title clearfix">
                            <div class="icon">
                                <img src="{!! getThemeAssetUrl('img/sub_icon_2.png') !!}">
                            </div>
                            <div class="text">Những thương hiệu sử dụng tại salon</div>
                        </div>
                        <div class="sub-info-block-content">
                            <div class="brand-list owl-carousel">
                                @php
                                $items = [
                                    getThemeAssetUrl('img/brand1.png'),
                                    getThemeAssetUrl('img/brand2.png'),
                                    getThemeAssetUrl('img/brand3.png'),
                                    getThemeAssetUrl('img/brand4.png'),

                                ];
                                @endphp
                                @foreach($items as $item)
                                    <div class="brand" style="background-image: url('{!! $item !!}')">

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sub-basic-info extended-info">
                    <div class="sub-info-block">
                        <div class="sub-info-block-title clearfix">
                            <div class="icon">
                                <img src="{!! getThemeAssetUrl('img/sub_icon_3.png') !!}">
                            </div>
                            <div class="text">Hỏi đáp nhanh</div>
                        </div>
                        <div class="sub-info-block-content">
                            <p>Hỏi: Học cắt tóc cần đầu tư bao nhiêu tiền và mất bao lâu?<br>
                                Đáp: Một tỷ và 20 năm.
                            </p>
                            <p>
                                Hỏi: Salon có hổ trợ trả góp<br>
                                Đáp: Chúng tôi hỗ trợ trả góp lãi suất thấp.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="info-block gallery-info" id="showcase">
            <div class="info-block-title clearfix">
                <div class="icon">
                    <img src="{!! getThemeAssetUrl('img/info_icon_2.png') !!}">
                </div>
                <div class="text">Tác phẩm</div>
            </div>
            <div class="info-block-content">
                <div class="showcase owl-carousel">
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
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="info-block review-info" id="review">
            <div class="info-block-title clearfix">
                <div class="icon">
                    <img src="{!! getThemeAssetUrl('img/info_icon_3.png') !!}">
                </div>
                <div class="text">Nhận xét & đánh giá</div>
            </div>
            <div class="info-block-content">
                <div class="rating-box">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="total-rating">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="number">
                                            <div class="current">4.0</div>
                                            <div class="total">/5</div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="star-block">
                                            @component(getThemeViewName('components.rating_stars'), ['score'=>4.0])
                                            @endcomponent
                                        </div>
                                        <div class="text-block">Có 210 đánh giá nhận xét</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="detail-rating">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="rating-detail-item">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="rating-star-block">
                                                        @component(getThemeViewName('components.rating_stars'), ['score' => 4.0])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="rating-title">
                                                        Giá cả
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="rating-detail-item">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="rating-star-block">
                                                        @component(getThemeViewName('components.rating_stars'), ['score' => 4.0])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="rating-title">
                                                        Phục vụ
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="rating-detail-item">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="rating-star-block">
                                                        @component(getThemeViewName('components.rating_stars'), ['score' => 4.0])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="rating-title">
                                                        Chất lượng
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="rating-detail-item">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="rating-star-block">
                                                        @component(getThemeViewName('components.rating_stars'), ['score' => 4.0])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="rating-title">
                                                        Không gian
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="review-box">
                    <div class="row">
                        <div class="col-md-8 order-md-1">
                            <div class="right-sec">
                                @component(getThemeViewName('components.review_items'), ['items' => $items])
                                @endcomponent
                            </div>
                        </div>
                        <div class="col-md-4 order-md-0">
                            <div class="review-filter">
                                <div class="wrapper">
                                    <div class="block-title">
                                        Lọc đánh giá
                                    </div>
                                    <div class="cat-filter">
                                        <select>
                                            @php
                                                $cats = [
                                                [
                                                    'title' => 'Hớt tóc nữ',
                                                    'count' => random_int(1, 9)
                                                ],
                                                [
                                                    'title' => 'Hớt tóc nam',
                                                    'count' => random_int(1, 9)
                                                ],
                                                [
                                                    'title' => 'Nhộm tóc',
                                                    'count' => random_int(1, 9)
                                                ],
                                                [
                                                    'title' => 'Nối tóc',
                                                    'count' => random_int(1, 9)
                                                ],
                                                [
                                                    'title' => 'Duỗi tóc',
                                                    'count' => random_int(1, 9)
                                                ],
                                                [
                                                    'title' => 'Tạo kiểu tóc',
                                                    'count' => random_int(1, 9)
                                                ],
                                                [
                                                    'title' => 'Combo làm tóc',
                                                    'count' => random_int(1, 9)
                                                ],
                                            ];
                                            @endphp
                                            <option>Tất cả dịch vụ</option>
                                            @foreach($cats as $cat)
                                                <option>{!! $cat['title'] !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="rating-filter">
                                        <div class="item clearfix">
                                            <label class="radio-container">
                                                <input type="radio" name="radio">
                                                <span class="checkmark"></span>
                                            </label>
                                            <div class="content clearfix">
                                                <div class="star-block">
                                                    @component(getThemeViewName('components.rating_stars'), ['score'=>0])
                                                    @endcomponent
                                                </div>
                                                <div class="stats">
                                                    <div class="bg">
                                                        <div class="prb" style="width: {!! random_int(0,100) !!}%"></div>
                                                    </div>
                                                </div>
                                                <div class="number">{!! random_int(10, 50) !!}</div>
                                            </div>
                                        </div>
                                        <div class="item clearfix">
                                            <label class="radio-container">
                                                <input type="radio" name="radio">
                                                <span class="checkmark"></span>
                                            </label>
                                            <div class="content clearfix">
                                                <div class="star-block">
                                                    @component(getThemeViewName('components.rating_stars'), ['score'=>1])
                                                    @endcomponent
                                                </div>
                                                <div class="stats">
                                                    <div class="bg">
                                                        <div class="prb" style="width: {!! random_int(0,100) !!}%"></div>
                                                    </div>
                                                </div>
                                                <div class="number">{!! random_int(10, 50) !!}</div>
                                            </div>
                                        </div>
                                        <div class="item clearfix">
                                            <label class="radio-container">
                                                <input type="radio" name="radio">
                                                <span class="checkmark"></span>
                                            </label>
                                            <div class="content clearfix">
                                                <div class="star-block">
                                                    @component(getThemeViewName('components.rating_stars'), ['score'=>2])
                                                    @endcomponent
                                                </div>
                                                <div class="stats">
                                                    <div class="bg">
                                                        <div class="prb" style="width: {!! random_int(0,100) !!}%"></div>
                                                    </div>
                                                </div>
                                                <div class="number">{!! random_int(10, 50) !!}</div>
                                            </div>
                                        </div>
                                        <div class="item clearfix">
                                            <label class="radio-container">
                                                <input type="radio" name="radio">
                                                <span class="checkmark"></span>
                                            </label>
                                            <div class="content clearfix">
                                                <div class="star-block">
                                                    @component(getThemeViewName('components.rating_stars'), ['score'=>3])
                                                    @endcomponent
                                                </div>
                                                <div class="stats">
                                                    <div class="bg">
                                                        <div class="prb" style="width: {!! random_int(0,100) !!}%"></div>
                                                    </div>
                                                </div>
                                                <div class="number">{!! random_int(10, 50) !!}</div>
                                            </div>
                                        </div>
                                        <div class="item clearfix">
                                            <label class="radio-container">
                                                <input type="radio" name="radio">
                                                <span class="checkmark"></span>
                                            </label>
                                            <div class="content clearfix">
                                                <div class="star-block">
                                                    @component(getThemeViewName('components.rating_stars'), ['score'=>4])
                                                    @endcomponent
                                                </div>
                                                <div class="stats">
                                                    <div class="bg">
                                                        <div class="prb" style="width: {!! random_int(0,100) !!}%"></div>
                                                    </div>
                                                </div>
                                                <div class="number">{!! random_int(30, 50) !!}</div>
                                            </div>
                                        </div>
                                        <div class="item clearfix">
                                            <label class="radio-container">
                                                <input type="radio" name="radio">
                                                <span class="checkmark"></span>
                                            </label>
                                            <div class="content clearfix">
                                                <div class="star-block">
                                                    @component(getThemeViewName('components.rating_stars'), ['score'=>5])
                                                    @endcomponent
                                                </div>
                                                <div class="stats">
                                                    <div class="bg">
                                                        <div class="prb" style="width: {!! random_int(0,100) !!}%"></div>
                                                    </div>
                                                </div>
                                                <div class="number">{!! random_int(10, 50) !!}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="write-review-button">
                                <button type="button">
                                    <i class="fa fa-edit"></i> Đánh giá dịch vụ
                                </button>
                            </div>
                            <div class="featured-salon">
                                <img src="{!! getThemeAssetUrl('img/featured_salon.png') !!}">
                                <div class="text">
                                    Cửa hành được nhiều<br>
                                    đánh giá tốt nhất tuần qua
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="related-salon">
    <div class="container">
        <div class="block-title">Có thể bạn quan tâm</div>
        <div class="salon-list owl-carousel">
            @php
            $items = [
                [
                    'name' => 'Viện tóc Envy',
                    'location' => 'Số 46 Trung Kính, Cầu Giấy, Hà Nội',
                    'rating' => 4.5,
                    'rating_count' => rand(10, 50),
                    'img' => getThemeAssetUrl('img/rsalon1.png')
                ],
                [
                    'name' => 'Salon Tóc Tây',
                    'location' => 'Số 37A, Trần Phú, Ninh Kiều, Cần Thơ',
                    'rating' => 3.5,
                    'rating_count' => rand(10, 50),
                    'img' => getThemeAssetUrl('img/rsalon2.png')
                ],
                [
                    'name' => 'Salon Tấn Can',
                    'location' => 'Số 56, Nguyễn Việt Hồng, Ninh Kiều, Cần Thơ',
                    'rating' => '5.0',
                    'rating_count' => rand(10, 50),
                    'img' => getThemeAssetUrl('img/rsalon3.png')
                ],
                [
                    'name' => 'Hớt Tóc Thanh Nữ',
                    'location' => 'Số 65, Lý Tự Trọng, Phường 2, Trà Vinh',
                    'rating' => 2.5,
                    'rating_count' => rand(10, 50),
                    'img' => getThemeAssetUrl('img/rsalon1.png')
                ],
            ];
            @endphp
            @foreach($items as $item)
                <a href="#" class="item d-block">
                    <div class="img">
                        <img src="{!! $item['img'] !!}">
                    </div>
                    <div class="info">
                        <div class="title">{!! $item['name'] !!}</div>
                        <div class="location"><i class="fa fa-map-marker" aria-hidden="true"></i> {!! $item['location'] !!}</div>
                        <div class="rating">
                            <div class="number">{!! $item['rating'] !!}</div>
                            <div class="stars">
                                @component(getThemeViewName('components.rating_stars'), ['score' => $item['rating']])
                                @endcomponent
                            </div>
                            <div class="total">({!! $item['rating_count'] !!})</div>
                        </div>
                    </div>
                </a>
            @endforeach
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
        $('.review-filter select').select2({
            width: '100%'
        });
        $('.salon-gallery .img-list').owlCarousel({
            margin:0,
            loop:true,
            items:1,
            dots: 0,
            nav: 1,
            navText: [
                '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ],
            responsive:{
                768: {
                    autoWidth:true,
                    items:3,
                }
            }
        });
        $('.stylist-list').owlCarousel({
            margin:0,
            loop:false,
            items:2,
            dots: 0,
            nav: 1,
            navText: [
                '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ],
            responsive:{
                992:{
                    items:9,
                },
                768:{
                    items:5,
                },
                575:{
                    items:3,
                }
            }
        });
        $('.brand-list').owlCarousel({
            margin:15,
            loop:false,
            items:2,
            dots: 0,
            nav: 1,
            navText: [
                '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ],
            responsive:{
                992:{
                    items:7,
                },
                768:{
                    items:5,
                },
                575:{
                    items:3,
                }
            }
        });

        $('.showcase').owlCarousel({
            margin:30,
            loop:true,
            items:1,
            dots: 0,
            nav: 1,
            navText: [
                '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ],
            responsive:{
                768: {
                    autoWidth:true,
                    items:3,
                },
            }
        });

        $('.related-salon .salon-list').owlCarousel({
            margin:30,
            loop:0,
            items:1,
            dots: 0,
            nav: 1,
            navText: [
                '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ],
            responsive:{
                768: {
                    items:3,
                }
            }
        });
        $('#local-nav').sticky();
        $('body').scrollspy({ target: '#local-nav', offset: 100 });
        $('#salon-share').popover({
            content: function () {
                return '<div class="salon-share-icon-list"><a href="#" style="background-color: #3F51B5" target="_blank"><i class="fa fa-facebook"></i></a>' +
                    '<a href="#" style="background-color: #a94442" target="_blank"><i class="fa fa-google-plus"></i></a>' +
                        '<a href="#" style="background-color: #00BCD4" target="_blank"><i class="fa fa-twitter"></i></a></div>'
            },
            placement: 'bottom',
            title: 'Chia sẻ thông tin salon này',
            trigger: 'focus',
            html: true
        });
    });
</script>
<script type="text/javascript">
    swal("Hello Tester", "Trang này đang xây dựng giao diện chỉ là html tĩnh", "warning");
</script>
@endpush