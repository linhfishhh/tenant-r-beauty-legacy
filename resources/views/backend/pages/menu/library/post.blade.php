@php
/** @var \App\Classes\MenuType $menu_type */
/** @var \App\Classes\FieldInput\FieldInputPostType $field_input_post_type */
@endphp
<template id="{{$menu_type->getJSID()}}_tpl">
    @component('backend.components.menu_link_option')
        @slot('title')
            {{__('Loại nội dung')}}
        @endslot
        @slot('content')
            <div class="post-type-field-input">
                @component('backend.components.field', ['field'=>$field_input_post_type, 'unhandled'=>1])
                @endcomponent
            </div>
        @endslot
    @endcomponent
        @component('backend.components.menu_link_option')
            @slot('title')
                {{__('Liên kết')}}
            @endslot
            @slot('content')

            @endslot
        @endcomponent
</template>
<script type="text/javascript">
    function {{$menu_type->getJSLoad()}}(parent, item) {
        var html = $('#{{$menu_type->getJSID()}}_tpl').html();
        html = $(html);
        $(parent).html('');
        $(parent).append(html);
        var field_input = $(html).find('.post-type-field-input');
        if (item){
            var value = item.options.post_type;
        }
        else{
            var value = '';
        }
        {!! $field_input_post_type->getHtmlTemplateHandleID() !!}(field_input, value);
    }
</script>