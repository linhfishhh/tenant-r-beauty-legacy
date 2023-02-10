@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
<template id="{{$field->getHtmlTemplateID()}}">
    <select name="{name}"></select>
</template>
@push('page_footer_js')

    <script type="text/javascript">
        function {!! $field->getJSRenderFunctionName() !!}(append_node, field_name, field_value, configs) {
            var node = $('#{{$field->getHtmlTemplateID()}}').html();
            node = node.replace(/{value}/g, field_value);
            node = node.replace(/{name}/g, field_name);
            node = $(node).appendTo(append_node);
            $(node).select2({
                width: '100%',
                minimumResultsForSearch: -1,
                multiple: configs.multiple,
            });
            $.each(configs.list, function(key, title){
                if (configs.multiple){
                    var option = new Option(title, key, 0, field_value.indexOf(key)>-1);
                }
                else{
                    var option = new Option(title, key, 0, key==field_value);
                }
                $(node).append(option);
            });
            $(node).trigger('change');
        }
    </script>
@endpush