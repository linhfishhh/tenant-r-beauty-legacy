@enqueueCSS('notification-page', getThemeAssetUrl('libs/styles/notification.css'), 'account-menu')
@extends(getThemeViewName('account.master'))
@section('content')
    @php
    /** @var \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications */
    @endphp
    <div class="mb-2" style="font-size: 10px; text-transform: uppercase">
        <a href="{!! url('test-notify') !!}">Tạo thông báo ảo để test</a>
    </div>
    <div class="content-box">
        <div class="content-title">Thông báo của bạn</div>
        <div class="content-body">
            <div id="notifications" class="notifications" style="min-height: 200px">
            </div>
            <div id="load-more-notifications" class="load-more d-none">
                <button type="button"><i class="fa fa-caret-down"></i> Thông báo cũ hơn</button>
            </div>
        </div>
    </div>
@endsection
@push('page_footer_js')
    <template id="tpl_notification">
        <div class="notification clearfix" data-id="{id}">
            <div class="img" style="{cover}">
                <div class="unread for-read"></div>
            </div>
            <div class="content">
                <div class="title">{title}</div>
                <div class="meta">
                    <span class="date">{date}</span>
                    <i class="fa fa-circle for-view"></i>
                    <a class="view for-view" href="{link}">Chi tiết</a>
                    <i class="fa fa-circle for-read"></i>
                    <a class="read for-read" href="#">Đã đọc</a>
                </div>
            </div>
            <div class="delete"><i class="fa fa-remove"></i></div>
        </div>
    </template>
    <script type="text/javascript">
        $(function () {
            var start_notification = 0;
           function loadNotifications(from_id) {
                $.ajax({
                    url: '{!! route('frontend.account.notification.list') !!}',
                    type: 'get',
                    dataType: 'json',
                    data:{
                        from_id: from_id
                    },
                    beforeSend: function () {
                        $('#notifications').addClass('loading');
                        $('#load-more-notifications').addClass('d-none');
                    },
                    complete: function () {
                        $('#notifications').removeClass('loading');
                        $('#load-more-notifications').removeClass('d-none');
                        if($('#load-more-notifications button').data('id') == 0){
                            $('#load-more-notifications').addClass('d-none');
                        }
                        if(start_notification == 0 && $('#notifications .notification').length==0){
                            $('#notifications').append('<div class="no-notification">Bạn chưa có thông báo nào</div>')
                        }
                        start_notification = 1;
                    },
                    success: function (json) {
                        var last_id = 0;
                        if(json.hasOwnProperty('length')){
                            $(json).each(function () {
                                //console.log(this);
                                var tpl = $('#tpl_notification').html();
                                tpl = tpl.replace(/{id}/g, this.id);
                                var cover = 'background-color:'+this.data.color+';';
                                if(this.data.cover){
                                    cover = cover+"background-image:url('"+this.cover+"');";
                                }
                                tpl = tpl.replace(/{cover}/g, cover);
                                tpl = tpl.replace(/{title}/g, this.data.title);
                                tpl = tpl.replace(/{link}/g, this.data.link?this.data.link:'#');
                                tpl = tpl.replace(/{date}/g, this.created_at);
                                tpl = $(tpl);
                                $('#notifications').append(tpl);
                                if(this.read_at){
                                    $(tpl).find('.for-read').remove();
                                }
                                if(!this.data.link){
                                    $(tpl).find('.for-view').remove();
                                }
                                last_id = this.order_num;
                            });
                            if(json.length<10){
                                last_id = 0;
                            }
                        }
                        $('#load-more-notifications button').data('id', last_id);
                    }
                });
           }
            $('#load-more-notifications button').click(function () {
                var from_id = $(this).data('id');
                loadNotifications(from_id);
            });
           loadNotifications(0);
           $('body').on('click', '#notifications .read', function () {
               var id = $(this).parents('.notification').data('id');
               var notification = $(this).parents('.notification');
               var url = '{!! route('frontend.account.notification.read', ['id' => '???']) !!}';
               url = url.replace('???', id);
                $.ajax({
                    url: url,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function () {
                        $('#notifications').addClass('loading');
                    },
                    complete: function () {
                        $('#notifications').removeClass('loading');
                    },
                    success: function (json) {
                        if(json){
                            $(notification).find('.for-read').remove();
                        }
                    }
                });
               return false;
           });
            $('body').on('click', '#notifications .delete', function () {
                var id = $(this).parents('.notification').data('id');
                var notification = $(this).parents('.notification');
                var url = '{!! route('frontend.account.notification.remove', ['id' => '???']) !!}';
                url = url.replace('???', id);
                $.ajax({
                    url: url,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function () {
                        $('#notifications').addClass('loading');
                    },
                    complete: function () {
                        $('#notifications').removeClass('loading');
                    },
                    success: function (json) {
                        if(json){
                            $(notification).find('.unread').remove();
                            $(notification).addClass('deleted')
                        }
                    }
                });
                return false;
            });
        });
    </script>
@endpush