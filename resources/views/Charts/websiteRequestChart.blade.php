@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Charts') }} - {{ MyHelpers::admin_trans(auth()->user()->id,'Website') }}
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
        <h3>{{ MyHelpers::admin_trans(auth()->user()->id,'Website') }}:</h3>
    </div>
</div>
<div class="topRow" >
    <form name="filter" id="filter" method="get" action="{{ route('websiteChartR') }}">
        <div class="row align-items-center text-center text-md-left">
            <div class="col-6">

                <label class="label">{{ MyHelpers::admin_trans(auth()->user()->id,'From Date') }}</label>
                <input class="form-control" type="date" name="startdate" value="{{ (app('request')->input('startdate')) != null ? (app('request')->input('startdate')) : date('Y-m-d')  }}">

            </div>
            <div class="col-6">

                <label class="label">{{ MyHelpers::admin_trans(auth()->user()->id,'To Date') }}</label>
                <input class="form-control" type="date" name="enddate"  value="{{(app('request')->input('enddate')) != null ? (app('request')->input('enddate')) :date('Y-m-d') }}">

            </div>

        </div>

        <div class="searchSub text-center d-block col-12">
            <div class="tableUserOption flex-wrap justify-content-md-end  justify-content-center downOrder text-center padding-top-15" style="display: block">
                <div class="addBtn  mt-lg-0 mt-3 orderBtns text-center">
                    <button class="text-center mr-3 green item"  name="submit" id="submit" type="submit" >
                        <i class="fas fa-search"></i>
                        بحث
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="main-content">
    <div class="section_content section_content--p30">
        <div class="container-fluid">


            @if($data_for_chart != null)
                @include('Charts.websiteRequestChart-table')
                @include('Charts.websiteRequestChart-chart')
            @else
             <br><center><h3 style="color:red">لا يوجد نتائج</h3></center>
            @endif

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
            initComplete: function() {
                $(".paginate_button").addClass("pagination-circle");
                /* To Adaptive with New Design */
                $('#example-search-input').keyup(function(){
                    dt.search($(this).val()).draw() ;
                })

                dt.buttons().container()
                    .appendTo( '#dt-btns' );
                $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title','تصدير') ;
                $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title','عرض') ;

                $('.buttons-excel').addClass('no-transition custom-btn');
                $('.buttons-collection').addClass('no-transition custom-btn');

                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)
            },

        });
</script>

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

@endsection
