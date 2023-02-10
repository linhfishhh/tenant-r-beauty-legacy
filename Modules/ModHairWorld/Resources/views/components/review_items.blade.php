<div class="review-list-block" id="{!! $id !!}">
    <div class="review-list">
    </div>
    <div class="load-more mb-4">
        <a href="#">Xem nhiều hơn</a>
    </div>
</div>

@unique('review_list')
    @push('page_footer_js')
        <script type="text/javascript">
            $('body').on('click', '.review-list-block .review-list .like', function () {
                @auth
                var node = $(this);
                var id = $(this).parents('.review').data('id');
                var url = '{!! route('frontend.salon.review.like', ['review' => '???review_id???']) !!}';
                url = url.replace('???review_id???', id);
                $.ajax({
                    url: url,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function () {
                        $(node).addClass('loading');
                    },
                    complete: function () {
                        $(node).removeClass('loading');
                    },
                    success: function (json) {
                        $(node).find('.count-block').html(json.count);
                        if(json.liked){
                            $(node).addClass('liked');
                        }
                        else{
                            $(node).removeClass('liked');
                        }
                    }
                });
                @else
                swal("Đăng nhập", "Vui lòng đăng nhập!", "warning")
                @endif
            });
        </script>
    @endpush
@endunique