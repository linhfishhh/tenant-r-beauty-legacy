@extends('backend.pages.post.edit')
@section('post_type_edit_after_title_post_form')
    @foreach($wa_field_groups as $group)
        @component('backend.components.field_group', ['group'=>$group])
        @endcomponent
    @endforeach
@endsection