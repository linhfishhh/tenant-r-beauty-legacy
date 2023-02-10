@enqueueJSByID(config('view.ui.files.js.ace.id'), JS_LOCATION_DEFAULT, 'bootstrap')
<template id="{{$field->getHtmlTemplateID()}}">
    <div class="code-editor-wrapper">
        <div class="editor-wrapper">{value}</div>
        <textarea name="{name}" class="hide1 form-control code-value hide"></textarea>
    </div>
</template>
@push('page_footer_js')
    <script type="text/javascript">
        function {!! $field->getJSRenderFunctionName() !!}(append_node, field_name, field_value, configs) {
            var node = $('#{{$field->getHtmlTemplateID()}}').html();
            node = node.replace(/{value}/g, field_value?field_value:'');
            node = node.replace(/{name}/g, field_name);
            node = $(node).appendTo(append_node);
            var editor = ace.edit($(node).find('.editor-wrapper')[0]);
            editor.setOptions(configs);
            editor.on('change', function () {
                $(node).find('textarea.code-value').val(editor.getValue());
            });
            editor.setValue(field_value);
        }
    </script>
@endpush