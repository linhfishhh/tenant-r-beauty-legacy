@unique('global_choose_service_options')
<div class="modal" tabindex="-1" role="dialog" id="choose-service-options-modal">
    <div class="modal-dialog" role="document">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>Chọn một trong những tuỳ chọn sau:</div>
                <div class="service-options">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ĐÓNG</button>
                <button type="submit" class="btn btn-primary">ĐẶT CHỖ</button>
            </div>
        </form>
    </div>
</div>
<template id="service-option-tpl">
    <div class="option">
        <label class="radio-container payment-type">
            <input {checked} value="{value}" type="radio" name="option">
            <span class="checkmark"></span>
            <div class="title">
                <span class="service-name">{title}</span>
                {prices}
            </div>
        </label>
    </div>
</template>
<script type="text/javascript">
    var $chooseOptionModal = $('#choose-service-options-modal').modal({
        show: false
    });

    $chooseOptionModal.find('form').submit(function () {
        var action = $(this).data('action');
        var data = $(this).serializeObject();
        //console.log(action);
        $chooseOptionModal.modal('hide');
        action(data.option);
        return false;
    });

    function addCartChooseOptions(service_id, after){
        var id = $(this).data('id');
        var url = '{!! route('frontend.service.options', ['service'=>'???']) !!}';
        url = url.replace('???', service_id);
        $.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            beforeSend: function () {
            },
            complete: function () {
            },
            success: function (json) {
                if(json){
                    $chooseOptionModal.find('.modal-header').html(json.service.name);
                    var options = json.options;
                    var html = '';
                    $(options).each(function (index, option) {
                        var checked = index === 0?'checked':'';
                        html += $('#service-option-tpl').html();
                        html = html.replace('{checked}', checked);
                        html = html.replace('{value}', option.id);
                        html = html.replace('{title}', option.name);
                        var prices = '';
                        if(option.price_org === option.price_final){
                            prices += '<span class="price-final">'+option.price_final_html+'</span>';
                        }
                        else{
                            prices += '<span class="price-org">'+option.price_org_html+'</span>'+
                                '<span class="price-final">'+option.price_final_html+'</span>';
                        }
                        html = html.replace('{prices}', prices);
                    });
                    $chooseOptionModal.find('.service-options').html(html);
                    $chooseOptionModal.find('form').data('action', after);
                    $chooseOptionModal.modal('show');
                }
                else{
                    after(false);
                }
            }
        });
    }
</script>
@endunique