@enqueueJSByID(config('view.ui.files.js.touchspin.id'), JS_LOCATION_DEFAULT, 'bootstrap')
<template id="{{$field->getHtmlTemplateID()}}">
    <input name="{name}" type="text" value="{value}">
</template>
@push('page_footer_js')

    <script type="text/javascript">
        function {!! $field->getJSRenderFunctionName() !!}(append_node, field_name, field_value, configs) {
            var node = $('#{{$field->getHtmlTemplateID()}}').html();
            node = node.replace(/{value}/g, field_value);
            node = node.replace(/{name}/g, field_name);
            node = $(node).appendTo(append_node);
            $(node).TouchSpin(configs);
        }
    </script>
@endpush