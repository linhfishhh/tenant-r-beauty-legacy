@enqueueJSByID(config('view.ui.files.js.spectrum.id'), JS_LOCATION_DEFAULT, 'bootstrap')
<template id="{{$field->getHtmlTemplateID()}}">
    <div>
        <input name="{name}" type="text" value="{value}" data-preferred-format="hex" data-cancel-text="{{__('Hủy')}}" data-choose-text="{{__('Chọn')}}">
    </div>
</template>
@push('page_footer_js')
    <script type="text/javascript">
        function {!! $field->getJSRenderFunctionName() !!}(append_node, field_name, field_value, configs) {
            var node = $('#{{$field->getHtmlTemplateID()}}').html();
            node = node.replace(/{value}/g, field_value);
            node = node.replace(/{name}/g, field_name);
            node = $(node).appendTo(append_node);
            $(node).find('input').spectrum({
                showInitial: true,
                showInput: true,
                showAlpha: true,
                allowEmpty: true
            });
        }
    </script>
@endpush