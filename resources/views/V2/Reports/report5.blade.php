@extends('layouts.content')

@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'Charts') }} - {{ MyHelpers::admin_trans(auth()->user()->id,'Requests') }}
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

@section('customer')
    <div class="addUser my-4">
        <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
            <h3>@lang('reports.report5'):</h3>
        </div>
    </div>

    <div class="topRow">
        <form name="filter" id="filter" method="get">
            <div class="row align-items-center text-center text-md-left">
                <div class="col-6">

                    <label class="label">{{ MyHelpers::admin_trans(auth()->user()->id,'From Date') }}</label>
                    <input class="form-control" type="date" name="startdate" value="{{ app('request')->input('startdate') }}">

                </div>
                <div class="col-6">

                    <label class="label">{{ MyHelpers::admin_trans(auth()->user()->id,'To Date') }}</label>
                    <input class="form-control" type="date" name="enddate" value="{{ app('request')->input('enddate') }}">

                </div>

                <div class="col-12">
                    <label class="label">@lang('attributes.quality_id')</label>
                    <div class="rs-select2 js-select-simple select--no-search">
                        <select class="form-control" name="quality_id[]" multiple id="quality_id">
                            @foreach($QualitySelect as $u)
                                <option value="{!! $u['id'] !!}" {{in_array($u['id'],request('quality_id',[]))? "selected" :""}}>{!! $u['name'] !!}</option>
                            @endforeach
                        </select>
                        <div class="select-dropdown"></div>
                    </div>

                </div>

                <div class="col-12">
                    <label class="label">حالات الطلب </label>
                    <div class="rs-select2 js-select-simple select--no-search">
                        <select class="form-control" name="status[]" multiple>
                            @foreach($QualityRequestStatusSelect as $val)
                                <option value="{{$val['id']}}" {{in_array($val['id'],request('status',[]))? "selected" :""}}>{{$val['name']}}</option>
                            @endforeach
                        </select>
                        <div class="select-dropdown"></div>
                    </div>
                </div>

                <div class="col-12">
                    <label class="label">تصنيف الطلب </label>
                    <div class="rs-select2 js-select-simple select--no-search">
                        <select class="form-control" name="class_id[]" multiple>
                            @foreach($QualityClassificationsSelect as $class)
                                <option value="{{$class['id']}}" {{in_array($class['id'],request('class_id',[]))? "selected" :""}}>
                                    {{$class['name']}}
                                </option>
                            @endforeach

                        </select>
                        <div class="select-dropdown"></div>
                    </div>
                </div>

                <div class="col-12">
                    <label class="label">سلال الطلب </label>
                    <div class="rs-select2 js-select-simple select--no-search">
                        <select class="form-control" name="baskets[]" multiple id="baskets">
                            @foreach($QualityBasketsSelect as $val)
                                <option value="{{$val['id']}}" {{in_array($val['id'],request('baskets',[]))? "selected" :""}}>{{$val['name']}}</option>
                            @endforeach
                        </select>
                        <div class="select-dropdown"></div>
                    </div>
                </div>
            </div>

            <div class="searchSub text-center d-block col-12">
                <div class="tableUserOption flex-wrap justify-content-md-end  justify-content-center downOrder text-center padding-top-15" style="display: block">
                    <div class="addBtn  mt-lg-0 mt-3 orderBtns text-center">
                        <button class="text-center mr-3 green item" name="submit" id="submit" type="submit">
                            <i class="fas fa-search"></i> @lang('global.search')</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="main-content">
        <div class="section_content section_content--p30">
            <div class="container-fluid">
                    @include('V2.Reports.partials.report5.table-statuses')
                    @include('V2.Reports.partials.report5.chart-statuses')
                <br>
                    @include('V2.Reports.partials.report5.table-class')
                    @include('V2.Reports.partials.report5.chart-class')
                <br>
                    @include('V2.Reports.partials.report5.table-basket')
                    @include('V2.Reports.partials.report5.chart-basket')
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        var dt = $('.data-table').DataTable({
            "language": {
                "url": "{{route('datatableLanguage')}}",
                buttons: {
                    excelHtml5: "اكسل",
                    pageLength: "عرض",

                }
            },
            "lengthMenu": [
                [10, 25, 50],
                [10, 25, 50]
            ],
            scrollX: true,
            scrollY: true,
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5',
                'pageLength'
            ],
            initComplete: function () {
                $(".paginate_button").addClass("pagination-circle");
                /* To Adaptive with New Design */
                $('#example-search-input').keyup(function () {
                    dt.search($(this).val()).draw();
                })

                dt.buttons().container()
                    .appendTo('#dt-btns');
                $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title', 'تصدير');
                $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');

                $('.buttons-excel').addClass('no-transition custom-btn');
                $('.buttons-collection').addClass('no-transition custom-btn');

                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)
            },

        });
        var dt2 = $('.data-table2').DataTable({
            "language": {
                "url": "{{route('datatableLanguage')}}",
                buttons: {
                    excelHtml5: "اكسل",
                    pageLength: "عرض",

                }
            },
            "lengthMenu": [
                [10, 25, 50],
                [10, 25, 50]
            ],
            scrollX: true,
            scrollY: true,
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5',
                'pageLength'
            ],
            initComplete: function () {
                $(".paginate_button").addClass("pagination-circle");
                /* To Adaptive with New Design */
                $('#example-search-input').keyup(function () {
                    dt2.search($(this).val()).draw();
                })

                dt2.buttons().container()
                    .appendTo('#dt-btns2');
                $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title', 'تصدير');
                $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');

                $('.buttons-excel').addClass('no-transition custom-btn');
                $('.buttons-collection').addClass('no-transition custom-btn');

                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)
            },

        });
        var dt3 = $('.data-table3').DataTable({
            "language": {
                "url": "{{route('datatableLanguage')}}",
                buttons: {
                    excelHtml5: "اكسل",
                    pageLength: "عرض",

                }
            },
            "lengthMenu": [
                [10, 25, 50],
                [10, 25, 50]
            ],
            scrollX: true,
            scrollY: true,
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5',
                'pageLength'
            ],
            initComplete: function () {
                $(".paginate_button").addClass("pagination-circle");
                /* To Adaptive with New Design */
                $('#example-search-input').keyup(function () {
                    dt3.search($(this).val()).draw();
                })

                dt3.buttons().container()
                    .appendTo('#dt-btns3');
                $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title', 'تصدير');
                $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');

                $('.buttons-excel').addClass('no-transition custom-btn');
                $('.buttons-collection').addClass('no-transition custom-btn');

                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)
            },

        });
    </script>
    <script src="{{ url('interface_style/search/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('interface_style/search/vendor/select2/select2.min.js') }}"></script>
    <script src="{{ url('interface_style/search/vendor/jquery-validate/jquery.validate.min.js') }}"></script>
    <script src="{{ url('interface_style/search/vendor/bootstrap-wizard/bootstrap.min.js') }}"></script>
    <script src="{{ url('interface_style/search/vendor/bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}"></script>
    <script src="{{ url('interface_style/search/vendor/datepicker/moment.min.js') }}"></script>
    <script src="{{ url('interface_style/search/vendor/datepicker/daterangepicker.js') }}"></script>
    <script src="{{ url('interface_style/search/js/global.js') }}"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
@endsection
