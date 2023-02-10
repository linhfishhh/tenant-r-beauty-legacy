@php
/** @var \App\Classes\BackendDashBoardWidget[] $widgets */
@endphp
@section('page_title', __('Bảng thống kê'))
@extends('layouts.backend')
@section('page_content_body')
    @foreach($widgets as $widget)
        @if(
        ($widget->getHasOnePermission() && me()->hasAnyPermissions($widget->getPermissions())) ||
        (!$widget->getHasOnePermission() && me()->hasAllPermissions($widget->getPermissions()))
        )
            @include($widget->getView(), $widget->getViewData())
        @endif
    @endforeach
@endsection