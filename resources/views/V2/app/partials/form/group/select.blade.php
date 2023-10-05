@php
    if( isset($multiple) )
        $attributes["multiple"] = "multiple";

    if(is_array($icon))
        $values = $icon;

    if(!$icon || is_array($icon))
        $icon = "fas fa-list-alt";

    if( isset($attributes["multiple"]))
        $name = rtrim($name,"[]") . "[]";
@endphp

@include('V2.app.partials.form.layout.top')

{!! Form::selectInput($name, $text, $icon, $values, $selected, $attributes, $col) !!}

@include('V2.app.partials.form.layout.bottom')
