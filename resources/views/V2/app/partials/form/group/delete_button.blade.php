@php
    $text = $text ?? __('global.delete');
@endphp

<a class="btn btn-sm btn-danger mb-2 hasDelete" data-form-url="{{$url}}" href="javascript:void(0)">
    <i class="fa fa-times-circle"></i> {!! $text !!}
</a>
