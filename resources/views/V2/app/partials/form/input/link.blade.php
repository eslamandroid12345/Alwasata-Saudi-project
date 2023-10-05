@php
if(is_bool($btnClass) && !is_null($btnClass)){

    $targetBlank = $btnClass;
    $btnClass = null;

}
$targetBlank = isset($targetBlank) && $targetBlank === true ? 'target="_blank"' : '';

if(!$btnClass) $btnClass = 'btn-primary';

@endphp
<a {{$targetBlank}} href="{{$url}}" class="btn btn-sm mb-2 {{$btnClass}}">{!! $text !!}</a>
