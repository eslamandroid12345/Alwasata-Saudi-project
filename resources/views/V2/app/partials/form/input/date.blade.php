@php
$readonly = isset($readonly) ? $readonly : ( isset($attributes['readonly']) ? $attributes['readonly'] : false);
@endphp
<div
    class="calendar-div {{ isset($attributes['groupCalendar']) ? "form-control" : "" }} ui calendar {{ isset($attributes['readonly']) ? $attributes['readonly'] : "" }} {{ isset($attributes['disabled']) ? $attributes['disabled'] :"" }}"
    id="date-{{$name}}-calendar">
    <div class="ui">
        {!! Form::textComponent($name, $text, $icon, $attributes, $value, $col, 'text') !!}
    </div>
</div>

@push('scripts')
    @if(!$readonly)
    <script type="text/javascript">
        $(document).ready(function () {
            $("#date-{{$name}}-calendar").calendar({
                type: '{{$dateType}}',
            });
        });
    </script>
    @endif
@endpush()
