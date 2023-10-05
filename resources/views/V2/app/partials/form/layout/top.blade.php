@php
    $input_group = isset($input_group) ? boolval($input_group): true;

    if(!isset($icon) || is_null($icon)) $icon = "fas fa-file-signature";

    if(!isset($attributes['id'])) $attributes['id'] = $name ;

    if(false && $icon){
        $icon = preg_replace("/\s+/"," ",trim($icon));
        $icon = explode(" ",$icon);
        if(!in_array("fas",$icon)) $icon[] = "fas";
        $icon = implode(" ",$icon);
    }
@endphp
<div class="{{$col}}" div-for="{{$attributes['id']}}">
    <div class="form-group ">
        {!! Form::label( isset($autocompleteName) ? $autocompleteName : $name, $text ) !!}
        @if( isset($attributes['required']) && $attributes['required'])
            <span class="text-danger">*</span>
        @endif
        @if($input_group)
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="{{$icon}}"></i></span>
                </div>
@endif
@stack($name . '_input_start')
