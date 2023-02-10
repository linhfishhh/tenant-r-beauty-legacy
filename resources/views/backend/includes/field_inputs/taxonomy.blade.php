@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
<template id="{{$field->getHtmlTemplateID()}}">
    <select name="{name}">
        @foreach($taxonomies as $taxonomy=>$tax_data)
            <optgroup label="{!! $tax_data['title'] !!}">
                @if(!isset($tax_data['items']))
                    @continue
                @endif
                @foreach($tax_data['items'] as $tax=>$tax_title)
                    <option value="{!! $tax !!}">{!! $tax_title !!}</option>
                @endforeach
            </optgroup>
        @endforeach
    </select>
</template>
@push('page_footer_js')
    <script type="text/javascript">
        function {!! $field->getJSRenderFunctionName() !!}(append_node, field_name, field_value, configs) {
            var node = $('#{{$field->getHtmlTemplateID()}}').html();
            node = node.replace(/{name}/g, field_name);
            node = $(node).appendTo(append_node);
            var params = {
                width: '100%',
                minimumResultsForSearch: -1,
                placeholder: '{{__('Chọn phân loại nội dung')}}'
            };
            var multiple = 0;
            if (configs.hasOwnProperty('multiple')){
                multiple = configs.multiple;
            }
            params.multiple = multiple;
            if (!multiple){
                params.allowClear = 1;
            }
            $(node).select2(params);
            fixSelect2(node);
            $(node).val(field_value).trigger('change');
        }
    </script>
@endpush