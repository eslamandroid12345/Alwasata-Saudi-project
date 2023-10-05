@php
    if(!$text) $text = __("global.add");
    if(!$col) $col = "col-12";

    if(!isset($attributes["type"]))
        $attributes["type"] = "submit";

    if(!isset($attributes["class"]))
        $attributes["class"] = "btn btn-primary";

    $attributes["class"] = preg_replace("/\s+/"," ",trim($attributes["class"]));
    $attributes["class"] = explode(" ",$attributes["class"]);

    if(!in_array("btn",$attributes["class"]))
        $attributes["class"][] = "btn";

    $attributes["class"] = implode(" ",$attributes["class"]);

if( $icon )
 $text = "<i class='{$icon}'></i> ". $text;
@endphp

<div class="{{ $col }} mt-3">
    {!! Form::button( $text , $attributes ) !!}
</div>
