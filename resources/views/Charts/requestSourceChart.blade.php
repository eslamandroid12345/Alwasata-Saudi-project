@extends('layouts.content')

@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'Charts') }} - {{ MyHelpers::admin_trans(auth()->user()->id,'request_sources') }}
@endsection

@section('css_style')

    <!-- Vendor CSS-->
    <link href="{{ url('interface_style/search/vendor/select2/select2.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ url('interface_style/search/vendor/datepicker/daterangepicker.css') }}" rel="stylesheet" media="all">

    <!-- Main CSS-->

    <!-- Main CSS-->

    <style>
        svg:not(:root) {
            overflow: hidden;
            direction: ltr;
        }
    </style>

    {{--    NEW STYLE   --}}
    <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">


@endsection

@section('customer')
    <!-- MAIN CONTENT-->
    <div class="addUser my-4">
        <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
            <h3>{{ MyHelpers::admin_trans(auth()->user()->id,'request_sources') }}:</h3>
        </div>
    </div>

    {{-- For Search Parameters   --}}
    @include('Charts.requestSourceChart-parameters')


    <div class="main-content">
        <div class="section_content section_content--p30">
            <div class="container-fluid">
                    @include('Charts.requestSourceChart-chart')
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!-- Jquery JS-->
    <script src="{{ url('interface_style/search/vendor/jquery/jquery.min.js') }}"></script>
    <!-- Vendor JS-->
    <script src="{{ url('interface_style/search/vendor/select2/select2.min.js') }}"></script>
    <script src="{{ url('interface_style/search/vendor/jquery-validate/jquery.validate.min.js') }}"></script>
    <script src="{{ url('interface_style/search/vendor/bootstrap-wizard/bootstrap.min.js') }}"></script>
    <script src="{{ url('interface_style/search/vendor/bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}"></script>
    <script src="{{ url('interface_style/search/vendor/datepicker/moment.min.js') }}"></script>
    <script src="{{ url('interface_style/search/vendor/datepicker/daterangepicker.js') }}"></script>

    <!-- Main JS-->
    <script src="{{ url('interface_style/search/js/global.js') }}"></script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script>
        var adviser_ids = ("{{implode(',',$adviser_ids)}}").split(',');
        //console.log(adviser_ids);
        $('#status_user').change(function () {
            reFullAdviser_id()
        })
        function reFullAdviser_id() {
            $this = $('#manager_id');
            $.get(
                '{{route('requestChartRApi')}}', {
                    managerId: $this.val(),
                    status_user:$('#status_user').val()
                },
                function(response) {
                    $data = '<option value="0">الكل</option>';
                    response.users.forEach(($user, $index) => {
                        $data += '<option value="' + $user.id + '"' +
                            (adviser_ids.includes('' + $user.id) ? 'selected' : '') +
                            '>' + $user.name + '</option>';

                    });
                    $('#adviser_id').html($data);
                });
        }

        reFullAdviser_id();
        $('#manager_id').on('change', function() {

            reFullAdviser_id();
        });

    </script>

@endsection
