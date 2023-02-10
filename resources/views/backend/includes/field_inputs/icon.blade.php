@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@php
    /** @var \App\Menu[] $menus */
@endphp
<template id="{{$field->getHtmlTemplateID()}}">
    <select name="{name}"></select>
</template>
@push('page_footer_js')
    <script type="text/javascript">
        function {!! $field->getJSRenderFunctionName() !!}(append_node, field_name, field_value, configs) {
            var node = $('#{{$field->getHtmlTemplateID()}}').html();
            node = node.replace(/{name}/g, field_name);
            node = $(node).appendTo(append_node);
            var params = {
                width: '100%',
                placeholder: '{{__('Chọn icon')}}',
                minimumInputLength: 2,
                ajax: {
                    url: '{!! asset('assets/icon-classes/icomoon.json') !!}',
                    dataType: 'json',
                    processResults: function (data, params) {
                        var items = [];
                        var kw = params.term.toLowerCase().trim();
                        $.each(data, function(){
                            if (this.includes(kw)){;
                                items.push({
                                    id: ''+this,
                                    text: ''+this
                                });
                            }
                        });
                        return {
                            results: items
                        };
                    }
                },
                escapeMarkup: function (markup) { return markup; },
                templateResult: function(item){
                    if (item.loading) {
                        return item.text;
                    }
                    return '<i class="position-left '+item.text+'"></i> <span>'+item.text+'</span>'
                },
                templateSelection: function(item, data){
                    return '<i class="position-left '+item.text+'"></i> <span>'+item.text+'</span>'
                },
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
            var val = [];
            if (Array.isArray(field_value)){
                val = field_value;
            }
            else{
                val.push(field_value);
            }
            $.each(val, function () {
                var option = new Option(this, this, false, true);
                $(node).append(option);
            });
            $(node).trigger('change');
        }
    </script>
@endpush