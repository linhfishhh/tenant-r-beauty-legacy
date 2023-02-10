@php
    /** @var \App\Classes\PostType $model */
    /** @var \App\Classes\PostType $post_type */
@endphp
@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.pnotify.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.moment.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.daterangepicker.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@extends('layouts.backend')
@if($model == null)
    @section('page_title', __('Tạo :post_type', ['post_type'=>mb_strtolower($post_type::getSingular())]))
@section('page_header_title')
    {!! __('Tạo <strong>:post_type</strong>', ['post_type'=>mb_strtolower($post_type::getSingular())]) !!}
@endsection
@else
    @section('page_title', __('Chỉnh sửa :post_type', ['post_type'=>mb_strtolower($post_type::getSingular())]))
@section('page_header_title')
    {!! __('Chỉnh sửa <strong>:post_type</strong>', ['post_type'=>mb_strtolower($post_type::getSingular())]) !!}
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
                @yield('post_type_before_post_sidebar_actions')
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
                @if($model && $post_type::isPublic())
                    <a target="_blank" href="{!! $model->getUrl() !!}" data-loading-text="<i class='icon-spinner4 spinner position-left'></i> {{__('ĐANG XỬ LÝ...')}}"
                       id="{{jSID('submit_view')}}" type="button" class="btn bg-teal btn-block btn-view">
                        {{__('XEM')}}
                    </a>
                @endif
                <button data-loading-text="<i class='icon-spinner4 spinner position-left'></i> {{__('ĐANG XỬ LÝ...')}}"
                        id="{{jSID('submit_back')}}" type="button" class="btn bg-info btn-block btn-save">
                    {{__('QUAY LẠI')}}
                </button>
                @yield('post_type_after_post_sidebar_actions')
                @event(new \App\Events\AfterHtmlBlock('sidebar.actions'))
            </div>
        </div>
    </div>
    @yield('post_type_edit_before_post_sidebar_items')
    @include('backend.pages.post.includes.menu_items')
    @yield('post_type_edit_after_post_sidebar_items')
@endsection
@section('page_content_body')
    @event(new \App\Events\BeforeHtmlBlock('content'))
    @yield('post_type_edit_before_post_content')
    <form class="" id="{{jSID('form')}}">
        @event(new \App\Events\BeforeHtmlBlock('content.form'))
        @yield('post_type_edit_before_post_form')
        @component('backend.components.panel', ['classes'=>'panel-default'])
            @slot('title')
                <h5 class="panel-title text-teal">{{__('Thông tin :post', ['post'=>mb_strtolower($post_type::getSingular())])}}</h5>
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
                </div>
            @endslot
        @endcomponent
        @yield('post_type_edit_after_title_post_form')
        @if($post_type::getTaxonomies())
        @component('backend.components.panel', ['classes'=>'panel-default'])
            @slot('title')
                <h5 class="panel-title text-teal">{{__('Phân loại :post', ['post'=>mb_strtolower($post_type::getSingular())])}}</h5>
            @endslot
            @slot('content')
                <div class="form-horizontal">
                    @foreach($post_type::getTaxonomies() as $taxonomy=>$rel)
                        @php
                            /** @var \App\Classes\Taxonomy  $taxonomy */
                            if(me()->hasPermission($post_type::getCatalogizePermissionID())){
                                $disabled = '';
                            }
                            else{
                                $disabled = 'disabled';
                            }
                        @endphp
                        <div class="form-group">
                            <label class="control-label col-lg-3">{{$taxonomy::getMenuTitle()}}</label>
                            <div class="col-lg-9">
                                @if($taxonomy::isSingle())
                                    <select {!! $disabled !!} id="tax-selector-{!! $taxonomy::getTaxSlug() !!}" class="tax-selector" name="tax-{!! $taxonomy::getTaxSlug() !!}"></select>
                                @else
                                    <select {!! $disabled !!} id="tax-selector-{!! $taxonomy::getTaxSlug() !!}" multiple class="tax-selector" name="tax-{!! $taxonomy::getTaxSlug() !!}"></select>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endslot
        @endcomponent
        @endif
        @yield('post_type_edit_after_cat_post_form')
        @component('backend.components.panel', ['classes'=>'panel-default'])
            @slot('title')
                <h5 class="panel-title text-teal">{{__('Thông tin xuất bản :post', ['post'=>mb_strtolower($post_type::getSingular())])}}</h5>
            @endslot
            @slot('content')
                @php
                    if(me()->hasPermission($post_type::getPublishPermissionID())){
                        $disabled = '';
                    }
                    else{
                        $disabled = 'disabled';
                    }
                @endphp
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-lg-3">{{__('Cho phép hiện thị')}}</label>
                        <div class="col-lg-9">
                            <select {!! $disabled !!} name="published">
                                <option value="1">{{__('Hiển thị')}}</option>
                                <option value="0">{{__('Tạm tắt')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3">{{__('Ngày đăng')}}</label>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                                <input {!! $disabled !!} name="published_at" readonly type="text" class="form-control" value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3">{{__('Tác giả')}}</label>
                        <div class="col-lg-9">
                            @php
                                if(me()->hasPermission($post_type::getChangeAuthorPermissionID())){
                                    $disabled = '';
                                }
                                else{
                                    $disabled = 'disabled';
                                }
                            @endphp
                            <select {!! $disabled !!} name="user_id"></select>
                        </div>
                    </div>
                </div>
            @endslot
        @endcomponent
        @yield('post_type_edit_after_post_form')
        @event(new \App\Events\AfterHtmlBlock('content.form'))
        <input type="hidden" name="is_update" value="{{$model?'1':'0'}}">
    </form>
    @yield('post_type_edit_after_post_content')
    @event(new \App\Events\AfterHtmlBlock('content'))
@endsection
@push('page_footer_js')
    @yield('post_type_edit_before_page_footer_js')
    <script type="text/javascript">
        $(function () {
            $('#{{jSID('submit_cancel')}}').click(function () {
                window.location = '{!! Request::url() !!}';
            });
            $('#{{jSID('submit_back')}}').click(function () {
                window.location = '{!! route('backend.post.index', ['post_type' => $post_type::getTypeSlug()]) !!}';
            });
            @if (isMultipleLanguage())
            $('#{{jSID('form')}} select[name=language]').select2({
                width: "100%",
                minimumResultsForSearch: Infinity,
            });
            @if ($model)
            $('#{{jSID('form')}} select[name=language]').val('{!! $model->language !!}').trigger('change');
            @endif
            $('#{{jSID('form')}} select[name=language]').on('select2:select', function () {
                $('#{{jSID('form')}} .tax-selector').val(null).trigger('change');
            });
            @endif

            @foreach($post_type::getTaxonomies() as $taxonomy=>$rel)
                @php
                    /** @var \App\Classes\Taxonomy  $taxonomy */
                    $rel_name = $taxonomy::getShortClass();
                    $ids = [];
                @endphp
                $('#{{jSID('form')}} .tax-selector[name=tax-{!! $taxonomy::getTaxSlug() !!}]').select2({
                @if ($taxonomy::isHierarchy())
                    escapeMarkup: function (markup) { return markup; },
                    templateResult: function (tax) {
                        if (tax.loading) return tax.text;
                        var title = tax.text;
                        for(var k=1; k<=tax.level; k++){
                            title = '├─'+title;
                        }
                        markup = title;
                        return markup;
                    },
                    templateSelection: function (tax) {
                        return tax.text;
                    },
                    @endif
                    width: "100%",
                    @if ($taxonomy::isSingle())
                    allowClear: true,
                    @endif
                    placeholder: '{!! $taxonomy::getSingular() !!}',
                    @if ($taxonomy::isHierarchy())
                    minimumResultsForSearch: -1,
                    @endif
                    ajax:{
                        url: '{!! route('backend.taxonomy.select',['post_type'=>$post_type::getTypeSlug(),'taxonomy'=>$taxonomy::getTaxSlug()]) !!}',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            var language = $('#{{jSID('form')}} select[name=language]').val();
                            return {
                                keyword: params.term, // search term
                                @if (!$taxonomy::isHierarchy())
                                page: params.page,
                                @endif
                                language: language
                            };
                        },
                        processResults: function (data, params) {
                            @if (!$taxonomy::isHierarchy())
                                params.page = params.page || 1;
                            @endif
                            var items = [];
                            $.each(data, function (i, v) {
                                items.push(
                                    {
                                        id: v.id,
                                        text: v.title,
                                        level: v.level
                                    }
                                );
                            });
                            return {
                                results: items,
                                @if (!$taxonomy::isHierarchy())
                                pagination: {
                                    more: (params.page * 50) < data.total_count
                                }
                                @endif
                            };
                        },
                    }
                });
                @if ($model)
                    @foreach($model->$rel_name as $tax)
                        @php
                            /** @var \App\Classes\Taxonomy  $tax */
                            $ids[] = $tax->id;
                        @endphp
                        var option = new Option('{!! $tax->title !!}', '{!! $tax->id !!}');
                        $('#{{jSID('form')}} .tax-selector[name=tax-{!! $taxonomy::getTaxSlug() !!}]').append(option);
                    @endforeach
                    $('#{{jSID('form')}} .tax-selector[name=tax-{!! $taxonomy::getTaxSlug() !!}]').val({!! json_encode($ids) !!}).trigger('change');
                @endif
            @endforeach

            $('#{{jSID('form')}} select[name=published]').select2({
                width: "100%",
                minimumResultsForSearch: Infinity,
            });

            $('#{{jSID('form')}} input[name=published_at]').daterangepicker({
                applyClass: 'bg-slate-600',
                cancelClass: 'btn-default',
                "singleDatePicker": true,
                "timePicker": true,
                "timePicker24Hour": true,
                "timePickerSeconds": true,
                "drops": "up",
                @if ($model)
                startDate: '{!! $model->published_at->format('Y-m-d H:i:s') !!}',
                @else
                startDate: '{!! date('Y-m-d H:i:s') !!}',
                @endif
                locale: {
                    format: 'YYYY-MM-DD HH:mm:ss',
                    applyLabel: '{{__('Đồng ý')}}',
                    cancelLabel: '{{__('Hủy chọn')}}',
                    startLabel: '{{__('Bắt đầu')}}',
                    endLabel: '{{__('Kết thúc')}}',
                    customRangeLabel: '{{__('Chọn khoản thời gian')}}',
                    daysOfWeek: ['{{__('CN')}}', '{{__('T2')}}', '{{__('T3')}}', '{{__('T4')}}', '{{__('T5')}}', '{{__('T6')}}','{{__('T7')}}'],
                    monthNames: ['{{__('THÁNG 01')}} ', '{{__('THÁNG 02')}} ', '{{__('THÁNG 03')}} ', '{{__('THÁNG 04')}} ', '{{__('THÁNG 05')}} ', '{{__('THÁNG 06')}} ', '{{__('THÁNG 07')}} ', '{{__('THÁNG 08')}} ', '{{__('THÁNG 09')}} ', '{{__('THÁNG 10')}} ', '{{__('THÁNG 11')}} ', '{{__('THÁNG 12')}} '],
                }
            });

            $('#{{jSID('form')}} select[name=user_id]').select2({
                    placeholder: '{{__('Tác giả')}}',
                    id: function (item) {
                        return item.id;
                    },
                    escapeMarkup: function (markup) {
                        return markup;
                    },
                    templateResult: function(repo) {
                        if (repo.loading) {
                            return repo.text;
                        }

                        var markup = '<div class="text-semibold">' + repo.text + '</div>' +
                            '<div class="text-grey">' + repo.role + '</div>' +
                            '<div class="text-size-mini">' + repo.email + '</div>'
                        ;

                        return markup;
                    },
                    templateSelection: function(repo) {
                        return repo.full_name || repo.text;
                    },
                    ajax: {
                        url: '{!! route('backend.user.select') !!}',
                        dataType: 'json',
                        data: function (params) {
                            var rs = {
                                search: params.term,
                            };
                            return rs;
                        },
                        processResults: function (data) {
                            $users = {};
                            var items = [];
                            $.each(data, function (i, v) {
                                $users[v.id] = v;
                                items.push(
                                    {
                                        id: v.id,
                                        text: v.name,
                                        email: v.email,
                                        role: v.role.title
                                    }
                                );
                            });
                            return {
                                results: items
                            };
                        }
                    },
                    minimumInputLength: 2,
            });

            @if ($model)
                @if ($model->user)
                    var option = new Option('{!! $model->user->name !!}', '{!! $model->user->id !!}');
                    $('#{{jSID('form')}} select[name=user_id]').append(option).trigger('change');
                @endif
                $('#{{jSID('form')}} select[name=published]').val('{!! $model->published?1:0 !!}').trigger('change');
            @else
                var option = new Option('{!! me()->name !!}', '{!! me()->id !!}');
                $('#{{jSID('form')}} select[name=user_id]').append(option).trigger('change');
                @if (me()->hasPermission($post_type::getAutoPublishPermissionID()))
                    $('#{{jSID('form')}} select[name=published]').val('1').trigger('change');
                @else
                    @if(me()->hasPermission($post_type::getPublishPermissionID()))
                        $('#{{jSID('form')}} select[name=published]').val('1').trigger('change');
                    @else
                        $('#{{jSID('form')}} select[name=published]').val('0').trigger('change');
                    @endif
                @endif
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
                    window.location = '{!! route('backend.post.index', ['post_type'=>$post_type::getTypeSlug()]) !!}';
                })
            });

            @if (!$model)
            $('#{{jSID('submit_create')}}').click(function () {
                saveForm(this, function (rs) {
                    window.location = '{!! route('backend.post.create', ['post_type'=>$post_type::getTypeSlug()]) !!}';
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
                    url: '{!! route('backend.post.update', ['post_type' =>$post_type::getTypeSlug(), 'post'=>$model->id])!!}',
                    type: 'post',
                    @else
                    url: '{!! route('backend.post.store', ['post_type'=>$post_type::getTypeSlug()]) !!}',
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
    @yield('post_type_edit_after_page_footer_js')
@endpush