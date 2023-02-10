@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@php
/** @var \App\Classes\PostType[] $post_types */
@endphp
<template id="{{$field->getHtmlTemplateID()}}">
    <div class="row">
        <div class="sl_post_type_wrapper">
            <select class="sl_post_type" name="{name}[post_type]">
                @foreach($post_types as $post_type)
                    <option data-slug="{{$post_type::getTypeSlug()}}" data-singular="{{$post_type::getSingular()}}" data-ajax-url="{{route('backend.post.select', ['post_type'=>$post_type::getTypeSlug()])}}" value="{{$post_type}}">{{$post_type::getSingular()}}</option>
                @endforeach
            </select>
        </div>
        <div class="sl_posts_wrapper">
            <select class="sl_posts" name="{name}[posts]"></select>
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
                $(node).find('.sl_post_type_wrapper').addClass('col-lg-12').hide();
                $(node).find('.sl_posts_wrapper').addClass('col-lg-12');
            }
            else{
                if (!configs.inline){
                    $(node).find('.sl_post_type_wrapper').addClass('col-lg-12').addClass('mb-10');
                    $(node).find('.sl_posts_wrapper').addClass('col-lg-12');
                }
                else{
                    $(node).find('.sl_post_type_wrapper').addClass('col-lg-4');
                    $(node).find('.sl_posts_wrapper').addClass('col-lg-8');
                }
            }

            $(node).find('.sl_post_type').select2({
                minimumResultsForSearch: -1
            });
            $(node).find('.sl_post_type').on('change', function(){
                var val = $(this).val();
                $(node).find('.sl_posts').val(null).trigger('change');
                if ($(node).find('.sl_posts').data('select2')){
                    $(node).find('.sl_posts').select2("destroy");;
                }
                if(val){
                    var getSingular = $(this).find('option:selected').data('singular');
                    var ajax_url = $(this).find('option:selected').data('ajax-url');
                    var slug = $(this).find('option:selected').data('slug');
                    var params = {
                        width: '100%',
                        placeholder: getSingular,
                        ajax:{
                            url: ajax_url,
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                return {
                                    keyword: params.term, // search term
                                    page: params.page,
                                    language: 'all'
                                };
                            },
                            processResults: function (data, params) {
                                params.page = params.page || 1;
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
                                return {
                                    results: items,
                                    more: (params.page * 50) < data.total_count
                                };

                            },
                        },
                        minimumInputLength: 2,
                    };

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

                    params.templateSelection = function (tax) {
                        return tax.text;
                    };


                    var multiple = 0;
                    if (configs.hasOwnProperty('multiple')){
                        multiple = configs.multiple;
                    }
                    params.multiple = multiple;

                    if (!multiple){
                        params.allowClear = 1;
                    }
                    $(node).find('.sl_posts').select2(params);
                    fixSelect2($(node).find('.sl_posts'));
                    if (loading){
                        if (field_value.hasOwnProperty('posts') && field_value.hasOwnProperty('post_type')){
                            var ids = [];
                            if (field_value.post_type == val){
                                if (Array.isArray(field_value.posts)){
                                    ids = field_value.posts;
                                }
                                else{
                                    ids.push(field_value.posts);
                                }
                            }
                            var url = '{!! route('backend.post.info',['post_type'=>'!--post_type--!']) !!}';
                            url = url.replace('!--post_type--!', slug);
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
                                            $(node).find('.sl_posts').append(option);
                                        });
                                        $(node).find('.sl_posts').trigger('change');
                                    }
                                }
                            });
                        }
                    }
                }
                else{
                    $(node).find('.sl_posts').select2({
                        placeholder:'{{__('Chọn Loại nội dung trước')}}',
                        minimumResultsForSearch: -1,
                    });
                }
            });
                if (configs.force){
                    if ($(node).find('.sl_post_type option[value='+JSON.stringify(configs.force)+']').length>0){
                        $(node).find('.sl_post_type').val(configs.force);
                        $(node).find('.sl_post_type option').not('[value='+JSON.stringify(configs.force)+']').remove();
                    }
                }

                if(field_value.post_type) {
                    $(node).find('.sl_post_type').val(field_value.post_type);
                }

            var loading = 1;
            $(node).find('.sl_post_type').trigger('change');
            var loading = 0;
        }
    </script>
@endpush