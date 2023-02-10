@php
/** @var \App\Classes\MenuTypeWithFieldInput $menu_type */
@endphp
<template id="{{$menu_type->getJSID()}}_tpl">
    @foreach($menu_type->getFieldInputs() as $fieldInput)
        @component('backend.components.menu_link_option')
            @slot('title')
                {{$fieldInput->getFieldLabel()}}
            @endslot
            @slot('content')
                <div class="wa-field-input wa-field-input-{!! $fieldInput->getFieldName() !!}">
                    @component('backend.components.field', ['field' => $fieldInput, 'unhandled' => 1, 'horizontal' => 1])@endcomponent
                </div>
            @endslot
        @endcomponent
    @endforeach
</template>
<script type="text/javascript">
    function {{$menu_type->getJSLoad()}}(parent, item) {
        var html = $('#{{$menu_type->getJSID()}}_tpl').html();
        html = $(html);
        $(parent).html('');
        $(parent).append(html);
        @foreach($menu_type->getFieldInputs() as $fieldInput)
            if(item){
                var value = item.options['{!! $fieldInput->getFieldName() !!}'];
            }
            else{
                var value = {!! json_encode($fieldInput->getRawFieldValue()) !!};
            }
            {!! $fieldInput->getHtmlTemplateHandleID() !!}($(html).find('.wa-field-input-{!! $fieldInput->getFieldName() !!}'), value, null);
        @endforeach
    }
</script>