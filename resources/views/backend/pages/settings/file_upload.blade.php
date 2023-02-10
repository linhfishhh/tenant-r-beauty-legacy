@enqueueJSByID(config('view.ui.files.js.touchspin.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@extends('backend.pages.settings.edit')
@section('content.form')
    @component('backend.components.panel', ['classes'=>'panel-default'])
        @slot('title')
            <h5 class="panel-title text-teal">{{__('Cấu hình tải lên')}}</h5>
        @endslot
        @slot('content')
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="control-label col-lg-3">{{__('Dung lượng cho phép')}}</label>
                    <div class="col-lg-9">
                        <input name="file_upload_max_size" type="text" value="{!! $settings['file_upload_max_size'] !!}">
                    </div>
                </div>
            </div>
        @endslot
    @endcomponent
@endsection
@push('page_footer_js')
    <script type="text/javascript">
        $(function () {
           $('input[name=file_upload_max_size]').TouchSpin({
               min: 0.01,
               max: {!! getFileUploadMaxSize()/(1024*1024*1.0) !!},
               step: 0.1,
               decimals: 2,
               postfix: 'MB'
           });
        });
    </script>
@endpush
