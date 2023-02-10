@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
<template id="{{$field->getHtmlTemplateID()}}">
        @php
        Debugbar::info($taxonomies);
        @endphp
        <div class="row">
            <div class="sl_taxonomy_wrapper">
                <select class="sl_taxonomy" name="{name}[taxonomy]">
                    @foreach($taxonomies as $tax=>$taxonomy)
                        <optgroup label="{!! $taxonomy['title'] !!}">
                            @foreach($taxonomy['items'] as $tax_class=>$item)
                                <option data-post-type-slug="{{$item['post_type_slug']}}" data-tax-slug="{{$item['tax_slug']}}" data-hierarchy="{{$item['hierarchy']}}" data-singular="{{$item['singular']}}" data-single="{{$item['single']}}" data-ajax-url="{{$item['ajax_url']}}" value="{{$tax_class}}">{{$item['singular']}}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
            <div class="sl_terms_wrapper">
                <select class="sl_terms" name="{name}[terms]"></select>
            </div>
        </div>
</template>
@push('page_footer_js')
    @include('backend.settings.language')
    <script type="text/javascript">
        function {!! $field->getJSRenderFunctionName() !!}(append_node, field_name, field_value, configs) {
            var node = $('#{{$field->getHtmlTemplateID()}}').html();
            node = node.replace(/{name}/g, field_name);
            node = $(node).appendTo(append_node);

            if (configs.force){
                $(node).find('.sl_taxonomy_wrapper').addClass('col-lg-12').hide();
                $(node).find('.sl_terms_wrapper').addClass('col-lg-12');
            }
            else{
                if (!configs.inline){
                    $(node).find('.sl_taxonomy_wrapper').addClass('col-lg-12').addClass('mb-10');
                    $(node).find('.sl_terms_wrapper').addClass('col-lg-12');
                }
                else{
                    $(node).find('.sl_taxonomy_wrapper').addClass('col-lg-4');
                    $(node).find('.sl_terms_wrapper').addClass('col-lg-8');
                }
            }

            $(node).find('.sl_taxonomy').select2({
                minimumResultsForSearch: -1
            });
            $(node).find('.sl_taxonomy').on('change', function(){
                var val = $(this).val();
                $(node).find('.sl_terms').val(null).trigger('change');
                if ($(node).find('.sl_terms').data('select2')){
                    $(node).find('.sl_terms').select2("destroy");;
                }
                if(val){
                    var isHierarchy =  $(this).find('option:selected').data('hierarchy');
                    var isSingle = $(this).find('option:selected').data('single');
                    var getSingular = $(this).find('option:selected').data('singular');
                    var ajax_url = $(this).find('option:selected').data('ajax-url');
                    var tax_slug = $(this).find('option:selected').data('tax-slug');
                    var post_type_slug = $(this).find('option:selected').data('post-type-slug');
                    var params = {
                        width: '100%',
                        placeholder: getSingular,
                        ajax:{
                            url: ajax_url,
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                if (isHierarchy){
                                    return {
                                        keyword: params.term, // search term
                                        language: 'all'
                                    };
                                }
                                else{
                                    return {
                                        keyword: params.term, // search term
                                        page: params.page,
                                        language: 'all'
                                    };
                                }
                            },
                            processResults: function (data, params) {
                                if (!isHierarchy){
                                    params.page = params.page || 1;
                                }
                                var items = [];
                                $.each(data, function (i, v) {
                                    items.push(
                                        {
                                            id: v.id,
                                            text: v.title,
                                            level: v.level,
                                            language: v.language
                                        }
                                    );
                                });
                                if (isHierarchy){
                                    return {
                                        results: items,
                                    };
                                }
                                else{
                                    return {
                                        results: items,
                                        more: (params.page * 50) < data.total_count
                                    };
                                }

                            },
                        },
                        minimumInputLength: 2,
                    };
                    if (isHierarchy){
                        params.escapeMarkup = function (markup) { return markup; };
                        params.templateResult = function (tax) {
                            if (tax.loading) return tax.text;
                            var title = tax.text;
                            for(var k=1; k<=tax.level; k++){
                                title = '├─'+title;
                            }
                            markup = title;
                            @if (isMultipleLanguage())
                                markup = '<span class="label bg-'+$language_tool.getMeta(tax.language).color_class+' mr-5" style="min-width: 30px">'+tax.language+'</span>'+ markup;
                            @endif
                                return markup;
                        };

                        params.minimumResultsForSearch = -1
                        params.minimumInputLength = -1;
                    }
                    else{
                        params.escapeMarkup = function (markup) { return markup; };
                        params.templateResult = function (tax) {
                            if (tax.loading) return tax.text;
                            var title = tax.text;
                            var markup = '<span>'+title+'</span>';
                            @if (isMultipleLanguage())
                                markup = '<span class="label bg-orange mr-5" style="min-width: 30px">'+tax.language+'</span>'+ markup;
                            @endif
                                return markup;
                        };
                    }

                    params.templateSelection = function (tax) {
                        return tax.text;
                    };

                    if (isSingle){
                        params.allowClear = true;
                    }

                    var multiple = 0;
                    if (configs.hasOwnProperty('multiple')){
                        multiple = configs.multiple;
                    }
                    params.multiple = multiple;

                    if (!multiple){
                        params.allowClear = 1;
                    }
                    $(node).find('.sl_terms').select2(params);
                    fixSelect2($(node).find('.sl_terms'));
                    if (loading){
                        if (field_value.hasOwnProperty('terms') && field_value.hasOwnProperty('taxonomy')){
                            if (field_value.taxonomy == val){
                                var ids = [];
                                if (Array.isArray(field_value.terms)){
                                    ids = field_value.terms;
                                }
                                else{
                                    ids.push(field_value.terms);
                                }
                                var url = '{!! route('backend.taxonomy.info',['post_type'=>'!--post_type--!', 'taxonomy' => '!--taxonomy--!']) !!}';
                                url = url.replace('!--post_type--!', post_type_slug);
                                url = url.replace('!--taxonomy--!', tax_slug);
                                $.ajax({
                                    url: url,
                                    type: 'get',
                                    dataType: 'json',
                                    data: {
                                        ids: ids
                                    },
                                    success: function(posts){
                                        if (Array.isArray(posts)){
                                            $.each(posts, function(){
                                                var option = new Option(this.title, this.id, 0, 1);
                                                $(node).find('.sl_terms').append(option);
                                            });
                                            $(node).find('.sl_terms').trigger('change');
                                        }
                                    }
                                });
                            }
                        }
                    }
                }
                else{
                    $(node).find('.sl_terms').select2({
                        placeholder:'{{__('Chọn phân loại nội dung trước')}}',
                        minimumResultsForSearch: -1,
                    });
                }
            });
            if (configs.force){
                if ($(node).find('.sl_taxonomy option[value='+JSON.stringify(configs.force)+']').length>0){
                    $(node).find('.sl_taxonomy').val(configs.force);
                    $(node).find('.sl_taxonomy option').not('[value='+JSON.stringify(configs.force)+']').remove();
                }
            }
            if(field_value.taxonomy){
                if (configs.force){
                }
                else{
                    $(node).find('.sl_taxonomy').val(field_value.taxonomy);
                }
            }
            var loading = 1;
            $(node).find('.sl_taxonomy').trigger('change');
            var loading = 0;
        }
    </script>
@endpush