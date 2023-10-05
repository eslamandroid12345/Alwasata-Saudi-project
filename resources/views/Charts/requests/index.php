@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Charts') }} - {{ MyHelpers::admin_trans(auth()->user()->id,'daily_prefromence') }}
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
</div>
<div class="row" id="preloader">
    <div style="padding: 35px;position: absolute;z-index: 999;width: 70%;margin-right: 1.3%;text-align: center">
        <div class="loader"></div>
        تحميل...
    </div>
</div>
{{-- For Search Parameters   --}}
@include('Charts.dailyPrefromenceChart-parameters')
<div class="tableBar">
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
    {{--<div class="col-12">
        <label class="label">إظهار  </label>
        <div class="rs-select2 js-select-simple select--no-search">
            <select class="form-control" multiple id="show">
                <option value="all">الكل</option>
                <option value="2" selected>المجموع</option>
                <option value="3" selected> طلبات جديدة (تلقائي)</option>
                <option value="4" selected>طلبات مميزة</option>
                <option value="5" selected>طلبات متابعة</option>
                <option value="6" selected>طلبات مؤرشفة</option>
                <option value="7" selected>طلبات مرفوعة</option>
                <option value="8" selected>طلبات مفرغة</option>
                @if (auth()->user()->role != 1)
                    <option value="9" selected>طلبات مُحدث عليها</option>
                @endif
                <option value="10" selected>طلبات تم فتحها</option>
                <option value="11" selected>مهام مستلمة</option>
                <option value="12" selected>مهام تم الرد عليها</option>
                <option value="13" selected>تذكيرات فائتة</option>
                <option value="14" selected>طلبات محولٌة منه</option>
                <option value="15" selected>طلبات محولٌة إليه</option>
            </select>
            <div class="select-dropdown"></div>
        </div>

    </div>--}}
   {{-- <button onclick="myFun(5)">ClickMe</button>--}}
    <div class="dashTable">
        <table id="pendingReqs-table" class="table table-bordred table-striped data-table">
            <thead>
            <tr>
                <th>#</th>
                <th> الاستشاري</th>
                <th>المجموع</th>
                <th> طلبات جديدة (تلقائي)</th>
                <th>طلبات مميزة</th>
                <th>طلبات متابعة</th>
                <th>طلبات مؤرشفة</th>
                <th>طلبات مرفوعة</th>
                <th>طلبات مفرغة</th>
                @if (auth()->user()->role != 1)
                    <th>طلبات مُحدث عليها</th>
                @endif
                <th>طلبات تم فتحها</th>
                <th>مهام مستلمة</th>
                <th>مهام تم الرد عليها</th>
                <th>تذكيرات فائتة</th>
                <th>طلبات محولٌة منه</th>
                <th>طلبات محولٌة إليه</th>
                <th>تحكم</th>
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
                @if (auth()->user()->role != 1)
            {
                data: 'updated_request',
                name: 'updated_request'
            },
                @endif
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
                data: 'missed_reminders',
                name: 'missed_reminders'
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
            $("#filter-search-req").on('click', function (e) {
                $('#preloader').css("display","block")
                e.preventDefault();
                api.draw();
            });
            $("#user_status").on('change', function (e) {
                e.preventDefault();
                api.draw();
            });
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
            dt.column( 16 ).visible(true)
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
