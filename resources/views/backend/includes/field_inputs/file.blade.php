<template id="{{$field->getHtmlTemplateID()}}">
    <div class="file-selector-wrapper p-5" style="background-color: #ececec">
        <div class="file-list">
        </div>
        <div class="add-more-btn ml-5 mr-5 mt-10 mb-10">
            <button type="button" class="btn btn-info"><i class="position-left icon-folder-open"></i>{button_title}</button>
        </div>
        <input type="hidden" name="{name}" class="field_value">
    </div>
</template>
<template id="{{$field->getHtmlTemplateID()}}_item">
    <div class="file-item">
        <div class="media p-10 bg-white text-slate m-5 pr-20">
            <div class="media-left">
                <a class=""><img style="width: 100px!important;height: auto!important;" src="{thumb}" class="img-lg" alt=""></a>
            </div>

            <div class="media-body hidden-xs pt-15">
                <h6 class="media-heading text-semibold">{file_name}</h6>
                {path}
                <ul class="list-inline">
                    <li><span class="label bg-warning text-size-mini cursor-pointer action-delete"><i class="icon-trash position-left text-size-mini"></i>{{__('Gỡ bỏ')}}</span></li>
                    <li><i class="icon-floppy-disk position-left"></i>{size}</li>
                </ul>
            </div>
        </div>
    </div>
</template>
@push('page_footer_js')
    <script type="text/javascript">
        function {!! $field->getJSRenderFunctionName() !!}_add_item(data, node, field_name, field_value, configs){
            var item_node_tpl = $('#{{$field->getHtmlTemplateID()}}_item').html();
            item_node_tpl = item_node_tpl.replace(/{file_name}/g, data.name+'.'+data.extension);
            item_node_tpl = item_node_tpl.replace(/{name}/g, field_name);
            item_node_tpl = item_node_tpl.replace(/{path}/g, data.path+'/'+data.name+'.'+data.extension);
            item_node_tpl = item_node_tpl.replace(/{thumb}/g, data.thumbnail);
            item_node_tpl = item_node_tpl.replace(/{size}/g, fileSizeFormat(data.size));
            item_node_tpl = item_node_tpl.replace(/{id}/g, data.id);
            var item_node = $(item_node_tpl);
            $(node).html('');
            $(item_node).appendTo(node);
            $(item_node).find('.action-delete').click(function(e){
                $(this).parents('.file-item').fadeOut(function(){
                    $(this).remove();
                    $(node).parents('.file-selector-wrapper').find('input.field_value').val(null);
                });
            });
            $(node).parents('.file-selector-wrapper').find('input.field_value').val(data.id);
        }

        function {!! $field->getJSRenderFunctionName() !!}(append_node, field_name, field_value, configs) {
            var node = $('#{{$field->getHtmlTemplateID()}}').html();
            node = node.replace(/{name}/g, field_name);
            var button_title = '{{__('Chọn tập tin')}}';
            if (configs.button_title != ''){
                button_title = configs.button_title;
            }
            node = node.replace(/{button_title}/g, button_title);
            node = $(node).appendTo(append_node);
            var file_list_node = $(node).find('.file-list');
            $(node).find('.add-more-btn button').click(function(e){
                if (configs.categories.length == 0){
                    delete configs.categories;
                }
                configs.select = 1;
                wa_file_manager(
                    configs
                    , function(items){
                        if (items.hasOwnProperty('length')){

                                {!! $field->getJSRenderFunctionName() !!}_add_item(items[0], file_list_node, field_name, field_value, configs);
                        }
                    }
                )
            });
            if (field_value != null && field_value != ''){
                var ids = [];
                if (Array.isArray(field_value)){
                    ids = field_value;
                }
                else{
                    ids.push(field_value);
                }
                $.ajax({
                    url: '{!! route('backend.file.info') !!}',
                    type: 'get',
                    dataType: 'json',
                    data: {
                        ids: ids
                    },
                    success: function(data){
                        $.each(data, function(){
                            {!! $field->getJSRenderFunctionName() !!}_add_item(this, file_list_node, field_name, field_value, configs);
                        });
                    }
                });
            }
        }
    </script>
@endpush