@php
Debugbar::info('Route Name: "'.Route::currentRouteName().'"')
@endphp
<!DOCTYPE html>
<html lang="@yield('page_language')">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @stack('page_meta')
        @event(new \App\Events\Page\PageMetaRendering())
        <title>@yield('page_title')</title>
        @showCSSQueue
        @showJSQueue(JS_LOCATION_HEAD)
        @stack('page_head')
        @event(new \App\Events\Page\PageHeadRendering())
        <script type="text/javascript" src="//script.crazyegg.com/pages/scripts/0082/2901.js" async="async"></script>
    </head>
    <body class="@yield('page_body_classes')" @yield('page_body_attributes')>
        @showJSQueue(JS_LOCATION_BODY)
        @stack('page_body_js')
        @event(new \App\Events\Page\PageBodyJSRendering())
        @yield('page_body')
        @showJSQueue(JS_LOCATION_FOOTER)
        @stack('page_footer_js')
        @event(new \App\Events\Page\PageFooterJSRendering())
        @stack('page_end')
    </body>
</html>