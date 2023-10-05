@extends('layouts.content')
@section('title',__('reports.report1'))
@section('customer')
    {{-- For Search Parameters   --}}
    <div class="topRow">
        <form name="filter" id="filter" method="get" action="{{ route('V2.Admin.report1') }}">
            <div class="row align-items-center text-center text-md-left">
                <div class="col-4">
                    <label class="label">{{ MyHelpers::admin_trans(auth()->user()->id,'From Date') }}</label>
                    <input class="form-control" type="date" name="start_date"
                           value="{{ request()->input('start_date',now()->subWeek()->format('Y-m-d')) }}">

                </div>
                <div class="col-4">
                    <label class="label">{{ MyHelpers::admin_trans(auth()->user()->id,'To Date') }}</label>
                    <input class="form-control" type="date" name="end_date"
                           value="{{ request()->input('end_date',now()->format('Y-m-d')) }}">

                </div>
                <div class="col-4">
                    <label class="label"> الحالة  </label>
                <select class="form-control" name="status_user" id="status_user" style="height: 38px">
                        <option value="2" {{request('status_user') == 2 ? 'selected' : ''}}>الكل</option>
                    <option value="0" {{request('status_user') == 0 ? 'selected' : ''}}>إستشاري مؤرشف</option>
                    <option value="1" {{request('status_user') == 1 ? 'selected' : ''}}>إستشاري نشط</option>
                    </select>
                </div>

                @if ($manager_role != 1 && $manager_role != 0 && $manager_role != 11)
                    <div class="col-12">
                        <label class="label">اسم المدير </label>
                        <div class="rs-select2 js-select-simple select--no-search">
                            <select class="form-control" name="manager_id[]" multiple id="manager_id">
                                {{--                                <option value="" {{in_array("allManager",$manager_ids) ? "selected" :""}}>الكل</option>--}}
                                @foreach($managers as $manager)
                                    <option
                                        value="{{ $manager->id }}" {{in_array($manager->id,$manager_id) ? "selected" :""}}>{{ $manager->name }}</option>
                                @endforeach
                            </select>
                            <div class="select-dropdown"></div>
                        </div>

                    </div>
                @endif

                @if ($manager_role == 7 || $manager_role == 4 )
                    <div class="col-12">
                        <label class="label">إسم المستشار </label>
                        <div class="rs-select2 js-select-simple select--no-search">
                            <select class="form-control" name="adviser_id[]" multiple id="adviser_id">
                                {{--                                <option value="0">الكل</option>--}}
                            </select>
                            <div class="select-dropdown"></div>
                        </div>

                    </div>
                @endif
            </div>

            <div class="searchSub text-center d-block col-12">
                <div class="rounded text-center padding-top-15" style="display: block">
                    <div class="addBtn  mt-lg-0 mt-3 orderBtns text-center">
                        <button class="text-center mr-3 green item" id="submit" type="submit">
                            <i class="fas fa-search"></i>
                            بحث
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label class="font-weight-bolder h5"><span>التاريخ من</span>: {{$startDate->toDateString()}}</label>
        </div>
        <div class="col-12 col-md-6">
            <label class="font-weight-bolder h5"><span>التاريخ الى</span>: {{$endDate->toDateString()}}</label>
        </div>
        <div class="col-12">
            <hr>
        </div>
        @if(!$chartUsers->count())
            <div class="col-12">
                <p class="text-center">لا يوجد نتائج</p>
            </div>
        @endif
    </div>
{{--    <div class="row">--}}
{{--        <div class="col-12">--}}
{{--            @foreach($result as $data)--}}
{{--                <p>{{$data['user']->name}}: {{$data['found']}}</p>--}}
{{--            @endforeach--}}
{{--        </div>--}}
{{--    </div>--}}
    {{--    @include('Charts.requestChart-parameters')--}}
    {{--    <div class="main-content">--}}
    {{--        <div class="section_content section_content--p30">--}}
    {{--            <div class="container-fluid">--}}
    {{--                @if($data_for_status_chart != null)--}}
    {{--                        @include('Charts.requestChart-table-status')--}}
    {{--                        @include('Charts.requestChart-chart-status')--}}
    {{--                @endif--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    <div class="row">
        <div class="col-lg-12">
            <div class="au-card m-b-30 panel panel-default">
                <div class="au-card-inner panel-body">
                    <div id="chart-container" style="width:100%; height:600px;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css_style')
    <link href="{{ url('interface_style/search/vendor/select2/select2.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ url('interface_style/search/vendor/datepicker/daterangepicker.css') }}" rel="stylesheet" media="all">
    <style>
        svg:not(:root) {
            overflow: hidden;
            direction: ltr;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">
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
        document.addEventListener('DOMContentLoaded', function () {
            Highcharts.chart('chart-container', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: '@lang('reports.report1')'
                },
                xAxis: {
                    categories: {!! $chartUsers->toJson(JSON_UNESCAPED_UNICODE) !!},
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'عدد الطلبات'
                    },
                    stackLabels: {
                        enabled: true,
                        style: {
                            fontWeight: 'bold',
                            color: ( // theme
                                Highcharts.defaultOptions.title.style &&
                                Highcharts.defaultOptions.title.style.color
                            ) || 'gray'
                        }
                    }
                },
                legend: {
                    align: 'right',
                    x: -30,
                    verticalAlign: 'top',
                    y: 25,
                    floating: true,
                    backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || 'white',
                    borderColor: '#CCC',
                    borderWidth: 1,
                    shadow: false
                },
                tooltip: {
                    headerFormat: '<b>{point.x}</b>',
                    pointFormat: ': {point.stackTotal}'
                },
                plotOptions: {
                    column: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: true
                        }
                    }
                },
                series: [
                    {
                        name: 'الطلبات المستلمة',
                        color: '#007bff',
                        data: {!! $chartValues->toJson(JSON_UNESCAPED_UNICODE) !!}
                    },
                ]
            })

        });
    </script>

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
                function (response) {
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
        $('#manager_id').on('change', function () {

            reFullAdviser_id();
        });

    </script>

@endsection
