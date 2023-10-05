@extends('layouts.content')

@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'Charts') }} - {{ MyHelpers::admin_trans(auth()->user()->id,'Requests') }}

    -
    سلال الطلب
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
    .middle-screen {
        height: 100%;
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: center;
    }

    .modal-backdrop {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: -2 !important;
        background-color: #000;
    }

    .tooltips {
        position: relative;
        display: inline-block;
        border-bottom: 1px dotted black;
    }

    .tooltips .tooltipstext {
        visibility: hidden;
        width: 120px;
        background-color: black;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;
        position: absolute;
        z-index: 1;
        bottom: 150%;
        left: 50%;
        margin-left: -60px;
    }

    .tooltips .tooltipstext::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: black transparent transparent transparent;
    }

    .tooltips:hover .tooltipstext {
        visibility: visible;
    }

</style>
<style>
    .loader {
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #3498db;
        width: 120px;
        margin: auto;
        height: 120px;
        -webkit-animation: spin 2s linear infinite; /* Safari */
        animation: spin 2s linear infinite;
    }

    /* Safari */
    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    input[type="radio"], input[type="checkbox"] {
        box-sizing: border-box;
        padding: 0;
        display: none;
    }
    .button-checkbox button{
        clip-path: polygon(100% 100%, 100% 0%, 10% 0%, 0% 55%, 12.1% 100%);
        padding-left: 15px !important;
    }
    .btn-default{
        background: #012248;
        color: #eee;
        opacity: .5;
    }
    .btn-default:hover{
        color: #eee;
    }
    .button-checkbox{
        margin: 1px 1px;
    }
    .fa-times {
        font-size: 14px;
        padding: 0 2px;
    }
</style>
{{--    NEW STYLE   --}}
<link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">


@endsection

@section('customer')
<!-- MAIN CONTENT-->
<div class="addUser my-4">
    <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
        <h3>سلال الطلب:</h3>
    </div>
</div>
<div class="row" id="preloader">
    <div style="padding: 35px;position: absolute;z-index: 999;width: 70%;margin-right: 1.3%;text-align: center">
        <div class="loader"></div>
        تحميل...
    </div>
</div>
{{-- For Search Parameters   --}}
@include('Charts.requests.form',["name" => "manager_id" ,"value" => auth()->id()])
<div class="tableBar">

    <div class="col-12">
        <label class="label">إظهار  </label>
        <div class="row">
          {{--  <span class="button-checkbox">
                <button type="button" onclick="myFun()" class="btn btn-sm pr-2 pl-2" data-color="primary">الكل</button>
                <input type="checkbox" value="all" class="hidden fields" checked id="all"/>
            </span>--}}
           <span class="button-checkbox">
                <button type="button" class="btn btn-sm pr-2 pl-2" data-color="primary">مكتملة</button>
                <input type="checkbox" value="2" class="hidden fields" checked name="fields"/>
            </span>
            <span class="button-checkbox">
                <button type="button" class="btn btn-sm pr-2 pl-2" data-color="primary">مؤرشفة</button>
                <input type="checkbox" value="3" class="hidden fields" checked name="fields"/>
            </span>
            <span class="button-checkbox">
                <button type="button" class="btn btn-sm pr-2 pl-2" data-color="primary">متابعة</button>
                <input type="checkbox" value="4" class="hidden fields" checked name="fields"/>
            </span>
            <span class="button-checkbox">
                <button type="button" class="btn btn-sm pr-2 pl-2" data-color="primary">مميزة</button>
                <input type="checkbox" value="5" class="hidden fields" checked name="fields"/>
            </span>
            <span class="button-checkbox">
                <button type="button" class="btn btn-sm pr-2 pl-2" data-color="primary">مستلمة</button>
                <input type="checkbox" value="6" class="hidden fields" checked name="fields"/>
            </span>
        </div>
    </div>
   {{-- <button onclick="myFun(5)">ClickMe</button>--}}
    <div class="dashTable row" style="overflow: hidden">

        <div class="col-lg-12 pt-2">
            <label for="" class="label">الفلتر الزمني</label>
        </div>
        <div class="col-lg-3 pr-0">
            <div class="btn-group btn-block" data-toggle="buttons" id="">
                <label class="btn btn-warning" id="all">
                    <input type="radio" checked>
                    الكل
                </label>
                <label class="btn btn-warning" id="day">
                    <input type="radio" checked>
                    اليوم
                </label>
                <label class="btn btn-warning" id="week">
                    <input type="radio">
                    أسبوع
                </label>
            </div>
        </div>

        <div class="col-lg-8 pl-0 pr-5">
            <input type="text" class="form-control ml-3" id="count" placeholder="عدد الأيام">

        </div>
        <div class="col-lg-1 pl-0 pr-5">
            <button id="many" class="btn btn-warning">
                بحث
            </button>
        </div>
    </div>
    <div class="topRow">
        <div class="row align-items-center text-center text-md-left">
            <div class="col-lg-9">
                {{--   <a href="{{route("requests.report.basket")}}" class="btn btn-primary p-2 pr-2 pl-2">
                       <i class="fa fa-shopping-basket"></i>
                       تقرير سلال الطلب
                   </a>--}}
                <a href="{{route("sales.manager.charts.sales.requests.classification")}}" class="btn btn-dark btn-sm">
                    <i class="fa fa-clipboard"></i>
                    تقرير تصنيفات الطلب
                </a>
                <button disabled href="{{route("sales.manager.charts.sales.requests.basket")}}" class="btn btn-sm btn-outline-dark">
                    <i class="fa fa-shopping-basket"></i>
                    تقرير سلال الطلب
                </button>

                <a href="{{route("sales.manager.charts.sales.requests.status")}}" class="btn btn-sm btn-dark">
                    <i class="fa fa-list"></i>
                    تقرير حالات الطلب
                </a>
            </div>
            <div class="col-lg-3 mt-lg-0 mt-3">
                <div id="dt-btns" class="tableAdminOption">

                </div>
            </div>
        </div>
    </div>
    <div class="dashTable">
        <table id="pendingReqs-table" class="table table-bordred table-striped data-table">
            <thead>
            <tr>
                <th>#</th>
                <th>إسم الإستشاري</th>
                <th>مكتملة</th>
                <th>مؤرشفة</th>
                <th>متابعة</th>
                <th>مميزة</th>
                <th>مستلمة</th>
                <th>تحكم</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
            <tfoot>
            <tr id="tfoot">
                <th>#</th>
                <th>مجموع النتائج</th>
                <th id="completes"><i class="fa fa-spinner fa-spin"></i></th>
                <th id="archived"><i class="fa fa-spinner fa-spin"></i></th>
                <th id="following"><i class="fa fa-spinner fa-spin"></i></th>
                <th id="star"><i class="fa fa-spinner fa-spin"></i></th>
                <th id="received"><i class="fa fa-spinner fa-spin"></i></th>
                <th>#</th>
            </tr>
            </tfoot>
        </table>
    </div>
   {{-- @include('Charts.dailyPrefromenceChart-chart')--}}
</div>
@endsection

@section('scripts')
<script src="{{ asset('backend/sweetalert/sweetalert2.min.js') }}"></script>
<link href="{{ asset('backend/sweetalert/sweetalert2.min.css') }}" rel="stylesheet">
<script>

    var dt = $('.data-table').DataTable({
        "language": {
            "url": "{{route('datatableLanguage')}}",
            buttons: {
                excelHtml5: "اكسل",
                print: "طباعة",
                pageLength: "عرض",
                colvis: "تصفية"
            }
        },
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "الكل"]
        ],
        dom: 'Bfrtip',
        buttons: [
            // 'copyHtml5',
            'excelHtml5',
            // 'csvHtml5',
            // 'pdfHtml5' ,
            'print',
            'pageLength'
        ],

        processing: true,
        serverSide: true,
        ajax: ({
           'url': "{{ route('requests.report.basket')  }}?sales_id={{auth()->id()}}",
           'method': 'GET',
           'data':function (data) {
               let status_user =  $('#status_user').val();
               let adviser_id =  $('#adviser_id').val();
               let manager_id =  $('#manager_id').val();
               let enddate =  $('#enddate').val();
               let startdate =  $('#startdate').val();

               if (status_user != '') {
                   data['status_user'] = status_user;
               }
               if (adviser_id != '') {
                   data['adviser_id'] = adviser_id;
               }
               if (manager_id != '') {
                   data['manager_id'] = manager_id;
               }
               if (enddate != '') {
                   data['enddate'] = enddate;
               }
               if (startdate != '') {
                   data['startdate'] = startdate;
               }
        }}),
        columns: [
            {
                data: 'idn',
                name: 'idn'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'complete',
                name: 'complete'
            },
            {
                data: 'archived',
                name: 'archived'
            },
            {
                data: 'following',
                name: 'following'
            },
            {
                data: 'star',
                name: 'star'
            },
            {
                data: 'received',
                name: 'received'
            },
            {
                data: 'action',
                name: 'action',
                orderable: !1,
                searchable: !1,
                sortable: !1,
            }
        ],
        "drawCallback": function( settings ) {
            $('#preloader').css("display","none")
        },
        initComplete: function() {

            let api = this.api();
            $("#day").on('click', function (e) {
                $('#preloader').css("display","block")
                $("#startdate").val("{{date('Y-m-d')}}")
                $("#enddate").val("{{date('Y-m-d')}}")
                $("#count").val(0)
                e.preventDefault();
                reset()
                sum();
                api.draw();

            });
            $("#week").on('click', function (e) {
                $('#preloader').css("display","block")
                $("#startdate").val("{{date('Y-m-d',strtotime(now()->subDays(7)))}}")
                $("#enddate").val("{{date('Y-m-d')}}")
                $("#count").val(7)
                e.preventDefault();
                reset()
                sum();
                api.draw();
            });
            $("#all").on('click', function (e) {
                $('#preloader').css("display","block")
                $("#startdate").val(null)
                $("#enddate").val("{{date('Y-m-d')}}")
                $("#count").val({{$maxVal}})
                e.preventDefault();
                sum();
                api.draw();

            });
            $("#many").on('click', function (e) {
                $("#day").removeClass("active");
                $("#week").removeClass("active");
                var max= "{{$maxVal}}";

                if(max < $("#count").val()){
                    swal({
                        title: 'خطأ',
                        text: "التقرير يعمل من يوم 2-4-2022 واقصي عدد ايام مسموح بيه هو "+max,
                        type: 'خطأ',
                        timer: '3000',
                        showCancelButton: false,
                        showConfirmButton: false,
                    })
                    $("#count").val(max)
                }
                var now = new Date();
                var day = ("0" + now.getDate()).slice(-2);
                var month = ("0" + (now.getMonth() + 1)).slice(-2);

                var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
                now.setDate(now.getDate() - $("#count").val());
                var day1 = ("0" + now.getDate()).slice(-2);
                var month2 = ("0" + (now.getMonth() + 1)).slice(-2);

                var today1 = now.getFullYear()+"-"+(month2)+"-"+(day1) ;
                $("#startdate").val(today1)
                $("#enddate").val(today)
                e.preventDefault();
                reset()
                sum();
                api.draw();
            });
            $("#filter-search-req").on('click', function (e) {
                $('#preloader').css("display","block")
                e.preventDefault();
                api.draw();
                reset()
                 sum()
            });
            $("#user_status").on('change', function (e) {
                e.preventDefault();
                api.draw();
                reset()
                 sum()
            });
            sum()
            dt.buttons().container()
                .appendTo('#dt-btns');
            $(".dt-button").last().html('<i class="fas fa-search"></i>').attr('title', 'بحث');
            $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title', 'تصدير');
            $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title', 'طباعة');
            $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');

            $('.buttons-excel').addClass('no-transition custom-btn');
            $('.buttons-print').addClass('no-transition custom-btn');
            $('.buttons-collection').addClass('no-transition custom-btn');

            $('.tableAdminOption span').tooltip(top)
            $('button.dt-button').tooltip(top)
            /* To Adaptive with New Design */

        },
    });
    function sum() {
        $("#tfoot th").each( function( key, value ) {
            ajaxCall(value.id)
        });
    }
    function reset() {
        $("#tfoot th").each( function( key, value ) {
            if(value.id != ""){
                $('#'+value.id).html("<i class='fa fa-spinner fa-spin'></i>")
            }
        });

    }
    function ajaxCall(key) {
        if(key != ""){
            $.ajax({
                url: "{{ route('requests.report.status.sum')  }}",
                type: "GET",
                data: {
                    'key' : key,
                    'startdate' : $('#startdate').val(),
                    'enddate' : $('#enddate').val(),
                    'status_user' : $('#status_user').val(),
                    'sales_id' : "{{auth()->id()}}",
                    'adviser_id' : $('#adviser_id').val(),
                },
                success: function(data) {
                    $('#'+key).html(data.data)
                }
            });
        }
    }
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
<script>
    //console.log(adviser_ids);
    $('#status_user').change(function () {
        reFullAdviser_id()
    })
    function reFullAdviser_id() {
        $this = $('#manager_id');
        $.get(
            '{{route('sales.manager.requestChartRApiForManager')}}', {
                status_user:$('#status_user').val()
            },
            function(response) {
                var data = '<option value="0">الكل</option>';
                $.each(response.users, function(k, v) {
                    data += '<option value="' + v.id + '">' + v.name + '</option>';
                });

                $('#adviser_id').html(data);
            });
    }
    $(function () {
        $('.button-checkbox').each(function () {

            // Settings
            var $widget = $(this),
                $button = $widget.find('button'),
                $checkbox = $widget.find('input:checkbox'),
                color = $button.data('color'),
                settings = {
                    on: {
                        icon: 'fa fa-check fa-1x'
                    },
                    off: {
                        icon: 'fa fa-times fa-1x'
                    }
                };
            $checkbox.prop('checked', true);
            // Event Handlers
            $button.on('click', function () {
                $checkbox.prop('checked', !$checkbox.is(':checked'));
                $checkbox.triggerHandler('change');
                updateDisplay();
                dt.columns().visible(true)

                dt.columns().visible(false)
                dt.column( 0 ).visible(true)
                dt.column( 1 ).visible(true)
                dt.column( 7 ).visible(true)

                $.each($("input[name='fields']:checked"), function(){
                    dt.column( $(this).val() ).visible(1)
                });

            });
            $checkbox.on('change', function () {
                updateDisplay();
            });

            // Actions
            function updateDisplay() {
                var isChecked = $checkbox.is(':checked');

                // Set the button's state
                $button.data('state', (isChecked) ? "on" : "off");

                // Set the button's icon
                $button.find('.state-icon')
                    .removeClass()
                    .addClass('state-icon ' + settings[$button.data('state')].icon);

                // Update the button's color
                if (isChecked) {
                    $button
                        .removeClass('btn-default')
                        .addClass('btn-' + color + ' active');
                }
                else {
                    $button
                        .removeClass('btn-' + color + ' active')
                        .addClass('btn-default');
                }
            }

            // Initialization
            function init() {

                updateDisplay();

                // Inject the icon if applicable
                if ($button.find('.state-icon').length == 0) {
                    $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
                }
            }
            init();
        });
    });
    function myFun(val){

        $('.button-checkbox').each(function () {
            var $widget = $(this),
                $button = $widget.find('button'),
                $checkbox = $widget.find('input:checkbox'),
                color = $button.data('color');
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');

        });

        var isChecked = $("#all").is(':checked');
        // Set the button's state
        if (isChecked) {
            $("#all")
                .removeClass('btn-default')
                .addClass('btn-' + color + ' active');
        }
        else {
            $("#all")
                .removeClass('btn-' + color + ' active')
                .addClass('btn-default');
        }
    }
    var show = $('#show');

    show.on('change', function() {
        dt.columns().visible(true)
        var isAll = jQuery.inArray( "all", $(this).val() )
        if(isAll != -1){
            dt.columns().visible(true)
        }else{
            dt.columns().visible(false)
            dt.column( 0 ).visible(true)
            dt.column( 1 ).visible(true)
            dt.column( 7 ).visible(true)
            $(this).val().forEach(myFunction);
        }
    });

    function myFunction(item,index) {
        dt.column( item ).visible(true)
    }
    reFullAdviser_id();


</script>

@endsection
