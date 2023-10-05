@php
    $attributes["method"] = "PUT";
    $attributes["class"] = (isset($attributes["class"]) ? $attributes["class"] : "") . "d-none";
    $attributes['url'] = $url;
@endphp
{!! Form::model($model,$attributes)!!}
<button type="submit" class="btn btn-sm btn-danger mb-2" >
    {!! $text !!}
</button>
{!! Form::close() !!}
