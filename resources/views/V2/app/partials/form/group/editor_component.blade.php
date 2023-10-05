@php
    if(!isset($attributes["class"])){
        $attributes["class"] = "editor";
    }

    if( is_array($name) ){
        if( !isset( $attributes["id"] ) )
            $attributes["id"] = $name[1];
        $name = $name[0];
    }

    $attributes["class"] = preg_replace("/\s+/"," ",trim($attributes["class"]));
    $attributes["class"] = explode(" ",$attributes["class"]);

    if(!in_array("editor",$attributes["class"]))
        $attributes["class"][] = "editor";

    $attributes["class"] = implode(" ",$attributes["class"]);

    if( !isset( $attributes["id"] ) )
         $attributes["id"] = $name;
@endphp
@include('V2.app.partials.form.layout.top', [ "input_group" => false ])
{!! Form::textarea($name,$value,$attributes) !!}
{{--{!! Form::textComponent($name, $text, $icon, $attributes, $value, $col, $textComponentType ) !!}--}}
@include('V2.app.partials.form.layout.bottom', [ "input_group" => false ])
