@php
    $input_group = isset($input_group) ? boolval($input_group): true;
    if(!isset($attributes['valid'])) $attributes['valid'] = '';

    if(!isset($attributes['invalid'])) $attributes['invalid'] = __("global.please_fill_in_this_field");
@endphp
<div class="valid-feedback">{{$attributes['valid']}}</div>
<div class="invalid-feedback">{{$attributes['invalid']}}</div>
@stack($name . '_input_end')
    @if($input_group)
        </div>
    @endif
    </div>
</div>
