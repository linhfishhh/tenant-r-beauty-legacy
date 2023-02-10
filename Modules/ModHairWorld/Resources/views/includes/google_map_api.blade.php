@unique('google_map_api')
    @push('page_end')
        <script type="text/javascript">
            function googleMapInit() {
                $(window).trigger('googleMapInit');
            }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDQWT3fahmtocJ9UKL4ChXkVzwKYGuNlj8&libraries=places&language=vi&callback=googleMapInit"></script>
    @endpush
@endunique