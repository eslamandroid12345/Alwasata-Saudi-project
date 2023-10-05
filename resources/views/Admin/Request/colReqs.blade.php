@extends('layouts.content')


@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'All Requests') }}
@endsection

@section('css_style')

    <link rel="stylesheet" href="{{ asset('css/tokenize2.min.css') }}">

    <style>
        #loading {
            position: absolute !important;
            width: 100%;
            height: 100%;
            position: fixed;
            background-color: #003b67f7;
            z-index: 99;
            display: grid;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
        }

        #loading img {
            animation: spin 2.5s infinite;
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
    @if ( session()->has('exeal_error') )
        <div class="alert alert-danger">
            <h1>الارقام التالية مسجله لدينا </h1>
            @foreach (session()->get('exeal_error') as $error)
                <ul>
                    <li>{{ $error }}</li>
                </ul>
            @endforeach
        </div>
    @endif
    @if ( session()->has('exeal_Count') )
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('exeal_Count') }} تم اضافة
        </div>
    @endif
    @if ( session()->has('er') )
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('er') }} مكررة
        </div>
    @endif

    <div id="msg2" class="alert alert-dismissible" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>

    <div class="addUser my-4">
        <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
            <h3> {{ MyHelpers::admin_trans(auth()->user()->id,'All Requests') }} :</h3>
            {{-- <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="type_of_classification" id="inlineRadio1" value="">
                <label class="form-check-label" for="inlineRadio1">جميع التصنيفات</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="type_of_classification" id="inlineRadio2" value="1">
                <label class="form-check-label" for="inlineRadio2">التصنيفات الايجابية</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="type_of_classification" id="inlineRadio3" value="0">
                <label class="form-check-label" for="inlineRadio3">التصنيفات السلبيه</label>
            </div> --}}
        </div>
    </div>
    <br>
    <div class="messages-box" style="display: none;" id="list-loading">
        <div id="loading"><img src="{{ url('assest/images/loadingLogo.png') }}" alt=""> </div>
    </div>
    <div class="tableBar">
        <div class="topRow">
            <div class="row align-items-center text-center text-md-left">
                <div class="col-lg-8 ">
                    <div class="tableUserOption  flex-wrap ">
                        <div class="input-group col-md-6 mt-lg-0 mt-3">
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
                    @include('Admin.datatable_display_number')
                    <div id="tableAdminOption" class="tableAdminOption">
                        <div id="dt-btns" class="tableAdminOption">
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                <div class="col-lg-10">
                    <div class="tableUserOption   flex-wrap justify-content-md-end">
                        <div class="addBtn  mt-lg-0 mt-3 orderBtns spansBtn">
                            <button class="mr-2 Red" style="cursor: not-allowed" disabled id="archAll" onclick="getReqests1()">
                                <i class="fas fa-trash-alt"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Archive Request') }}
                            </button>

                            <button class="mr-2 Cloud" style="cursor: not-allowed" disabled id="moveAll" onclick="getReqests2()">
                                <i class="fas fa-random"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Move Reqs') }}
                            </button>

                            <button class="mr-2 Pink" style="cursor: not-allowed" disabled id="moveNeesActionAll" onclick="getReqests3()">
                                <i class="fas fa-directions"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Move Reqs To Need Action') }}
                            </button>

                            <button class="mr-2 Red2-light" style="cursor: not-allowed" disabled id="moveQualityAll" onclick="getReqests4()">
                                <i class="fas fa-paper-plane"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Move Reqs To Quality') }}
                            </button>

                            <button class="ml-3 item DarkRed " style="cursor: not-allowed" disabled id="moveToFreezeBtn">
                                <i class="fas fa-paper-plane mr-2"></i>
                                @lang('global.moveToFreeze')
                            </button>

                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="dashTable">
            <table class="table table-bordred table-striped data-table" id="myreqs-table">
                <thead>
                <tr>
                    <th></th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req num') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'req date') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'mobile') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req status') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req classification agent') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'note') }} <br> {{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'notes_status_quality') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'assign req date') }} <br>
                        {{ MyHelpers::admin_trans(auth()->user()->id,'in agent') }}</th>
                    <th> هل تم استلامه من <br> قبل الجودة</th>
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
    @include('Admin.Request.filterReqs')
    @include('Admin.Request.moveReq')
    @include('Admin.Request.moveReq2')
    @include('Admin.Request.confirmArchMsg')
    @include('Admin.Request.confirmSendMsg')
@endsection
@section('scripts')
    <script></script>
    <script>
        function hideLodingDiv(value) {
            $('#list-loading').css('display', value);
        }
    </script>
    <script src="{{ asset('js/tokenize2.min.js') }}"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js"></script>
    <script>
        let datatable
        $(document).ready(function () {
            datatable = $('.data-table').DataTable({
                language: {
                    "url": "{{route('datatableLanguage')}}",
                    buttons: {
                        excelHtml5: "اكسل",
                        print: "طباعة",
                        // pageLength: "عرض",
                    }
                },
                scrollY: '50vh',
                scrollX: true,
                scrollCollapse: true,
                fixedColumns: {
                    leftColumns: 0,
                    rightColumns: 1
                },
                // lengthMenu: [
                //     [10, 50, 100, 150, 200],
                //     [10, 50, 100, 150, 200],
                //     // [10, 50, 500, 1000, 2000]
                // ],
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5',
                    'print',
                    //'pageLength',
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
                    'url': "{{ route('admin.colReqs_datatable',$id) }}",
                    'method': 'Get',
                    'data': function (data) {
                        let agents_ids = $('#agents_ids').data('tokenize2').toArray();
                        let reqTypes = $("#request_type").data('tokenize2').toArray();
                        let customer_salary = $('#customer-salary').val();
                        let customer_salary_to = $('#customer-salary-to').val()
                        let customer_phone = $('#customer-phone').val();
                        let customer_birth = $('#customer-birth').val();
                        let req_date_from = $('#request-date-from').val();
                        let req_date_to = $('#request-date-to').val();
                        let complete_date_from = $('#complete-date-from').val();
                        let updated_at_from = $('#updated_at_from').val();
                        let updated_at_to = $('#updated_at_to').val();
                        let complete_date_to = $('#complete-date-to').val();
                        let req_status = ($("#request_status").data('tokenize2').toArray());
                        let pay_status = ($("#pay_status").data('tokenize2').toArray());
                        let source = $('#source').data('tokenize2').toArray();
                        let collaborator = $('#collaborator').data('tokenize2').toArray();
                        let work_source = $('#work_source').data('tokenize2').toArray();
                        let salary_source = $('#salary_source').data('tokenize2').toArray();
                        let founding_sources = $('#founding_sources').data('tokenize2').toArray();
                        let notes_status = $('#notes_status').data('tokenize2').toArray();
                        let app_downloaded = $('#app_downloaded').data('tokenize2').toArray();
                        let quality_recived = $('#quality_recived').data('tokenize2').toArray();
                        let user_status =  $('#user_status').val();
                        let region_ip = ($("#region_ip").data('tokenize2').toArray());

                        // data.type_of_classification=$('input[name="type_of_classification"]:checked').val()
                        data.type_of_classification=$('#type_of_classification').val()


                        if (user_status != '') {
                            data['user_status'] = user_status;
                        }
                        if (req_date_from != '') {
                            data['req_date_from'] = req_date_from;
                        }
                        if (req_date_to != '') {
                            data['req_date_to'] = req_date_to;
                        }
                        if (complete_date_from != '') {
                            data['complete_date_from'] = complete_date_from;
                        }
                        if (complete_date_to != '') {
                            data['complete_date_to'] = complete_date_to;
                        }
                        if (updated_at_from != '') {
                            data['updated_at_from'] = updated_at_from;
                        }
                        if (updated_at_to != '') {
                            data['updated_at_to'] = updated_at_to;
                        }
                        if (region_ip != '') data['region_ip'] = region_ip;
                        if (customer_birth != '') data['customer_birth'] = customer_birth;
                        if (customer_salary != '') data['customer_salary'] = customer_salary;
                        if (customer_salary_to != '') data['customer_salary_to'] = customer_salary_to;
                        if (customer_phone != '') data['customer_phone'] = customer_phone;
                        if (source != '') data['source'] = source;
                        if (collaborator != '') data['collaborator'] = collaborator;
                        if (work_source != '') data['work_source'] = work_source;
                        if (salary_source != '') data['salary_source'] = salary_source;
                        if (founding_sources != '') data['founding_sources'] = founding_sources;
                        if (app_downloaded != '') data['app_downloaded'] = app_downloaded;
                        if (agents_ids != '') data['agents_ids'] = agents_ids;
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
                        if (pay_status != '') data['pay_status'] = pay_status;
                        if (reqTypes != '') data['reqTypes'] = reqTypes;
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
                        if (quality_recived != '') {
                            var yesValue = false;
                            var noValue = false;
                            yesValue = quality_recived.includes("yes");
                            noValue = quality_recived.includes("no");
                            if (yesValue && noValue) // choose all optiones
                                quality_recived = 0;
                            else if (yesValue && !noValue) // choose contain only
                                quality_recived = 1;
                            else if (!yesValue && noValue) // choose empty only
                                quality_recived = 2;
                            else
                                quality_recived = null;
                            data['quality_recived'] = quality_recived;
                        }
                        //console.log(customer_phone);
                        xses.forEach(function (item) {
                            if (getClassifcationX(item) != '') {
                                data['class_id_' + item] = getClassifcationX(item)
                            }
                        })
                    },
                }),
                columns: [
                    {
                        "targets": 0,
                        "data": "id",
                        "render": function (data, type, row, meta) {
                            return `<input class="checkbox-item" type="checkbox" id="chbx-${data}" name="chbx[]" onchange="disabledButton()" value="` + data + '"/>';
                        }
                    },
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
                    {
                        data: 'type',
                        name: 'requests.type'
                    },
                    {
                        data: 'user_name',
                        name: 'users.name'
                    },
                    {
                        data: 'cust_name',
                        name: 'customers.name'
                    },
                    {
                        data: 'mobile',
                        name: 'customers.mobile'
                    },
                    {
                        data: 'statusReq',
                        name: 'statusReq'
                    },
                    {
                        data: 'source',
                        name: 'source'
                    },
                    {
                        data: 'class_id_agent',
                        name: 'class_id_agent'
                    },
                    {
                        data: 'comment',
                        name: 'comment'
                    },
                    {
                        data: 'quacomment',
                        name: 'quacomment'
                    },
                    {
                        "targets": 0,
                        "data": "agent_date", //because created_at is updated for each move
                        "render": function (data, type, row, meta) {
                            return data.split(" ").join("<br/>");
                        }
                    },
                    {
                        data: 'is_quality_recived',
                        name: 'is_quality_recived'
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

                    $('input:radio').on('click', function(e) {
                        datatable.draw();
                    });
                //====================draw table when change in display number=====================
                $('#display_number').focusout(function(){
                    datatable.page.len( $(this).val()).draw();
                });
                //==================================================================================

                    $("#user_status").on('change', function (e) {
                        e.preventDefault();
                        api.draw();
                    });
                    $(".paginate_button").addClass("pagination-circle");
                    /* To Adaptive with New Design */
                    $('#example-search-input').change(_.debounce(function () {
                        datatable.search($(this).val()).draw();
                    }, 500))
                    datatable.buttons().container().appendTo('#dt-btns');
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
        $('#user_status').change(function () {
            reFullAdviser_id()
        })
        function reFullAdviser_id() {
            $.get(
                '{{route('getUsersByStatus')}}', {
                    status_user:$('#user_status').val()
                },
                function(response) {
                    console.log(response)
                    response.users.forEach(($user, $index) => {
                        $data += '<option value="' + $user.id + '"' +
                            '>' + $user.name + '</option>';

                    });

                    $('#frm-update #agents_ids').html($data);
                });
        }
        $(document).on('click', '#moveToFreezeBtn', function () {
            confirmMessage().then(v => {
                if(v=== !0){
                    const ids = $('.checkbox-item:checked').map((i, a) => a.value).toArray()
                    // console.log(ids)
                    $.post('{{route('admin.Request.moveRequestToFreeze')}}', {ids})
                        .done((r) => {
                            if (r?.success === !0) {
                                alertSuccess(r.message)
                                datatable.draw();
                            }
                        })
                        .fail((e) => {
                            alertError(e?.message || '@lang('messages.fail')')
                        })
                }
            })
            // console.log(ids)
        })


        function getReqests1() {
            var array = []
            var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].value != "on" && checkboxes[i].value != "off") {
                    var val = parseInt(checkboxes[i].value);
                    array.push(val);
                }
            }
            archiveAllReqs(array);
        }

        function getReqests2() {
            document.getElementById("salesagent2").value = '';
            document.getElementById('salesagentsError2').innerHTML = '';
            $('#mi-modal8').modal('show');
        }

        function getReqests3() {
            var array = []
            var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].value != "on" && checkboxes[i].value != "off") {
                    var val = parseInt(checkboxes[i].value);
                    array.push(val);
                }
            }
            addReqToNeedActionReqFromAdminArray(array);
        }

        function getReqests4() {
            // document.getElementById("qulityManagers").value = '';
            document.getElementById('qualityErrors').innerHTML = '';
            addReqToQualityAll();
        }

        $(document).on('click', '#move', function (e) {
            document.getElementById("salesagent").value = '';
            document.getElementById('salesagentsError').innerHTML = '';
            var id = $(this).attr('data-id');
            $('#frm-update1').find('#id1').val(id);
            document.getElementById("movedReqID").value = id;
            $('#mi-modal7').modal('show');
        });
        $(document).on('click', '#submitMove', function (e) {

            var salesAgent = document.getElementById("salesagent").value;
            var id = document.getElementById("movedReqID").value;


            var url = "{{ route('admin.moveReqToAnother')}}";


            if (salesAgent != '') {


                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        salesAgent: salesAgent,
                        id: id
                    },
                    cache: false,
                    beforeSend: function () {
                        $('#submitMove').attr("disabled", true);
                        $("#submitMove").html("<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Please Wait') }}");
                    },
                    success: function (data) {
                        if (data.updatereq == 1) {

                            $('#myreqs-table').DataTable().ajax.reload();

                            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

                        }


                        $('#mi-modal7').modal('hide');
                        $("#submitMove").html("تحويل");
                        $('#submitMove').attr("disabled", false);


                    },
                    error: function (jqXHR) {
                        if (jqXHR.status && (jqXHR.status == 400 || jqXHR.status == 500)) {
                            var result = JSON.parse(jqXHR.responseText);

                        } else {
                            $("#submitMove").html("تحويل");
                            $('#submitMove').attr("disabled", false);
                        }
                    },

                });


            } else
                document.getElementById('salesagentsError').innerHTML = 'الرجاء اختيار استشاري';
            $("#submitMove").html("تحويل");
            $('#submitMove').attr("disabled", false);


        });
        $(document).on('click', '#submitMove2', function (e) {


            var array = []
            var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].value != "on" && checkboxes[i].value != "off") {
                    var val = parseInt(checkboxes[i].value);
                    array.push(val);
                }
            }


            var salesAgent = document.getElementById("salesagent2").value;
            var id = array;


            if (salesAgent != '') {
                var url = "{{ route('admin.moveReqToAnotherArray')}}";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        salesAgent: salesAgent,
                        id: id
                    },
                    cache: false,
                    beforeSend: function () {
                        $('#submitMove2').attr("disabled", true);
                        $("#submitMove2").html("<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Please Wait') }}");

                    },
                    success: function (data) {
                        $("#submitMove2").html("تحويل");
                        $('#submitMove2').attr("disabled", false);

                        if (data.status != 0) {
                            $('#myreqs-table').DataTable().ajax.reload();

                            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> تم نقل " + data.counter + " بنجاح ");
                        } else
                            $('#msg2').addClass("alert-warning").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);


                        $('#mi-modal8').modal('hide');
                        $('#allreq').prop('checked', false);
                        $("#moveAll, #archAll").attr('disabled', 'disabled');

                    },
                    error: function (jqXHR) {
                        if (jqXHR.status && (jqXHR.status == 400 || jqXHR.status == 500)) {
                            var result = JSON.parse(jqXHR.responseText);

                        } else {
                            $("#submitMove2").html("تحويل");
                            $('#submitMove2').attr("disabled", false);
                        }
                    },

                });

            } else {
                document.getElementById('salesagentsError2').innerHTML = 'الرجاء اختيار استشاري';
            }
            $("#submitMove2").html("تحويل");
            $('#submitMove2').attr("disabled", false);


        });
        // $('#qulityManagers').tokenize2().trigger('tokenize:clear');
        function archiveAllReqs(array) {
            var modalConfirm = function (callback) {
                $("#mi-modal3").modal('show');
                $("#modal-btn-si3").on("click", function () {
                    callback(true);
                    $("#mi-modal3").modal('hide');
                });
                $("#modal-btn-no3").on("click", function () {
                    callback(false);
                    $("#mi-modal3").modal('hide');
                });
            };
            modalConfirm(function (confirm) {
                if (confirm) {
                    $.post("{{ route('admin.archReqArray')}}", {
                        array: array,
                        _token: "{{csrf_token()}}",
                    }, function (data) {
                        var url = '{{ route("admin.myRequests") }}';
                        if (data != 0) {
                            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')}}");
                            window.location.href = url; //using a named route
                        } else
                            $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id, 'Try Again')}}");
                    });
                    $('#allreq').prop('checked', false);
                    $("#moveAll, #archAll").attr('disabled', 'disabled');


                } else {
                    //reject
                }
            });


        };

        function disabledButton() {

            if ($(':checkbox[name="chbx[]"]:checked').length > 0) {
                document.getElementById("archAll").disabled = false;
                document.getElementById("archAll").style = "";

                document.getElementById("moveAll").disabled = false;
                document.getElementById("moveAll").style = "";

                document.getElementById("moveNeesActionAll").disabled = false;
                document.getElementById("moveNeesActionAll").style = "";

                document.getElementById("moveQualityAll").disabled = false;
                document.getElementById("moveQualityAll").style = "";

                const moveToFreezeBtn = document.getElementById("moveToFreezeBtn")
                if (moveToFreezeBtn) {
                    moveToFreezeBtn.disabled = false;
                    moveToFreezeBtn.style = "";
                }
            } else {
                document.getElementById("archAll").disabled = true;
                document.getElementById("archAll").style = "cursor: not-allowed";

                document.getElementById("moveAll").disabled = true;
                document.getElementById("moveAll").style = "cursor: not-allowed";

                document.getElementById("moveNeesActionAll").disabled = true;
                document.getElementById("moveNeesActionAll").style = "cursor: not-allowed";

                document.getElementById("moveQualityAll").disabled = true;
                document.getElementById("moveQualityAll").style = "cursor: not-allowed";

                const moveToFreezeBtn = document.getElementById("moveToFreezeBtn")
                if (moveToFreezeBtn) {
                    moveToFreezeBtn.disabled = true;
                    moveToFreezeBtn.style = "cursor: not-allowed";
                }
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

        var xses = [
            'sa',
            'sm',
            'fm',
            'mm',
            'gm',
            'qu'
        ];

        function getClassifcationX($x) {
            return $("#classifcation_" + $x).data('tokenize2').toArray();
        }

        function getReqTypes() {
            return $("#request_type").data('tokenize2').toArray();
        }

        $(document).on('click', '#syncOtared', function (e) {
            $('#syncOtared').attr("disabled", true);
            $('#syncOtared').removeClass('btn-info');
            $('#syncOtared').addClass('loadingButton');
            document.querySelector('#syncOtared').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Please Wait') }}";
            $.get("{{ route('admin.otaredSync') }}", {}, function (response) {
                document.querySelector('#syncOtared').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'sync otared') }}";
                $('#syncOtared').removeClass('loadingButton');
                $('#syncOtared').addClass('btn-info');
                $('#syncOtared').attr("disabled", false);
                $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> <i class='fa fa-check'></i>" + response.msg + "");
            }).fail(function (jqXHR, textStatus, errorThrown) {
                document.querySelector('#syncOtared').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'sync otared') }}";
                $('#syncOtared').removeClass('loadingButton');
                $('#syncOtared').addClass('btn-info');
                $('#syncOtared').attr("disabled", false);
                $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> لم يتم اكمال العملية بنجاح حاول مرة أخرى");
            });
        });

        var xhrCount = 0;
        $(document).on('click', '#addQuality', function (e) {
            document.getElementById("qualityError").innerHTML = '';
            var quality = '';
            var id = $(this).attr('data-id');
            $('#msg2').removeClass(["alert-success", "alert-danger"]).removeAttr("style").html("");
            var modalConfirm = function (callback) {
                $("#mi-modal5").modal('show');
                $("#modal-btn-si5").on("click", function () {
                    quality = document.getElementById("qulityManager").value;
                    $("#mi-modal5").modal('hide');
                    if (quality != '') {
                        callback(true);
                        $("#mi-modal5").modal('hide');
                    } else
                        document.getElementById("qualityError").innerHTML = 'الحقل مطلوب';
                });
                $("#modal-btn-no5").on("click", function () {
                    callback(false);
                    $("#mi-modal5").modal('hide');
                });
            };
            modalConfirm(function (confirm) {
                if (confirm) {
                    var seqNumber = ++xhrCount;
                    $.get("{{route('admin.addReqToQuality')}}", {
                        id: id,
                        quality: quality,
                    }, function (data) {
                        if (seqNumber === xhrCount) {
                            if (data.status != 0) {
                                $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

                            } else
                                $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                        }
                    });
                } else {
                    //reject
                }
            });
        });
        function addReqToQualityAll() {
            var quality = '';
            var turn = '';
            var modalConfirm = function (callback) {
                $("#mi-modal55").modal('show');
                $("#modal-btn-si55").on("click", function () {
                    quality = $('#qulityManagers').val();
                    if(quality == ""){
                        turn = true
                    }
                    $("#mi-modal55").modal('hide');
                    if (quality != '' || turn == true) {
                        callback(true);
                        $("#mi-modal55").modal('hide');
                    } else
                        document.getElementById("qualityErrors").innerHTML = 'الحقل مطلوب';
                });
                $("#modal-btn-no55").on("click", function () {
                    callback(false);
                    $("#mi-modal55").modal('hide');
                });
            };
            modalConfirm(function (confirm) {
                if (confirm) {
                    var array = []
                    var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
                    for (var i = 0; i < checkboxes.length; i++) {
                        if (checkboxes[i].value != "on" && checkboxes[i].value != "off") {
                            var val = parseInt(checkboxes[i].value);
                            array.push(val);
                        }
                    }
                    var seqNumber = ++xhrCount;
                    hideLodingDiv('block');
                    $.get("{{ route('admin.addReqToQualityArray')}}", {
                        array: array,
                        quality: quality,
                        turn: turn,
                    }, function (data) {
                        hideLodingDiv('none');
                        var url = '{{ route("admin.myRequests") }}';
                        if (seqNumber === xhrCount) {
                            if (data.status != 0) {
                                $('#msg2').addClass("alert-success").removeAttr("style").
                                html("<button type='button' class='close' data-dismiss='alert'>&times;</button> تمت الاضافة بنجاح");
                                // $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>تم إضافة " + data.move_count + " من أصل " + data.request_count);
                                window.location.href = url;
                            }
                        }
                    });
                }
            });
        }

        // Khaled
        $(document).on('change','#sales-managers',function(){
            var sales_manager_id=$(this).val()
            console.log(sales_manager_id)
            $.ajax({
                type:'GET',
                url:"{{ route('admin.salesAgents')}}",
                data:{
                    sales_manager_id:sales_manager_id,
                    _token: '{{csrf_token()}}'
                },
                success:function(response){
                    console.log(response)
                    $('#agents_ids').empty()
                    $.each(response.salesAgents, function (key, value) {
                            $("#agents_ids").append('<option value="' + value
                                .id + '">' + value.name + '</option>');
                    });
                }

            })
        })
    </script>
@endsection
