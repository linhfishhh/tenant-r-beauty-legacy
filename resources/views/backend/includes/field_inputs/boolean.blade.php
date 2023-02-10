@enqueueJSByID(config('view.ui.files.js.switch.id'), JS_LOCATION_DEFAULT, 'bootstrap')
<template id="{{$field->getHtmlTemplateID()}}">
    <div class="checkbox checkbox-switch">
        <label>
            <input type="checkbox" class="switch">
            <input class="value" type="hidden" name="{name}" value="{value}">
        </label>
    </div>
</template>
@push('page_footer_js')

    <script type="text/javascript">
        function {!! $field->getJSRenderFunctionName() !!}(append_node, field_name, field_value, configs) {
            var node = $('#{{$field->getHtmlTemplateID()}}').html();
            node = node.replace(/{name}/g, field_name);
            node = node.replace(/{value}/g, field_value);
            node = $(node).appendTo(append_node);
            if (field_value == true || field_value == 1 || field_value == 'true' || field_value == '1'){
                $(node).find('input.switch').attr('checked', true);
            }
            if (configs.hasOwnProperty('true_label')){
                $(node).find('input.switch').data('on-text', configs.true_label);
            }
            if (configs.hasOwnProperty('false_label')){
                $(node).find('input.switch').data('off-text', configs.false_label);
            }
            if (configs.hasOwnProperty('true_color_class')){
                if (configs.true_color_class){
                    $(node).find('input.switch').data('on-color', configs.true_color_class);
                }
            }
            if (configs.hasOwnProperty('false_color_class')){
                if (configs.true_color_class){
                    $(node).find('input.switch').data('off-color', configs.false_color_class);
                }
            }
            $(node).find('input.switch').bootstrapSwitch();
            $(node).find('input.switch').on('switchChange.bootstrapSwitch', function(event, state){
                $(node).find('input.value').val(state?1:0);
            });
        }
    </script>
@endpush