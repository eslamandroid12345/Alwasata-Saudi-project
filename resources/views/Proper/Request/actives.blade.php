@extends('layouts.content')
@section('title')
   الطلبات النشطة
@endsection
@section('css_style')

    <link rel="stylesheet" href="{{ asset('css/tokenize2.min.css') }}">
    <style>
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

        .loadingButton {
            background-color: #0088cc;
            color: azure;
            cursor: not-allowed;
        }

        .reqType {
            width: 2%;
        }

        .commentStyle {
            max-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
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

    {{-- NEW STYLE   --}}
    <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">

@endsection

@section('customer')

    <div class="addUser my-4">
        <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
            <h3>  الطلبات النشطة :</h3>
        </div>
    </div>
    <br>


    <div class="tableBar">

        <div class="topRow">
            <div class="row align-items-center text-center text-md-left">

                <div class="col-lg-8 ">
                    <div class="tableUserOption  flex-wrap ">

                        <div class="input-group col-md-7 mt-lg-0 mt-3">
                            <input class="form-control py-2" type="search" placeholder="ابحث هنا" id="example-search-input">
                            <span class="input-group-append">
                            <button class="btn btn-outline-info" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                        </div>

                    </div>
                </div>

                <div class="col-lg-4 mt-lg-0 mt-4">
                    <div id="tableAdminOption" class="tableAdminOption">
                        <div id="dt-btns" class="tableAdminOption">
                            {{-- Here We Will Add Buttons of Datatable  --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashTable">
            <table class="table table-bordred table-striped data-table" id="myreqs-table">
                <thead>
                <tr>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req num') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'req date') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'mobile') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req status') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req classification agent') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'my_notes') }}</th>
                   {{-- <th>{{ MyHelpers::admin_trans(auth()->user()->id,'wasata_notes') }}</th>--}}
                    <th>@lang('global.updated_at')</th>
                    <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection
@section('updateModel')
    @include('Proper.Request.single.filterReqs')
@endsection
@section('scripts')
    <script src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js"></script>
    <script>
        $('.tokenizeable').tokenize2();
        $(".tokenizeable").on("tokenize:select", function () {
            $(this).trigger('tokenize:search', "");
        });

        function getCustomerIDS() {
            return $("#customer_ids").data('tokenize2').toArray();
        }

        function getUserIDS() {
            return $("#user_ids").data('tokenize2').toArray();
        }

        function getReqTypes() {
            return $("#request_type").data('tokenize2').toArray();
        }

        $(document).ready(function () {
            var dt = $('.data-table').DataTable({
                language: {
                    "url": "{{route('datatableLanguage')}}",
                    buttons: {
                        excelHtml5: "اكسل",
                        print: "طباعة",
                        pageLength: "عرض",
                    }
                },
                scrollY: '50vh',
                scrollX: true,
                scrollCollapse: true,
                fixedColumns: {
                    leftColumns: 0,
                    rightColumns: 1
                },
                lengthMenu: [
                    [10, 50, 500, 1000, 2000],
                    [10, 50, 500, 1000, 2000]
                ],
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5',
                    'print',
                    'pageLength',
                    {
                        text: '{{ MyHelpers::admin_trans(auth()->user()->id,"Search") }}',
                        action: function (e, dt, node, config) {
                            $('#myModal').modal('show');
                        }
                    }
                ],
                processing: true,
                serverSide: true,
                ajax: ({
                    'url': "{{ url('requests/collaborator/datatable/active') }}",
                    'method': 'Get',
                    'data': function (data) {
                        let agents_ids = $('#agents_ids').data('tokenize2').toArray();
                        let classifcation_sa = $('#classifcation_sa').data('tokenize2').toArray();
                        let customer_phone = $('#customer-phone').val();
                        let req_date_from = $('#request-date-from').val();
                        let req_date_to = $('#request-date-to').val();
                        let req_status = ($("#request_status").data('tokenize2').toArray());
                        let notes_status = $('#notes_status').data('tokenize2').toArray();
                        if (req_date_from != '') {
                            data['req_date_from'] = req_date_from;
                        }
                        if (req_date_to != '') {
                            data['req_date_to'] = req_date_to;
                        }
                        if (customer_phone != '') data['customer_phone'] = customer_phone;
                        if (agents_ids != '') data['agents_ids'] = agents_ids;
                        if (classifcation_sa != '') data['classifcation_sa'] = classifcation_sa;
                        if (req_status != '') {
                            var contain = false;
                            contain = req_status.includes("3"); // because wating for sales manager is equal to 5 (archived in sales maanager)
                            if (contain)
                                req_status.push("5", "18"); //status of arachived request in sales manager,wating sales manager req mor-pur req
                            contain = false;
                            contain = req_status.includes("4"); // rejected sales manager req
                            if (contain)
                                req_status.push("20"); //status of rejected sales manager req mor-pur req
                            contain = false;
                            contain = req_status.includes("7"); // rejected funding manager req
                            if (contain)
                                req_status.push("22"); //status of rejected funding manager req mor-pur req
                            contain = false;
                            contain = req_status.includes("6"); // wating funding manager req
                            if (contain)
                                req_status.push("8", "21"); //archive in funding manager req,wating funding manager req mor-pur req
                            contain = false;
                            contain = req_status.includes("9"); // wating mortgage manager req
                            if (contain)
                                req_status.push("11"); //archive in mortgage manager req
                            contain = false;
                            contain = req_status.includes("13"); // rejected general manager req
                            if (contain)
                                req_status.push("25"); //status of rejected general manager req mor-pur req
                            contain = false;
                            contain = req_status.includes("12"); // wating general manager req
                            if (contain)
                                req_status.push("14", "23"); //archive in general manager req,,wating general manager req mor-pur req
                            contain = false;
                            contain = req_status.includes("16"); // completed
                            if (contain)
                                req_status.push("26"); //completed mor-pur req
                            contain = false;
                            contain = req_status.includes("15"); // Canceled
                            if (contain)
                                req_status.push("27"); //Canceled mor-pur req
                            contain = false;
                            contain = req_status.includes("29"); // Rejected and archived
                            if (contain) {
                                data['checkExisted'] = "29";
                                req_status.push("2"); //archived in sales agent
                                req_status.splice(req_status.indexOf('29'), 1);
                            } else
                                data['checkExisted'] = null;
                            data['req_status'] = req_status;
                        }
                        if (notes_status != '') {
                            var contain = false;
                            var empty = false;
                            contain = notes_status.includes("1"); // returns true
                            empty = notes_status.includes("0"); // returns true
                            if (contain && empty) // choose all optiones
                                notes_status = 0;
                            else if (contain && !empty) // choose contain only
                                notes_status = 1;
                            else if (!contain && empty) // choose empty only
                                notes_status = 2;
                            else
                                notes_status = null;
                            data['notes_status'] = notes_status;
                        }
                    },
                }),
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        "targets": 0,
                        "data": "created_at", // first history related to the request
                        "name": "requests.created_at",
                        "render": function (data, type, row, meta) {
                            return data.split(" ").join("<br/>");
                        }
                    },
                    @if(auth()->user()->code != null)
                    {
                        data: 'user_name',
                        name: 'user_name'
                    },
                    @endif
                    {
                        data: 'cust_name',
                        name: 'cust_name'
                    },
                    {
                        data: 'mobile',
                        name: 'mobile'
                    },
                    {
                        data: 'statusReq',
                        name: 'statusReq'
                    },
                    {
                        data: 'class_id_agent',
                        name: 'class_id_agent'
                    },
                    {
                        data: 'private_notes',
                        name: 'private_notes'
                    },
                    {
                        data: 'collaborator_notes',
                        name: 'collaborator_notes'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    {
                        data: 'action',
                        name: 'action'
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
                    $('#example-search-input').change(_.debounce(function () {
                        dt.search($(this).val()).draw();
                    }, 500))
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
                },
                createdRow: function (row, data, index) {
                    $('td', row).eq(10).addClass('commentStyle'); // 6 is index of column
                    $('td', row).eq(10).attr('title', data.comment); // to show other text of comment
                    $('td', row).eq(11).addClass('commentStyle'); // 6 is index of column
                    $('td', row).eq(11).attr('title', data.quacomment);
                    $('td', row).eq(5).addClass('commentStyle'); // 6 is index of column
                    $('td', row).eq(5).attr('title', data.cust_name); // to show other text of comment
                    $('td', row).eq(0).addClass('reqNum'); // 6 is index of column
                    $('td', row).eq(1).addClass('reqNum'); // 6 is index of column
                    $('td', row).eq(2).addClass('reqDate'); // 6 is index of column
                    $('td', row).eq(12).addClass('reqDate'); // 6 is index of column
                    $('td', row).eq(3).addClass('reqType'); // 6 is index of column
                    $('td', row).eq(7).addClass('reqType'); // 6 is index of column
                },
                order: [[2, 'desc']]
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
    </script>
@endsection
