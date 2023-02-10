@php
/** @var \App\Classes\Taxonomy $model */
/** @var \App\Classes\Taxonomy $taxonomy */
/** @var \App\Classes\PostType $post_type */
@endphp
@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.pnotify.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@extends('layouts.backend')
@if($model == null)
    @section('page_title', __('Tạo :taxonomy', ['taxonomy'=>mb_strtolower($taxonomy::getSingular())]))
@section('page_header_title')
    {!! __('Tạo <strong>:taxonomy</strong>', ['taxonomy'=>mb_strtolower($taxonomy::getSingular())]) !!}
@endsection
@else
    @section('page_title', __('Chỉnh sửa :taxonomy', ['taxonomy'=>mb_strtolower($taxonomy::getSingular())]))
@section('page_header_title')
    {!! __('Chỉnh sửa <strong>:taxonomy</strong>', ['taxonomy'=>mb_strtolower($taxonomy::getSingular())]) !!}
@endsection
@endif
@section('sidebar_second')
    <div class="sidebar-category">
        <div class="category-title">
            <span>{{__('HÀNH ĐỘNG')}}</span>
            <ul class="icons-list">
                <li><a href="#" data-action="collapse"></a></li>
            </ul>
        </div>

        <div class="category-content text-center">
            <div class="mb-10">
                @event(new \App\Events\BeforeHtmlBlock('sidebar.actions'))
                @yield('taxonomy_edit_before_taxonomy_sidebar_actions')
                <button data-loading-text="<i class='icon-spinner4 spinner position-left'></i> {{__('ĐANG XỬ LÝ...')}}"
                        id="{{jSID('submit')}}" type="button" class="btn bg-primary btn-block btn-save">
                    {{__('LƯU THAY ĐỔI')}}
                </button>
                <button data-loading-text="<i class='icon-spinner4 spinner position-left'></i> {{__('ĐANG XỬ LÝ...')}}"
                        id="{{jSID('submit_return')}}" type="button" class="btn bg-orange btn-block btn-save">
                    {{__('LƯU VÀ QUAY LẠI')}}
                </button>
                @if(!$model)
                    <button data-loading-text="<i class='icon-spinner4 spinner position-left'></i> {{__('ĐANG XỬ LÝ...')}}"
                            id="{{jSID('submit_create')}}" type="button" class="btn bg-success btn-block btn-save">
                        {{__('LƯU VÀ TẠO TIẾP')}}
                    </button>
                @endif
                <button data-loading-text="<i class='icon-spinner4 spinner position-left'></i> {{__('ĐANG XỬ LÝ...')}}"
                        id="{{jSID('submit_cancel')}}" type="button" class="btn bg-warning btn-block btn-save">
                    {{__('HỦY THAY ĐỔI')}}
                </button>
                <button data-loading-text="<i class='icon-spinner4 spinner position-left'></i> {{__('ĐANG XỬ LÝ...')}}"
                        id="{{jSID('submit_back')}}" type="button" class="btn bg-info btn-block btn-save">
                    {{__('QUAY LẠI')}}
                </button>
                @yield('taxonomy_edit_after_taxonomy_sidebar_actions')
                @event(new \App\Events\AfterHtmlBlock('sidebar.actions'))
            </div>
        </div>
    </div>
    @yield('taxonomy_edit_before_taxonomy_sidebar_items')
    @include('backend.pages.taxonomy.includes.menu_items')
    @yield('taxonomy_edit_after_taxonomy_sidebar_items')
@endsection
@section('page_content_body')
    @event(new \App\Events\BeforeHtmlBlock('content'))
    @yield('taxonomy_edit_before_taxonomy_content')
    <form class="" id="{{jSID('form')}}">
        @event(new \App\Events\BeforeHtmlBlock('content.form'))
        @yield('taxonomy_edit_before_taxonomy_form')
        @component('backend.components.panel', ['classes'=>'panel-default'])
            @slot('title')
                <h5 class="panel-title text-teal">{{__('Thông :taxonomy', ['taxonomy'=>mb_strtolower($taxonomy::getSingular())])}}</h5>
            @endslot
            @slot('content')
                <div class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-lg-3">{{__('Tiêu đề')}}<span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input placeholder="{{__('Nhập tiêu đề')}}" class="form-control" type="text" spellcheck="false" name="title" value="{{$model?$model->title:''}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-3">{{__('Định danh link/slug')}}</label>
                            <div class="col-lg-9">
                                <input placeholder="{{__('Bỏ trống sẽ tự tạo')}}" class="form-control" type="text" spellcheck="false" name="slug" value="{{$model?$model->slug:''}}">
                            </div>
                        </div>
                        @if(isMultipleLanguage())
                            <div class="form-group">
                                <label class="control-label col-lg-3">{{__('Ngôn ngữ')}}</label>
                                <div class="col-lg-9">
                                    @component('backend.components.language_seletor')
                                        @slot('name', 'language')
                                        @slot('id', jSID('language'))
                                    @endcomponent
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="language" value="{{config('app.locale')}}">
                        @endif
                        @if($taxonomy::isHierarchy())
                            <div class="form-group">
                                <label class="control-label col-lg-3">{{__(':taxonomy cha', ['taxonomy'=>$taxonomy::getSingular()])}}</label>
                                <div class="col-lg-9">
                                    <select name="parent_id"></select>
                                </div>
                            </div>
                        @endif
                    </div>
            @endslot
        @endcomponent
        @yield('taxonomy_edit_after_taxonomy_form')
        @event(new \App\Events\AfterHtmlBlock('content.form'))
        <input type="hidden" name="is_update" value="{{$model?'1':'0'}}">
    </form>
    @if(isMultipleLanguage())
        @if($model)
            @if($taxonomy::isHierarchy())
                <div class="alert alert-warning alert-styled-left no-margin-bottom mt-10">
                    {{__('Thay đổi ngôn ngữ của :tax_singular cha cũng sẽ làm thay đổi ngôn ngữ :tax_plural con', ['tax_singular'=>$taxonomy::getSingular(), 'tax_plural'=>$taxonomy::getPlural()])}}
                </div>
            @endif
            <div class="alert alert-warning alert-styled-left no-margin-bottom mt-10">
                {{__('Thay đổi ngôn ngữ của :tax_singular cha cũng sẽ gỡ tất cả :post_plural con ra khỏi nó', ['tax_singular'=>$taxonomy::getSingular(), 'tax_plural'=>$taxonomy::getPlural(), 'post_plural'=>$post_type::getPlural()])}}
            </div>
        @endif
    @endif
    @yield('taxonomy_edit_after_taxonomy_content')
    @event(new \App\Events\AfterHtmlBlock('content'))
@endsection
@push('page_footer_js')
    @yield('taxonomy_edit_before_page_footer_js')
    <script type="text/javascript">
        $(function () {
            $('#{{jSID('submit_cancel')}}').click(function () {
                window.location = '{!! Request::url() !!}';
            });
            $('#{{jSID('submit_back')}}').click(function () {
                window.location = '{!! route('backend.taxonomy.index', ['post_type' => $post_type::getTypeSlug(), 'taxonomy' => $taxonomy::getTaxSlug()]) !!}';
            });
            @if (isMultipleLanguage())
                $('#{{jSID('form')}} select[name=language]').select2({
                    width: "100%",
                    minimumResultsForSearch: Infinity,
                });
                $('#{{jSID('form')}} select[name=language]').on('select2:select', function () {
                    var language = $(this).val();
                    console.log(language);
                    @if ($taxonomy::isHierarchy())
                    loadHierarchyWithLanguage(language);
                    @endif
                });
            @endif
            @if($taxonomy::isHierarchy())
                $('#{{jSID('form')}} select[name=parent_id]').select2({
                    width: "100%",
                    minimumResultsForSearch: Infinity,
                });

                @if (isSingleLanguage())
                    var $hierarchy = {!! json_encode($taxonomy::whereLanguage(config('app.locale'))->get(['id', 'parent_id', 'language', 'title'])) !!};
                @else
                    var $hierarchy = {!! json_encode($taxonomy::all(['id', 'parent_id', 'language', 'title'])) !!};
                @endif

                function loadHierarchyWithLanguage(language){
                    $('#{{jSID('form')}} select[name=parent_id]').html('');
                    var option = new Option('{{_('Không có')}}', '', false, false);
                    $('#{{jSID('form')}} select[name=parent_id]').append(option);
                    loadHierarchy(null, language, 0);
                }

                @if (isSingleLanguage())
                    @if ($model)
                        loadHierarchyWithLanguage('{!! $model->language !!}');
                        $('#{{jSID('form')}} select[name=parent_id]').val('{!! $model->parent_id !!}').trigger('change');
                    @else
                        loadHierarchyWithLanguage('{!! config('app.locale') !!}');
                    @endif
                @else
                    @if ($model)
                        $('#{{jSID('form')}} select[name=language]').val('{!! $model->language !!}').trigger('change');
                        loadHierarchyWithLanguage('{!! $model->language !!}');
                        $('#{{jSID('form')}} select[name=parent_id]').val('{!! $model->parent_id !!}').trigger('change');
                    @else
                        $('#{{jSID('form')}} select[name=language]').val('{!! config('app.locale') !!}').trigger('change');
                        loadHierarchyWithLanguage('{!! config('app.locale') !!}');
                    @endif
                @endif

                function loadHierarchy(parent_id, lang, level){
                    $.each($hierarchy, function (i, v) {
                        var title = v.title;
                        for(var k=1; k<=level; k++){
                            title = '├─'+title;
                        }
                        if (v.parent_id == parent_id && v.language == lang){
                            @if ($model)
                                if (v.id == {!! $model->id !!}){
                                    return true;
                                }
                            @endif
                            var option = new Option(title, v.id, false, false);
                            $('#{{jSID('form')}} select[name=parent_id]').append(option);
                            loadHierarchy(v.id, lang, level+1)
                        }
                    });
                    $('#{{jSID('form')}} select[name=parent_id]').trigger('change');
                }
            @endif

            $('#{{jSID('submit')}}').click(function () {
                saveForm(this, function (rs) {
                    @if ($model)
                        window.location = '{!! URL::current() !!}';
                    @else
                        window.location = rs;
                    @endif
                })
            });

            $('#{{jSID('submit_return')}}').click(function () {
                saveForm(this, function (rs) {
                    window.location = '{{route('backend.taxonomy.index', ['post_type'=>$post_type::getTypeSlug(), 'taxonomy'=>$taxonomy::getTaxSlug()])}}';
                })
            });

            @if (!$model)
            $('#{{jSID('submit_create')}}').click(function () {
                saveForm(this, function (rs) {
                    window.location = '{{route('backend.taxonomy.create', ['post_type'=>$post_type::getTypeSlug(), 'taxonomy'=>$taxonomy::getTaxSlug()])}}';
                })
            });

            @endif

            function saveForm(btn, after_success) {
                var form = $('#{{jSID('form')}}');
                var data = $(form).serializeObject();
                @if ($model)
                      data._method = 'put';
                @endif
                $.ajax({
                    @if ($model)
                    url: '{{route('backend.taxonomy.update', ['post_type'=>$post_type::getTypeSlug(), 'taxonomy'=>$taxonomy::getTaxSlug(), 'term'=>$model])}}',
                    type: 'post',
                    @else
                    url: '{{route('backend.taxonomy.store', ['post_type'=>$post_type::getTypeSlug(), 'taxonomy'=>$taxonomy::getTaxSlug()])}}',
                    type: 'post',
                    @endif
                    dataType: 'json',
                    data: data,
                    beforeSend: function () {
                        cleanErrorMessage(form);
                        $(btn).button('loading');
                        $('.btn-save').not(btn).prop('disabled', true);
                    },
                    success: function (rs) {
                        after_success(rs);
                    },
                    error: function (rs) {
                        new PNotify({
                            title: '{{'LƯU DỮ LIỆU'}}',
                            text: '{{__('Có lỗi xảy ra, vui lòng kiểm tra lại!')}}',
                            addclass: 'bg-danger stack-bottom-right',
                            stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25},
                            buttons: {
                                sticker: false
                            },
                            delay: 2000
                        });
                        handleErrorMessage(form, rs);
                    },
                    complete: function () {
                        $(btn).button('reset');
                        $('.btn-save').not(btn).prop('disabled', false);
                    }
                });
            }
        });
    </script>
    @yield('taxonomy_edit_after_page_footer_js')
@endpush