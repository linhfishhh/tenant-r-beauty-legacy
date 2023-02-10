<template id="{{$field->getHtmlTemplateID()}}">
    <textarea spellcheck="false" placeholder="{placeholder}" rows="{rows}" class="form-control" name="{name}">{value}</textarea>
</template>
@push('page_footer_js')
    <script type="text/javascript">
        function {!! $field->getJSRenderFunctionName() !!}(append_node, field_name, field_value, configs) {
            var node = $('#{{$field->getHtmlTemplateID()}}').html();
            node = node.replace(/{value}/g, field_value?field_value:'');
            node = node.replace(/{name}/g, field_name);
            var rows = 5;
            if (configs.hasOwnProperty('rows')){
                rows = configs.rows;
            }
            node = node.replace(/{rows}/g, rows);

            var placeholder = '';
            if (configs.hasOwnProperty('placeholder')){
                placeholder = configs.placeholder;
            }
            node = node.replace(/{placeholder}/g, placeholder);

            node = $(node).appendTo(append_node);
        }
    </script>
@endpush