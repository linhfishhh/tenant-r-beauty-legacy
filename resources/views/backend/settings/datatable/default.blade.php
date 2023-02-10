<script type="text/javascript">
    $.fn.dataTable.ext.errMode = 'none';
    $.extend( $.fn.dataTable.defaults, {
        order: [
            [
                0, 'desc'
            ]
        ],
        buttons: [
            {
                extend: 'colvis',
                text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                className: 'btn btn-default btn-icon',
                collectionLayout: 'fixed two-column'
            }
        ],
        processing: true,
        stateSave: true,
        select: false,
        pageLength: 20,
        searchDelay: 500,
        stateSaveCallback: function(settings,data) {
            $.ajax( {
                url: "{{route('backend.setting.set')}}",
                data: {
                    name: '{{Route::currentRouteName()}}_'+settings.sInstance,
                    value: data,
                    _method: 'put'
                },
                error: function (data) {
                    console.log(data)
                },
                dataType: "json",
                type: 'post'
            } );
        },
        stateLoadCallback: function(settings, callback) {
            //return JSON.parse( localStorage.getItem( '{{Route::currentRouteName()}}_' + settings.sInstance ) )
            $.ajax( {
                url: "{{route('backend.setting.get')}}",
                data: {
                    name: '{{Route::currentRouteName()}}_'+settings.sInstance,
                    'default': {},
                },
                complete: function (data) {
                    if (data.responseJSON.hasOwnProperty('columns')){
                        $.each(data.responseJSON.columns, function (i,c)
                        {
                            if (c.hasOwnProperty('visible')){
                                if (c.visible == "true"){
                                    c.visible = true;
                                }
                                if (c.visible == "false"){
                                    c.visible = false;
                                }
                            }
                        });
                    }
                    callback(data.responseJSON);
                },
                dataType: "json",
                type: 'get'
            } );
        },
        stateSaveParams: function (settings, data) {
            delete data.search;
            data.start = 0;

        },
        serverSide: true,
        // fixedHeader: {
        //     header: true,
        //     headerOffset: $('.navbar-fixed-top').height()
        //
        // },
        highlight: false,
        //dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        dom: '<"datatable-header"fBl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            select: {
                rows: {
                    _: '{{__("Đã chọn :num dòng", ['num'=>'%d'])}}',
                    1: '{{__('Đã chọn 1 dòng')}}'
                }
            },
            search: '_INPUT_',
            searchPlaceholder: '{{__('Nhập từ khóa...')}}',
            lengthMenu: '<span>{{__('Hiển thị')}}:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' },

            "decimal":        "",
            "emptyTable":     "{{__('Không có dữ liệu tương ứng')}}",
            info:           '{{__('Trang :start/:end trên tổng :total bản tin', ['start'=>'_PAGE_', 'end'=>'_PAGES_', 'total'=>'_TOTAL_'])}}',
            infoEmpty:      '',
            infoFiltered:   '{{__('Lọc từ :max bản tin', ['max'=>'_MAX_'])}}',
            //"infoPostFix":    "",
            //"thousands":      ",",
            loadingRecords: "{{__('Đang tải...')}}",
            processing:     "{{__('Đang xử lý...')}}",
            zeroRecords:    "{{__('Không tìm thấy dữ liệu tương ứng')}}",
            aria: {
                sortAscending:  ": {{__('Sắp sếp tăng dần')}}",
                sortDescending: ": {{__('Sắp sếp giảm dần')}}"
            }
        },
        initComplete: function () {
            $(this).parents('.dataTables_wrapper').find('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity,
                width: '100px'
            });
        },
        "lengthMenu":  [10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 150, 200],
    });
    $.dataTableColumnHandler = function dataTableColumnHandler(columns) {
        var self = this;
        self.columns = columns;
        self.findColumnByName = function (column_name) {
            var rs = null;
            $.each(self.columns, function(i, column){
                if (column.name == column_name){
                    rs = column;
                    return false;
                }
            });
            return rs;
        };
        self.removeColumnByName = function (column_name) {
            var rs = [];
            $.each(self.columns, function(i, column) {
                if (column.name != column_name){
                    rs.push(column);
                }
            });
            self.columns = rs;
            return rs;
        };
        self.setColumnAttributesByName = function (column_name, attrs) {
            $.each(self.columns, function (i, column) {
               if (column.name == column_name){
                   $.each(attrs, function (attr_name, attr_value) {
                       column[attr_name] = attr_value;
                   });
                   return false;
               }
            });
        };
        self.addColumnBefore = function (column_name, column) {
           var rs = [];
           var added = false;
            $.each(self.columns, function (i, current_column) {
                if (current_column.name == column_name){
                    rs.push(column);
                    added = true;
                }
                rs.push(current_column);
            });
            if (!added){
                rs.push(column);
            }
            self.columns = rs;
        };
        self.addColumnAfter = function (column_name, column) {
            var rs = [];
            var added = false;
            $.each(self.columns, function (i, current_column) {
                rs.push(current_column);
                if (current_column.name == column_name){
                    rs.push(column);
                    added = true;
                }
            });
            if (!added){
                rs.push(column);
            }
            self.columns = rs;
        };
        self.makeColumn = function (name, data, title_html, cell_define) {
            return {
                name: name,
                data: data,
                title_html: title_html,
                cell_define: cell_define
            };
        };
        return self;
    };
</script>