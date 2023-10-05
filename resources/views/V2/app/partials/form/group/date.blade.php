@php
    $attributes['groupCalendar'] = true;
if(!isset($icon) || !$icon){
    if($dateType === 'date') $icon = 'far fa-calendar';
    elseif($dateType === 'time') $icon = 'far fa-clock';
    elseif($dateType === 'datetime') $icon = 'far fa-calendar-alt';
}


@endphp
@include('V2.app.partials.form.layout.top')
{!! Form::dateInput( $name, $text, $icon, $attributes, $value, $col, 'text', $dateType ) !!}
@include('V2.app.partials.form.layout.bottom')
