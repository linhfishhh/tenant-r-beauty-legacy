@php
/** @var \App\Classes\FieldInput\FieldInputRepeater $field */
/** @var \App\Classes\FieldInput[] $children */
$children = $field->getConfigs()['child_fields'];
@endphp
@foreach($children as $fieldInput)
    @php
        $fieldInput->setParent($field->getFieldName());
    @endphp
    @component('backend.components.field', ['field'=>$fieldInput, 'unhandled'=>1, 'horizontal' => 0])@endcomponent
@endforeach
@push('page_footer_js')
    <script type="text/javascript">
        @foreach($children as $fieldInput)
        function render_repeat_{!! $fieldInput->getUID() !!}(append_to, value, parent_name, row_id ){
            var name = parent_name+'['+row_id+'][{!! $fieldInput->getFieldName() !!}]';
            var configs = {!! json_encode($fieldInput->getConfigs()) !!};
            var append_node = $(append_to).find('.wa-field-{!! $fieldInput->getUID() !!}');
            configs.repeater_field_name = name;
            {!! $fieldInput->getJSRenderFunctionName() !!}(append_node, name, value, configs);
        }
        @endforeach
    </script>
@endpush