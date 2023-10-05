@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {!! $greeting !!}
@else
@if ($level === 'error')
# @lang('global.whoops')
@else
# @lang('global.hello')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{!! $salutation !!}
@else
@lang('global.regards'),<br>{{config('app.name')}}
@endif

{{-- Subcopy --}}
@isset($actionText)
@component('mail::subcopy')
@lang('global.mail_btn',['actionText' => $actionText,'actionURL' => $actionUrl,])
@endcomponent
@endisset
@endcomponent