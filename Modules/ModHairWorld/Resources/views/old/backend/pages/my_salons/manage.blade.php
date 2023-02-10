@php
    /** @var \Modules\ModHairWorld\Entities\Salon $salon */
@endphp
@enqueueJSByID(config('view.ui.files.js.jquery_ui.id'), JS_LOCATION_DEFAULT, 'jquery')
@extends('layouts.backend')
@section('page_title', $salon->name)
@section('page_header_title')
    {!! __('Quản lý') !!} <strong>{{$salon->name}}</strong>
@endsection
@section('page_content_body')
    @event(new \App\Events\BeforeHtmlBlock('content'))
    <div class="row">
        <div class="col-sm-6 col-lg-3">
            <div class="thumbnail">
                <div class="thumb">
                    <img src="{!! getNoThumbnailUrl() !!}" alt="">
                    <div class="caption-overflow">
                        <span>
                            <a id="wa_change_salon_image" href="#" class="btn bg-warning"><i class="icon-image2 position-left"></i>{!! __('Đổi ảnh đại diện') !!}</a>
                        </span>
                    </div>
                </div>

                <div class="panel panel-body no-border no-border-radius no-margin no-shadow">
                    <div class="form-group mt-5">
                        <label class="text-semibold">{!! __('Tên salon') !!}:</label>
                        <div>{!! $salon->name !!}</div>
                    </div>

                    <div class="form-group">
                        <label class="text-semibold">Địa chỉ:</label>
                        <div>
                            @if($salon_address = $salon->getAddressLine())
                                <span>{!! $salon_address !!}</span>
                            @else
                                <span class="text-muted">{!! __('Chưa cập nhật') !!}</span>
                            @endif
                        </div>
                    </div>
                    @if(!$salon->info)
                        <div class="text-warning text-semibold text-size-mini">*{!! __('Thiếu phần thông tin chung giới thiệu salon') !!}</div>
                    @endif
                </div>
                <div class="panel-body text-center alpha-grey">
                    <a href="{!! route('backend.my_salon.edit_info', ['salon'=>$salon]) !!}" class="btn bg-primary">
                        <i class="icon-pencil7 position-left"></i>
                        {!! __('Cập nhật thông tin') !!}
                    </a>
                </div>
            </div>
            <div class="panel">
                <div class="panel-body">
                    <h5 class="content-group">
										<span class="label label-flat label-rounded label-icon border-indigo-400 text-indigo-400 mr-10">
											<i class="icon-watch2"></i>
										</span>

                        <a href="#" class="text-default">
                            {!! __('GIỜ LÀM VIỆC') !!}
                        </a>
                    </h5>

                    <p class="content-group">
                        {!! __('Liệt kê giờ mở cửa và đóng cửa của salon bạn để khách hàng tiện trong việc đặt lịch sử dụng dịch vụ') !!}
                    </p>

                    <ul class="list list-icons">
                        <li>
                            <label><i class="icon-watch2 text-indigo position-left"></i>Thứ 2:</label>
                            <span class="pull-right-sm text-muted">08:00 - 19:00</span>
                        </li>
                        <li>
                            <label><i class="icon-watch2 text-indigo position-left"></i>Thứ 3:</label>
                            <span class="pull-right-sm text-muted">08:00 - 19:00</span>
                        </li>
                        <li>
                            <label><i class="icon-watch2 text-indigo position-left"></i>Thứ 4:</label>
                            <span class="pull-right-sm text-muted">08:00 - 19:00</span>
                        </li>
                        <li>
                            <label><i class="icon-watch2 text-indigo position-left"></i>Thứ 5:</label>
                            <span class="pull-right-sm text-muted">08:00 - 19:00</span>
                        </li>
                        <li>
                            <label><i class="icon-watch2 text-indigo position-left"></i>Thứ 6:</label>
                            <span class="pull-right-sm text-muted">08:00 - 19:00</span>
                        </li>
                        <li>
                            <label><i class="icon-watch2 text-indigo position-left"></i>Thứ 7:</label>
                            <span class="pull-right-sm text-muted">08:00 - 19:00</span>
                        </li>
                        <li>
                            <label><i class="icon-watch2 text-indigo position-left"></i>Chủ nhật:</label>
                            <span class="pull-right-sm text-muted">08:00 - 19:00</span>
                        </li>
                    </ul>
                </div>

                <hr class="no-margin">

                <div class="panel-body text-center alpha-grey">
                    <a href="#" class="btn bg-indigo-400">
                        <i class="icon-watch position-left"></i>
                        {!! __('Quản lý giờ mở cửa') !!}
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h6 class="panel-title">
                         <span class="label label-flat label-rounded label-icon border-success text-success mr-10">
                                <i class="icon-gallery"></i>
                            </span>
                        {!! __('TÁC PHẨM') !!}
                        <a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
                    <div class="heading-elements">
                        <span class="badge bg-success-400">28</span>
                    </div>
                </div>
                <div class="media-list panel-body">
                    <p class="content-group">
                        {!! __('Giới thiệu danh sách các tác phẩm tâm đắc nhất được thực hiện bởi salon của bạn') !!}
                    </p>
                    @for($i=1; $i<=5; $i++)
                        <div class="media">
                            <div class="media-left">
                                <a href="#">
                                    <img src="{!! getNoThumbnailUrl() !!}" class="img img-lg" alt="">
                                </a>
                            </div>

                            <div class="media-body">
                                <h6 class="media-heading">James Alexander</h6>
                                <span class="text-muted">Lead developer</span>
                            </div>
                        </div>
                    @endfor
                </div>
                <div class="panel-body text-center alpha-grey">
                    <a href="#" class="btn bg-success-400">
                        <i class="icon-scissors position-left"></i>
                        <span>{!! __('Quản lý stylist') !!}</span>
                    </a>
                </div>
            </div>
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h6 class="panel-title">
                         <span class="label label-flat label-rounded label-icon border-pink text-pink mr-10">
                                <i class="icon-scissors"></i>
                            </span>
                        {!! __('STYLIST') !!}
                        <a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
                    <div class="heading-elements">
                        <span class="badge bg-pink-400">28</span>
                    </div>
                </div>
                <div class="media-list panel-body">
                    <p class="content-group">
                        {!! __('Giới thiệu danh sách các stylist của salon đến khách hàng của bạn') !!}
                    </p>
                    @for($i=1; $i<=5; $i++)
                        <div class="media">
                            <div class="media-left">
                                <a href="#">
                                    <img src="{!! getNoAvatarUrl() !!}" class="img-circle img-lg" alt="">
                                </a>
                            </div>

                            <div class="media-body">
                                <h6 class="media-heading">James Alexander</h6>
                                <span class="text-muted">Lead developer</span>
                            </div>
                        </div>
                    @endfor
                </div>
                <div class="panel-body text-center alpha-grey">
                    <a href="#" class="btn bg-pink-400">
                        <i class="icon-scissors position-left"></i>
                        <span>{!! __('Quản lý stylist') !!}</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-6">
            <div class="panel panel-flat">
                <div class="panel-body no-padding-bottom">
                    <h5 class="content-group">
                            <span class="label label-flat label-rounded label-icon border-orange text-orange mr-10">
                                <i class="icon-cart5"></i>
                            </span>

                        <a href="#" class="text-default">
                            {!! __('ĐƠN HÀNG') !!}
                        </a>
                    </h5>
                </div>
                <div class="container-fluid">
                    <div class="row text-center">
                        <div class="col-sm-3 col-xs-6">
                            <div class="content-group">
                                <h6 class="text-semibold no-margin"><i class="icon-clipboard3 position-left text-slate"></i> 2,345</h6>
                                <span class="text-muted text-size-small">chờ xử lý</span>
                            </div>
                        </div>

                        <div class="col-sm-3 col-xs-6">
                            <div class="content-group">
                                <h6 class="text-semibold no-margin"><i class="icon-calendar3 position-left text-slate"></i> 3,568</h6>
                                <span class="text-muted text-size-small">đã xử lý</span>
                            </div>
                        </div>

                        <div class="col-sm-3 col-xs-6">
                            <div class="content-group">
                                <h6 class="text-semibold no-margin"><i class="icon-calendar3 position-left text-slate"></i> 3,568</h6>
                                <span class="text-muted text-size-small">đã hủy</span>
                            </div>
                        </div>

                        <div class="col-sm-3 col-xs-6">
                            <div class="content-group">
                                <h6 class="text-semibold no-margin"><i class="icon-comments position-left text-slate"></i> 32,693</h6>
                                <span class="text-muted text-size-small">Tất cả</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <tbody>
                        @for($i=1; $i<=16; $i++)
                            <tr>
                                <td>
                                    <div class="media-left media-middle">
                                        <span class="label bg-orange">#1245</span>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading">
                                            <a href="#" class="letter-icon-title">Sigma application</a>
                                        </div>

                                        <div class="text-muted text-size-small"><i class="icon-checkmark3 text-size-mini position-left"></i> New order</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted text-size-small">06:28 pm</span>
                                </td>
                                <td>
                                    <h6 class="text-semibold no-margin">$49.90</h6>
                                </td>
                            </tr>
                        @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @event(new \App\Events\AfterHtmlBlock('content'))
@endsection
@push('page_footer_js')
    @component('backend.includes.file_uploader')
    @endcomponent
    <script type="text/javascript">
        $(function () {
           $('#wa_change_salon_image').click(function () {
               wa_file_uploader();
               return false;
           });
        });
    </script>
@endpush