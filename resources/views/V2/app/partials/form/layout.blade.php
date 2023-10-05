@php
    if(!isset($col)) $col = "" ;
    if(!isset($icon)) $icon = "" ;
    if(!isset($attributes['id'])) $attributes['id'] = $name ;

    if(!isset($attributes['required'])) $attributes['required'] = '';

    if(!isset($attributes['valid'])) $attributes['valid'] = '';

    if(!isset($attributes['invalid'])) $attributes['invalid'] = __("global.please_fill_in_this_field");

    if(!isset($attributes['class'])) $attributes['class'] = 'form-control';


    $attributes['class'] = preg_replace("/\s+/"," ",trim($attributes['class']));
    $attributes['class'] = explode(" ",$attributes['class']);

    if(!in_array("form-control",$attributes['class'])) $attributes['class'][] = "form-control";

    $attributes['class'] = implode(" ",$attributes['class']);

@endphp

<div class="{{$col}}" div-for="{{$attributes['id']}}">
    <div class="form-group ">
        {!! Form::label($name, $text, ['class' => '', 'for' => $attributes['id'], ]) !!}
        @if( $attributes['required'] )
            <span class="text-danger">*</span>
        @endif
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="{{$icon}}"></i></span>
            </div>
            {{--@stack('form_input')--}}
            @yield('form_input')
        </div>

        <div class="valid-feedback">{{$attributes['valid']}}</div>
        <div class="invalid-feedback">{{$attributes['invalid']}}</div>
    </div>
</div>
