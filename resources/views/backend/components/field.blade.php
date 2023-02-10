@php
    /** @var \App\Classes\FieldInput $field */
    $uid = $field->getUID();
    $horizontal = isset($horizontal)?$horizontal:1;
    $unhandled = isset($unhandled)?$unhandled: 0;
    $field_value = $field->getFieldValue();
@endphp
@if($unhandled)
    <div class="wa-field-placeholder wa-field-{!! $uid !!}"></div>
@else
    @if($horizontal)
        <div id="wa-field-{!! $uid !!}" class="form-horizontal"></div>
    @else
        <div id="wa-field-{!! $uid !!}"></div>
    @endif
@endif
@push('page_footer_js')
    @unique('field_wrapper_tpl')
    <template id="field_wrapper_tpl">
        <div class="form-group">
            <label class="control-label">{label}</label>
            <div class="field-wrapper"></div>
            @if($field->getFieldHelp())
                <div class="help-block">{!! $field->getFieldHelp() !!}</div>
            @endif
        </div>
    </template>
    <template id="field_wrapper_horizontal_tpl">
        <div class="form-group">
            <label class="control-label col-lg-3">{label}</label>
            <div class="col-lg-9">
                <div class="field-wrapper"></div>
                @if($field->getFieldHelp())
                    <div class="help-block">{!! $field->getFieldHelp() !!}</div>
                @endif
            </div>
        </div>
    </template>
    @endunique

    @unique($field->getHtmlTemplateID())
    @include($field->getViewName(), $field->getViewData())
    @endunique

    @isset($field->getFieldExtra()['addition_view'])
        @if($field->getFieldExtra()['addition_view'])
            @include($field->getFieldExtra()['addition_view'], ['field'=>$field])
        @endif
    @endisset

    @if($unhandled)
        @unique($field->getHtmlTemplateHandleID())
        <script type="text/javascript">
            function {!! $field->getHtmlTemplateHandleID() !!}(append_to,  value, configs_overwrite_fn) {
                var name = '{!! $field->getFieldName() !!}';
                var configs = {!! json_encode($field->getConfigs()) !!};
                if(typeof configs_overwrite_fn == 'function'){
                    configs = configs_overwrite_fn(configs);
                }
                var append_node = $(append_to).find('.wa-field-{!! $uid !!}');
                {!! $field->getJSRenderFunctionName() !!}(append_node, name, value, configs);
            }
        </script>
        @endunique
    @else
        <script type="text/javascript">
            $(function(){
                var node = $('#wa-field-{!! $uid !!}');
                        @if ($horizontal)
                var wrapper_node = $('#field_wrapper_horizontal_tpl').html();
                        @else
                var wrapper_node = $('#field_wrapper_tpl').html();
                @endif
                    wrapper_node = wrapper_node.replace(/{label}/g, '{!! $field->getFieldLabel() !!}');
                wrapper_node = $(node).append(wrapper_node).find('.field-wrapper');
                var name = '{!! $field->getFieldName() !!}';
                var value = {!! json_encode($field_value) !!};
                var configs = {!! json_encode($field->getConfigs()) !!};
                {!! $field->getJSRenderFunctionName() !!}(wrapper_node, name, value, configs);
            });
        </script>
    @endif

@endpush