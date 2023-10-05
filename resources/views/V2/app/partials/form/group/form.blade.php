@php

    if(!isset($attributes["class"]))
        $attributes["class"] = "form-validation form-ajax";

    $attributes["class"] = preg_replace("/\s+/"," ",trim($attributes["class"]));
    $attributes["class"] = explode(" ",$attributes["class"]);

    if(!in_array("form-ajax",$attributes["class"]))
        $attributes["class"][] = "form-ajax";
    if(!in_array("form-validation",$attributes["class"]))
        $attributes["class"][] = "form-validation";

    $attributes["class"] = implode(" ",$attributes["class"]);
@endphp
{!! Form::model($model,$attributes)!!}
