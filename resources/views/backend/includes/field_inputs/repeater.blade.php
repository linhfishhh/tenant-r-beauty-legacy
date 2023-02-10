@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.jquery_ui.id'), JS_LOCATION_DEFAULT, 'jquery')
@php
    /** @var App\Classes\FieldInput $field */
@endphp
<template id="{{$field->getHtmlTemplateID()}}_row">
    @component('backend.components.panel', ['classes'=>'panel-default  mb-5 field-group-item', 'no_collapse'=>1])
        @slot('header_items')
            <li><a class="text-danger btn-remove-row btn-remove-row-{uid}"><i class="icon-cross"></i></a></li>
        @endslot
        @slot('title')
            <h6 class="panel-title num-order"></h6>
        @endslot
        @slot('content')
            <div class="field-group-item-content">
            </div>
        @endslot
    @endcomponent
</template>
<template id="{{$field->getHtmlTemplateID()}}_row_field">
    <div class="form-group">
        <label class="control-label">{label}</label>
        <div class="wa-field-{uid}"></div>
    </div>
</template>
<template id="{{$field->getHtmlTemplateID()}}">
        <div class="field-input-repeater-wrapper p-5" style="background-color: #ececec">
            <div class="field-group-list sort-for-{name}">

            </div>
            <div class="actions">
                <button class="btn btn-success btn-add" type="button"><i class="icon-plus-circle2 position-left"></i> {add_btn_label}</button>
            </div>
        </div>
        <input class="repeater-value" type="hidden" disabled name="{name}">
</template>

@push('page_footer_js')
    <script type="text/javascript">
        function {!! $field->getJSRenderFunctionName() !!}_reorder_num(list_node, lbl){
            $(list_node).find('>.field-group-item').each(function(i, node){
                $(node).find('>.panel-heading .num-order').html(''+lbl+'#'+(i+1));
            });
            var value_input = $(list_node).parent().next('.repeater-value');
            $(value_input).val([]);
            $(value_input).attr('disabled', false);
            if ($(list_node).find('>.field-group-item').length > 0){
                $(value_input).attr('disabled', true);
            }
        }

        function {!! $field->getJSRenderFunctionName() !!}_add_row(field_name, list_node, configs, value_to_load){
            var row_id = new Date().getTime();
            if(window['last_repeater_id']){
                if(window['last_repeater_id'] === row_id){
                    row_id++;
                }
            }
            window['last_repeater_id'] = row_id;
            var item = $('#{{$field->getHtmlTemplateID()}}_row').html();
            item = item.replace(/{uid}/g, row_id);
            item = $(item).appendTo(list_node);
            $(item).find('.btn-remove-row-'+row_id).click(function(){
                $(item).fadeOut(function () {
                    $(this).remove();
                    {!! $field->getJSRenderFunctionName() !!}_reorder_num(list_node, configs.item_label);
                });
            });
            {!! $field->getJSRenderFunctionName() !!}_reorder_num(list_node, configs.item_label);
            var group_node = $(item).find('.field-group-item-content');
            var parent_name = configs.hasOwnProperty('repeater_field_name')?configs.repeater_field_name:field_name;
            $.each(configs.child_field_data, function(){
                //console.log(this);
                var fn = window['render_repeat_'+this.uid];
                var field_node = $('#{{$field->getHtmlTemplateID()}}_row_field').html();
                field_node = field_node.replace(/{label}/g, this.label)
                field_node = field_node.replace(/{uid}/g, this.uid)
                field_node = $(field_node).appendTo(group_node);
                var value = this.value;
                if (value_to_load.hasOwnProperty(this.name)){
                   value = value_to_load[this.name];
                }
                fn(field_node, value, parent_name,row_id);
            });
        }

        function {!! $field->getJSRenderFunctionName() !!}(append_node, field_name, field_value, configs) {

            var node = $('#{{$field->getHtmlTemplateID()}}').html();
            node = node.replace(/{name}/g, field_name);
            node = node.replace(/{add_btn_label}/g, (configs.add_btn_label&&configs.add_btn_label.length>0)?configs.add_btn_label:'{{__('Thêm mới')}}');
            node = $(node).appendTo(append_node);
            var list_node = $(node).find('.field-group-list');
            $(node).find('.actions .btn-add').click(function(){
                {!! $field->getJSRenderFunctionName() !!}_add_row(field_name, list_node, configs, {});
            });
            if (!field_value){
                field_value = [];
            }
            $.each(field_value, function(){
                {!! $field->getJSRenderFunctionName() !!}_add_row(field_name, list_node, configs, this)
            });

            $(node).find('.field-group-list.sort-for-'+field_name).sortable({
                placeholder: 'sortable-placeholder',
                start: function(e, ui){
                    ui.placeholder.height(ui.item.outerHeight());
                }
            });
            $(node).find('.field-group-list.sort-for-'+field_name).disableSelection();
        }
    </script>
@endpush