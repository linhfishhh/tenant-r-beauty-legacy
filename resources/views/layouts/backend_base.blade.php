@enqueueBundle(config('view.ui.bundles.limitless.id'))
@enqueueJSByID(config('view.ui.files.js.uniform.id'))
@enqueueJSByID(config('view.ui.files.js.ladda.id'))
@enqueueJSByID(config('view.ui.files.js.spin.id'))
@extends('layouts.base')
@section('page_body')
    
    @hasSection('navbar_main')
    <!-- Main navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top">
        @yield('navbar_main')
    </div>
    <!-- /main navbar -->
    @endif
    
    @hasSection('page_header')
    <!-- Page header -->
    <div class="page-header page-header-default">
        @yield('page_header')
    </div>
    <!-- /page header -->
    @endif
    
    <!-- Page container -->
    <div class="page-container">
        
        <!-- Page content -->
        <div class="page-content">
            
            @hasSection('sidebar_main')
            <!-- Main sidebar -->
            <div class="sidebar sidebar-main sidebar-fixed">
                <div class="sidebar-content">
                @yield('sidebar_main')
                </div>
            </div>
            <!-- /main sidebar -->
            @endif


            @hasSection('sidebar_second')
            <!-- Secondary sidebar -->
                <div class="sidebar sidebar-opposite sidebar-fixed sidebar-default">
                    <div class="sidebar-content">
                        @yield('sidebar_second')
                    </div>
                </div>
            <!-- /secondary sidebar -->
            @endif
            
            <!-- Main content -->
            <div class="content-wrapper">
                @yield('page_content')
            </div>
            <!-- /main content -->
        
        </div>
        <!-- /page content -->
    
        @hasSection('page_footer')
        <!-- Footer -->
        <div class="footer">
            @yield('page_footer')
        </div>
        <!-- /footer -->
        @endif
    
    </div>
    <!-- /page container -->

@endsection
@push('page_meta')<script type="text/javascript">paceOptions = {elements: false, restartOnRequestAfter: false}</script>@endpush
@push('page_footer_js')
    <script type="text/javascript">
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Style checkboxes and radios
            $('.styled').uniform();
        });
    </script>
@endpush