@php
    $attributes['groupCalendar'] = true;
@endphp
@include('V2.app.partials.form.layout.top')
{!! Form::dateHijriInput( $name, $text, $icon, $attributes, $value, $col, 'text' ) !!}
@include('V2.app.partials.form.layout.bottom')


