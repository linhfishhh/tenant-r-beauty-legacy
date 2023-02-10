@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@php
@endphp
<template id="{{$field->getHtmlTemplateID()}}">
    <select name="{name}">
        @php
        /** @var \App\Classes\Permission[] $permissions */
        /** @var \App\Classes\PermissionGroup[] $groups */
        @endphp
        @foreach($groups as $group)
            <optgroup label="{!! $group->title !!}">
                @foreach($permissions as $permission)
                    @if($permission->group != $group->id)
                        @continue
                    @endif
                    <option value="{!! $permission->id !!}">{!! $permission->title !!}</option>
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
                placeholder: '{{__('Chọn phân quyền')}}'
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