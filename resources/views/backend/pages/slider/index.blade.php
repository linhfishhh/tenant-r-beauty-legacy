@extends('layouts.backend')
@section('page_title', __('Trình quản lý slider'))
@section('page_header_title')
    <strong>{{__('Trình quản lý slider')}}</strong>
@endsection
@section('page_content')
    <div>
    <iframe id="revslider-iframe" style="width: 100%; height: calc(100vh - 55px)" frameborder="0" src="{!! url('apps/revslider') !!}?c=account&m=login&code={!! $code !!}&id={!! $id !!}"></iframe>
    </div>
@endsection
@push('page_footer_js')
@endpush