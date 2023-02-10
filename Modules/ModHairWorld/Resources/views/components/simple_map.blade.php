<div id="{!! $id !!}" class="wa-simple-map {!! isset($classes)?$classes:'' !!}">

</div>
@push('page_footer_js')
    <script type="text/javascript">
        $(window).on('googleMapInit', function () {
            var $map;
            var $marker;
            $map = new google.maps.Map(
                $('#{!! $id !!}')[0]
                , {
                    center: {lat: {!! $location['lat'] !!}, lng: {!! $location['lng'] !!}},
                    zoom: {!! $location['zoom'] !!}
                });
            $marker = new google.maps.Marker(
                {
                    position: {lat: {!! $location['lat'] !!}, lng: {!! $location['lng'] !!}},
                    map: $map,
                });
        });
    </script>
@endpush
@include(getThemeViewName('includes.google_map_api'))