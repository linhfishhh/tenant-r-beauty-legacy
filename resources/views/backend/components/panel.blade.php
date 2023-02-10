<div class="panel {{$classes or ''}}">
    @isset($title)
        <div class="panel-heading">
            {!! $title !!}
            <div class="heading-elements">
                <ul class="icons-list">
                    @if(!isset($no_collapse))
                        <li>
                            <a data-action="collapse"></a>
                        </li>
                    @endif
                    {!! $header_items or '' !!}
                </ul>
            </div>
        </div>
    @endisset
    @if(!isset($has_body) || $has_body)
    <div class="panel-body">
        {!! $content or '' !!}
    </div>
    @else
        {!! $content or '' !!}
    @endif
</div>