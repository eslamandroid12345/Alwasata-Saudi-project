@php
$icon = "fas fa-search";

if( !isset($attributes["placeholder"]) )
    $attributes["placeholder"] = __( "replace.search",[ "name" => $text ] ) ;

$autocompleteName = "{$name}_label";

@endphp
@include('V2.app.partials.form.layout.top', ['autocompleteName' => $autocompleteName])
{{ Form::autocompleteInput($name,$url,$label,$value,$attributes) }}
@include('V2.app.partials.form.layout.bottom')
