@enqueueJSByID(config('view.ui.files.js.nicescroll.id'), JS_LOCATION_DEFAULT, config('view.ui.files.js.jquery.id'))
@enqueueJSByID(config('view.ui.files.js.layout_fixed_custom.id'), JS_LOCATION_DEFAULT, config('view.ui.files.js.jquery.id'))
@extends('layouts.backend_base')
@hasSection('sidebar_second')
    @section('page_body_classes')
        navbar-top sidebar-opposite-visible
        sidebar-xs
    @endsection
@else
    @section('page_body_classes')
        navbar-top sidebar-xs
    @endsection
@endif
@section('sidebar_main')
    <!-- User menu -->
    <div class="sidebar-user">
        <div class="category-content">
            <div class="media">
                <a href="{{route('backend.profile.edit')}}" class="media-left">
                    @if(me()->avatar)
                        <img src="{!! me()->avatar->getThumbnailUrl(config('app.default_thumbnail_name'), getNoAvatarUrl()) !!}" class="img-circle img-sm" alt="">
                    @else
                        <img src="{!! getNoAvatarUrl() !!}" class="img-circle img-sm" alt="">
                    @endif
                </a>
                <div class="media-body">
                    <span class="media-heading text-semibold">{{Auth::user()->name}}</span>
                    <div class="text-size-mini text-muted">
                        <i class="icon-medal text-size-small"></i> &nbsp;{{Auth::user()->getRoleTitle()}}
                    </div>
                </div>
                
                <div class="media-right media-middle">
                    <ul class="icons-list">
                        <li>
                            <a href="{{route('backend.profile.edit')}}"><i class="icon-cog3"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- /user menu -->
    @event(new \App\Events\BackendMainSidebarBefore())
    <!-- Main navigation -->
    <div class="sidebar-category sidebar-category-visible">
        <div class="category-content no-padding">
            <ul class="navigation navigation-main navigation-accordion navigation-bordered">
                @php
                    $register_event = app('backend_menu');
                @endphp
                @include('backend.includes.mainnav_render', ['items'=>$register_event->bladeItems(), 'parent' => false])
            </ul>
        </div>
    </div>
    <!-- /main navigation -->
    @event(new \App\Events\BackendMainSidebarAfter())
@endsection
@section('navbar_main')
    <div class="navbar-header">
        <a class="navbar-brand" href="{{route('backend.dashboard.index')}}"><img src="{{asset('assets/ui/images/logo_light.png')}}" alt=""></a>

        <ul class="nav navbar-nav pull-right visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
            <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
            @hasSection('sidebar_second')
            <li><a class="sidebar-mobile-opposite-toggle"><i class="icon-menu"></i></a></li>
            @endif
        </ul>
    </div>

    <div class="navbar-collapse collapse" id="navbar-mobile">
        <ul class="nav navbar-nav">
            <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>
            @hasPermission('manage_files')
            <li><a onclick="wa_file_manager()"><i class="icon-floppy-disk position-left"></i> {{__('QUẢN LÝ FILE')}}</a></li>
            @endif
            @event(new \App\Events\BackendTopNavLeft())
        </ul>

        <ul class="nav navbar-nav navbar-right">

            <li>
                <a target="_blank" href="{{url('')}}">
                    <i class="icon-eye8"></i>
                    <span class="position-right">{{__('XEM WEBSITE')}}</span>
                </a>
            </li>
            <li>
                <a href="{{route('backend.logout')}}">
                    <i class="icon-switch2"></i>
                    <span class="position-right">{{__('ĐĂNG XUẤT')}}</span>
                </a>
            </li>
            <li class="dropdown dropdown-user hide">
                <a class="dropdown-toggle" data-toggle="dropdown">
                    <img src="{{asset('assets/ui/images/image.png')}}" alt="">
                    <span>Victoria</span>
                    <i class="caret"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="#"><i class="icon-user-plus"></i> My profile</a></li>
                    <li><a href="#"><i class="icon-coins"></i> My balance</a></li>
                    <li><a href="#"><span class="badge badge-warning pull-right">58</span> <i class="icon-comment-discussion"></i> Messages</a></li>
                    <li class="divider"></li>
                    <li><a href="#"><i class="icon-cog5"></i> Account settings</a></li>
                    <li><a href="#"><i class="icon-switch2"></i> Logout</a></li>
                </ul>
            </li>
            @event(new \App\Events\BackendTopNavRight())
        </ul>
    </div>
@endsection
@section('page_content')
    <!-- Page header -->
    <div class="page-header page-header-default">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-arrow-left52 position-left"></i>
                    @hasSection('page_header_title')
                        @yield('page_header_title')
                    @else
                        @yield('page_title')
                    @endif
                </h4>
                @hasSection('breadcrumb')
                    <ul class="breadcrumb position-right">
                        <li><a href="{{route('backend.index')}}"></a></li>
                        @yield('breadcrumb')
                        <li class="active">@yield('page_title')</li>
                    </ul>
                @endif
            </div>

            <div class="heading-elements">
            </div>
        </div>
    </div>
    <!-- /page header -->
    <div class="content">
    @isset($notification)
    <div class="alert alert-{{$notification['status']}} alert-styled-left">
        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
        {{$notification['message']}}
    </div>
    @endisset
    @yield('page_content_body')
    </div>
@endsection
@push('page_footer_js')
    @include('backend.includes.file_manager_js')
@endpush