@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
<template id="{{$field->getHtmlTemplateID()}}">
    <select name="{name}"></select>
</template>
@push('page_footer_js')

    <script type="text/javascript">
        function {!! $field->getJSRenderFunctionName() !!}(append_node, field_name, field_value, configs) {
            var node = $('#{{$field->getHtmlTemplateID()}}').html();
            node = node.replace(/{name}/g, field_name);
            node = $(node).appendTo(append_node);
            var params = {
                width: "100%",
                placeholder: '{!! __('Chọn tỉnh/thành phố') !!}',
                ajax: {
                    url: '{!! route('info.tinh_thanh_pho.list') !!}',
                    dataType: 'json',
                    data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }
                        return query;
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        var items = [];
                        $.each(data.data, function () {
                            items.push({
                                id: ''+this.id,
                                text: this.name
                            });
                        });
                        return {
                            results: items,
                            pagination: {
                                more: (params.page * 30) < data.total
                            }
                        };
                    }
                }
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
            var ids = [];
            if (Array.isArray(field_value)){
                ids = field_value;
            }
            else{
                ids.push(field_value);
            }
            if (ids.length>0){
                $.ajax({
                    url: '{!! route('info.tinh_thanh_pho.from_ids') !!}',
                    type: 'get',
                    dataType: 'json',
                    data:{
                        ids: ids
                    } ,
                    success: function(data){
                        $.each(data, function(i, v){
                            var option = new Option(v.name, v.id, 0, 1);
                            $(node).append(option);
                        });
                        $(node).trigger('change');
                    }
                });
            }
        }
    </script>
@endpush