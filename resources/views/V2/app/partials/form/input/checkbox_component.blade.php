@php
if(!isset($attributes['id']))
    $attributes['id'] = $id;

    if(!isset($attributes["class"])){
        $attributes["class"] = "form-check-input";
    }

    $attributes["class"] = preg_replace("/\s+/"," ",trim($attributes["class"]));
    $attributes["class"] = explode(" ",$attributes["class"]);

    if(!in_array("form-check-input",$attributes["class"]))
        $attributes["class"][] = "form-check-input";

    $attributes["class"] = implode(" ",$attributes["class"]);
@endphp
<div class="icheck-primary mr-2">
{!! Form::{$input_type}($name, $value, $checked , $attributes )  !!}
<label for="{{$id}}">{{ $label }}</label>
</div>
