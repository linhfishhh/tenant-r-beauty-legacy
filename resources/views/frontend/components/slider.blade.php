@isset($alias)
    @enqueueJS('rev-slider-tools', asset('assets/slider/js/jquery.themepunch.tools.min.js'), JS_LOCATION_HEAD,'jquery')
    @enqueueJS('rev-slider-revolution', asset('assets/slider/js/jquery.themepunch.revolution.min.js'), JS_LOCATION_HEAD, 'jquery')
    @php
        /** @var \App\RevSlider $slider */
    $slider = getThemeSlider($alias);
    $uid = uniqid();
    $slug = str_slug($alias, '_');
    $data = $slider->getFrontEndData();
    @endphp
    @foreach($data->assets as $asset)
        @php
            $file = $asset->file;
            $info = pathinfo($file);
        @endphp
        @if($info['extension'] == 'css')
            @enqueueCSS('rev-'.$info['filename'], $file)
        @endif
        @if($info['extension'] == 'js')
            @enqueueJS('rev-'.$info['filename'], $file, JS_LOCATION_HEAD, 'jquery')
        @endif
    @endforeach
    @if($slider)
        <div data-wa-slider-id="{!! $alias !!}" id="wa-rev-slider-{!! $uid !!}">
            {!! $data->slider !!}
        </div>
    @endif
@endisset
@push('page_footer_js')


@endpush