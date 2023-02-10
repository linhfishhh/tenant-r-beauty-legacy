@extends('backend.pages.post.edit')
@section('post_type_edit_after_title_post_form')
    @php
    /** @var \App\Classes\FieldGroup[] $wa_field_groups*/
    @endphp
    @foreach($wa_field_groups as $group)
        <div class="field-group-wrapper" data-for-group="{!! str_slug($group->getTitle()) !!}">
            @component('backend.components.field_group', ['group'=>$group])
            @endcomponent
        </div>
    @endforeach
    @push('page_footer_js')
    <script type="text/javascript">
        $(function () {
            $('form select[name=content_type]').change(function () {
                var v = $(this).val();
                if(v == 1){
                    $('.form-group[data-for-field=button_title]').show();
                    $('.form-group[data-for-field=button_link]').show();
                    $('.form-group[data-for-field=content_1]').show();
                    $('.form-group[data-for-field=content_2]').hide();
                    $('.form-group[data-for-field=content_2] .btn-remove-row').click();
                }
                else if(v == 2){
                    $('.form-group[data-for-field=button_title]').show();
                    $('.form-group[data-for-field=button_link]').show();
                    $('.form-group[data-for-field=content_2]').show();
                    $('.form-group[data-for-field=content_1]').hide();
                    $('.form-group[data-for-field=content_1] .btn-remove-row').click();
                }
                else{
                    $('.form-group[data-for-field=button_title]').hide();
                    $('.form-group[data-for-field=button_link]').hide();
                    $('.form-group[data-for-field=content_1]').hide();
                    $('.form-group[data-for-field=content_2]').hide();
                    $('.form-group[data-for-field=content_2] .btn-remove-row').click();
                    $('.form-group[data-for-field=content_1] .btn-remove-row').click();
                }
            });
            $('form select[name=content_type]').trigger('change');
        })
    </script>
    @endpush
@endsection