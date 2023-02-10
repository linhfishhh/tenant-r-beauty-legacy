@enqueueCSS('help-page', getThemeAssetUrl('libs/styles/help.css'), 'account-menu')
@extends(getThemeViewName('account.master'))
@section('content')
    <div class="content-box">
        <div class="content-title">Trợ giúp</div>
        <div class="content-body">
            <div id="help-list" class="help-list">
                @php
                /** @var \Modules\ModFAQ\Entities\FAQ[] $faqs */
                @endphp
                @foreach($faqs as $k=>$item)
                    <div class="item">
                        <div class="question">
                            <div class="collapsed" data-toggle="collapse" data-target="#collapse-{!! $k !!}" aria-expanded="false">
                                {!! $item->title !!}
                            </div>
                        </div>
                        <div id="collapse-{!! $k !!}" class="collapse" data-parent="#help-list">
                            <div class="answer common-content-block">
                                {!! $item->answer !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection