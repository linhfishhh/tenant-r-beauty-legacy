@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level == 'error')
# {!! __('Chào bạn, rất tiết!') !!}
@else
# {!! __('Chào bạn!') !!}
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{!! $line !!}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
            $color = 'green';
            break;
        case 'error':
            $color = 'red';
            break;
        default:
            $color = 'blue';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{!! $line  !!}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
{!! __('Trân trọng, :site_name', ['site_name'=>config('app.name')]) !!}
@endif

{{-- Subcopy --}}
@isset($actionText)
@component('mail::subcopy')
{!! __('Nếu bạn không thể click vào nút ":button_text" vui lòng nhấp vào đường link này', ['button_text' => $actionText ]) !!}: [{{ $actionUrl }}]({{ $actionUrl }})
@endcomponent
@endisset
@endcomponent
