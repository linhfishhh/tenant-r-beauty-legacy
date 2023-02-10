@php
/** @var \App\Classes\MenuType $menu_type */
@endphp
<template id="{{$menu_type->getJSID()}}_tpl">
    @component('backend.components.menu_link_option')
        @slot('title')
            {{__('Liên kết')}}
        @endslot
        @slot('content')
            <input name="url" spellcheck="false" class="form-control" placeholder="{{__('Nhập link liên kết bạn cần')}}">
        @endslot
    @endcomponent
</template>
<script type="text/javascript">
    function {{$menu_type->getJSLoad()}}(parent, item) {
        var html = $('#{{$menu_type->getJSID()}}_tpl').html();
        html = $(html);
        $(parent).html(html);
        if (item){
            $(html).find('[name=url]').val(item.options.url);
        }
    }
</script>