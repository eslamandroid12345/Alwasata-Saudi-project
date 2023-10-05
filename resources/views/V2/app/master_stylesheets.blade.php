<link rel="stylesheet" href="{{asset('myth-plugins/fontawesome-free/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset("myth-plugins/icheck-bootstrap/icheck-bootstrap-{$direction}.css")}}">
<link rel="stylesheet" href="{{asset("myth-plugins/pace-progress/themes/black/pace-theme-flat-top.css")}}">
<link rel="stylesheet" href="{{asset("myth-plugins/bootstrap-hijri-datepicker-master/css/bootstrap-datetimepicker.min.css")}}">

<link href="{{asset("myth-plugins/semantic/semantic.{$direction}.css")}}" rel="stylesheet" type="text/css">
<link href="{{asset('myth-plugins/semantic/calendar.css')}}" rel="stylesheet" type="text/css"/>

<link href="{{asset('myth-plugins/fullcalendar/main.min.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('myth-plugins/fullcalendar-daygrid/main.min.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('myth-plugins/fullcalendar-timegrid/main.min.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('myth-plugins/fullcalendar-bootstrap/main.min.css')}}" rel="stylesheet" type="text/css"/>

<link rel="stylesheet" href="{{asset("dist/css/adminlte-{$direction}.css")}}">
<link rel="stylesheet" href="{{asset("myth-plugins/select2/css/select2.min.css")}}">
<link rel="stylesheet" href="{{asset("myth-plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css")}}">
<style>
    .swal-text {
        text-align: center;
    }

    .card .overlay, .overlay-wrapper .overlay {
        z-index: 6000;
    }

    .layout-footer-fixed.text-sm .wrapper .content-wrapper {
        margin-bottom: calc(3rem + 1px);
        padding-bottom: calc(1.5rem + 1px);
    }

    div.daterangepicker {
        direction: {{$direction}};
        text-align: {{$align}};
    }

    .daterangepicker .calendar-table .next span, .daterangepicker .calendar-table .prev span {
        @if( $locale == 'ar' )
             border-width: 2px 0 0 2px;
        @else
             border-width: 0 2px 2px 0;
    @endif

    }

    body.text-sm,
    body.text-sm .custom-file-label::after,
    body.text-sm .custom-file-label {
        line-height: 1.8 !important;
    }

    .custom-file-label::after {
        content: "@lang("global.browse")" !important;
    }

    .sidebar::-webkit-scrollbar {
        width: 10px;
        border-radius: 10px;
    }

    .sidebar::-webkit-scrollbar-track {
        /*background: #f1f1f1;*/
        background: #343a40;
        border-radius: 5px;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 5px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
        background: #555;
        border-radius: 5px;
    }

    {{-- Select2 Style --}}

    .was-validated [class*="icheck-"] > input:invalid + label::before {
        border-color: #dc3545;
    }

    .was-validated [class*="icheck-"] > input:valid + label::before {
        /*border-color: #28a745;*/
    }

    .was-validated :invalid + .select2-container .select2-selection,
    .was-validated :invalid + label.custom-file-label {
        border-color: #dc3545;
        padding- {{$align2}}: 2.25rem;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23dc3545' viewBox='-2 -2 7 7'%3e%3cpath stroke='%23dc3545' d='M0 0l3 3m0-3L0 3'/%3e%3ccircle r='.5'/%3e%3ccircle cx='3' r='.5'/%3e%3ccircle cy='3' r='.5'/%3e%3ccircle cx='3' cy='3' r='.5'/%3e%3c/svg%3E");
        background-repeat: no-repeat;
        background-position: center {{$align2}} calc(0.375em + 0.1875rem);
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .was-validated :valid + .select2-container .select2-selection,
    .was-validated :valid + label.custom-file-label {
        border-color: #28a745;
        padding- {{$align2}}: 2.25rem;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: center {{$align2}} calc(0.375em + 0.1875rem);
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        margin- {{$align}}: 5px !important;
        margin- {{$align2}}: 2px !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        padding: 0 0.375rem 0;
    }

    .text-sm .select2-container--default .select2-selection--multiple .select2-search.select2-search--inline .select2-search__field, .select2-container--default .select2-selection--multiple.text-sm .select2-search.select2-search--inline .select2-search__field {
        margin-top: 6px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice{
        background-color: #007bff !important;
    }

    {{-- Select2 Style --}}

    .custom-file-label {
        overflow: hidden;
    }

    .input-group-prepend + .ui.calendar .form-control {
        border-radius: 0;
    }

    .input-group > .form-control.ui.calendar {
        position: relative;
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        width: 1%;
        margin-bottom: 0;
        padding: 0;
        border: 0;
    }

    .checkboxComponent.invalid ~ .valid-feedback {
        display: none;
    }

    .checkboxComponent.valid ~ .valid-feedback {
        display: block;
    }

    .checkboxComponent.invalid ~ .invalid-feedback {
        display: block;
    }

    .checkboxComponent.valid ~ .invalid-feedback {
        display: none;
    }

    .pointer {
        user-select: none;
        cursor: pointer !important;
    }

    .pointer:hover {
        opacity: .5 !important;
    }

    .text-align {
        text-align: {{$align}}  !important;
    }

    .ui.input input,
    .ui.search .prompt {
        border-radius: 0;
    }

    .div-select2.form-control,
    .ui.form-control,
    .select2.form-control {
        border: 0;
        padding: 0;
        height: auto;
    }
    .select2-container .select2-selection--single{
        min-height: 38px;
    }
    .select2-container .select2-selection--multiple{
        min-height: 38px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow{
        top: 0;
        bottom: 0;
        margin-top: auto;
        margin-bottom: auto;
    }

    .direction-ltr {
        direction: ltr !important;
    }

    .direction-rtl {
        direction: rtl !important;
    }

    .text-ltr {
        text-align: left !important;
    }

    .text-rtl {
        text-align: right !important;
    }

    .background-qr {
        background-image: url('{{asset('images/1499401426qr_icon.svg')}}');
        background-repeat: no-repeat;
        cursor: pointer;
        background-size: contain;
        background-position: center;
        width: 50px;
        margin: 0;
        height: 100%;
    }

    .qr-scanner {
        position: absolute;
        overflow: hidden;
        width: 100%;
        height: 100%;
        opacity: 0;
        top: 0;
        left: 0;
    }

    .cursor-not-allowed {
        cursor: not-allowed !important;
    }
</style>
