@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Charts') }} - {{ MyHelpers::admin_trans(auth()->user()->id,'daily_prefromence') }}
@endsection

@section('css_style')
    <script src="{{ asset('backend/sweetalert/sweetalert2.min.js') }}"></script>
    <link href="{{ asset('backend/sweetalert/sweetalert2.min.css') }}" rel="stylesheet">


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
        <h3>{{ MyHelpers::admin_trans(auth()->user()->id,'daily_prefromence') }}:</h3>

    </div>
    <div class="alert alert-danger mt-3">
        <b>تنبيه </b>
        التقرير يعمل بشكل صحيح من يوم 02/04/2022 الثانى من شهر أبريل لعام 2022, لم يتم إعتماد النتائج السابقة لهذا اليوم
    </div>
</div>
<div class="row" id="preloader">
    <div style="padding: 35px;position: absolute;z-index: 999;width: 70%;margin-right: 1.3%;text-align: center">
        <div class="loader"></div>
        تحميل...
    </div>
</div>
{{-- For Search Parameters   --}}
@include('Charts.daily-performance.form')
<div class="tableBar">

   <div class="col-12">

        <label class="label">إظهار  </label>
       <div class="row">
               <span class="button-checkbox">
                    <button type="button" class="btn btn-sm" data-color="primary">المستلمة</button>
                    <input type="checkbox" value="2" class="hidden fields" checked name="fields"/>
                </span>
            <span class="button-checkbox">
                <button type="button" class="btn btn-sm" data-color="primary">طلبات جديدة (تلقائي)</button>
                <input type="checkbox" value="3" class="hidden fields" checked name="fields" />
            </span>

           <span class="button-checkbox">
                    <button  type="button" class="btn btn-sm" data-color="primary">طلبات مميزة</button>
                    <input value="4" type="checkbox" class="hidden fields" checked name="fields" />
                </span>
           <span class="button-checkbox">
                    <button  type="button" class="btn btn-sm" data-color="primary">طلبات متابعة</button>
                     <input value="5" type="checkbox" class="hidden fields" checked name="fields" />
                </span>
           <span class="button-checkbox">
                    <button  type="button" class="btn btn-sm" data-color="primary">طلبات مؤرشفة</button>
                     <input value="6" type="checkbox" class="hidden fields" checked name="fields" />
                </span>
           <span class="button-checkbox">
                    <button  type="button" class="btn btn-sm" data-color="primary">طلبات مرفوعة</button>
                     <input value="7" type="checkbox" class="hidden fields" checked name="fields" />
                </span>
           <span class="button-checkbox">
                <button  type="button" class="btn btn-sm" data-color="primary">طلبات مفرغة</button>
                 <input value="8" type="checkbox" class="hidden fields" checked name="fields" />
            </span>
           <span class="button-checkbox">
                    <button  type="button" class="btn btn-sm" data-color="primary">طلبات مُحدث عليها</button>
                     <input value="9" type="checkbox" class="hidden fields" checked name="fields" />
                </span>
           <span class="button-checkbox">
                    <button  type="button" class="btn btn-sm" data-color="primary">طلبات تم فتحها</button>
                     <input value="10" type="checkbox" class="hidden fields" checked name="fields" />
                </span>
           <span class="button-checkbox">
                    <button  type="button" class="btn btn-sm" data-color="primary">مهام مستلمة</button>
                     <input value="11" type="checkbox" class="hidden fields" checked name="fields" />
                </span>
           <span class="button-checkbox">
                    <button  type="button" class="btn btn-sm" data-color="primary">مهام تم الرد عليها</button>
                     <input value="12" type="checkbox" class="hidden fields" checked name="fields" />
                </span>
           <span class="button-checkbox">
                     <button  type="button" class="btn btn-sm" data-color="primary">طلبات محولٌة منه</button>
                     <input value="13" type="checkbox" class="hidden fields" checked name="fields" />
                </span>
           <span class="button-checkbox">
                    <button  type="button" class="btn btn-sm" data-color="primary">طلبات محولٌة إليه</button>
                     <input value="14" type="checkbox" class="hidden fields" checked name="fields" />
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
            <input type="number" max="{{ $maxVal }}" class="form-control ml-3" id="count" placeholder="عدد الأيام">

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
                <th> الاستشاري</th>
                <th>المستقبلة</th>
                <th> طلبات جديدة (تلقائي)</th>
                <th>طلبات مميزة</th>
                <th>طلبات متابعة</th>
                <th>طلبات مؤرشفة</th>
                <th>طلبات مرفوعة</th>
                <th>طلبات مفرغة</th>
                <th>طلبات مُحدث عليها</th>
                <th>طلبات تم فتحها</th>
                <th>مهام مستلمة</th>
                <th>مهام تم الرد عليها</th>
                <th>طلبات محولٌة منه</th>
                <th>طلبات محولٌة إليه</th>
                <th>تحكم</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
            <tfoot>
            <tr>
                <th>#</th>
                <th>مجموع النتائج</th>
                <th id="total_recived_request">0</th>
                <th id="received_basket">0</th>
                <th id="star_basket">0</th>
                <th id="followed_basket">0</th>
                <th id="archived_basket">0</th>
                <th id="sent_basket">0</th>
                <th id="completed_request">0</th>
                <th id="updated_request">0</th>
                <th id="opened_request">0</th>
                <th id="received_task">0</th>
                <th id="replayed_task">0</th>
                <th id="move_request_from">0</th>
                <th id="move_request_to">0</th>
                <th>#</th>
            </tr>
            </tfoot>
        </table>

        <select class="form-control type_of_requests w-25 my-5" name="type_of_requests">
            <option value="0">طلبات مستلمة</option>
            <option value="1">طلبات جديدة</option>
            <option value="2">طلبات مميزة</option>
            <option value="3">طلبات متابعة</option>
            <option value="4">طلبات مؤرشفة</option>
            <option value="5">طلبات مرفوعه</option>
            <option value="6">طلبات مفرغة</option>
            <option value="7">طلبات محدث عليها</option>
            <option value="8">طلبات تم فتحها</option>
            <option value="9">مهام مستلمة</option>
            <option value="10">مهام تم الرد عليها</option>
            <option value="11">طلبات محولة منه</option>
            <option value="12">طلبات محوله اليه</option>
            <option value="99">جميع الطلبات</option>
        </select>

    {{-- {!! $chart->html() !!}
    {!! Charts::scripts() !!}
    {!! $chart->script() !!} --}}

    <div style="max-height: 900px">
        {!! $chart2->container() !!}
    </div>

    {{-- {!! $chart2->script() !!} --}}
    </div>
   {{-- @include('Charts.dailyPrefromenceChart-chart')--}}
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
{!! $chart2->script() !!}

<script type="text/javascript">
    var original_api_url = {{ $chart2->id }}_api_url;
    $(document).on('change','.type_of_requests',function(){
        var type_of_requests = $(this).val();
        console.log(type_of_requests)
        {{ $chart2->id }}_refresh(original_api_url + "?type_of_requests="+type_of_requests);
    });
</script>

<script src="{{ asset('backend/sweetalert/sweetalert2.min.js') }}"></script>
<link href="{{ asset('backend/sweetalert/sweetalert2.min.css') }}" rel="stylesheet">

<script>
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
                var favorite = [];
                dt.columns().visible(false)
                dt.column( 0 ).visible(true)
                dt.column( 1 ).visible(true)

                dt.column( 15 ).visible(true)

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
        $("#pendingReqs-table tr td:nth-child("+val+")").css("display","none")
        $("#pendingReqs-table tr th:nth-child("+val+")").css("display","none")
    }
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
           'url': "{{ route('daily.report')  }}",
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
                data: 'total_recived_request',
                name: 'total_recived_request'
            },
            {
                data: 'received_basket',
                name: 'received_basket'
            },
            {
                data: 'star_basket',
                name: 'star_basket'
            },
            {
                data: 'followed_basket',
                name: 'followed_basket'
            },
            {
                data: 'archived_basket',
                name: 'archived_basket'
            },
            {
                data: 'sent_basket',
                name: 'sent_basket'
            },
            {
                data: 'completed_request',
                name: 'completed_request'
            },

            {
                data: 'updated_request',
                name: 'updated_request'
            },

            {
                data: 'opened_request',
                name: 'opened_request'
            },
            {
                data: 'received_task',
                name: 'received_task'
            },
            {
                data: 'replayed_task',
                name: 'replayed_task'
            },
            {
                data: 'move_request_from',
                name: 'move_request_from'
            },
            {
                data: 'move_request_to',
                name: 'move_request_to'
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
                sum();
                api.draw();

            });
            $("#all").on('click', function (e) {
                $('#preloader').css("display","block")
                $("#startdate").val("2022-04-02")
                $("#enddate").val("{{date('Y-m-d')}}")
                $("#count").val({{$maxVal}})
                e.preventDefault();
                sum();
                api.draw();

            });
            $("#week").on('click', function (e) {
                $('#preloader').css("display","block")
                $("#startdate").val("{{date('Y-m-d',strtotime(now()->subDays(7)))}}")
                $("#enddate").val("{{date('Y-m-d')}}")
                $("#count").val(7)
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
                sum();
                api.draw();
            });
            $("#filter-search-req").on('click', function (e) {
                $('#preloader').css("display","block")
                e.preventDefault();
                // ---------- when click search button ---------------
                //1- return report data /2- draw chart
                sum();
                api.draw();
                //----------------------------------------------------
            });
            $("#status_user").on('change', function (e) {
                e.preventDefault();
                sum();
                api.draw();
            });
            sum();
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
        $.ajax({
            url: "{{ route('daily.report.sum') }}",
            type: "GET",
            data: {
                'startdate' : $('#startdate').val(),
                'enddate' : $('#enddate').val(),
                'status_user' : $('#status_user').val(),
                'manager_id' : $('#manager_id').val(),
                'adviser_id' : $('#adviser_id').val(),
            },
            success: function(data) {
                $.each( data.data, function( key, value ) {
                    $('#'+key).html(value)
                });
            },
            error: function() {
                swal({
                    title: 'خطأ',
                    text: data.message,
                    type: 'خطأ',
                    timer: '750'
                })
            }
        });
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
            '{{route("requestChartRApi")}}', {
                managerId: $this.val(),
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

            $(this).val().forEach(myFunction);
        }
    });

    function myFunction(item,index) {
        dt.column( item ).visible(true)
    }
    reFullAdviser_id();

    $('#manager_id').on('change', function() {
        reFullAdviser_id();
    });

</script>

@endsection
