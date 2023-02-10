<div class="checkbox-list">
    @foreach($items as $item)
        <div class="item">
            <label class="checkbox-container">
                <input name="{!! $item['name'] !!}" value="{!! $item['value'] !!}" type="checkbox">
                <span class="checkmark"></span>
                {!! $item['title'] !!}
                @if(isset($item['number']))
                    <div class="number">{!! $item['number'] !!}</div>
                @endif
            </label>
        </div>
    @endforeach
</div>