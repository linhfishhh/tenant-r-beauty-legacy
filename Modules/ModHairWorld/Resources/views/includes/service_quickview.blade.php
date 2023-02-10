@unique('service-quick-view')
@enqueueCSS('service-quick-view', getThemeAssetUrl('libs/styles/service-quickview.css'), 'master-page')
@php
    $can_book = isset($can_book)?$can_book:false;
@endphp
<div class="modal fade" id="modal-salon-service-detail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title">Chi tiết dịch vụ
                </span>
            </div>
            <div class="modal-body">
                <div class="service-detail-wrapper">
                    <div class="service-detail-inner">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary wa-close" data-dismiss="modal">Đóng</button>
                <button class="btn btn-secondary wa-book">Đặt ngay</button>
            </div>
        </div>
    </div>
</div>
@push('page_footer_js')
    <script type="text/javascript">
        var $modal_service_quick_view = $('#modal-salon-service-detail').modal({
            show: false
        });
        $modal_service_quick_view.on('shown.bs.modal', function () {
            var l = $('.modal-backdrop').length;
            $($('.modal-backdrop')[l-1]).addClass('quick-view');
        });
        $modal_service_quick_view.on('hidden.bs.modal', function () {
            $('.modal:visible').length && $(document.body).addClass('modal-open');
        });
        $modal_service_quick_view.on('click', '.review-list-block .load-more', function () {
            var page = $(this).data('page');
            var service_id = $(this).data('service-id');
            var salon_id = $(this).data('salon-id');
            loadReviewQuickView(page + 1, salon_id, service_id);
            return false;
        });

        $modal_service_quick_view.find('.wa-book').click(function(){
            @auth
            var salon = $modal_service_quick_view.data('salon');
            if(!salon.open){
                $modal_service_quick_view.modal('hide');
                swal("Salon ngoại tuyến!", "Salon hiện tại đã ngoại tuyến không tiếp nhận đơn đặt chỗ mới, nếu bạn muốn đặt chỗ xin vui lòng quay lại sau nhé!", "warning")
                return false;
            }
            var service_id = $modal_service_quick_view.data('service_id');
            $modal_service_quick_view.modal('hide');
            addCartChooseOptions(service_id, function (option_id) {
                var id = service_id;
                var url = '{!! route('frontend.service.add_to_cart', ['service'=>'???']) !!}';
                var node = $(this);
                url = url.replace('???', id);
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        overwrite: true,
                        option_id: option_id
                    },
                    beforeSend: function () {
                        node.addClass('loading');
                    },
                    complete: function () {
                        node.removeClass('loading');
                    },
                    success: function (json) {
                        //console.log(json);
                        window.location = '{!! route('frontend.cart.1') !!}';
                    }
                });
            });
            @else
                $modal_service_quick_view.modal('hide');
                $('.show-login-form-link').click();
                return false;
            @endif
        });

        function loadReviewQuickView(page, salon_id, service_id) {
            var rurl = '{!! route('frontend.salon.review.list', ['salon'=>'???']) !!}';
            rurl = rurl.replace('???', salon_id);
            $.ajax({
                url: rurl,
                type: 'get',
                dataType: 'json',
                data: {
                    service_id: service_id,
                    rating: -1,
                    page: page
                },
                beforeSend: function () {
                    $modal_service_quick_view.addClass('loading');
                },
                complete: function () {
                    $modal_service_quick_view.removeClass('loading');
                },
                success: function (rjson) {
                    wa_review_items_component_load('service_quick_view_review_box', rjson);
                    $modal_service_quick_view.find('.review-list-block .load-more').data('salon-id', salon_id);
                    $modal_service_quick_view.find('.review-list-block .load-more').data('service-id', service_id);
                }
            });
        }
        function showServiceQuickView(service_id) {
            $modal_service_quick_view.find('.service-detail-inner').html('');
            $modal_service_quick_view.modal('show');
            var url = '{!! route('frontend.service.detail', ['service' => '???']) !!}';
            url = url.replace('???', service_id);
            $.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                beforeSend: function(){
                    $modal_service_quick_view.addClass('loading');
                    $modal_service_quick_view.find('.modal-title').html('Đang tải...');
                },
                complete: function(){

                },
                success: function (json) {
                    //console.log(json.salon);
                    $modal_service_quick_view.find('.service-detail-inner').append(json.html);
                    $modal_service_quick_view.find('.modal-title').html(json.salon.name);
                    if(json.salon.open){
                        $modal_service_quick_view.find('.wa-book').show();
                    }
                    else{
                        $modal_service_quick_view.find('.wa-book').hide();
                    }
                    $modal_service_quick_view.data('salon', json.salon);
                    $modal_service_quick_view.data('service_id', service_id);
                    loadReviewQuickView(1, json.salon.id, service_id);
                }
            });
        }
    </script>
@endpush
@endunique