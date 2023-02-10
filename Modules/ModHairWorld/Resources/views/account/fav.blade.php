@enqueueCSS('fancybox', getThemeAssetUrl('libs/fancybox/jquery.fancybox.min.css'), 'master-page')
@enqueueJS('fancybox', getThemeAssetUrl('libs/fancybox/jquery.fancybox.min.js'), JS_LOCATION_HEAD, 'jquery')
@enqueueCSS('fav-page', getThemeAssetUrl('libs/styles/fav.css'), 'account-menu')
@extends(getThemeViewName('account.master'))
@section('content')
    <div class="content-box">
        <div class="content-title">Danh sách yêu thích</div>
        <div class="content-body">
            <div class="page-nav">
                <div class="item {!! $salon?'active':'' !!}">
                    <a href="{!! route('frontend.account.fav_Salon') !!}">Salon</a>
                </div>
                <div class="item {!! !$salon?'active':'' !!}">
                    <a href="{!! route('frontend.account.fav_showcase') !!}">Tác phẩm</a>
                </div>
            </div>
            <div class="fav-content">
                @if($salon)
                    <div class="fav-salon-list">


                    </div>
                    <div class="load-more d-none">
                        <i class="fa fa-caret-down"></i> Tải thêm
                    </div>
                    @push('page_footer_js')
                        <script type="text/javascript">
                            $('.fav-content .load-more').click(function () {
                                var next = $(this).data('next');
                                loadFavSalon(next);
                            });
                            $('.fav-content').on('click', '.salon .remove a', function () {
                                var id = $(this).data('id');
                                var node = $(this);
                                $.ajax({
                                    url: '{!! route('frontend.account.fav_Salon.remove') !!}',
                                    type: 'post',
                                    dataType: 'json',
                                    data: {
                                        id: id,
                                        _method: 'delete'
                                    },
                                    beforeSend: function () {
                                        $('.fav-content').addClass('loading');
                                    },
                                    complete: function () {
                                        $('.fav-content').removeClass('loading');
                                    },
                                    success: function (json) {
                                        if(json){
                                            $(node).parents('.salon').addClass('deleted');
                                        }
                                    }
                                });
                                return false;
                            });
                            function loadFavSalon($from) {
                                $.ajax({
                                    url: '{!! route('frontend.account.fav_Salon.list') !!}',
                                    type:'get',
                                    dataType: 'json',
                                    data: {
                                        'from': $from
                                    },
                                    beforeSend: function () {
                                        $('.fav-content').addClass('loading');
                                    },
                                    complete: function () {
                                        $('.fav-content').removeClass('loading');
                                    },
                                    success: function (json) {
                                        if($from == -1){
                                            $('.fav-content .fav-salon-list').html('');
                                        }
                                        var html = json.html;
                                        $('.fav-content .fav-salon-list').append(html);
                                        if(json.last){
                                            $('.fav-content .load-more').addClass('d-none');
                                        }
                                        else{
                                            $('.fav-content .load-more').removeClass('d-none');
                                            $('.fav-content .load-more').data('next', json.next);
                                        }
                                    }
                                });
                            }
                            loadFavSalon(-1);
                        </script>
                    @endpush
                @else
                    <div class="fav-showcase">
                        <div class="row showcase-list">

                        </div>
                        <div class="load-more d-none">
                            <i class="fa fa-caret-down"></i> Tải thêm
                        </div>
                    </div>
                    @push('page_footer_js')
                        <script type="text/javascript">
                            $('.fav-content .load-more').click(function () {
                                var next = $(this).data('next');
                                loadFavShowcase(next);
                            });
                            $('.fav-content').on('click', '.item .remove', function () {
                                var id = $(this).data('id');
                                var node = $(this);
                                $.ajax({
                                    url: '{!! route('frontend.account.fav_showcase.remove') !!}',
                                    type: 'post',
                                    dataType: 'json',
                                    data: {
                                        id: id,
                                        _method: 'delete'
                                    },
                                    beforeSend: function () {
                                        $('.fav-content').addClass('loading');
                                    },
                                    complete: function () {
                                        $('.fav-content').removeClass('loading');
                                    },
                                    success: function (json) {
                                        if(json){
                                            $(node).parents('.item').addClass('deleted');
                                        }
                                    }
                                });
                                return false;
                            });
                            function loadFavShowcase($from) {
                                $.ajax({
                                    url: '{!! route('frontend.account.fav_showcase.list') !!}',
                                    type:'get',
                                    dataType: 'json',
                                    data: {
                                        'from': $from
                                    },
                                    beforeSend: function () {
                                        $('.fav-content').addClass('loading');
                                    },
                                    complete: function () {
                                        $('.fav-content').removeClass('loading');
                                    },
                                    success: function (json) {
                                        if($from == -1){
                                            $('.fav-content .showcase-list').html('');
                                        }
                                        var html = json.html;
                                        $('.fav-content .showcase-list').append(html);
                                        if(json.last){
                                            $('.fav-content .load-more').addClass('d-none');
                                        }
                                        else{
                                            $('.fav-content .load-more').removeClass('d-none');
                                            $('.fav-content .load-more').data('next', json.next);
                                        }
                                    }
                                });
                            }
                            loadFavShowcase(-1);
                        </script>
                    @endpush
                @endif
            </div>
        </div>
    </div>
@endsection