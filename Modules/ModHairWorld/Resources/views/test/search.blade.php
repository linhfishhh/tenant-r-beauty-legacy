@enqueueCSS('search-page', getThemeAssetUrl('libs/styles/search.css'), 'master-page')
@enqueueCSS('select2', getThemeAssetUrl('libs/select2/css/select2.min.css'), 'bootstrap')
@enqueueJS('select2', getThemeAssetUrl('libs/select2/js/select2.full.min.js'), JS_LOCATION_HEAD, 'jquery')
@extends(getThemeViewName('master'))
@section('page_content')
    @php
    $dark_theme = 1;
    @endphp
    @include(getThemeViewName('includes.service_quickview'))
    <div class="search-page-header common-page-header" style="background-image: url('{!! getThemeAssetUrl('img/search_bg.jpg') !!}')">
        <div class="container">
            <div class="wrapper">
                <div class="inner">
                    <div class="title text-center">
                        2.500 Salon trên toàn quốc
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="filter-section">
                        <div class="map-filter">
                            <div class="map">
                                <img style="width: 100%" src="{!! getThemeAssetUrl('img/map1.jpg') !!}">
                            </div>
                            <div class="map-open">
                                <a href="#" id="map-view-link">
                                    <img src="{!! getThemeAssetUrl('img/map_open_icon.png') !!}">
                                    <span>Mở kết quả trên bản đồ</span>
                                </a>
                            </div>
                        </div>
                        <div class="filter-block">
                            <div class="block-title">
                                Vị trí
                            </div>
                            <div class="block-content">
                                <div class="location-search">
                                    <div class="icon">
                                        <img src="{!! getThemeAssetUrl('img/marker.png') !!}">
                                    </div>
                                    <input placeholder="Nhập địa điểm">
                                    <div class="clear">
                                        <i class="fa fa-close"></i>
                                    </div>
                                </div>
                                <div class="sub-block distance-filter">
                                    <div class="sub-block-title">Khoảng cách</div>
                                    @component(getThemeViewName('components.slider'), [
                                    'id' => 'distance-filter',
                                    'unit' => 'Km',
                                    'attributes' => 'type="range" min="1" max="50" step="1" value="1"'
                                    ]
                                    )
                                    @endcomponent
                                </div>
                            </div>
                        </div>
                        <div class="filter-block">
                            <div class="block-title">
                                Bộ lọc
                            </div>
                            <div class="block-content">
                                <div class="basic-fillter">
                                    @component(getThemeViewName('components.checkbox_list'), [
                                    'items' => [
                                        [
                                            'title' => 'Đang khuyến mãi',
                                            'name' => 'test',
                                            'value' => 'test'
                                        ],
                                        [
                                            'title' => 'Combo trọn gói',
                                            'name' => 'test',
                                            'value' => 'test'
                                        ],
                                    ]
                                ])
                                    @endcomponent
                                </div>
                                <div class="sub-block rating-filter">
                                    <div class="sub-block-title">Đánh giá</div>
                                    <select>
                                        <option>Tất cả</option>
                                        <option>1 Sao</option>
                                        <option>2 Sao</option>
                                        <option>3 Sao</option>
                                        <option>4 Sao</option>
                                        <option>5 Sao</option>
                                    </select>
                                </div>
                                <div class="sub-block price-filter">
                                    <div class="sub-block-title">Giá dịch vụ</div>
                                    @component(getThemeViewName('components.slider'), [
                                    'id' => 'price-filter',
                                    'unit' => 'K',
                                    'attributes' => 'type="range" min="0" max="50000" step="1" value="0"'
                                    ]
                                    )
                                    @endcomponent
                                </div>
                            </div>
                        </div>
                        <div class="filter-block">
                            <div class="block-title">
                                Khu Vực
                            </div>
                            <div class="block-content">
                                <div class="area-filter">
                                    @component(getThemeViewName('components.checkbox_list'), [
                                        'items'=> [
                                            [
                                                'title' => 'Ba Đình',
                                                'number' => random_int(10, 50),
                                                'name' => 'test',
                                                'value' => 'test'
                                            ],
                                            [
                                                'title' => 'Cầu Giấy',
                                                'number' => random_int(10, 50),
                                                'name' => 'test',
                                                'value' => 'test'
                                            ],
                                            [
                                                'title' => 'Đống Đa',
                                                'number' => random_int(10, 50),
                                                'name' => 'test',
                                                'value' => 'test'
                                            ],
                                            [
                                                'title' => 'Hoàng Mai',
                                                'number' => random_int(10, 50),
                                                'name' => 'test',
                                                'value' => 'test'
                                            ],
                                            [
                                                'title' => 'Hoàng Kiếm',
                                                'number' => random_int(10, 50),
                                                'name' => 'test',
                                                'value' => 'test'
                                            ],
                                        ]
                                    ])
                                    @endcomponent
                                </div>
                            </div>
                        </div>
                        <div class="filter-block">
                            <div class="block-title">
                                Dịch vụ
                            </div>
                            <div class="block-content">
                                <div class="cat-filter">
                                    @component(getThemeViewName('components.checkbox_list'), [
                                        'items' => [
                                            [
                                                'title' => 'Hớt tóc nam',
                                                'number' => random_int(10, 50),
                                                'name' => 'test',
                                                'value' => 'test'
                                            ],
                                            [
                                                'title' => 'Hớt tóc nữ',
                                                'number' => random_int(10, 50),
                                                'name' => 'test',
                                                'value' => 'test'
                                            ],
                                            [
                                                'title' => 'Nhôm tóc',
                                                'number' => random_int(10, 50),
                                                'name' => 'test',
                                                'value' => 'test'
                                            ],
                                            [
                                                'title' => 'Hớt duỗi tóc',
                                                'number' => random_int(10, 50),
                                                'name' => 'test',
                                                'value' => 'test'
                                            ],
                                            [
                                                'title' => 'Nối tóc',
                                                'number' => random_int(10, 50),
                                                'name' => 'test',
                                                'value' => 'test'
                                            ],
                                            [
                                                'title' => 'Tạo kiểu tóc',
                                                'number' => random_int(10, 50),
                                                'name' => 'test',
                                                'value' => 'test'
                                            ],
                                        ]
                                    ])
                                    @endcomponent
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="salon-list-result">
                        @php
                        $items = [
                            [
                                'img' => getThemeAssetUrl('img/salon1.jpg'),
                                'title' => 'I Hair Salon Bà Triệu',
                                'location' => '10 Ngõ Bà Triệu, Bà Triệu, P. Lê Đại Hành, Q. Hoàn Kiếm, Hà Nội',
                                'rating' => '4.5',
                                'rating_count' => random_int(10, 50),
                                'services' => [
                                    [
                                        'title' => 'Cắt tóc nữ',
                                        'time' => [
                                            30,
                                            45
                                        ],
                                        'price' => [
                                            95,
                                            110
                                        ]
                                    ],
                                    [
                                        'title' => 'Gội xả',
                                        'time' => [
                                            45,
                                            60
                                        ],
                                        'price' => [
                                            55,
                                            55
                                        ]
                                    ],
                                    [
                                        'title' => 'Nhộm tóc',
                                        'time' => [
                                            45,
                                            45
                                        ],
                                        'price' => [
                                            100,
                                            150
                                        ]
                                    ],
                                ]
                            ],
                            [
                                'img' => getThemeAssetUrl('img/salon2.jpg'),
                                'title' => 'Thanh Sài Gòn Hair Salon',
                                'location' => 'Hàng Nón, Q. Hoàn Kiếm, Hà Nội',
                                'rating' => '4.5',
                                'rating_count' => random_int(10, 50),
                                'services' => [
                                    [
                                        'title' => 'Cắt tóc nữ',
                                        'time' => [
                                            30,
                                            45
                                        ],
                                        'price' => [
                                            95,
                                            110
                                        ]
                                    ],
                                    [
                                        'title' => 'Gội xả',
                                        'time' => [
                                            45,
                                            60
                                        ],
                                        'price' => [
                                            55,
                                            55
                                        ]
                                    ],
                                    [
                                        'title' => 'Nhộm tóc',
                                        'time' => [
                                            45,
                                            45
                                        ],
                                        'price' => [
                                            100,
                                            150
                                        ]
                                    ],
                                ]
                            ],
                            [
                                'img' => getThemeAssetUrl('img/salon3.jpg'),
                                'title' => 'Hair System',
                                'location' => 'Lò Sũ, Q. Hoàn Kiếm, Hà Nội',
                                'rating' => '4.5',
                                'rating_count' => random_int(10, 50),
                                'services' => [
                                    [
                                        'title' => 'Cắt tóc nữ',
                                        'time' => [
                                            30,
                                            45
                                        ],
                                        'price' => [
                                            95,
                                            110
                                        ]
                                    ],
                                    [
                                        'title' => 'Gội xả',
                                        'time' => [
                                            45,
                                            60
                                        ],
                                        'price' => [
                                            55,
                                            55
                                        ]
                                    ],
                                    [
                                        'title' => 'Nhộm tóc',
                                        'time' => [
                                            45,
                                            45
                                        ],
                                        'price' => [
                                            100,
                                            150
                                        ]
                                    ],
                                ]
                            ],
                            [
                                'img' => getThemeAssetUrl('img/salon4.jpg'),
                                'title' => 'Salon Quốc Việt',
                                'location' => 'Trần Quốc Toản, Q.Hoàn Kiếm, Hà Nội',
                                'rating' => '4.5',
                                'rating_count' => random_int(10, 50),
                                'services' => [
                                    [
                                        'title' => 'Cắt tóc nữ',
                                        'time' => [
                                            30,
                                            45
                                        ],
                                        'price' => [
                                            95,
                                            110
                                        ]
                                    ],
                                    [
                                        'title' => 'Gội xả',
                                        'time' => [
                                            45,
                                            60
                                        ],
                                        'price' => [
                                            55,
                                            55
                                        ]
                                    ],
                                    [
                                        'title' => 'Nhộm tóc',
                                        'time' => [
                                            45,
                                            45
                                        ],
                                        'price' => [
                                            100,
                                            150
                                        ]
                                    ],
                                ]
                            ],
                            [
                                'img' => getThemeAssetUrl('img/salon5.jpg'),
                                'title' => 'Nam Hair Salon',
                                'location' => '17 Hàng Mành, Q. Hoàn Kiếm, Hà Nội',
                                'rating' => '4.5',
                                'rating_count' => random_int(10, 50),
                                'services' => [
                                    [
                                        'title' => 'Cắt tóc nữ',
                                        'time' => [
                                            30,
                                            45
                                        ],
                                        'price' => [
                                            95,
                                            110
                                        ]
                                    ],
                                    [
                                        'title' => 'Gội xả',
                                        'time' => [
                                            45,
                                            60
                                        ],
                                        'price' => [
                                            55,
                                            55
                                        ]
                                    ],
                                    [
                                        'title' => 'Nhộm tóc',
                                        'time' => [
                                            45,
                                            45
                                        ],
                                        'price' => [
                                            100,
                                            150
                                        ]
                                    ],
                                ]
                            ],
                        ];
                        @endphp
                        @foreach($items as $item)
                            <div class="salon">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="img">
                                            <a href="{!! route('test.salon.post') !!}">
                                                <img src="{!! $item['img'] !!}">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="title">
                                            <a href="{!! route('test.salon.post') !!}">{!! $item['title'] !!}</a>
                                        </div>
                                        <div class="location">
                                            <i class="fa fa-map-marker"></i>
                                            <span>{!! $item['location'] !!}</span>
                                        </div>
                                        <div class="rating">
                                            <div class="number">{!! $item['rating'] !!}</div>
                                            <div class="stars">
                                                @component(getThemeViewName('components.rating_stars'), ['score'=>$item['rating']])
                                                @endcomponent
                                            </div>
                                            <div class="total">({!! $item['rating_count'] !!})</div>
                                        </div>
                                        <div class="services">
                                            @foreach($item['services'] as $service)
                                                <div class="service">
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <div class="service-title">
                                                                <a href="#" onclick="showServiceQuickView();return false;">{!! $service['title'] !!}</a>
                                                            </div>
                                                            <div class="service-time">
                                                                {!! $service['time'][0] !!} phút @if(isset($service['time'][1]) && ($service['time'][1] != $service['time'][0])) - {!! $service['time'][1] !!} phút @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="service-price">
                                                                @if(isset($service['price'][1]) && $service['price'][1] != $service['price'][0])
                                                                    <span class="old">{!! $service['price'][1] !!}K</span>
                                                                @endif
                                                                <span class="current">{!! $service['price'][0] !!}K</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-bread-cumber">
        <div class="container">
            <a href="#">Trang chủ</a> / <a href="#">Xu hướng tóc</a>
        </div>
    </div>
    <div class="modal fade" id="modal-map-view" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tìm kiếm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="map-view">
                        <div class="map" style="background: url('{!! getThemeAssetUrl('img/map_view.jpg') !!}'); background-position: center; background-size: cover">

                        </div>
                        <div class="selected-salon">
                            <div class="wrapper">
                                <div class="salon-list-result">
                                    @php
                                    $item = [
                                        'img' => getThemeAssetUrl('img/salon5.jpg'),
                                        'title' => 'Nam Hair Salon',
                                        'location' => '17 Hàng Mành, Q. Hoàn Kiếm, Hà Nội',
                                        'rating' => '4.5',
                                        'rating_count' => random_int(10, 50),
                                        'services' => [
                                            [
                                                'title' => 'Cắt tóc nữ',
                                                'time' => [
                                                    30,
                                                    45
                                                ],
                                                'price' => [
                                                    95,
                                                    110
                                                ]
                                            ],
                                            [
                                                'title' => 'Gội xả',
                                                'time' => [
                                                    45,
                                                    60
                                                ],
                                                'price' => [
                                                    55,
                                                    55
                                                ]
                                            ],

                                        ]
                                    ];
                                    @endphp
                                    <div class="salon">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="img">
                                                    <a href="{!! route('test.salon.post') !!}" style="background-image: url('{!! $item['img'] !!}')">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="right-block">
                                                    <div class="title">
                                                        <a href="{!! route('test.salon.post') !!}">{!! $item['title'] !!}</a>
                                                    </div>
                                                    <div class="location">
                                                        <i class="fa fa-map-marker"></i>
                                                        <span>{!! $item['location'] !!}</span>
                                                    </div>
                                                    <div class="rating">
                                                        <div class="number">{!! $item['rating'] !!}</div>
                                                        <div class="stars">
                                                            @component(getThemeViewName('components.rating_stars'), ['score'=>$item['rating']])
                                                            @endcomponent
                                                        </div>
                                                        <div class="total">({!! $item['rating_count'] !!})</div>
                                                    </div>
                                                    <div class="services">
                                                        @foreach($item['services'] as $service)
                                                            <div class="service">
                                                                <div class="row">
                                                                    <div class="col-7">
                                                                        <div class="service-title">
                                                                            <a href="#" onclick="showServiceQuickView();return false;">{!! $service['title'] !!}</a>
                                                                        </div>
                                                                        <div class="service-time">
                                                                            {!! $service['time'][0] !!} phút @if(isset($service['time'][1]) && ($service['time'][1] != $service['time'][0])) - {!! $service['time'][1] !!} phút @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-5">
                                                                        <div class="service-price">
                                                                            @if(isset($service['price'][1]) && $service['price'][1] != $service['price'][0])
                                                                                <span class="old">{!! $service['price'][1] !!}K</span>
                                                                            @endif
                                                                            <span class="current">{!! $service['price'][0] !!}K</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="view">
                                                        <a href="{!! route('test.salon.post') !!}">Xem nhiều hơn</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="nav-block clearfix">
                                    <div class="prev"><i class="fa fa-chevron-left"></i></div>
                                    <div class="next"><i class="fa fa-chevron-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('page_footer_js')
    <script type="text/javascript">
        swal("Hello Tester", "Trang này đang xây dựng giao diện chỉ là html tĩnh", "warning");
    </script>
    <script type="text/javascript">
        $(function () {
            $modal_map_view = $('#modal-map-view').modal({
                show: false
            });
            $('#map-view-link').click(function () {
                $modal_map_view.modal('show');
                return false;
            });
            $('.rating-filter select').select2({
                width: '100%',
                minimumResultsForSearch: Infinity
            });
        });
    </script>
@endpush