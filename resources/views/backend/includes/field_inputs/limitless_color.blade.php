@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@php
    /** @var \App\Role[] $roles */
@endphp
<template id="{{$field->getHtmlTemplateID()}}">
    <select name="{name}">
        @foreach($colors as $color)
            <option value="{!! $color !!}">{!! $color !!}</option>
        @endforeach
    </select>
</template>
@push('page_footer_js')
    <script type="text/javascript">
        function {!! $field->getJSRenderFunctionName() !!}(append_node, field_name, field_value, configs) {
            var node = $('#{{$field->getHtmlTemplateID()}}').html();
            node = node.replace(/{name}/g, field_name);
            node = $(node).appendTo(append_node);
            var multiple = 0;
            if (configs.hasOwnProperty('multiple')){
                multiple = configs.multiple;
            }
            if (!multiple){
                var fn = function (item) {
                    return '<span class="text-'+item.text+' text-bold">'+item.text+'</span>'
                };
            }
            else{
                var fn = function (item) {
                    return item.text;
                };
            }
            var params = {
                width: '100%',
                minimumResultsForSearch: -1,
                placeholder: '{{__('Chọn color class')}}',
                templateResult: function(item){
                    if (multiple){
                        return '<span class="text-'+item.text+' text-bold">'+item.text+'</span>';
                    }
                    else{
                        return '<span class="text-'+item.text+' text-bold">'+item.text+'</span>';
                    }
                },
                templateSelection: function (item, container) {
                    if (multiple){
                        $(container).addClass('bg-'+item.text).removeClass('select2-selection__choice').addClass('select2-choice-plant');
                        return item.text;
                    }
                    else{
                        return '<span class="text-'+item.text+' text-bold">'+item.text+'</span>';
                    }
                },
                escapeMarkup: function(m) { return m; }
            };
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