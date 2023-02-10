@php
/** @var \App\Classes\FieldGroup[] $groups */
@endphp
@extends('backend.pages.settings.edit')
@section('content.form')
    @foreach($groups as $group)
        @component('backend.components.field_group',['group'=>$group])@endcomponent
    @endforeach
@endsection
