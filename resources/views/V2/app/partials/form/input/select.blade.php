@php

    if(!isset($attributes['class'])){
        $attributes['class'] = 'form-control select2';
    }
    else{
        $attributes['class'] = preg_replace("/\s+/"," ",trim($attributes['class']));
        $attributes['class'] = explode(" ",$attributes['class']);

        if(!in_array("select2",$attributes['class']))
            $attributes['class'][] = "select2";

        if(!in_array("form-control",$attributes['class']))
            $attributes['class'][] = "form-control";

        $attributes['class'] = implode(" ",$attributes['class']);
    }
    $attributes_placeholder = __('replace.choose', ['name' => $text] );
    if(!isset($attributes['data-placeholder']))
        $attributes['data-placeholder'] = $attributes_placeholder;

    if( !isset($attributes['multiple']) || !$attributes['multiple'] )
        $attributes["placeholder"] = $attributes_placeholder;

    if(method_exists($values,'pluck')){
        try{
            $values = $values->pluck('name','id');
        }
        catch(\Exception $exception ){
            $values = $values->pluck(locale_attribute(),'id');
        }
    }
    elseif(is_array($values)){
        $values = collect($values)->pluck('name','id');
    }
@endphp

{!! Form::select($name, $values, $selected, $attributes ) !!}
