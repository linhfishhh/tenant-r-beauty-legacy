<template id="{{$field->getHtmlTemplateID()}}">
    <input placeholder="{placeholder}" spellcheck="false" class="form-control" name="{name}" type="text" value="{value}">
</template>
<template id="{{$field->getHtmlTemplateID()}}_group">
    <div class="input-group">
        {prepend}
        <input placeholder="{placeholder}" spellcheck="false" class="form-control" name="{name}" type="text" value="{value}">
        {append}
    </div>
</template>
@push('page_footer_js')

    <script type="text/javascript">
        function {!! $field->getJSRenderFunctionName() !!}(append_node, field_name, field_value, configs) {
            if ((configs.hasOwnProperty('prepend')&& configs.prepend != '' && configs.prepend) || (configs.hasOwnProperty('append')&& configs.append != ''  && configs.append)){
                var node = $('#{{$field->getHtmlTemplateID()}}_group').html();
                if (configs.hasOwnProperty('prepend') && configs.prepend != '' && configs.prepend){
                    node = node.replace(/{prepend}/g, '<span class="input-group-addon">'+configs.prepend+'</span>');
                }
                else{
                    node = node.replace(/{prepend}/g, '');
                }

                if (configs.hasOwnProperty('append') && configs.append != ''  && configs.append){
                    node = node.replace(/{append}/g, '<span class="input-group-addon">'+configs.append+'</span>');
                }
                else{
                    node = node.replace(/{append}/g, '');
                }
            }
            else{
                var node = $('#{{$field->getHtmlTemplateID()}}').html();
            }
            var placeholder = '';
            if (configs.hasOwnProperty('placeholder')){
                placeholder = configs.placeholder;
            }
            node = node.replace(/{placeholder}/g, placeholder);
            node = node.replace(/{value}/g, field_value?field_value:'');
            node = node.replace(/{name}/g, field_name);
            node = $(node).appendTo(append_node);
        }
    </script>
@endpush