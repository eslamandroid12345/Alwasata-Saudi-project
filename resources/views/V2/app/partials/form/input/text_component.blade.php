@php
    if(!isset($attributes["class"])){
        $attributes["class"] = "form-control";
    }
    if( is_array($name) ){
        if( !isset( $attributes["id"] ) )
            if( !isset( $attributes["id"] ) && isset($name[1]))
        $name = $name[0];
    }

    $attributes["class"] = preg_replace("/\s+/"," ",trim($attributes["class"]));
    $attributes["class"] = explode(" ",$attributes["class"]);

    if(!in_array("form-control",$attributes["class"]))
        $attributes["class"][] = "form-control";

    if( $textComponentType =='file' && !in_array( "custom-file-input", $attributes["class"] ) )
        $attributes["class"][] = "custom-file-input";


    if($textComponentType == "number"){
        $textComponentType = "text";

        if(!in_array("text-number-event",$attributes["class"]))
            $attributes["class"][] = "text-number-event";
    }

    $attributes["class"] = implode(" ",$attributes["class"]);

    if( !isset($attributes["placeholder"]) )
        $attributes["placeholder"] = __( "replace.enter",[ "name" => $text ] ) ;

    if( !isset( $attributes["id"] ) )
         $attributes["id"] = $name;

    $isNotRegular = in_array($textComponentType,["password","file"]);

    if( !isset( $attributes["autocomplete"] ) )
        $attributes["autocomplete"] = "off";

if($textComponentType == "textarea"){
    if(!isset($attributes["rows"]))
        $attributes["rows"] = 4;
}
    //dd($attributes,$textComponentType);

@endphp
@if($textComponentType == 'file' )
<div class="custom-file">
    {!! Form::file($name, $attributes ) !!}
    <label class="custom-file-label" for="{{$attributes['id']}}">@lang('global.choose')</label>
</div>
@else

{!! Form::{$textComponentType}($name, $isNotRegular ? $attributes :$value, $isNotRegular ? null : $attributes ) !!}
@endif
{{--

@php
 $formatDate = "MM-DD-YYYY";
@endphp
@push('scripts')
@if( $dateType == 'date' )
    <script type="text/javascript">
        $(document).ready(function () {
            $("input[name='{{$name}}']").daterangepicker({
                "singleDatePicker": true,
                "showDropdowns": false,
                "showCustomRangeLabel": false,
                "opens": "{{$align2}}",
                locale:{
                    direction: '{{$direction}}',
                    "format": "{{$formatDate}}",
                }
            },function (start, end, label) {});
        });
    </script>
@elseif( $dateType == 'date-hijri' )
    <script type="text/javascript">
        $(document).ready(function () {
            $("input[name='{{$name}}']").hijriDatePicker();
        });
    </script>
@elseif( $dateType == 'date-time' )
    <script type="text/javascript">
        $(document).ready(function () {
            $("#{{$attributes["id"]}}").datetimepicker({
                format: 'LT'
            })
        });
    </script>
@endif
@endpush
--}}
