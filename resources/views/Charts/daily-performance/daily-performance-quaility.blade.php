@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Charts') }} - {{ MyHelpers::admin_trans(auth()->user()->id,'daily_prefromence') }}
لالجودة
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


    img{
        display: block;
        margin: auto;
    }
    
</style>
{{--    NEW STYLE   --}}
<link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">
@endsection

@section('customer')
<!-- MAIN CONTENT-->
<div class="addUser my-4">
    <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
        <h3>{{ MyHelpers::admin_trans(auth()->user()->id,'daily_prefromence') }} لالجودة :</h3>

    </div>
    <div class="alert alert-danger mt-3">
        <b>تنبيه </b>
        التقرير يعمل بشكل صحيح من يوم 2022-06-01 الأول من شهر يونيو لعام 2022, لم يتم إعتماد النتائج السابقة لهذا اليوم
    </div>
</div>
<div class="row" id="preloader">
    <div style="padding: 35px;position: absolute;z-index: 999;width: 70%;margin-right: 1.3%;text-align: center">
        <div class="loader"></div>
        تحميل...
    </div>
</div>
{{-- For Search style   --}}
<div class="topRow" >
    <form method="POST" id="frm-update">
        @csrf
        <div class="row align-items-center text-center text-md-left">
            <div class="col-4">

                <label class="label">{{ MyHelpers::admin_trans(auth()->user()->id,'From Date') }}</label>
                <input class="form-control" type="date" min="2022-06-01" name="startdate" id="startdate" value="{{date('Y-m-d') }}">

            </div>
            <div class="col-4">

                <label class="label">{{ MyHelpers::admin_trans(auth()->user()->id,'To Date') }}</label>
                <input class="form-control" type="date" min="2022-06-01" name="enddate" id="enddate" value="{{date('Y-m-d') }}">

            </div>
            <div class="col-4">
                <label class="label"> الحالة  </label>
                <select class="form-control" name="status_user" id="status_user" style="height: 38px">
                    <option value="1">مدير نشط</option>
                    <option value="0">مدير مؤرشف</option>
                    <option value="2">الكل</option>

                </select>
            </div>

            <div class="col-12">
                <label class="label">اسم المدير </label>
                <div class="rs-select2 js-select-simple select--no-search">
                    <select class="form-control" name="adviser_id[]" multiple id="adviser_id">
                        <option value="0">الكل</option>
                        @foreach($users as $manager)
                            <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                        @endforeach
                    </select>
                    <div class="select-dropdown"></div>
                </div>

            </div>

        </div>

        <div class="searchSub text-center d-block col-12">
            <div class="tableUserOption flex-wrap justify-content-md-end  justify-content-center downOrder text-center padding-top-15" style="display: block">
                <div class="addBtn  mt-lg-0 mt-3 orderBtns text-center">
                    <button class="text-center mr-3 green item"  name="submit" id="filter-search-req"  >
                        <i class="fas fa-search"></i>
                        بحث
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="tableBar">

   <div class="col-12">

        <label class="label">إظهار  </label>
       <div class="row">
     
              {{-- <span class="button-checkbox">
                    <button type="button" class="btn btn-sm" data-color="primary">المستقبلة</button>
                    <input type="checkbox" value="2" class="hidden fields" checked name="fields"/>
                </span>--}}
                <span class="button-checkbox">
                    <button  type="button" class="btn btn-sm" data-color="primary">طلبات متابعة</button>
                     <input value="2" type="checkbox" class="hidden fields" checked name="fields" />
                </span>
                <span class="button-checkbox">
                    <button  type="button" class="btn btn-sm" data-color="primary">طلبات مؤرشفة</button>
                     <input value="3" type="checkbox" class="hidden fields" checked name="fields" />
                </span>
               <span class="button-checkbox">
                        <button  type="button" class="btn btn-sm" data-color="primary">طلبات غير مكتملة</button>
                         <input value="4" type="checkbox" class="hidden fields" checked name="fields" />
                    </span>
              {{-- <span class="button-checkbox">
                    <button  type="button" class="btn btn-sm" data-color="primary">طلبات مكتملة</button>
                     <input value="6" type="checkbox" class="hidden fields" checked name="fields" />
                </span>--}}
                <span class="button-checkbox">
                    <button  type="button" class="btn btn-sm" data-color="primary">طلبات مُحدث عليها</button>
                     <input value="5" type="checkbox" class="hidden fields" checked name="fields" />
                </span>
                <span class="button-checkbox">
                    <button  type="button" class="btn btn-sm" data-color="primary">طلبات تم فتحها</button>
                     <input value="6" type="checkbox" class="hidden fields" checked name="fields" />
                </span>
                <span class="button-checkbox">
                    <button  type="button" class="btn btn-sm" data-color="primary">مهام مرسلة</button>
                     <input value="7" type="checkbox" class="hidden fields" checked name="fields" />
                </span>
                {{--<span class="button-checkbox">
                    <button  type="button" class="btn btn-sm" data-color="primary">مهام تم الرد عليها</button>
                     <input value="10" type="checkbox" class="hidden fields" checked name="fields" />
                </span>--}}
                <span class="button-checkbox">
                    <button  type="button" class="btn btn-sm" data-color="primary">الإستطلاعات</button>
                     <input value="8" type="checkbox" class="hidden fields" checked name="fields" />
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
                <th> الجودة</th>{{--
                <th>المستقبلة</th>--}}
                <th>طلبات متابعة</th>
                <th>طلبات مؤرشفة</th>
                <th>طلبات غير مكتملة</th>{{--
                <th>طلبات مكتملة</th>--}}
                <th>طلبات مُحدث عليها</th>
                <th>طلبات تم فتحها</th>
                <th>مهام مرسلة</th>{{--
                <th>مهام تم الرد عليها</th>--}}
                <th>الإستطلاعات</th>
                <th>تحكم</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
            <tfoot>
            <tr>
                <th>#</th>
                <th>مجموع النتائج</th>{{--
                <th id="received_basket">0</th>--}}
                <th id="followed_basket">0</th>
                <th id="archived_basket">0</th>
                <th id="sent_basket">0</th>{{--
                <th id="completed_request">0</th>--}}
                <th id="updated_request">0</th>
                <th id="opened_request">0</th>
                <th id="received_task">0</th>{{--
                <th id="replayed_task">0</th>--}}
                <th id="star_basket">0</th>
                <th>#</th>
            </tr>
            </tfoot>
        </table>
        
<!-- ========================================================================================== -->

        <select class="form-control type_of_requests w-25 my-5" name="type_of_requests">
            <!-- <option value="">--</option> -->
            <!-- <option value="2">طلبات مميزة</option> -->
            <option value="3">طلبات متابعة</option>
            <option value="4">طلبات مؤرشفة</option>
            <option value="5">طلبات غير مكتمله</option>
            <!-- <option value="6">طلبات مفرغة</option> -->
            <option value="7">طلبات محدث عليها</option>
            <option value="8">طلبات تم فتحها</option>
            <!-- <option value="10">مهام تم الرد عليها</option> -->
            <option value="99" selected>جميع الطلبات</option>
        </select>

    <div style="max-height: 900px">
            <div class="image-loader" style="display: none">
                <img src="https://i.imgur.com/fXUIBfi.gif" alt="Chart will Render Here..."/>
            </div>
            <div class="chart-draw">
                {!! $chart2->container() !!}
            </div>
        </div>

<!-- ========================================================================================== -->


    </div>

</div>
@endsection

@section('scripts')
<script src="{{ asset('backend/sweetalert/sweetalert2.min.js') }}"></script>
<link href="{{ asset('backend/sweetalert/sweetalert2.min.css') }}" rel="stylesheet">

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
{!! $chart2->script() !!}

<script type="text/javascript">
    
    var original_api_url = {{ $chart2->id }}_api_url;
   // alert(original_api_url);
    $(document).on('change','.type_of_requests',function(){
       //drawChart();
       quiltyRepoertChart();
    });

    function drawChart(){
        var status_user= $('#status_user').val();
        var startdate = $('#startdate').val();
        var enddate = $('#enddate').val();
       // var adviser_id = $('#adviser_id').val();
        var type_of_requests = $('.type_of_requests').val();


        var adviser_id = [];
        for (var option of document.getElementById('adviser_id').options)
        {
            if (option.selected) {
                adviser_id.push(option.value);
            }
        }
        var url= "{{ URL::to('/chart/ajax-chart-line-quilty')}}";
       // alert(url);

      
       // console.log(enddate)
        console.log(url + "?type_of_requests="+type_of_requests+"&status_user="+status_user+'&startdate='+startdate+'&enddate='+enddate+'&adviser_id='+adviser_id)
        {{ $chart2->id }}_refresh(url + "?type_of_requests="+type_of_requests+"&status_user="+status_user+'&startdate='+startdate+'&enddate='+enddate+'&adviser_id='+adviser_id);
    
    }

    //========================================================================

    function quiltyRepoertChart()
    {
        // var adviser_id = [];
        // for (var option of document.getElementById('adviser_id').options)
        // {
        //     if (option.selected) {
        //         adviser_id.push(option.value);
        //     }
        // }
        
        let users_of_labels=[];
        let count_of_dataset=[];

        let status_user                 =  $('#status_user').val();
        let type_of_requests            =  $('.type_of_requests').val();
        let enddate                     =  $('#enddate').val();
        let startdate                   =  $('#startdate').val();
        let adviser_id                  =  $('#adviser_id').val();

        //console.log(adviser_id);
        $('.image-loader').css("display","block");
        $('.chart-draw').css("display","none");

        // console.log(startdate);
        // console.log(enddate);

        $.ajax({
           
                url: "{{ route('quiltyRepoertChart')  }}",
                type: "POST",
                data: {
                    "_token"                    : "{{csrf_token()}}",
                    'status_user'               : status_user,
                    'adviser_id'                : adviser_id,
                    'type_of_requests'          : type_of_requests,
                    'enddate'                   : enddate,
                    'startdate'                 : startdate,
                },
                success: function(data) {
                   // console.log(data);
                    let title=data.title;

                    $.each(data.users, function(key,value) {
                        users_of_labels.push(value.name);
                    });

                    $.each(data.result, function(key,value) {
                        count_of_dataset.push(value);
                    });

                    let myChart = window.{{ $chart2->id }};

                    myChart.data.labels = users_of_labels;          // to change labels of chart

                    // console.log('users');
                    // console.log(users_of_labels);

                    // console.log('result');
                    // console.log(count_of_dataset);

                    var obj = Object.assign({}, count_of_dataset);  // convert array to object because datasets can hold object or array
                    myChart.data.datasets[0].data  = obj;        // to change datasets of chart by object

                    // console.log('object');
                    // console.log(obj);
                    
                    // myChart.data.datasets[0].data  = count_of_dataset  // to change datasets of chart by array

                    myChart.data.datasets[0].label = title;          // to change label=title of chart

                    myChart.update();
                    
                    $('.image-loader').css("display","none");
                    $('.chart-draw').css("display","block");
                }
        });
    }
</script>


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
                dt.column( 9 ).visible(true)
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
           'url': "{{ route('daily.report')  }}",//this route used in daily report and quilty daily report also
           'method': 'GET',
           'data':function (data) {
               let status_user =  $('#status_user').val();
               let adviser_id =  $('#adviser_id').val();
               let enddate =  $('#enddate').val();
               let role =  5;
               let startdate =  $('#startdate').val();

               if (status_user != '') {
                   data['status_user'] = status_user;
               }
               if (role != '') {
                   data['role'] = role;
               }
               if (adviser_id != '') {
                   data['adviser_id'] = adviser_id;
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
                data: 'name_for_admin',
                name: 'name_for_admin'
            },/*
            {
                data: 'received_basket',
                name: 'received_basket'
            },*/
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
            },/*
            {
                data: 'completed_request',
                name: 'completed_request'
            },*/
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
            },/*
            {
                data: 'replayed_task',
                name: 'replayed_task'
            },*/

            {
                data: 'star_basket',
                name: 'star_basket'
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
            $('.image-loader').css("display","none")
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
               /* var date1 = Date.parse(today1)
                var date2 = Date.parse(today)
                alert(date1+" - "+date2)
                if(date1 > date2) {
                    swal({
                        title: 'خطأ',
                        text: "قيمة عدد الأيام المدخلة غير صحيحة",
                        type: 'خطأ',
                        timer: '750'
                    })

                }*/
                $("#startdate").val(today1)
                $("#enddate").val(today)
                e.preventDefault();
                sum();
                api.draw();
            });
            
            $("#filter-search-req").on('click', function (e) {
                $('#preloader').css("display","block")
                e.preventDefault();
                sum();
                api.draw();
                quiltyRepoertChart();
                // drawChart();
            });
            
            $(document).on('change', '#status_user,#adviser_id',function (e) {
                e.preventDefault();
                sum();
                api.draw();
                quiltyRepoertChart();
            });

            
           // sum();

            dt.buttons().container() .appendTo('#dt-btns');
            $(".dt-button").last().html('<i class="fas fa-search"></i>').attr('title', 'بحث');
            $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title', 'تصدير');
            $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title', 'طباعة');
            $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');

            $('.buttons-excel').addClass('no-transition custom-btn');
            $('.buttons-print').addClass('no-transition custom-btn');
            $('.buttons-collection').addClass('no-transition custom-btn');

            //$('.tableAdminOption span').tooltip(top);
            //$('button.dt-button').tooltip(top);

            /* To Adaptive with New Design */

        }
    });

    
    function sum() {
        $.ajax({
            url: "{{ route('daily.report.sum') }}",
            type: "GET",
            data: {
                'startdate' : $('#startdate').val(),
                'enddate' : $('#enddate').val(),
                'status_user' : $('#status_user').val(),
                'role' : 5,
                'adviser_id' : $('#adviser_id').val(),
            },
            success: function(data) {
                $.each( data.data, function( key, value ) {
                    $('#'+key).html(value)
                });
                $('#preloader').css("display","none")
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
    function reFullAdviser_id() {//دى اللى هتعرض اليوزر بناءا على الحاله
        $.get(
            '{{route("requestChartRApiQuality")}}', {
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
            dt.column( 12 ).visible(true)

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
