@php
    if(!isset($text_enable) || !$text_enable ) $text_enable = __("global.yes");
    if(!isset($text_disable) || !$text_disable ) $text_disable = __("global.no");
@endphp
{!! Form::radioGroup($name, $text, [ 0 => $text_disable, 1 => $text_enable],null,$attributes,$col, "col-md-3" )  !!}
