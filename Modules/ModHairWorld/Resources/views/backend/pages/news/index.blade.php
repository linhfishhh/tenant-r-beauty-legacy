@extends('backend.pages.post.index')
@section('post_type_index_before_footer_js')
    <script type="text/javascript">
        $(function () {
            $(window).on('wa.datatable.options.{{jSID()}}', function (event) {
                var $columns = event.table_column_handles;
                var handler = new $.dataTableColumnHandler($columns);
                var logo_col = handler.makeColumn('cover','cover', '<th>{!! __('Ảnh') !!}</th>', {
                    width: '100px',
                    className: 'text-center',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        if (data){
                            return '<img style="max-width:100px" class="full-width" src="'+data+'" />';
                        }
                        return '';
                    }
                });
                handler.addColumnBefore('title', logo_col);
                event.table_column_handles = handler.columns;
            });
        });
    </script>
@endsection