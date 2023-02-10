@enqueueJS('exceljs', getThemeAssetUrl('libs/excel/xlsx.full.min.js'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.blockui.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@enqueueJSByID(config('view.ui.files.js.moment.id'), JS_LOCATION_DEFAULT, 'jquery')
@enqueueJSByID(config('view.ui.files.js.sweet_alert.id'), JS_LOCATION_DEFAULT, 'jquery')
@php

@endphp
@extends('layouts.backend')
@section('page_title',__('Import thông tin salon'))
@section('page_header_title')
    <strong>{{__('Import thông tin salon')}}</strong>
@endsection
@section('page_content_body')
    @component('backend.components.panel', ['classes'=>'panel-default wa-tool-import-wrapper', 'has_body' => true])
        @slot('content')
        <div class="form-horizontal">
            <fieldset class="content-group">
                <legend class="text-bold">File excel</legend>
                <div class="form-group">
                    <label class="control-label col-lg-2">Chọn file excel xử lý</label>
                    <div class="col-lg-10">
                        <div class="uploader"><input accept=".xls,.xlsx" id="wa-file-excel" type="file" class="file-styled-primary"><span id="wa-file-excel-name" class="filename" style="user-select: none;">Chưa chọn file excel</span><span class="action btn bg-blue" style="user-select: none;">CHỌN FILE EXCEL</span></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-2">Số dòng dữ liệu</label>
                    <div class="col-lg-10">
                        <div id="wa-salon-count" class="form-control-static">
                            N/A
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-2">Đã xử lý</label>
                    <div class="col-lg-10">
                        <div id="wa-salon-processed" class="form-control-static">
                            N/A
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-2">Thành công</label>
                    <div class="col-lg-10">
                        <div id="wa-salon-success" class="form-control-static">
                            N/A
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-2">Thất bại</label>
                    <div class="col-lg-10">
                        <div id="wa-salon-error" class="form-control-static">
                            N/A
                        </div>
                    </div>
                </div>
            </fieldset>
            <div class="progress content-group-sm">
                <div class="progress-bar progress-bar-success progress-bar-striped active" id="wa-import-progress" style="width: 0%">
                </div>
            </div>
            <div class="text-right">
                <button id="wa-import-button-rollback" type="submit" class="btn btn-warning disabled">ROLLBACK IMPORT</button>
                <button id="wa-import-button" type="submit" class="btn btn-success disabled">CHẠY IMPORT</button>
            </div>
        </div>
        @endslot
    @endcomponent
    @component('backend.components.panel', ['title'=>'LOG KẾT QUẢ', 'classes'=>'panel-default', 'has_body' => false])
        @slot('content')
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                    <tr>
                        <th style="width: 150px; text-align: center">Dòng</th>
                        <th style="width: 200px; text-align: center">Trang thái</th>
                        <th>Thông điệp</th>
                    </tr>
                    </thead>
                    <tbody id="wa-log-list">
                    </tbody>
                </table>
            </div>
        @endslot
    @endcomponent
@endsection
@push('page_footer_js')
    <script type="text/javascript">
       $(function () {
           var rABS = true;
           var import_data = [];
           var do_import_data = [];
           var processed = 0;
           var success = 0;
           var error = 0;
           var processing = false;
           var imported_rows = [];

           function updateStats() {
               $('#wa-salon-processed').html(processed);
               $('#wa-salon-success').html(success);
               $('#wa-salon-error').html(error);
               updateProgress();
           }

           function resetProgress() {
               $('#wa-import-progress').css('width', '0%')
           }

           function updateProgress() {
               var cr = import_data.length;
               var tt = processed;
               var percent = tt*100.0/cr;
               $('#wa-import-progress').css('width', percent+'%')
           }

           function resetStats() {
               processed = 0;
               success = 0;
               error = 0;
               imported_rows = [],
               updateStats();
           }

           function rollback() {
               if(imported_rows.length == 0){
                   return;
               }
               $.ajax({
                   url: '{!! route('salon_tools.import.rollback') !!}',
                   type: 'post',
                   dataType: 'json',
                   data: {
                       record: imported_rows
                   },
                   beforeSend: function(){
                       resetLog();
                       processing = true;
                       updateStats();
                       $('.wa-tool-import-wrapper').block({
                           message: '<i class="icon-spinner4 spinner"></i>',
                           overlayCSS: {
                               backgroundColor: '#fff',
                               opacity: 0.8,
                               cursor: 'wait'
                           },
                           css: {
                               border: 0,
                               padding: 0,
                               backgroundColor: 'transparent'
                           }
                       });
                   },
                   success: function (json) {
                        imported_rows = [];
                       swal({
                           title: "{!! __('Rollback') !!}",
                           text: "{!! __('Những thông tin import đã được xoá bỏ thành công!') !!}",
                           type: "success",
                           confirmButtonColor: "#EF5350",
                           confirmButtonText: "{{__('ĐÃ HIỂU')}}",
                           closeOnConfirm: true,
                       });
                   },
                   error: function (json) {
                   },
                   complete: function () {
                       processing = false;
                       $('.wa-tool-import-wrapper').unblock();
                       resetStats();
                       updateStats();
                       checkButton();
                   }
               });
           }

           function importRow() {
               if(do_import_data.length == 0){
                   processing = false;
                   resetProgress();
                   checkButton();
                   swal({
                       title: "{!! __('Import') !!}",
                       text: "{!! __('Tiến trình import đã hoàn thành!') !!}",
                       type: "success",
                       confirmButtonColor: "#EF5350",
                       confirmButtonText: "{{__('ĐÃ HIỂU')}}",
                       closeOnConfirm: true,
                   });
                   return;
               }
               var current_do = do_import_data.pop();
               var post_data = {
                   salon_name: current_do.salon_name,
                   salon_desc:  current_do.salon_desc,
                   salon_stylists: current_do.stylists.split("\n"),
                   manager_name: current_do.manager_name,
                   manager_phone:  current_do.manager_phone,
                   manager_email: current_do.manager_email,
                   salon_times: [
                       {
                           weekday: 1,
                           start: moment().startOf('day').add(current_do.t2_open*24*60, 'minute').format('HH:mm'),
                           end: moment().startOf('day').add(current_do.t2_close*24*60, 'minute').format('HH:mm'),
                       },
                       {
                           weekday: 2,
                           start: moment().startOf('day').add(current_do.t3_open*24*60, 'minute').format('HH:mm'),
                           end: moment().startOf('day').add(current_do.t3_close*24*60, 'minute').format('HH:mm'),
                       },
                       {
                           weekday: 3,
                           start: moment().startOf('day').add(current_do.t4_open*24*60, 'minute').format('HH:mm'),
                           end: moment().startOf('day').add(current_do.t4_close*24*60, 'minute').format('HH:mm'),
                       },
                       {
                           weekday: 4,
                           start: moment().startOf('day').add(current_do.t5_open*24*60, 'minute').format('HH:mm'),
                           end: moment().startOf('day').add(current_do.t5_close*24*60, 'minute').format('HH:mm'),
                       },
                       {
                           weekday: 5,
                           start: moment().startOf('day').add(current_do.t6_open*24*60, 'minute').format('HH:mm'),
                           end: moment().startOf('day').add(current_do.t6_close*24*60, 'minute').format('HH:mm'),
                       },
                       {
                           weekday: 6,
                           start: moment().startOf('day').add(current_do.t7_open*24*60, 'minute').format('HH:mm'),
                           end: moment().startOf('day').add(current_do.t7_close*24*60, 'minute').format('HH:mm'),
                       },
                       {
                           weekday: 7,
                           start: moment().startOf('day').add(current_do.t8_open*24*60, 'minute').format('HH:mm'),
                           end: moment().startOf('day').add(current_do.t8_close*24*60, 'minute').format('HH:mm'),
                       },
                   ]
               };
               //console.log(post_data);
               $.ajax({
                   url: '{!! route('salon_tools.import.do') !!}',
                   type: 'post',
                   dataType: 'json',
                   data: post_data,
                   success: function (json) {
                       success++;
                       imported_rows = imported_rows.concat(json.rollback);
                       log(processed+1, '<span class="text-semibold text-success">Thành công</span>', json.message);
                       // console.log(json);
                   },
                   error: function (json) {
                       error++;
                       var error_msg = '<ul>';
                       var add = '<li>Lỗi khi thực thi import</li>';
                       if(json.status == 422){
                           if(json.responseJSON){
                               if(json.responseJSON.errors){
                                   for(var name in json.responseJSON.errors){
                                       add += '<li>'+json.responseJSON.errors[name][0]+'</li>';
                                   }
                               }
                           }
                       }
                       error_msg += add+'</ul>';
                       log(processed+1, '<span class="text-semibold text-danger">Thất bại</span>', error_msg);
                       //console.log(json);
                   },
                   complete: function () {
                       processed++;
                       updateStats();
                       importRow();
                   }
               });
           }

           function runimport() {
               do_import_data = JSON.parse(JSON.stringify(import_data));
               do_import_data.reverse();
               imported_rows = [];
               processing = true;
               resetStats();
               checkButton();
               resetLog();
               importRow();
           }

           function checkButton() {
               //console.log(imported_rows);
               //console.log(processing, import_data.length)
               if(!processing && import_data.length>0){
                    $('#wa-import-button').removeClass('disabled');
               }
               else{
                   $('#wa-import-button').addClass('disabled');
               }

               if(!processing && imported_rows.length>0){
                   $('#wa-import-button-rollback').removeClass('disabled');
               }
               else{
                   $('#wa-import-button-rollback').addClass('disabled');
               }
           }

           function resetLog() {
               $('#wa-log-list').html('');
           }

           function log(row, status, message) {
                var row = '<tr>' +
                    '<td style="width: 150px; text-align: center">'+row+'</td>' +
                    '<td style="width: 200px; text-align: center">'+status+'</td>' +
                    '<td>'+message+'</td>' +
                    '</tr>';
                $('#wa-log-list').append(row);
           }

           function handleFile(file) {
               var reader = new FileReader();
               reader.onload = function(e) {
                   processing = true;
                   checkButton();
                   resetLog();
                   var data = e.target.result;
                   if(!rABS) data = new Uint8Array(data);
                   var workbook = XLSX.read(data, {type: rABS ? 'binary' : 'array', cellDates: false,});

                   /* DO SOMETHING WITH workbook HERE */
                   //console.log(workbook);
                   var first_sheet_name = workbook.SheetNames[0];
                   var worksheet = workbook.Sheets[first_sheet_name];
                   var data = XLSX.utils.sheet_to_json(worksheet, {range: 3, header: [
                        'salon_name',
                           'salon_desc',
                           'stylists',
                           'manager_name',
                           'manager_phone',
                           'manager_email',

                           't2_open',
                           't2_close',

                           't3_open',
                           't3_close',

                           't4_open',
                           't4_close',

                           't5_open',
                           't5_close',

                           't6_open',
                           't6_close',

                           't7_open',
                           't7_close',

                           't8_open',
                           't8_close',
                       ], defval: null});
                   import_data = data;
                   $('#wa-salon-count').html(data.length);
                   $('.wa-tool-import-wrapper').unblock();
                   processing = false;
                   resetStats();
                   checkButton();
               };
               if(rABS) reader.readAsBinaryString(file); else reader.readAsArrayBuffer(file);
           }
           $('#wa-file-excel').on('change', function () {
               if(!this.files[0]){
                   return;
               }
               var file = this.files[0];
               //console.log(file);
               $('#wa-file-excel-name').html(file.name);
               $('.wa-tool-import-wrapper').block({
                   message: '<i class="icon-spinner4 spinner"></i>',
                   overlayCSS: {
                       backgroundColor: '#fff',
                       opacity: 0.8,
                       cursor: 'wait'
                   },
                   css: {
                       border: 0,
                       padding: 0,
                       backgroundColor: 'transparent'
                   }
               });
               handleFile(file);
               $(this).val(null);
           });

           $('#wa-import-button').click(function () {
               runimport();
           });

           $('#wa-import-button-rollback').click(function () {
               rollback();
           });

           checkButton();
       })
    </script>
@endpush