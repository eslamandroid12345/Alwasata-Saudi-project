@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Charts') }}
@endsection

@section('css_style')

    <!-- Vendor CSS-->
    <link href="{{ url('interface_style/search/vendor/select2/select2.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ url('interface_style/search/vendor/datepicker/daterangepicker.css') }}" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="{{ url('interface_style/search/css/main.css') }}" rel="stylesheet" media="all">

    <!-- Main CSS-->

<style>
    svg:not(:root) {
        overflow: hidden;
        direction: ltr;
    }
   </style>

   
@endsection

@section('customer')
<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            
            <div class="row">
                <div class="page-wrapper bg-color-1 p-t-165 p-b-100">
                    <div class="col-12">
                        <div class="card card-2">
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" >
                                        <div class="row row-space">
                                            <div class="col-12"  style="min-width: 747px;">
                                                <span class="desc">{{ MyHelpers::admin_trans(auth()->user()->id,'Extract New Chart depending on search parameter') }}
                                                    <span style="float:right"><i class="fa fa-arrow-circle-o-down fa-2x" id="advance-search-toggle"></i></span>
                                                </span>
                                                <hr>
                                            </div>
                                        </div>
                                    
                                        <form method="post" action="{{route('extractCharts')}}" id="chart-search" style="display:none">
                                            @csrf
                                            <div class="row row-space">
                                                <div class="col-12">
                                                    <div class="input-group">
                                                        <label class="label"> {{ MyHelpers::admin_trans(auth()->user()->id,'Chart Type') }}  </label>
                                                        <div class="rs-select2 js-select-simple select--no-search">
                                                            <select name="chart" id="chart">
                                                                <option value="bar">{{ MyHelpers::admin_trans(auth()->user()->id,'Bar') }}</option>
                                                                <option value="line"> {{ MyHelpers::admin_trans(auth()->user()->id,'Line') }}</option>
                                                                <!-- <option value="pie">Pie</option>
                                                                <option value="donut">Donut</option>
                                                                <option value="area">Area</option>
                                                                <option value="percentage">Percentage</option> -->
                                                            </select>
                                                            <div class="select-dropdown"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="input-group">
                                                        <label class="label"> {{ MyHelpers::admin_trans(auth()->user()->id,'users') }} </label>
                                                        <div class="rs-select2 js-select-simple select--no-search">
                                                            <select name="subs_ids[]"  id="subs_ids" multiple>
                                                            @foreach($subs as $sub)
                                                            <option value="{{$sub->id}}">{{$sub->name}} </option>
                                                            @endforeach
                                                            </select>
                                                            <div class="select-dropdown"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row row-space">
                                                <div class="col-6">
                                                    <div class="input-group">
                                                        <label class="label">{{ MyHelpers::admin_trans(auth()->user()->id,'From Date') }}</label>
                                                        <input class="input--style-1" type="text" name="fromdate" placeholder="mm/dd/yyyy" id="input-start">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="input-group">
                                                        <label class="label">{{ MyHelpers::admin_trans(auth()->user()->id,'To Date') }}</label>
                                                        <input class="input--style-1" type="text" name="todate" placeholder="mm/dd/yyyy" id="input-end">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row row-space">
                                                <div class="col-6">
                                                    <div class="input-group">
                                                        <label class="label">{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</label>
                                                        <div class="rs-select2 js-select-simple select--no-search">
                                                            <select name="type" id="type">
                                                                <option selected="selected" disabled>----</option>
                                                                <option value="شراء">شراء</option>
                                                                <option value="رهن">رهن</option>
                                                                <option value="رهن-شراء">شراء - رهن</option>
                                                            </select>
                                                            <div class="select-dropdown"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="col-6">
                                                    <div class="input-group">
                                                        <label class="label">{{ MyHelpers::admin_trans(auth()->user()->id,'req status') }}</label>
                                                        <div class="rs-select2 js-select-simple select--no-search">
                                                            <select name="statusReq" id="statusReq">
                                                                <option selected="selected" disabled>----</option>
                                                                <option value="0">{{ MyHelpers::admin_trans(auth()->user()->id,'new req') }}</option>
                                                                <option value="1">{{ MyHelpers::admin_trans(auth()->user()->id,'open req') }}</option>
                                                                <option value="2">{{ MyHelpers::admin_trans(auth()->user()->id,'archive in sales agent req') }}</option>
                                                                <option value="3">{{ MyHelpers::admin_trans(auth()->user()->id,'wating sales manager req') }}</option>
                                                                <option value="4">{{ MyHelpers::admin_trans(auth()->user()->id,'rejected sales manager req') }}</option>
                                                                <option value="5">{{ MyHelpers::admin_trans(auth()->user()->id,'archive in sales manager req') }}</option>
                                                                <option value="6">{{ MyHelpers::admin_trans(auth()->user()->id,'wating funding manager req') }}</option>
                                                                <option value="7">{{ MyHelpers::admin_trans(auth()->user()->id,'rejected funding manager req') }}</option>
                                                                <option value="8">{{ MyHelpers::admin_trans(auth()->user()->id,'archive in funding manager req') }}</option>
                                                                <option value="9">{{ MyHelpers::admin_trans(auth()->user()->id,'wating mortgage manager req') }}</option>
                                                                <option value="10">{{ MyHelpers::admin_trans(auth()->user()->id,'rejected mortgage manager req') }}</option>
                                                                <option value="11">{{ MyHelpers::admin_trans(auth()->user()->id,'archive in mortgage manager req') }}</option>
                                                                <option value="12">{{ MyHelpers::admin_trans(auth()->user()->id,'wating general manager req') }}</option>
                                                                <option value="13">{{ MyHelpers::admin_trans(auth()->user()->id,'rejected general manager req') }}</option>
                                                                <option value="14">{{ MyHelpers::admin_trans(auth()->user()->id,'archive in general manager req') }}</option>
                                                                <option value="15">{{ MyHelpers::admin_trans(auth()->user()->id,'Canceled') }}</option>
                                                                <option value="16">{{ MyHelpers::admin_trans(auth()->user()->id,'Completed') }}</option>
                                                            </select>
                                                            <div class="select-dropdown"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="p-t-5">
                                                <hr>
                                                    <label class="checkbox-container m-r-45">{{ MyHelpers::admin_trans(auth()->user()->id,'Calculate average time of data handling') }}
                                                        <input type="checkbox"  name="avg">
                                                        <span class="checkmark"></span>( {{ MyHelpers::admin_trans(auth()->user()->id,'Affected only by users') }} ) 
                                                    </label>
                                                </div>
                                            </div>
                                            <button class="btn-submit btn-info" id="search" type="submit">{{ MyHelpers::admin_trans(auth()->user()->id,'Search') }}</button>
                                        </form>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="extracted-chart"></div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="au-card m-b-30 panel panel-default" >
                        <div class="au-card-inner panel-body">
                        {!! $areaspline->html() !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="au-card m-b-30 panel panel-default" >
                        <div class="au-card-inner panel-body">
                            <!-- show sales agent requests in last 6 months-->
                            {!! $chart->html() !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="au-card m-b-30 panel panel-default" >
                        <div class="au-card-inner panel-body">
                            <!-- show sales agent requests by type -->
                            {!! $pie->html() !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="au-card m-b-30 panel panel-default" >
                        <div class="au-card-inner panel-body">
                             <!-- show sales agent requests by status -->
                            {!! $donut->html() !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="au-card m-b-30 panel panel-default" >
                        <div class="au-card-inner panel-body">
                            {!! $area->html() !!}
                        </div>
                    </div>
                </div>
            </div>


            <div class="row" hidden>
                <div class="col-lg-6">
                    <div class="au-card m-b-30">
                        <div class="au-card-inner">
                            <h3 class="title-2 m-b-40">Yearly Sales</h3>
                            <canvas id="sales-chart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="au-card m-b-30">
                        <div class="au-card-inner">
                            <h3 class="title-2 m-b-40">Team Commits</h3>
                            <canvas id="team-chart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="au-card m-b-30">
                        <div class="au-card-inner">
                            <h3 class="title-2 m-b-40">Bar chart</h3>
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="au-card m-b-30">
                        <div class="au-card-inner">
                            <h3 class="title-2 m-b-40">Rader chart</h3>
                            <canvas id="radarChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="au-card m-b-30">
                        <div class="au-card-inner">
                            <h3 class="title-2 m-b-40">Line Chart</h3>
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="au-card m-b-30">
                        <div class="au-card-inner">
                            <h3 class="title-2 m-b-40">Doughut Chart</h3>
                            <canvas id="doughutChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="au-card m-b-30">
                        <div class="au-card-inner">
                            <h3 class="title-2 m-b-40">Pie Chart</h3>
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="au-card m-b-30">
                        <div class="au-card-inner">
                            <h3 class="title-2 m-b-40">Polar Chart</h3>
                            <canvas id="polarChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="au-card m-b-30">
                        <div class="au-card-inner">
                            <h3 class="title-2 m-b-40">Single Bar Chart</h3>
                            <canvas id="singelBarChart"></canvas>
                        </div>
                    </div>
                </div>
                
            </div>
           
    
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
{!! Charts::scripts() !!}
{!! $chart->script() !!}
{!! $pie->script() !!}
{!! $donut->script() !!}
{!! $area->script() !!}
{!! $areaspline->script() !!}
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

<script>
    $(document).ready(function(){
    $("#advance-search-toggle").click(function(){
        $("#chart-search").toggle();
    });
    });
</script>
<script>
    function isEmpty(obj) {
        for(var key in obj) {
            if(obj.hasOwnProperty(key))
                return false;
        }
    return true;
}
</script>
<script>
    $(document).ready(function () {
        // ajax setup form csrf token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

   
</script>


<script>
    $('#subs_ids').select2();
</script>
@endsection