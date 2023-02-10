<div class="sidebar-category" data-group-id="menu-salon">
    <div class="category-title">
        <span>{!! $salon->name !!}</span>
        <ul class="icons-list">
            <li><a href="#" data-action="collapse" class=""></a></li>
        </ul>
    </div>
    <div class="category-content no-padding" style="display: block;">
        <ul id="salon-sidebar-items" class="navigation navigation-alt navigation-accordion datatable-salon-actions">
            @event(new \App\Events\BeforeHtmlBlock('sidebar.groups.salon'))
            <li class="{!! Route::currentRouteNamed('backend.salon.basic_info.edit')?'active':'' !!}">
                <a href="{!! route('backend.salon.basic_info.edit', ['salon' => $salon]) !!}"><i class="icon-book"></i> {!! __('Thông tin cơ bản') !!}
                </a>
            </li>
            <li class="{!! Route::currentRouteNamed('backend.salon.extended_info.edit')?'active':'' !!}">
                <a href="{!! route('backend.salon.extended_info.edit', ['salon' => $salon]) !!}"><i class="icon-info22"></i> {!! __('Thông tin mở rộng') !!}
                    <span class="badge bg-info extended-info">0</span>
                </a>
            </li>
            <li class="{!! Route::currentRouteNamed('backend.salon.managers.edit')?'active':'' !!}">
                <a href="{!! route('backend.salon.managers.edit', ['salon' => $salon]) !!}"><i class="icon-users"></i> {!! __('Tài khoản quản lý') !!}
                    <span class="badge bg-danger manager">0</span>
                </a>
            </li>
            <li class="{!! Route::currentRouteNamed('backend.salon.gallery.edit')?'active':'' !!}">
                <a href="{!! route('backend.salon.gallery.edit', ['salon' => $salon]) !!}"><i class="icon-image2"></i> {!! __('Gallery ảnh') !!}
                    <span class="badge bg-blue images">0</span>
                </a>
            </li>
            <li class="{!! Route::currentRouteNamed('backend.salon.time.edit')?'active':'' !!}">
                <a href="{!! route('backend.salon.time.edit', ['salon' => $salon]) !!}"><i class="icon-watch2"></i> {!! __('Giờ làm việc') !!}
                    <span class="badge bg-blue time">0</span>
                </a>
            </li>
            <li class="{!! Route::currentRouteNamed('backend.salon.stylist.edit')?'active':'' !!}">
                <a href="{!! route('backend.salon.stylist.edit', ['salon' => $salon]) !!}"><i class="icon-scissors"></i> {!! __('Đội ngũ stylist') !!}
                    <span class="badge bg-pink stylist">0</span>
                </a>
            </li>
            <li class="{!! Route::currentRouteNamed('backend.salon.showcase.edit')?'active':'' !!}">
                <a href="{!! route('backend.salon.showcase.edit', ['salon' => $salon]) !!}"><i class="icon-image5"></i> {!! __('Tác phẩm nổi bật') !!}
                    <span class="badge bg-orange gallery">0</span>
                </a>
            </li>
            <li class="{!! Route::currentRouteNamed('backend.salon.brand.edit')?'active':'' !!}">
                <a href="{!! route('backend.salon.brand.edit', ['salon' => $salon]) !!}"><i class="icon-bag"></i> {!! __('Thương hiệu') !!}
                    <span class="badge bg-purple brand">0</span>
                </a>
            </li>
            <li class="{!! Route::currentRouteNamed('backend.salon.service.edit')?'active':'' !!}">
                <a href="{!! route('backend.salon.service.edit', ['salon' => $salon]) !!}"><i class="icon-stars"></i> {!! __('Dịch vụ') !!}
                    <span class="badge bg-warning service">0</span>
                </a>
            </li>
            <li class="{!! Route::currentRouteNamed('backend.salon.sale.edit')?'active':'' !!}">
                <a href="{!! route('backend.salon.sale.edit', ['salon' => $salon]) !!}"><i class="icon-gift"></i> {!! __('Khuyến mãi') !!}
                    <span class="badge bg-indigo sale">0</span>
                </a>
            </li>
            @event(new \App\Events\AfterHtmlBlock('sidebar.groups.salon'))
        </ul>
    </div>
</div>
@push('page_footer_js')
    <script type="text/javascript">
        function loadSalonSidebarItemCount(){
            $.ajax({
                url: '{!! route('backend.salon.stats', ['salon' => $salon]) !!}',
                type: 'get',
                dataType: 'json',
                success: function (json) {
                    $('#salon-sidebar-items .extended-info').html(json.extended_info);
                    $('#salon-sidebar-items .manager').html(json.manager);
                    $('#salon-sidebar-items .time').html(json.time);
                    $('#salon-sidebar-items .stylist').html(json.stylist);
                    $('#salon-sidebar-items .gallery').html(json.gallery);
                    $('#salon-sidebar-items .brand').html(json.brand);
                    $('#salon-sidebar-items .service').html(json.service);
                    $('#salon-sidebar-items .sale').html(json.sale);
                    $('#salon-sidebar-items .images').html(json.images);
                }
            });
        }
        $(function(){
            loadSalonSidebarItemCount();
        });
    </script>
@endpush