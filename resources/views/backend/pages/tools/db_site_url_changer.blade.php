@enqueueJSByID(config('view.ui.files.js.pnotify.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.sweet_alert.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@extends('layouts.backend')
@section('page_title', __('Công cụ tạo đổi site url'))
@section('page_header_title')
    {!! __('Công cụ <strong>đổi site url</strong>') !!}
@endsection
@section('sidebar_second')
    <div class="sidebar-category">
        <div class="category-title">
            <span>{{__('HÀNH ĐỘNG')}}</span>
            <ul class="icons-list">
                <li><a href="#" data-action="collapse"></a></li>
            </ul>
        </div>

        <div class="category-content tool-actions">
            <div class="form-group">
                <label>{!! __('Url cũ cần thay') !!}</label>
                <input id="tool_old_url" class="form-control" type="text" spellcheck="false" value=""
                       placeholder="{!! __('Nhập site url cũ') !!}">
            </div>
            <div class="form-group">
                <label>{!! __('Sẽ thay bằng') !!}</label>
                <input id="tool_new_url" class="form-control" type="text" spellcheck="false" value="{!! url('') !!}">
            </div>
            <div class="content-group">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active" style="width: 0%">
                        <span class="sr-only">0% Complete</span>
                    </div>
                </div>
            </div>
            <div class="mb-10">
                <button data-loading-text="<i class='icon-spinner4 spinner position-left'></i> {{__('ĐANG XỬ LÝ...')}}"
                        id="{{jSID('submit')}}" type="button" class="btn bg-orange btn-block btn-pt">
                    {{__('PHÂN TÍCH')}}
                </button>
                <button data-loading-text="<i class='icon-spinner4 spinner position-left'></i> {{__('ĐANG XỬ LÝ...')}}"
                        id="{{jSID('submit')}}" type="button" class="btn bg-primary btn-block btn-run">
                    {{__('THỰC THI')}}
                </button>
            </div>
        </div>
    </div>
@endsection
@section('page_content_body')
    <div class="alert alert-info">
        <p>{!! __('Công cụ này dùng để đổi những link cứng nằm trong các cột của các bảng cơ sở dữ liệu sang địa chỉ url của trang website hiện tại <strong>:current_url</strong>. Hữu dụng khi đổi domain hoặc đổi địa chỉ lưu trữ website', ['current_url'=>url('')]) !!}</p>
    </div>
    <div class="alert alert-danger">
        <p>{!! __('Vui lòng backup cơ sở dữ liệu trước khi sử dụng công cụ này!') !!}</p>
    </div>
    @component('backend.components.panel', ['classes' => 'panel-default', 'has_body' => 0])
        @slot('title')
            <strong class="text-teal">{!! __('Các bảng được hổ trợ') !!}</strong>
        @endslot
        @slot('content')
                <table class="table table-bordered table-condensed">
                        <thead>
                        <tr>
                            <th>{!! __('Tên bảng') !!}</th>
                            <th>{!! __('Cột') !!}</th>
                            <th class="text-center">{!! __('Số dòng bị thay') !!}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($changers as $table_name=>$column_names)
                            @foreach($column_names as $key=>$column_name)
                                <tr data-table="{!! $table_name !!}" data-column="{!! $column_name !!}">
                                    @if(count($column_names)>1)
                                        @if($key == 0)
                                            <td rowspan="{!! count($column_names) !!}">
                                                <span class="text-semibold text-slate">{!! $table_name !!}</span>
                                            </td>
                                        @endif
                                        <td><span class="text-semibold text-slate">{!! $column_name !!}</span></td>
                                        @if($key == 0)
                                            <td rowspan="{!! count($column_names) !!}" class="text-center"><span class="text-slate-300">{!! __('Click nút "Phân tích" để xem') !!}</span></td>
                                        @endif
                                    @else
                                        <td>
                                            <span class="text-semibold text-slate">{!! $table_name !!}</span>
                                        </td>
                                        <td><span class="text-semibold text-slate">{!! $column_name !!}</span></td>
                                        <td class="text-center"><span class="text-slate-300">{!! __('Click nút "Phân tích" để xem') !!}</span></td>
                                    @endif
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
        @endslot
    @endcomponent
@endsection
@push('page_footer_js')
    <script type="text/javascript">
        $(function () {
            var $data = {!! json_encode($changers) !!};
            function check() {
                var old_url = $('#tool_old_url').val().trim();
                var new_url = $('#tool_new_url').val().trim();
                if (old_url.length == 0 || new_url.length == 0) {
                    swal({
                        title: "{{__('Không thể thực thi')}}",
                        text: "{{__('Vui lòng nhập đầy đủ thông tin về url cũ và mới')}}",
                        confirmButtonColor: "#EF5350",
                        confirmButtonText: "{{__('Đã hiểu')}}",
                        type: "error"
                    });
                    return false;
                }
                else {
                    return [old_url, new_url];
                }
            }
            var $done = 0;
            var $total = 0;
            var $query = 0;
            function runAnalyze(){
                var table = $query.pop();
                $.ajax(
                    {
                        url: '{!! route('backend.tool.change_db_site_url.analyze') !!}',
                        type: 'get',
                        dataType: 'json',
                        data:{
                            table: table.table
                        },
                        success: function (data) {
                            console.log(data);
                        },
                        complete: function () {
                            if ($query.length>0){
                                runAnalyze();
                            }
                        }
                    }
                );
            }
            function initQueue() {
                $query = getQueryArray();
                $total = $query.length;
                $done = 0;
            }
            function getQueryArray(){
                $rs = [];
                $.each($data, function (i, v) {
                    $rs.push({
                        table: i,
                        columns: v
                    });
                });
                return $rs;
            }
            $('.tool-actions .btn-pt').click(function () {
                var urls = check();
                if (urls) {
                    initQueue();
                    runAnalyze();
                }
            });
        })
    </script>
@endpush