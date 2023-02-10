@php
    /** @var \Modules\ModHairWorld\Entities\Salon $salon */
@endphp
@enqueueJSByID(config('view.ui.files.js.datatables.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.datatables_fixed_header.id'), JS_LOCATION_DEFAULT, 'datatables')
@enqueueJSByID(config('view.ui.files.js.datatables_select.id'), JS_LOCATION_DEFAULT, 'datatables')
@enqueueJSByID(config('view.ui.files.js.datatables_buttons.id'), JS_LOCATION_DEFAULT, 'datatables')
@enqueueJSByID(config('view.ui.files.js.editable.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.select2.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.sweet_alert.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.pnotify.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.moment.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.daterangepicker.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@extends('layouts.backend')
@section('page_title', 'Danh sách salon tham gia tuỳ chọn')
@section('page_header_title')
    <strong>Danh sách salon tham gia tuỳ chọn</strong>
@endsection
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
                <button data-loading-text="<i class='icon-spinner4 spinner position-left'></i> {{__('ĐANG XỬ LÝ...')}}"
                        id="{{jSID('add_manager')}}" type="button" class="btn bg-primary btn-block btn-add">
                    {{__('THÊM SALON')}}
                </button>
                @event(new \App\Events\AfterHtmlBlock('sidebar.actions'))
            </div>
        </div>
    </div>
{{--
    @include('modhairworld::backend.pages.salon.edit.includes.sidebar_items', ['salon'=>$salon])
--}}
@endsection
@section('page_content_body')
    @event(new \App\Events\BeforeHtmlBlock('content'))
    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title text-teal text-semibold text-uppercase mb-3" id="title">{{$salon->name}}</h6>
            <form class="form-inline frmEditName" method="post" action="" > 
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="form-group mx-sm-6 mb-2">
                    <input type="text" class="form-control" name="title" id="txtTitle" placeholder="Nhập tên mới" >
                </div>
                <button type="submit" class="btn btn-primary mb-2">Sửa</button>
            </form>
             @if ($errors->any())
                <div class="text-danger">
                        @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                        @endforeach
                </div>
                @endif
        </div>
        <table data-table-name="{{jSID()}}" id="datatable_{{jSID()}}" class="table table-condensed table-hover">
            <thead>
            <tr></tr>
            </thead>
        </table>
    </div>
    <div id="modal-add-manager" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header bg-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h6 class="modal-title">{!! __('THÊM SALON') !!}</h6>
                    </div>
                    <div class="modal-body">
                        <select id="salon-selector" name="salon_id"></select>
                        <div class="error-placeholder text-danger"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">{!! __('ĐÓNG') !!}</button>
                        <button type="submit" class="btn btn-primary">{!! __('THÊM SALON') !!}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @event(new \App\Events\AfterHtmlBlock('content'))
@endsection
@push('page_footer_js')
    @include('backend.settings.datatable.default')
    @include('backend.settings.xeditable.default')
    <script type="text/javascript">
            // $('.frmEditName').submit(function(e){
            //     e.preventDefault();
            //     $.ajax({
            //         url: route('backend.top_salons.postshow',$id),
            //         type: 'post',
            //         data: {name: $('#txtTitle')},
            //         dataType: 'json',
            //         beforeSend: function(){
                        
            //         },
            //         error: function(json){
                       
            //         },
            //         success: function (json) {
            //             console.log(json)
            //         }
            //     });
            //     return false;
            // });
            // });
            $(function () {
            $modal_add = $('#modal-add-manager').modal({
                show: false,
                backdrop: 'static'
            });
            $('#{{jSID('add_manager')}}').click(function () {
                $modal_add.modal('show');
            });

            $('#salon-selector').select2({
                width: '100%',
                placeholder: '{{__('Chọn salon')}}',
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

                    var markup = '<div class="text-semibold">'+repo.text + '</div>' +
                        '<div class="text-muted">' + repo.address + '</div>'
                    ;

                    return markup;
                },
                templateSelection: function(repo) {
                    return repo.full_name || repo.text;
                },
                ajax: {
                    url: '{!! route('backend.top_salons.salons') !!}',
                    dataType: 'json',
                    data: function (params) {
                        var rs = {
                            search: params.term,
                        };
                        return rs;
                    },
                    processResults: function (data) {
                        var items = [];
                        $.each(data, function (i, v) {
                            items.push(
                                {
                                    id: v.id,
                                    text: v.name,
                                    address: v.address_cache
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

            $modal_add.find('form').submit(function(){
                var form = this;
                var id = {{$id}};
                var salon_id = $(form).find('select').val();
                var url = '{!! route('backend.top_salons.add', ['salon'=>'???']) !!}';
                url = url.replace('???', salon_id);
                $.ajax({
                    url: url,
                    type: 'post',
                    data: {id},
                    dataType: 'json',
                    beforeSend: function(){
                        cleanErrorMessage(form);
                        $modal_add.find('button').prop('disabled', true);
                    },
                    error: function(json){
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
                        handleErrorMessage(form, json);
                    },
                    success: function (json) {
                        cleanErrorMessage(form);
                        $modal_add.modal('hide');
                        if(json){
                            new PNotify({
                                title: '{{'LƯU DỮ LIỆU'}}',
                                text: '{{__('Lưu dữ liệu thành công')}}',
                                addclass: 'bg-success stack-bottom-right',
                                stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25},
                                buttons: {
                                    sticker: false
                                },
                                delay: 2000
                            });
                        }
                        else{
                            new PNotify({
                                title: '{{'LƯU DỮ LIỆU'}}',
                                text: '{{__('Salon này đã nằm trong danh sách tham gia rồi')}}',
                                addclass: 'bg-danger stack-bottom-right',
                                stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25},
                                buttons: {
                                    sticker: false
                                },
                                delay: 2000
                            });
                        }
                        $table.draw('page');
                    },
                    complete: function () {
                        $modal_add.find('button').prop('disabled', false);
                    }
                });
                return false;
            });

            $(window).trigger({
                type: 'wa.datatable.init.{{jSID()}}',
                table_html: $('#datatable_{{jSID()}}')[0]
            });
            var $table_column_handles = [
                {
                    data: 'id',
                    name: 'id',
                    title_html: '<th>{!! __('ID') !!}</th>',
                    cell_define: {
                        searchable: false,
                        width: "100px",
                    }
                },
                {
                    data: 'name',
                    name: 'name',
                    title_html: '<th>{!! __('Tên') !!}</th>',
                    cell_define: {
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            return '<div class="text-bold text-teal">'+row.name+'</div>' +
                                '<div class="text-muted">'+row.address_cache+'</div>';
                        }
                    }
                },
                {
                    data: '',
                    name: 'action',
                    title_html: '<th>{!! __('Hành động') !!}</th>',
                    cell_define: {
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: "150px",
                        render: function (data, type, row) {
                            // console.log(row);
                            var html = '<a data-id="'+row.id+'" class="label label-warning label-icon cursor-pointer delete-manager"><i class="icon-trash"></i></a>';
                            return html;
                        }
                    }
                },
            ];
            var $options = {
                select: false,
                autoWidth: false,
                search: false,
                ajax: '{!! route('backend.top_salons.show', $id ) !!}',
                columns: [],
                columnDefs: []
            };
            var event = $.Event('wa.datatable.options.{{jSID()}}');
            event.table_options = $options;
            event.table_html = $('#datatable_{{jSID()}}')[0];
            event.table_column_handles = $table_column_handles;
            $(window).trigger(event);
            $.each(event.table_column_handles, function (i, v) {
                $('#datatable_{{jSID()}}>thead>tr').append(v.title_html);
                $('#datatable_{{jSID()}}>tfoot>tr').append(v.title_html);
                $options.columns.push({
                    name: v.name,
                    data: v.data
                });
                var h = v.cell_define;
                h.targets = i;
                $options.columnDefs.push(h);
            });
            var $table = $('#datatable_{{jSID()}}').DataTable(event.table_options);
            $table.on('preXhr.dt', function (e, settings, data) {});
            $table.on('preInit.dt', function (e) {});
            $table.on('draw', function (e, settings) {
                $(e.target).find('[data-popup="tooltip"]').tooltip({
                    title: function () {
                        var t = $(this).data('tooltip-title');
                        return t;
                    }
                });
                $(e.target).find('.delete-manager').click(function () {
                    var ids = [];
                    var id = $(this).data('id');
                    ids.push(id);
                    deleteManagers(ids);
                });
            });
            function deleteManagers(ids){
                if (ids.length == 0) {
                    swal({
                        title: "{{__('Không thể thực thi')}}",
                        text: "{!! __('Vui lòng chọn 1 quản lý để xóa') !!}",
                        confirmButtonColor: "#EF5350",
                        confirmButtonText: "{{__('Đã hiểu')}}",
                        type: "error"
                    });
                    return;
                }
                swal({
                        title: "{!! __('Gở bỏ salon tham gia') !!}",
                        text: "{!! __('Bạn chắc chắn muốn gở bỏ salon này ra khỏi danh sách tham gia') !!}",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#EF5350",
                        confirmButtonText: "{{__('Đồng ý')}}",
                        cancelButtonText: "{{__('Hủy bỏ')}}",
                        closeOnConfirm: true,
                        closeOnCancel: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: '',
                                dataType: 'json',
                                type: 'post',
                                data: {
                                    ids: ids,
                                    _method: 'delete'
                                },
                                success: function () {
                                    new PNotify({
                                        title: '{{'XỬ LÝ THÀNH CÔNG'}}',
                                        text: "{!! __('Đã gở salon tham gia thành công') !!}",
                                        addclass: 'bg-success stack-bottom-right',
                                        stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25},
                                        buttons: {
                                            sticker: false
                                        },
                                        delay: 2000
                                    });
                                },
                                error: function (data) {
                                    new PNotify({
                                        title: '{{'LỖI XẢY RA'}}',
                                        text: getJSONErrorMessage(data, '{{__('Lỗi không xác định')}}'),
                                        addclass: 'bg-danger stack-bottom-right',
                                        stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25},
                                        buttons: {
                                            sticker: false
                                        },
                                        delay: 2000
                                    });
                                },
                                complete: function () {
                                    $table.draw('page');
                                }
                            });
                        }
                    });
            }


            $(window).trigger({
                type: 'wa.datatable.ran.{{jSID()}}',
                table_object: $table,
                table_html: $('#datatable_{{jSID()}}')[0]
            });
        });

    </script>
@endpush