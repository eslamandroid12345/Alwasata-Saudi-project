{!! Form::textComponent($name, $text, $icon, $attributes, $value, $col, 'text') !!}
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $("input[name='{{$name}}']").hijriDatePicker({
                hijri: true,
                locale: "ar-sa",
                format: "YYYY-MM-DD",
                hijriFormat: "iYYYY-iMM-iDD",
                // hijriFormat:"iDD-iMM-iYYYY",
            });
        });
    </script>
@endpush()
