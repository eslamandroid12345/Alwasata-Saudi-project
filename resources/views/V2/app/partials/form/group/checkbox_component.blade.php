@php
$modeIsModel = !is_null($form_display_value);
if($input_type == "checkbox" && isset($attributes["required"])){
    unset($attributes["required"]);
}
@endphp

@include('V2.app.partials.form.layout.top', [ "input_group" => false ])
<div class="row checkboxComponent">
    @if($items)
        {{--{{dd($items)}}--}}
    @foreach($items as $itemsKey => $itemsValue)
        @php
            $label = $modeIsModel ? $itemsValue->{$form_display_value} : $itemsValue;
            $foreach_value =  $modeIsModel ? $itemsValue->id : $itemsKey ;
            $foreach_id = "{$name}_{$foreach_value}";
        @endphp

        <div class="{{$col_items}}">
            {!! Form::checkboxComponent($input_type ,$name, $foreach_id, $label, $foreach_value, $attributes,null) !!}
        </div>
    @endforeach
    @endif
</div>
@include('V2.app.partials.form.layout.bottom', [ "input_group" => false ])
