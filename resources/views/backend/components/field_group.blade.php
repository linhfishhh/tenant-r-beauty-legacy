@php
/** @var \App\Classes\FieldGroup $group */
$uid = rand(100000000, 999999999);
@endphp
@component('backend.components.panel', ['classes'=>'panel-default'])
    @slot('title')
        <h5 class="panel-title text-teal">{{$group->getTitle()}}</h5>
    @endslot
    @slot('content')
        @if($group->getHorizontal())
            <div class="group-wrapper form-horizontal" id="field-group-{!! $uid !!}">

            </div>
        @else
            <div class="group-wrapper" id="field-group-{!! $uid !!}">

            </div>
        @endif
    @endslot
@endcomponent
@push('page_footer_js')
    @unique('field_wrapper_tpl')
        <template id="field_wrapper_tpl">
            <div class="form-group">
                <label class="control-label">{label}</label>
                <div class="field-wrapper"></div>
                {help}
            </div>
        </template>
        <template id="field_wrapper_horizontal_tpl">
            <div class="form-group" data-for-field="{name}">
                <label class="control-label col-lg-3">{label}</label>
                <div class="col-lg-9">
                    <div class="field-wrapper"></div>
                    {help}
                </div>
            </div>
        </template>
    @endunique
    @foreach($group->getFields() as $field)
        @unique($field->getHtmlTemplateID())
            @include($field->getViewName(), $field->getViewData())
        @endunique
        @isset($field->getFieldExtra()['addition_view'])
            @if($field->getFieldExtra()['addition_view'])
                @include($field->getFieldExtra()['addition_view'], ['field'=>$field])
            @endif
        @endisset
    @endforeach
    <script type="text/javascript">
        $(function () {
                    @foreach($group->getFields() as $field)
            var node = $('#field-group-{!! $uid !!}');
                    @if ($group->getHorizontal())
            var wrapper_node = $('#field_wrapper_horizontal_tpl').html();
                    @else
            var wrapper_node = $('#field_wrapper_tpl').html();
            @endif
            wrapper_node = wrapper_node.replace(/{label}/g, '{!! $field->getFieldLabel() !!}');
            wrapper_node = wrapper_node.replace(/{name}/g, '{!! $field->getFieldName() !!}');
            @if ($field->getFieldHelp())
                wrapper_node = wrapper_node.replace(/{help}/g, '<div class="help-block">{!! $field->getFieldHelp() !!}</div>');
            @else
                wrapper_node = wrapper_node.replace(/{help}/g, '');
            @endif
            wrapper_node = $(wrapper_node).appendTo(node).find('.field-wrapper');
            var name = '{!! $field->getFieldName() !!}';
            var value = {!! json_encode($field->getFieldValue()) !!};
            var configs = {!! json_encode($field->getConfigs()) !!};
            {!! $field->getJSRenderFunctionName() !!}(wrapper_node, name, value, configs);
            @endforeach
        })
    </script>
@endpush