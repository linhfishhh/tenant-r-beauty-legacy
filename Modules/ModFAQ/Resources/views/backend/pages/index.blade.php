@extends('backend.pages.post.index')
@section('post_type_index_before_footer_js')
    <template id="{!! jSID('faq_filter_tpl') !!}">
        <div class="dataTables_filter answer_filter">
            <div class="input-group">
                <select id="{!! jSID('answer_filter') !!}">
                    <option value="-1">{{__('Trạng thái trả lời')}}</option>
                    <option value="0">{!! __('Chưa trả lời') !!}</option>
                    <option value="1">{!! __('Đã trả lời') !!}</option>
                </select>
            </div>
        </div>
    </template>
    <script type="text/javascript">
        $(function () {
            $(window).on('wa.datatable.ran.{{jSID()}}', function (e) {
                var $table = e.table_object;
                $table.on('preXhr.dt', function (e, settings, data) {
                    data.answered = $('#{!! jSID('answer_filter') !!}').val();
                });
                $table.on('preInit.dt', function (e) {
                    var node = $('#{{jSID('faq_filter_tpl')}}').html();
                    node = $(node);
                    var parent_node = $(e.target).parents('.dataTables_wrapper').find('.datatable-header:first-child .visible_filter');
                    $(node).insertAfter(parent_node);
                    $(node).find('select').select2({
                        minimumResultsForSearch: Infinity,
                        width: "150px",
                    }).on('change', function () {
                        $table.draw('page');
                    });
                });
                $table.on('draw', function (e) {
                    $(e.target).find('.notify-faq').click(function () {
                        var items = $table.rows(':eq(' + $(this).parents('tr').index() + ')', {page: 'current'});
                        var data = items.data()[0];
                        if(data.answer.trim().length == 0){
                            swal({
                                title: "{{__('Không thể thực thi')}}",
                                text: "{{__('Vui lòng nhập câu trả lời trước khi thông báo cho người đặt câu hỏi')}}",
                                confirmButtonColor: "#EF5350",
                                confirmButtonText: "{{__('Đã hiểu')}}",
                                type: "error"
                            });
                        }
                        else{
                            var ids = [data.id];
                            swal({
                                    title: "Gửi thông báo",
                                    text: "Bạn thật sự muốn gửi thông báo đến người đặt câu hỏi về việc câu hỏi đã được trả lời hay không?",
                                    type: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#EF5350",
                                    confirmButtonText: "{{__('Đồng ý')}}",
                                    cancelButtonText: "{{__('Hủy bỏ')}}",
                                    closeOnConfirm: true,
                                    closeOnCancel: true
                                },
                                function (isConfirm) {
                                    if (isConfirm){
                                        $.ajax({
                                            url: '{!! route('backend.faq.send_notify') !!}',
                                            type: 'post',
                                            dataType: 'json',
                                            data:{
                                                ids: ids
                                            },
                                            success: function () {
                                                new PNotify({
                                                    title: '{{'XỬ LÝ THÀNH CÔNG'}}',
                                                    text: "Đã xử lý thông báo thành công",
                                                    addclass: 'bg-success stack-bottom-right',
                                                    stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25},
                                                    buttons: {
                                                        sticker: false
                                                    },
                                                    delay: 2000
                                                });
                                                $table.draw('page');
                                            },
                                            error: function () {
                                                new PNotify({
                                                    title: '{{'LỖI XẢY RA'}}',
                                                    text: getJSONErrorMessage(data, '{{__('Lỗi không xác định')}}'),
                                                    addclass: 'bg-danger stack-bottom-right',
                                                    stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25},
                                                    buttons: {
                                                        sticker: false
                                                    },
                                                    delay: 2000
                                                })
                                            }
                                        });
                                    }
                                });
                        }
                    });
                });
            });
            $(window).on('wa.datatable.options.{{jSID()}}', function (event) {
                var $columns = event.table_column_handles;
                var handler = new $.dataTableColumnHandler($columns);
                var title_col = handler.findColumnByName('title');
                var tfn = title_col.cell_define.render;
                title_col.cell_define.render = function(data, type, row){
                    var html = tfn(data, type, row);
                    html += '<div class="mt-5">';
                    if (row.answer){
                        html += '<i class="text-success icon-checkmark position-left"></i><span class="">Đã trả lời</span>';
                    }
                    else{
                        html += '<i class="text-danger icon-blocked position-left"></i><span class="">Chưa trả lời</span>';
                    }
                    html += '</div>';
                    if(row.need_notify) {
                        html += '<div class="mt-5 "><span class="text-warning"><i class="icon-users position-left"></i>Câu hỏi của khách</span>';
                        if(row.notified){
                            html += '<span class="ml-15 text-success cursor-pointer notify-faq"><i class="icon-station position-left"></i>Đã thông báo</span></div>';
                        }
                        else{
                            html += '<span class="ml-15 text-muted cursor-pointer notify-faq"><i class="icon-station position-left"></i>Chưa thông báo</span></div>';
                        }
                    }
                    return html;
                };
                event.table_column_handles = handler.columns;
            });
        });
    </script>
@endsection