@php
if( isset($multiple) )
    $attributes['multiple'] = "multiple";
@endphp
@include('V2.app.partials.form.layout.top')
{!! Form::textComponent($name, $text, $icon, $attributes, $value, $col, $textComponentType ) !!}
@include('V2.app.partials.form.layout.bottom')
