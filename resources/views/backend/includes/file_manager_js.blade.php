<div id="wa_file_manager_modal" class="modal fade">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header bg-success-600">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <span class="modal-title"><i class="icon-floppy-disk position-left"></i> <span></span></span>
            </div>

            <div class="modal-body">
                <iframe name="main-frame" frameborder="0" width="100%" style="height: calc(90vh - 90px)"></iframe>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var wa_file_manager_modal = $('#wa_file_manager_modal').modal({
        show: false
    });
    @php
    /** @var \App\Events\FileCategoryRegister $cats */
    $cats = app('file_categories');
    $cats = $cats->categories;
    @endphp
    var allow_cats = [
        @foreach($cats as $cat_id=>$cat_title)
        '{!! $cat_id !!}',
        @endforeach
    ];
    function wa_file_manager(settings, get_files) {
        if (typeof settings == 'undefined'){
            settings = {};
        }
        var title = '{{__('QUẢN LÝ FILE')}}';
        if (settings.hasOwnProperty('title')){
            title = settings.title;
        }
        if (!settings.hasOwnProperty('owned')){
            settings.owned = false;
        }
        if (!settings.hasOwnProperty('limit')){
            settings.limit = [];
        }
        if (!settings.hasOwnProperty('select')){
            settings.select = 0;
        }
        if (!settings.hasOwnProperty('categories')){
            settings.categories = {!! json_encode(app('file_categories')->getCategoryIds()) !!};
        }
        else{
            var cats = [];
            $.each(settings.categories, function(i, v){
                if (allow_cats.indexOf(v)>-1){
                    cats.push(v);
                }
            });
            settings.categories = cats;
        }

        if (settings.categories.length==0){
            console.log('categories empty!');
            return;
        }
        var param = {};
        param.settings = settings;
        param = $.param(param);
        $('#wa_file_manager_modal .modal-title span').html(title);
        $('#wa_file_manager_modal iframe')[0].src = '{!! route('backend.file.manager.index') !!}?'+param;
        wa_file_manager_modal.modal('show');
        window.wa_get_files = function (items) {
            wa_file_manager_modal.modal('hide');
            get_files(items);
        };
    }
</script>

