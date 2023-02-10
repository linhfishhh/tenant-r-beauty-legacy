@enqueueJSByID(config('view.ui.files.js.moment.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.daterangepicker.id'), JS_LOCATION_DEFAULT, 'bootstrap')
<template id="{{$field->getHtmlTemplateID()}}">
    <div class="input-group">
        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
        <input spellcheck="false" name="{name}" type="text" class="form-control" value="{value}">
    </div>
</template>
@push('page_footer_js')
    <script type="text/javascript">
        function {!! $field->getJSRenderFunctionName() !!}(append_node, field_name, field_value, configs) {
            var node = $('#{{$field->getHtmlTemplateID()}}').html();
            node = node.replace(/{value}/g, field_value);
            node = node.replace(/{name}/g, field_name);
            node = $(node).appendTo(append_node);
            var params = {
                singleDatePicker: true,
                timePicker: true,
                timePicker24Hour: true,
                timePickerSeconds: true,
                locale: {
                    format: 'YYYY-MM-DD HH:mm:ss',
                    applyLabel: '{{__('Đồng ý')}}',
                    cancelLabel: '{{__('Hủy chọn')}}',
                    startLabel: '{{__('Bắt đầu')}}',
                    endLabel: '{{__('Kết thúc')}}',
                    customRangeLabel: '{{__('Chọn khoản thời gian')}}',
                    daysOfWeek: ['{{__('CN')}}', '{{__('T2')}}', '{{__('T3')}}', '{{__('T4')}}', '{{__('T5')}}', '{{__('T6')}}','{{__('T7')}}'],
                    monthNames: ['{{__('THÁNG 01')}} ', '{{__('THÁNG 02')}} ', '{{__('THÁNG 03')}} ', '{{__('THÁNG 04')}} ', '{{__('THÁNG 05')}} ', '{{__('THÁNG 06')}} ', '{{__('THÁNG 07')}} ', '{{__('THÁNG 08')}} ', '{{__('THÁNG 09')}} ', '{{__('THÁNG 10')}} ', '{{__('THÁNG 11')}} ', '{{__('THÁNG 12')}} '],
                },
            };

            if (configs.hasOwnProperty('drops')){
                if (configs.drops){
                    params.drops = configs.drops;
                }
            }

            if (configs.hasOwnProperty('minDate')){
                if (configs.minDate){
                    params.minDate = configs.minDate;
                }
            }

            if (configs.hasOwnProperty('maxDate')){
                if(configs.maxDate){
                    params.maxDate = configs.maxDate;
                }
            }

            $(node).find('input').daterangepicker(params);
        }
    </script>
@endpush