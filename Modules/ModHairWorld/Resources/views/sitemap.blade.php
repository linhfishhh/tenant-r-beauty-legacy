{!! '<?xml version="1.0" encoding="utf-8"?>' !!}
@php
    /** @var \Illuminate\Support\Collection $data */
@endphp
@if($index)
<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd">
@else
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
@endif
@foreach($data as $node)
<{!! $node['type'] !!}>
    @foreach($node as $key=>$value)
        @if($key == 'type')
            @continue
        @endif
    <{!! $key !!}>{!! $value !!}</{!! $key !!}>
        @endforeach
</{!! $node['type'] !!}>
@endforeach
@if(!$index)
</urlset>
@else
</sitemapindex>
@endif