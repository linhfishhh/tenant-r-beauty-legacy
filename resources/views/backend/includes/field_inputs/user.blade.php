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
                width: '100%',
                placeholder: '{{__('Chọn tài khoản')}}',
                id: function (item) {
                    return item.id;
                },
                escapeMarkup: function (markup) {
                    return markup;
                },
                templateResult: function(repo) {
                    if (repo.loading) {
                        return repo.text;
                    }

                    var markup = '<div class="text-semibold">' + repo.text + '</div>' +
                        '<div class="text-grey">' + repo.role + '</div>' +
                        '<div class="text-size-mini">' + repo.email + '</div>'
                    ;

                    return markup;
                },
                templateSelection: function(repo) {
                    return repo.full_name || repo.text;
                },
                ajax: {
                    url: '{!! route('backend.user.select') !!}',
                    dataType: 'json',
                    data: function (params) {
                        var rs = {
                            search: params.term,
                        };
                        return rs;
                    },
                    processResults: function (data) {
                        var items = [];
                        $.each(data, function (i, v) {
                            items.push(
                                {
                                    id: v.id,
                                    text: v.name,
                                    email: v.email,
                                    role: v.role.title
                                }
                            );
                        });
                        return {
                            results: items
                        };
                    }
                },
                minimumInputLength: 2,
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
                   url: '{!! route('backend.user.info') !!}',
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