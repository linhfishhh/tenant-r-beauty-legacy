@enqueueCSS('range-slider', getThemeAssetUrl('libs/rangeslider/rangeslider.css'), 'bootstrap')
@enqueueJS('range-slider', getThemeAssetUrl('libs/rangeslider/rangeslider.min.js'), JS_LOCATION_HEAD, 'jquery')
<div class="number-slider" id="{!! $id !!}">
    <input type="range" {!! isset($name)?'name="'.$name.'"':'' !!} {!! isset($attributes)?$attributes:'' !!}>
    <div class="value-display"><span></span>{!! isset($unit)?$unit:'' !!}</div>
</div>
@push('page_footer_js')
    <script type="text/javascript">
        $(function () {
            $('#{!! $id !!} input').rangeslider({
                polyfill: false,
                onSlide: function(position, value) {
                    $('#{!! $id !!} .value-display span').html(value);
                },
                onSlideEnd: function(position, value) {
                    $('#{!! $id !!}').trigger('slide_change', position, value);
                }
            }).change();
        });
    </script>
@endpush