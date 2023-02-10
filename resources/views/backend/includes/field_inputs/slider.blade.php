@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@php
@endphp
<template id="{{$field->getHtmlTemplateID()}}">
    <select name="{name}">
        @php
        /** @var \App\RevSlider[] $sliders */
        $slide_thumbnail_uri = 'apps/revslider/media/thumb';
        $slide_thumbnail = public_path($slide_thumbnail_uri);
        @endphp
        @foreach($sliders as $slider)
            @php
            $img = getNoThumbnailUrl();
            if($slider->slides->count()>0){
                $slide = $slider->slides->first();
                $params = json_decode($slide->params, true);
                if(isset($params['image'])){
                 $img = $params['image'];
                 $info =  pathinfo($img);
                 $file_name = $info['basename'];
                 $file_name = '300x200_'.$file_name;
                 $file_path = $slide_thumbnail.'/'.$file_name;
                 if(file_exists($file_path)){
                    $img = $slide_thumbnail_uri.'/'.$file_name;
                    $img = url($img);
                 }
                }
            }
            @endphp
            <option data-slides="{!! $slider->slides->count() !!}" data-img="{{$img}}" value="{!! $slider->alias !!}">{!! $slider->title!!}</option>
        @endforeach
    </select>
</template>
<template id="{{$field->getHtmlTemplateID()}}_item">
    <div class="row">
        <div class="col-md-2"><img class="full-width" src="{img}"></div>
        <div class="col-md-10"><div class="text-semibold text-size-large mb-5">{title}</div><div><i class="icon-image2 position-left"></i>{slides} {!! __('slides') !!}</div></div>
    </div>
</template>
@push('page_footer_js')
    <script type="text/javascript">
        function {!! $field->getJSRenderFunctionName() !!}(append_node, field_name, field_value, configs) {
            var node = $('#{{$field->getHtmlTemplateID()}}').html();
            node = node.replace(/{name}/g, field_name);
            node = $(node).appendTo(append_node);
            var params = {
                width: '100%',
                minimumResultsForSearch: -1,
                placeholder: '{{__('Chọn slider')}}'
            };
            var multiple = 0;
            if (configs.hasOwnProperty('multiple')){
                multiple = configs.multiple;
            }
            params.multiple = multiple;
            if (!multiple){
                params.allowClear = 1;
            }
            params.templateResult = function(slider, e){
                if (!slider.id) {
                    return slider.text;
                }
                var img = $(slider.element).data('img');
                var count = $(slider.element).data('slides');
                var $slider = $('#{{$field->getHtmlTemplateID()}}_item').html();
                $slider = $slider.replace(/{title}/g, slider.text);
                $slider = $slider.replace(/{img}/g, img);
                $slider = $slider.replace(/{slides}/g, count);
                return $($slider);
            };
            $(node).select2(params);
            fixSelect2(node);
            $(node).val(field_value).trigger('change');
        }
    </script>
@endpush