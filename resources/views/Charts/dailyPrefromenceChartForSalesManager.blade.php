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

        .colVis .select2-container--default .select2-selection--multiple{
            min-height: 64px !important;
        }
        .colVis .select2-container--default.select2-container--focus .select2-selection--multiple {
            border: solid black 1px;
            min-height: 64px !important;
        }
        input[type="radio"], input[type="checkbox"] {
            box-sizing: border-box;
            padding: 0;
            display: none;

        }
        .btn-default{
        background: #012248;
        color: #eee;
        opacity: .5;
    }
    .btn-default:hover{
        color: #eee;
    }
        .button-checkbox button{
            clip-path: polygon(100% 100%, 100% 0%, 10% 0%, 0% 55%, 12.1% 100%);
            padding-left: 15px !important;
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
    </div>
    <div class="row" id="preloader">
        <div style="padding: 35px;position: absolute;z-index: 999;width: 70%;margin-right: 1.3%;text-align: center">
            <div class="loader"></div>
            تحميل...
        </div>
    </div>
    {{-- For Search Parameters   --}}
    {{-- For Search style   --}}
    <div class="topRow" >
        <form method="POST" id="frm-update">
            @csrf
            <div class="row align-items-center text-center text-md-left">
                <div class="col-4">
                    <input type="hidden" name="manager_id" id="manager_id" value="{{auth()->user()->id}}">
                    <label class="label">{{ MyHelpers::admin_trans(auth()->user()->id,'From Date') }}</label>
                    <input class="form-control" type="date" name="startdate" id="startdate" value="{{date('Y-m-d') }}">

                </div>
                <div class="col-4">

                    <label class="label">{{ MyHelpers::admin_trans(auth()->user()->id,'To Date') }}</label>
                    <input class="form-control" type="date" name="enddate" id="enddate" value="{{date('Y-m-d') }}">

                </div>
                <div class="col-4">
                    <label class="label"> الحالة  </label>
                    <select class="form-control" name="status_user" id="status_user" style="height: 38px">
                        <option value="1">إستشاري نشط</option>
                        <option value="0">إستشاري مؤرشف</option>
                        <option value="2">الكل</option>

                    </select>
                </div>

                <div class="col-12">
                    <label class="label">إسم المستشار </label>
                    <div class="rs-select2 js-select-simple select--no-search">
                        <select class="form-control" name="adviser_id[]" multiple id="adviser_id">
                            <option value="0">الكل</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
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
        <div class="dashTable">
            <table id="pendingReqs-table" class="table table-bordred table-striped data-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th> الاستشاري</th>
                    <th>المستلمة</th>
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
                'url': "{{ route('daily.report')  }}?sales_id={{auth()->id()}}",
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
                },/*
                {
                    data: 'received_basket',
                    name: 'received_basket'
                },*/
                {
                   /* data: 'star_basket',
                    name: 'star_basket'*/
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
                $("#filter-search-req").on('click', function (e) {
                    $('#preloader').css("display","block")
                    e.preventDefault();
                    api.draw();
                });
                $("#status_user").on('change', function (e) {
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
        //console.log(adviser_ids);
        $('#status_user').change(function () {
            reFullAdviser_id()
        })
        function reFullAdviser_id() {
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
        var show = $('#show');
        function myFunction(item,index) {
            dt.column( item ).visible(true)
        }
        show.on('change', function() {
            dt.columns().visible(true)
            var isAll = jQuery.inArray( "all", $(this).val() )

            if(isAll != -1){
                dt.columns().visible(true)
            }else{

                dt.columns().visible(false)
                dt.column( 0 ).visible(true)
                dt.column( 1 ).visible(true)
                dt.column( 15 ).visible(true)
                $(this).val().forEach(myFunction);
            }
        });


        reFullAdviser_id();

        $('#manager_id').on('change', function() {
            reFullAdviser_id();
        });

    </script>

@endsection
