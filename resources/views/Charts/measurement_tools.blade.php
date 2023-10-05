@extends('layouts.content')

@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'Charts') }}
    -
    ادوات القياس
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
</style>
{{--    NEW STYLE   --}}
<link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">


@endsection

@section('customer')
<!-- MAIN CONTENT-->
<div class="addUser my-4">
    <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
        <h3>ادوات القياس:</h3>
    </div>
</div>
<div class="row" id="preloader">
    <div style="padding: 35px;position: absolute;z-index: 999;width: 70%;margin-right: 1.3%;text-align: center">
        <div class="loader"></div>
        تحميل...
    </div>
</div>
{{-- For Search Parameters   --}}
<div class="tableBar">

   {{-- <button onclick="myFun(5)">ClickMe</button>--}}
    <div class="dashTable row" style="display: none">

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
            <div class="col-lg-12 mt-lg-0 mt-3">
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
                <th>إسم العميل</th>
                <th>التصنيف</th>
                <th>الرد</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
   {{-- @include('Charts.dailyPrefromenceChart-chart')--}}
</div>
@endsection

@section('scripts')
<script src="{{ asset('backend/sweetalert/sweetalert2.min.js') }}"></script>
<link href="{{ asset('backend/sweetalert/sweetalert2.min.css') }}" rel="stylesheet">
<script>
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
        order: [],
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
           'url': "{{ route('measurement_tools.datatable')  }}",
           'method': 'GET',
           'data':function (data) {
               /*let status_user =  $('#status_user').val();
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
               }*/
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
                data: 'customer',
                name: 'customer'
            },
            {
                data: 'classification',
                name: 'classification'
            },
            {
                data: 'reply',
                name: 'reply'
            }/*,
            {
                data: 'action',
                name: 'action',
                orderable: !1,
                searchable: !1,
                sortable: !1,
            }*/
        ],
        "drawCallback": function( settings ) {
            $('#preloader').css("display","none")
        },
        initComplete: function() {

            let api = this.api();
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
            '{{route('requestChartRApi')}}', {
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


    function myFunction(item,index) {
        dt.column( item ).visible(true)
    }
    reFullAdviser_id();

    $('#manager_id').on('change', function() {
        reFullAdviser_id();
    });

</script>

@endsection
