@enqueueJSByID(config('view.ui.files.js.tinymce.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@php
/** @var \App\Classes\FieldInput $field */
@endphp
<template id="{{$field->getHtmlTemplateID()}}">
    <div>
        <textarea data-name="{named}" name="{name}">{value}</textarea>
    </div>
    @if(me()->isUltimateUser())
        {script_hook}
    @endif
</template>
@push('page_footer_js')
    <script type="text/javascript">
        function {!! $field->getJSRenderFunctionName() !!}(append_node, field_name, field_value, configs) {
            var node = $('#{{$field->getHtmlTemplateID()}}').html();
            field_value = field_value?field_value:'';
            node = node.replace(/{value}/g, field_value?field_value:'');
            node = node.replace(/{name}/g, field_name);
            var script_hook_id = '';
            var body
            if (configs.hasOwnProperty('script_hook_id')){
                script_hook_id = configs.script_hook_id;
                delete configs.script_hook_id;
            }
            if (script_hook_id) {
                node = node.replace(/{script_hook}/g, '<div class="alert alert-info text-center mt-5">CSS Hook Guide: <strong>' + script_hook_id + '</strong></div>');
            }
            else{
                node = node.replace(/{script_hook}/g, '');
            }
            var named = field_name.replace(/\[/g, '_');
            named = named.replace(/]/g, '_');
            node = node.replace(/{named}/g, named);
            node = $(node).appendTo(append_node);
            configs.selector = 'textarea[data-name='+named+']';
            var setup = '';
            if (configs.hasOwnProperty('setup')){
                setup = configs.setup;
            }
            var fontsize_formats = '';
            for (var i = 1; i<=80; i++){
                fontsize_formats += ' '+i+'px';
            }
            configs.fontsize_formats = fontsize_formats.trim();
            configs.relative_urls = false;
            configs.remove_script_host = false;
            configs.setup = function(editor){
                if (setup){
                    setup(editor);
                }
                editor.on('blur', function () {
                    editor.save();
                });

                var wa_image_insert = '';
                if (configs.hasOwnProperty('wa_image_insert')){
                    wa_image_insert = configs.wa_image_insert;
                    delete  configs.wa_image_insert;
                    wa_image_insert.limit = ['image'];
                }
                var wa_link_insert = '';
                if (configs.hasOwnProperty('wa_link_insert')){
                    wa_link_insert = configs.wa_link_insert;
                    delete  configs.wa_link_insert;
                }
                var wa_media_insert = '';
                if (configs.hasOwnProperty('wa_media_insert')){
                    wa_media_insert = configs.wa_media_insert;
                    delete  configs.wa_media_insert;
                    wa_media_insert.limit = ['media'];
                }
                if (wa_image_insert || wa_link_insert || wa_media_insert){
                    var values = [];
                    if (wa_link_insert){
                        values.push({
                            text: '{{__('Chèn link tập tin')}}',
                            value: 'wa_link_insert',
                            action: function(){
                                wa_file_manager(wa_link_insert, function(items){
                                    $.each(items, function (i, file) {
                                        editor.insertContent('<a href="'+file.link+'">'+file.name+'.'+file.extension+'</a>');
                                    });
                                });
                            }
                        });
                    }
                    if (wa_image_insert) {
                        values.push({
                            text: '{{__('Chèn ảnh')}}',
                            value: 'wa_image_insert',
                            action: function(){
                                wa_file_manager(wa_image_insert, function(items){
                                    $.each(items, function () {
                                        editor.insertContent('<img src="'+this.link+'" />');
                                    });
                                });
                            }
                        });
                    }
                    if (wa_media_insert) {
                        values.push({
                            text: '{{__('Chèn file đa phương tiện')}}',
                            value: 'wa_media_insert',
                            action: function(){
                                wa_file_manager(wa_media_insert, function(items){
                                    $.each(items, function () {
                                        editor.insertContent('<video src="'+this.link+'" width="100%" height="450" controls></video>');
                                    });
                                });
                            }
                        });
                    }
                    editor.addButton('wainsert', {
                        type: 'listbox',
                        text: '{{__('Chèn nâng cao')}}',
                        onselect: function (e) {
                            var val = this.value();
                            $.each(values, function(i,v){
                                if (val==v.value){
                                    v.action();
                                }
                            });
                            this.value('');
                        },
                        values: values,
                        onPostRender: function () {
                            this.value('');
                        }
                    });
                }
            };
            var event = $.Event('wa_field_tinymce');
            event.configs = configs;
            event.route = '{!! Route::currentRouteName() !!}';
            event.field_name = field_name;
            $(window).trigger(event);
            configs = event.configs;
            var editor = tinymce.init(configs);
        }
    </script>
@endpush