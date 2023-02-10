@enqueueJSByID(config('view.ui.files.js.jquery_ui.id'), JS_LOCATION_DEFAULT, 'bootstrap')
@extends('backend.pages.post.index')
@section('post_type_index_after_footer_js')
    <div id="modal_cat_ordering" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" id="cat-ordering-form">
                <div class="modal-header">
                    <h5 class="modal-title">SĂP XẾP THỨ TỰ DANH MỤC</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <ul class="selectable-demo-list" id="sortable-cats-placeholder" style="width: 100%!important;">

                    </ul>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">ĐÓNG</button>
                    <button type="submit" class="btn bg-primary">LƯU THỨ TỰ</button>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        $(function () {
           $('.datatable-post-actions').append(
               '<li>' +
                   '<a class="action-ordering">' +
                   '<i class="icon-sort"></i>'+
               'Sắp xếp thứ tự' +
               '</a>'+
               '</li>'
           );
           $modal = $('#modal_cat_ordering').modal({
               show:false,
               backdrop: 'static'
           });
           var working = false;

            $('#sortable-cats-placeholder').sortable({
                placeholder: 'sortable-placeholder',
                start: function(e, ui){
                    ui.placeholder.height(ui.item.outerHeight());
                }
            });
            $('#sortable-cats-placeholder').disableSelection();

           $('.datatable-post-actions .action-ordering').click(function(){
               if(working){
                   return;
               }
               $('#sortable-cats-placeholder').html('');
               $.ajax({
                   url: '{!! route('backend.service_cat_ordering.index') !!}',
                   method: 'get',
                   dataType: 'json',
                   beforeSend: function(){
                     working = true;
                   },
                   success: function (json) {
                       if(json){
                           $(json).each(function(index, item){
                               $('#sortable-cats-placeholder').append(
                                   '<li class="p-2 bg-light border rounded cursor-move mt-1"><input type="hidden" name="cat[]" value="'+item.id+'"/>'+item.title+'</li>'
                               );
                           });
                       }
                       $('#sortable-cats-placeholder').sortable('refresh');
                       $modal.modal('show');
                   },
                   complete: function () {
                       working = false;
                   }
               });
           });

           $('#cat-ordering-form').submit(function () {
               if(working){
                   return;
               }
               var form = $(this);
               var data = $(form).serializeObject();
               $.ajax({
                   url: '{!! route('backend.service_cat_ordering.update') !!}',
                   method: 'post',
                   data: data,
                   beforeSend: function(){
                       working = true;
                   },
                   success: function (json) {
                       if(json){
                           //console.log(json);
                           $modal.modal('hide');
                       }
                   },
                   complete: function () {
                       working = false;
                   }
               });
               return false;
           });
        });
    </script>
@endsection