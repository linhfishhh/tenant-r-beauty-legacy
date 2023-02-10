@php

@endphp
<style>
    .pac-container {
        z-index: 1051 !important;
    }
</style>
<div id="{{$field->getHtmlTemplateID()}}_picker" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">{!! __('Chọn vị trí') !!}</h5>
            </div>

            <div class="modal-body">
                <input spellcheck="false" class="controls pac-input form-control mb-10" type="text" placeholder="{!! __('Nhập địa chỉ cần tìm') !!}">
                <div class="map" style="height: 450px">

                </div>
                <div class="text-muted mt-5">
                    {!! __('Di chuyển con trỏ bản đồ đến vị trí mong muống hoặc gõ địa chỉ vào hộp tìm kiếm bên trên bản đồ') !!}
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">{!! __('Đóng lại') !!}</button>
                <button type="button" class="btn bg-orange btn-save">{!! __('Chọn vị trí này') !!}</button>
            </div>
        </div>
    </div>
</div>
<template id="{{$field->getHtmlTemplateID()}}">
    <div class="row">
        <div class="col-md-3">
            <input value="21.0227387" readonly name="{name}[lat]" class="form-control text-center lat">
        </div>
        <div class="col-md-3">
            <input value="105.8194541" readonly name="{name}[lng]" class="form-control text-center lng">
        </div>
        <div class="col-md-3">
            <input value="14" readonly name="{name}[zoom]" class="form-control text-center zoom">
        </div>
        <div class="col-md-3">
            <button type="button" class="btn bg-orange full-width">{!! __('Chọn vị trí') !!}</button>
        </div>
    </div>
</template>
@push('page_end')
    <script type="text/javascript">
        function googleMapInit() {
            $(window).trigger('googleMapInit');
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDQWT3fahmtocJ9UKL4ChXkVzwKYGuNlj8&libraries=places&language=vi&callback=googleMapInit"></script>
@endpush
@push('page_footer_js')
    <script type="text/javascript">
        var $modal_{{$field->getHtmlTemplateID()}}_picker = $('#{{$field->getHtmlTemplateID()}}_picker').modal({
            show: false
        });
        $modal_{{$field->getHtmlTemplateID()}}_picker.find('.btn-save').click(function(){
            var node = $modal_{{$field->getHtmlTemplateID()}}_picker.data('node');
            var location = $marker_{{$field->getHtmlTemplateID()}}_picker.position;
            var zoom = $map_{{$field->getHtmlTemplateID()}}_picker.zoom;
            $(node).find('input.lat').val(location.lat);
            $(node).find('input.lng').val(location.lng);
            $(node).find('input.zoom').val(zoom);
            $modal_{{$field->getHtmlTemplateID()}}_picker.modal('hide');
        });
        var $map_{{$field->getHtmlTemplateID()}}_picker;
        var $marker_{{$field->getHtmlTemplateID()}}_picker;
        $(window).on('googleMapInit', function(){
            $map_{{$field->getHtmlTemplateID()}}_picker = new google.maps.Map(
                $modal_{{$field->getHtmlTemplateID()}}_picker.find('.map')[0]
                , {
                center: {lat: -34.397, lng: 150.644},
                zoom: 8
            });
            $marker_{{$field->getHtmlTemplateID()}}_picker = new google.maps.Marker(
                {
                    position: {lat: -34.397, lng: 150.644},
                    map: $map_{{$field->getHtmlTemplateID()}}_picker,
                    draggable: true,
                });
            google.maps.event.addListener($marker_{{$field->getHtmlTemplateID()}}_picker, 'dragend', function (event) {
                $map_{{$field->getHtmlTemplateID()}}_picker.setCenter(
                    $marker_{{$field->getHtmlTemplateID()}}_picker.position
                );
            });

            var input = $modal_{{$field->getHtmlTemplateID()}}_picker.find('.pac-input')[0];
            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo('bounds', $map_{{$field->getHtmlTemplateID()}}_picker);
            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                if(place.geometry){
                    $marker_{{$field->getHtmlTemplateID()}}_picker.setPosition(place.geometry.location);
                    $map_{{$field->getHtmlTemplateID()}}_picker.setCenter(
                        $marker_{{$field->getHtmlTemplateID()}}_picker.position
                    );
                }
            });
        });
        function {!! $field->getJSRenderFunctionName() !!}(append_node, field_name, field_value, configs) {
            var node = $('#{{$field->getHtmlTemplateID()}}').html();
            node = node.replace(/{value}/g, field_value);
            node = node.replace(/{name}/g, field_name);
            node = $(node).appendTo(append_node);
            $(node).find('input.lat').val(field_value.lat);
            $(node).find('input.lng').val(field_value.lng);
            $(node).find('input.zoom').val(field_value.zoom);
            node.find('button').click(function(){
                var lat = $(node).find('input.lat').val();
                var lng = $(node).find('input.lng').val();
                var zoom = $(node).find('input.zoom').val();
                var location = {
                    lat: lat*1.0,
                    lng: lng*1.0
                };
                $marker_{{$field->getHtmlTemplateID()}}_picker.setPosition(location);
                $map_{{$field->getHtmlTemplateID()}}_picker.setCenter(
                    $marker_{{$field->getHtmlTemplateID()}}_picker.position
                );
                $map_{{$field->getHtmlTemplateID()}}_picker.setZoom(zoom*1.0);
                $modal_{{$field->getHtmlTemplateID()}}_picker.data('node', node);
                $modal_{{$field->getHtmlTemplateID()}}_picker.modal('show');
            });
        }
    </script>
@endpush