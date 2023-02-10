@extends('backend.pages.taxonomy.edit')
@section('taxonomy_edit_after_taxonomy_form')
    @foreach($wa_field_groups as $group)
        @component('backend.components.field_group', ['group'=>$group])
        @endcomponent
    @endforeach
@endsection