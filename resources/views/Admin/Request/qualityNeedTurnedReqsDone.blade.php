@extends('layouts.content')


@section('title')
    بحاجة للتحويل - جودة (تمت المعالجة)
@endsection

@section('css_style')

<script src="{{ asset('backend/sweetalert/sweetalert2.min.js') }}"></script>
<link href="{{ asset('backend/sweetalert/sweetalert2.min.css') }}" rel="stylesheet">


<!-- Vendor CSS-->
<link href="{{ url('interface_style/search/vendor/select2/select2.min.css') }}" rel="stylesheet" media="all">
<link href="{{ url('interface_style/search/vendor/datepicker/daterangepicker.css') }}" rel="stylesheet" media="all">

    <style>

        #multiple-sales-managers{
            width: 320px;
        }

        .select2-container--default .select2-selection--multiple{
            position: relative !important;
            min-height: 55px !important;
            max-height: 150px !important;
            height: auto !important;
            padding: 0 0 5px 5px !important;
            margin-top: 3px;
            overflow-y: auto;
        }

        .middle-screen {
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }

        table {
            width: 100%;
            text-align: center;
        }

        td {
            width: 15%;
        }

        .reqNum {
            width: 0.5%;
        }

        .reqDate {
            text-align: center;
        }

        .reqType {
            width: 2%;
        }

        tr:hover td {
            background: #d1e0e0
        }

        .newReq {
            background: rgba(98, 255, 0, 0.4) ! important;
        }

        .needFollow {
            background: rgba(12, 211, 255, 0.3) ! important;
        }

        .noNeed {
            background: rgba(0, 0, 0, 0.2) ! important;
        }

        .wating {
            background: rgba(255, 255, 0, 0.2) ! important;
        }

        .watingReal {
            background: rgba(0, 255, 42, 0.2) ! important;
        }

        .rejected {
            background: rgba(255, 12, 0, 0.2) ! important;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">
@endsection

@section('customer')



    @if(!empty($message))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ $message }}
        </div>
    @endif

    @if ( session()->has('message') )
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('message') }}
        </div>
    @endif

    @if ( session()->has('message2') )
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('message2') }}
        </div>
    @endif

    @if($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <ul>
                    <li>{{ $error }}</li>
                </ul>

            </div>
        @endforeach
    @endif

    @if(!empty($message))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ $message }}
        </div>
    @endif

    @if ( session()->has('message') )
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('message') }}
        </div>
    @endif



    <div id="msg2" class="alert alert-dismissible" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>

    </div>

    <div class="addUser my-4">
        <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
            <h3>    بحاجة للتحويل - جودة (تمت المعالجة):</h3>

        </div>
    </div>

    <br>



    @if ($requests>0)
        <div class="tableBar">
            <div class="topRow">
                <div class="row align-items-center text-center text-md-left">
                    <div class="col-lg-2">
                        <div class="selectAll">
                            <div class="form-check">
                                <input type="checkbox" id="allreq" class="form-check-input" onclick="chbx_toggle1(this);"/>
                                <label class="form-check-label" for="allreq">تحديد الكل </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 ">

                        <div class="tableUserOption flex-wrap justify-content-between">


                            <div class="input-group col-12 mt-3">
                                <input class="form-control py-2" type="search" placeholder="ابحث هنا" id="example-search-input">
                                <span class="input-group-append">
                                    <button class="btn btn-outline-info" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>

                        </div>


                    </div>

                    
                    <div class="col-lg-3 mt-lg-0 mt-3">
                        <div id="tableAdminOption" class="tableAdminOption">
                            <div id="dt-btns" class="tableAdminOption">
                                {{-- Here We Will Add Buttons of Datatable  --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dashTable">
                <table id="pendingReqs-table" class="table table-bordred table-striped data-table">
                    <thead>
                    <tr>
                        <th></th>
                        <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'req date') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req status') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Quality User') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'mobile') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req classification agent') }}</th>
                        <th> {{ MyHelpers::admin_trans(auth()->user()->id,'comment') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'quality comment') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'reject reason') }}</th>
                        <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>

                    </tr>
                    </thead>
                    <tbody>

                    </tbody>

                </table>
            </div>
        </div>
    @else
        <div class="middle-screen">
            <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No New Requests') }}</h2>
        </div>
        @endif

        </div>





@stop

@section('updateModel')
    @include('Admin.Request.filterReqs-NeedTurnedReq')
    @include('Admin.Request.moveReq')
    @include('Admin.Request.moveReq3-multi')
    @include('Admin.Request.confirmArchMsg')
@endsection

@section('scripts')

<!-- Vendor JS-->
<script src="{{ url('interface_style/search/vendor/select2/select2.min.js') }}"></script>



    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script> --}}


    <script>

        function disabledButton() {
            if ($(':checkbox[name="chbx[]"]:checked').length > 0) {
                document.getElementById("moveAll").disabled = false;
                document.getElementById("moveAll").style = "";
            } else {
                document.getElementById("moveAll").disabled = true;
                document.getElementById("moveAll").style = "cursor: not-allowed";
            }
        }

        function chbx_toggle1(source) {
            var checkboxes = document.querySelectorAll('input[type="checkbox"]');
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i] != source)
                    checkboxes[i].checked = source.checked;
            }

            disabledButton();
        }

        function getReqests1() {
            document.getElementById("salesagent3").value = '';
            document.getElementById('salesagentsError3').innerHTML = '';
            $('#mi-modal9').modal('show');
        }




    </script>



    <script src="{{ asset('js/tokenize2.min.js') }}"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js"></script>


    <script>
        // $('.khaled').tokenize2()
        $('.tokenizeable').tokenize2();
        $(".tokenizeable").on("tokenize:select", function () {
            $(this).trigger('tokenize:search', "");
        });


        var xses = [
            'sa'
        ];

        function getClassifcationX($x) {
            return $("#classifcation_" + $x).data('tokenize2').toArray();
        }

        function getReqTypes() {
            return $("#request_type").data('tokenize2').toArray();
        }

        $(document).ready(function () {
            var dt = $('.data-table').DataTable({
                "language": {
                    "url": "{{route('datatableLanguage')}}",
                    buttons: {
                        excelHtml5: "اكسل",
                        print: "طباعة",
                        pageLength: "عرض",

                    }
                },
                "lengthMenu": [
                    [10, 25, 50],
                    [10, 25, 50]
                ],
                dom: 'Bfrtip',
                buttons: [
                    // 'copyHtml5',
                    'excelHtml5',
                    // 'csvHtml5',
                    // 'pdfHtml5' ,
                    'print',
                    'pageLength',
                    {
                        text: '{{ MyHelpers::admin_trans(auth()->user()->id,"Search") }}',
                        action: function (e, dt, node, config) {
                            $('#myModal').modal('show');
                        }
                    }
                ],
                scrollY: true,
                scrollX: true,
                scrollCollapse: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': "{{ url('admin/needToBeTurnedReqDone-datatable') }}",
                    'method': 'Get',
                    'data': function (data) {
                        let agents_ids = $('#agents_ids').data('tokenize2').toArray();
                        let qualityUser = $('#qualityUser').data('tokenize2').toArray();

                        xses.forEach(function (item) {
                            if (getClassifcationX(item) != '') {
                                data['class_id_' + item] = getClassifcationX(item)
                            }
                        });


                        if (agents_ids != '') data['agents_ids'] = agents_ids;
                        if (qualityUser != '') data['qualityUser'] = agents_ids;
                    },
                },
                columns: [
                    {
                        "targets": 0,
                        "data": "turned_id",
                        "render": function (data, type, row, meta) {
                            return '<input data-need-id="' + row.turned_id + '" type="checkbox" id="chbx" name="chbx[]" onchange="disabledButton()" value="' + data + '"/>';
                        },
                        searchable: !1,
                        sortable: !1,
                    },
                    {
                        "targets": 0,
                        "data": "created_at",
                        "render": function (data, type, row, meta) {
                            return data.split(" ").join("<br/>");
                        }
                    },
                    {
                        data: 'statusReq',
                        name: 'statusReq'
                    },
                    {
                        data: 'quality',
                        name: 'others.name'
                    },
                    {
                        data: 'agentName',
                        name: 'users.name'
                    },
                    {
                        data: 'customer_name',
                        name: 'customers.name'
                    },
                    {
                        data: 'mobile',
                        name: 'customers.mobile'
                    },
                    {
                        data: 'class_id_agent',
                        name: 'class_id_agent',
                        searchable: !1,
                        sortable: !1,
                    },
                    {
                        data: 'comment',
                        name: 'comment',
                        searchable: !1,
                        sortable: !1,
                    },
                    {
                        data: 'quacomment',
                        name: 'quacomment',
                        searchable: !1,
                        sortable: !1,
                    },
                    {
                        data: 'reject_reason',
                        name: 'quality_request_need_turneds.reject_reason'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        searchable: !1,
                        sortable: !1,
                    }

                ],
                initComplete: function () {
                    let api = this.api();
                    $("#filter-search-req").on('click', function (e) {
                        e.preventDefault();
                        api.draw();
                        //checktable(api);
                        $('#myModal').modal('hide');
                    });

                    $(".paginate_button").addClass("pagination-circle");
                    /* To Adaptive with New Design */
                    // $('#example-search-input').keyup(function () {
                    //     dt.search($(this).val()).draw();
                    // })
                    /* To Adaptive with New Design */
                    $('#example-search-input').keyup(_.debounce(function () {
                        dt.search($(this).val()).draw();
                    }, 500))


                    dt.buttons().container().appendTo('#dt-btns');

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
                createdRow: function (row, data, index) {



                    $('td', row).eq(5).addClass('commentStyle');
                    $('td', row).eq(5).attr('title', data.customer_name);

                    $('td', row).eq(8).addClass('commentStyle');
                    $('td', row).eq(8).attr('title', data.comment);

                    $('td', row).eq(9).addClass('commentStyle');
                    $('td', row).eq(9).attr('title', data.quacomment);

                },
            });
        });


        $(function () {
            $('#source').on('tokenize:tokens:add', function (e, value, text) {


                if (value == 2) {


                    document.getElementById("collaboratorDiv").style.display = "block";


                }
            });

            $('#source').on('tokenize:tokens:remove', function (e, value) {

                if (value == 2) {


                    document.getElementById("collaboratorDiv").style.display = "none";
                    document.getElementById("collaborator").value = "";

                }
            });

        });


        $(function () {
            $('#request_type').on('tokenize:tokens:add', function (e, value, text) {


                if (value == "شراء-دفعة") {


                    document.getElementById("paystatusDiv").style.display = "block";


                }
            });

            $('#request_type').on('tokenize:tokens:remove', function (e, value) {

                if (value == "شراء-دفعة") {


                    document.getElementById("paystatusDiv").style.display = "none";
                    document.getElementById("pay_status").value = "";

                }
            });

        });

        // Khaled

        $('#multiple-sales-managers').on('change.select2',function(){
            $('#agents_ids').tokenize2().trigger('tokenize:clear')
            var multiple_sales_manager_ids=$(this).val()
            console.log(multiple_sales_manager_ids)
            $.ajax({
                type:'GET',
                url:"{{ route('admin.multipleSalesAgents')}}",
                data:{
                    multiple_sales_manager_ids:multiple_sales_manager_ids,
                    _token: '{{csrf_token()}}'
                },
                success:function(response){
                    console.log(response)
                    $('#agents_ids').empty()
                    $.each(response.salesAgents, function (key, value) {
                            $("#agents_ids").append('<option  value="' + value
                                .id + '">' + value.name + '</option>');
                    });
                    $('#agents_ids option').each(function(){
                        $(this).attr("selected",true);
                    });
                    $('#agents_ids').tokenize2().trigger('tokenize:remap')
                }
            })
        })

        $(document).on('change','#allow-recived-sales-managers',function(){
            var active_sales_managers
            if(this.checked)
            {
                active_sales_managers = 0
            }else{
                active_sales_managers = 1
            }
            console.log(active_sales_managers)
            $.ajax({
                type:'GET',
                url:"{{ route('admin.allowRecievedSalesManagers')}}",
                data:{
                    active_sales_managers:active_sales_managers,
                    _token: '{{csrf_token()}}'
                },
                success:function(response){
                    console.log(response)
                    $('#multiple-sales-managers').empty()
                    // $("#multiple-sales-managers").append('<option>' + '--' + '</option>');
                    $.each(response.activeSalesManagers, function (key, value) {
                            $("#multiple-sales-managers").append('<option value="' + value
                                .id + '">' + value.name + '</option>');
                    });
                }

            })
        })

    </script>

<script src="{{ url('interface_style/search/vendor/select2/select2.min.js') }}"></script>
<script src="{{ url('interface_style/search/vendor/jquery-validate/jquery.validate.min.js') }}"></script>
{{-- <script src="{{ url('interface_style/search/vendor/bootstrap-wizard/bootstrap.min.js') }}"></script> --}}
<script src="{{ url('interface_style/search/vendor/bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}"></script>
<script src="{{ url('interface_style/search/vendor/datepicker/moment.min.js') }}"></script>
<script src="{{ url('interface_style/search/vendor/datepicker/daterangepicker.js') }}"></script>

<!-- Main JS-->
<script src="{{ url('interface_style/search/js/global.js') }}"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>

@endsection
