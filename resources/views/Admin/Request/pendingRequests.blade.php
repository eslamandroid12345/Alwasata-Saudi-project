@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'PendingRequests') }}
@endsection


@section('css_style')
<link rel="stylesheet" href="{{ asset('css/tokenize2.min.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ url("/") }}/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />

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
    }

    td {
        width: 15%;
    }

    .reqNum {
        width: 1%;
    }

    .data-table-coulmn {
        text-align: center;
    }

    .reqType {
        width: 2%;
    }

    .reqDate {
        text-align: center;
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




<div id="msg2" class="alert alert-dismissible" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>


<div class="addUser my-4">
    <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
        <h3> {{ MyHelpers::admin_trans(auth()->user()->id,'PendingRequests') }}:</h3>

    </div>
</div>
<br>

@if (count($pending_requests) > 0)

<div class="tableBar">
    <div class="topRow">
        <div class="row align-items-center text-center text-md-left">
            <div class="col-lg-2">
                <div class="selectAll">
                    <div class="form-check">
                        <input type="checkbox" id="allreq" class="form-check-input" onclick="chbx_toggle1(this);" />
                        <label class="form-check-label" for="allreq">تحديد الكل </label>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 ">
                <div class="tableUserOption  flex-wrap ">
                    <div class="addBtn col-md-5 mt-lg-0 mt-3">
                        <button disabled class="mov" style="cursor: not-allowed" id="moveAll" onclick="getReqests1()">
                            <i class="fas fa-random"></i>
                            تحويل الطلبات
                        </button>
                    </div>
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
            <div class="col-lg-3 mt-lg-0 mt-3">
                @include('Admin.datatable_display_number')
                <div id="dt-btns" class="tableAdminOption">

                </div>
            </div>
        </div>
    </div>

    <div class="dashTable">
        <table id="pendingReqs-table" class="table table-bordred table-striped data-table">
            <thead>
                <tr>

                    <th> </th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'req date') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'mobile_number') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'is supported') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'has_property') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'has_joint') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'has obligations') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'has financial distress') }}</th>
                    <th>هل يمتلك عقار</th>
                    {{-- <th>{{ MyHelpers::admin_trans(auth()->user()->id,'birth_date') }}</th>--}}
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'birth_date') .' '.
                            MyHelpers::admin_trans(auth()->user()->id,'hijri') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</th>
                    <th>@lang('global.updated_at')</th>
                    {{-- <th>{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</th> --}}
                    {{-- <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th> --}}
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


@endsection


@section('updateModel')
@include('Admin.Request.confirmPendingReqs')
@include('Admin.Request.filterReqs')
@include('Admin.Request.moveReq')
@include('Admin.Request.moveReq2')
@include('Admin.Request.moveReq3-multi')
@include('Admin.Request.ConditionReqs')
@endsection

@section('scripts')


<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="{{ asset('js/tokenize2.min.js') }}"></script>
<script src="{{ url('/') }}/js/bootstrap-hijri-datetimepicker.min.js"></script>
<script src="{{ url('/') }}/js/notify.min.js"></script>

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


    $(document).on('click', '#submitMove3', function(e) {


        var array = []
        var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].value != "on" && checkboxes[i].value != "off") {
                var val = parseInt(checkboxes[i].value);
                array.push(val);
            }
        }

        $('#submitMove3').attr("disabled", true);

        document.querySelector('#submitMove3').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Please Wait') }}";

        let agents_ids = $('#salesagent3').data('tokenize2').toArray();
        var id = array;


        var url = "{{ route('admin.movePendingReqToAnotherArray')}}";

        $.get(url, {
            agents_ids: agents_ids,
            id: id
        }, function(data) {

            document.querySelector('#submitMove3').innerHTML = "تحويل";
            $('#submitMove3').attr("disabled", false);

            if (data.status != 0) {
                $('.data-table').DataTable().ajax.reload();
                $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
            } else
                $('#msg2').addClass("alert-warning").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);



        });


        document.querySelector('#submitMove3').innerHTML = 'تحويل'
        $('#submitMove3').attr("disabled", false);
        document.getElementById("moveAll").disabled = true;
        document.getElementById("moveAll").style = "cursor: not-allowed";
        $('#mi-modal9').modal('hide');
        $('#salesagent3')[0].selectedIndex = -1;


    });
</script>


<script type="text/javascript">
    $(document).ready(function() {
        $.notify.defaults({
            globalPosition: 'top left'
        })


        $("#hijri-date").hijriDatePicker({
            hijri: true,
            format: "YYYY/MM/DD",
            hijriFormat: 'iYYYY-iMM-iDD',
            showSwitcher: false,
            showTodayButton: true,
            showClose: true
        });
        $("#hijri-date1").hijriDatePicker({
            hijri: true,
            format: "YYYY/MM/DD",
            hijriFormat: 'iYYYY-iMM-iDD',
            showSwitcher: false,
            showTodayButton: true,
            showClose: true
        });


        $(document).on('click', "#btn_condition", function(event) {


            document.getElementById("acceptedCount").innerHTML = "";


            $this = $('#contion_req_form_model');
            $url = "{{route('admin.getAcceptedCondition')}}";
            $data = $this.serialize();

            $.get($url, $data, function(response) {

                document.getElementById("acceptedCount").innerHTML = response.accepted_count + " من أصل " + response.count;

            }).fail(function(jqXHR, textStatus, errorThrown) {});


            $('#contion_req_model').modal('hide');


            var modalConfirm = function(callback) {


                $("#mi-modal6").modal('show');


                $("#modal-btn-si6").on("click", function() {

                    callback(true);
                    $("#mi-modal6").modal('hide');

                });


                $("#modal-btn-no6").on("click", function() {
                    callback(false);
                    $("#mi-modal6").modal('hide');
                });
            };

            modalConfirm(function(confirm) {
                if (confirm) {

                    $('#msg2').addClass("alert-warning").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> <i class='fa fa-spinner fa-spin'></i> يتم معالجة العملية");

                    $this = $('#contion_req_form_model');
                    $url = $this.attr('action');
                    $data = $this.serialize();

                    $.post($url, $data, function(response) {

                        $('.data-table').DataTable().ajax.reload();

                        $('#msg2').removeClass("alert-warning").addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + response.msg);


                    }).fail(function(jqXHR, textStatus, errorThrown) {

                        $('#msg2').removeClass("alert-warning").addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> لم يتم إكتمال العملية بنجاح");

                    });


                } else {
                    //reject
                }
            });




        });



    });
</script>

<script>
    $('.tokenizeable').tokenize2();
    $(".tokenizeable").on("tokenize:select", function() {
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
        'gm'
    ];

    function getClassifcationX($x) {
        return $("#classifcation_" + $x).data('tokenize2').toArray();
    }

    function getReqTypes() {
        return $("#request_type").data('tokenize2').toArray();
    }


    $(document).ready(function() {
        var dt = $('.data-table').DataTable({
            "language": {
                "url": "{{route('datatableLanguage')}}",
                buttons: {
                    pageLength: "عرض",
                    print: "طباعة",
                    

                }
            },
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "lengthMenu": [
                [10, 25, 50],
                [10, 25, 50]
            ],
            dom: 'Bfrtip',
            buttons: [
                'pageLength',
                // 'copyHtml5',
                //'excelHtml5',
                // 'csvHtml5',
                // 'pdfHtml5' ,
                'print',
                
                {
                    text: '{{ MyHelpers::admin_trans(auth()->user()->id,"Search") }}',
                    className: 'buttons-search',
                    action: function(e, dt, node, config) {
                        $('#myModal').modal('show');
                    }
                },
                {
                    text: '{{ MyHelpers::admin_trans(auth()->user()->id,"add_condition_to_pending_request") }}',
                    className: 'buttons-condition',
                    action: function(e, dt, node, config) {
                        $('#contion_req_model').modal('show');
                    }
                }
            ],
            processing: true,
            serverSide: true,
            ajax: {
                'url': "{{ url('admin/pending/request/datatable') }}",
                'data': function(data) {

                    // console.log(data);
                    let reqTypes = $("#request_type").data('tokenize2').toArray();
                    let customer_salary = $('#customer-salary').val();
                    let customer_salary_to = $('#customer-salary-to').val()
                    let customer_phone = $('#customer-phone').val();
                    let customer_birth = $('#customer-birth').val();
                    let req_date_from = $('#request-date-from').val();
                    let req_date_to = $('#request-date-to').val();
                    let complete_date_from = $('#complete-date-from').val();
                    let complete_date_to = $('#complete-date-to').val();
                    let req_status = ($("#request_status").data('tokenize2').toArray());
                    let pay_status = ($("#pay_status").data('tokenize2').toArray());
                    let source = $('#source').data('tokenize2').toArray();
                    let collaborator = $('#collaborator').data('tokenize2').toArray();
                    let work_source = $('#work_source').data('tokenize2').toArray();
                    let salary_source = $('#salary_source').data('tokenize2').toArray();
                    let founding_sources = $('#founding_sources').data('tokenize2').toArray();
                    let notes_status = $('#notes_status').data('tokenize2').toArray();
                    let region_ip = ($("#region_ip").data('tokenize2').toArray());

                    data.updated_at_from = $('#updated_at_from').val();
                    data.updated_at_to = $('#updated_at_to').val();


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

                    if (region_ip != '') data['region_ip'] = region_ip;
                    // console.log(req_date_to);
                    //console.log(req_date_from);

                    if (customer_birth != '') data['customer_birth'] = customer_birth;

                    if (customer_salary != '') data['customer_salary'] = customer_salary;
                    if (customer_salary_to != '') data['customer_salary_to'] = customer_salary_to;
                    if (customer_phone != '') data['customer_phone'] = customer_phone;

                    if (source != '') data['source'] = source;
                    if (collaborator != '') data['collaborator'] = collaborator;
                    if (work_source != '') data['work_source'] = work_source;

                    //console.log(work_source);

                    if (salary_source != '') data['salary_source'] = salary_source;
                    if (founding_sources != '') data['founding_sources'] = founding_sources;


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
                            req_status.push("11", "30"); //archive in mortgage manager req, wating mortgage manager req

                        contain = false;
                        contain = req_status.includes("10"); // rejected mortgage manager req
                        if (contain)
                            req_status.push("31"); //rejected mortgage manager req


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

                    // console.log(req_status);

                    xses.forEach(function(item) {
                        if (getClassifcationX(item) != '') {
                            data['class_id_' + item] = getClassifcationX(item)
                        }
                    })
                },
            },
            columns: [

                {
                    "targets": 0,
                    "data": "id",
                    "render": function(data, type, row, meta) {
                        return '<input type="checkbox" id="chbx" name="chbx[]" onchange="disabledButton()" value="' + data + '"/>';
                    }
                },

                {
                    "targets": 0,
                    "data": "created_at",
                    "className": "data-table-coulmn",
                    "render": function(data, type, row, meta) {
                        return data.split(" ").join("<br/>");
                    }


                },

                {
                    data: 'name',
                    name: 'customer.name',
                    className: "data-table-coulmn"
                },
                {
                    data: 'mobile',
                    name: 'customer.mobile',
                    className: "data-table-coulmn"
                },
                {
                    data: 'work',
                    name: 'customer.work',
                    className: "data-table-coulmn"
                },
                {
                    data: 'is_supported',
                    name: 'customer.is_supported',
                    className: "data-table-coulmn"
                },
                {
                    data: 'salary',
                    name: 'customer.salary',
                    className: "data-table-coulmn"
                },
                {
                    data: 'has_property',
                    name: 'realEstate.has_property',
                    className: "data-table-coulmn"
                },
                {
                    data: 'has_joint',
                    name: 'customer.has_joint',
                    className: "data-table-coulmn"
                },
                {
                    data: 'has_obligations',
                    name: 'customer.has_obligations',
                    className: "data-table-coulmn"
                },
                {
                    data: 'has_financial_distress',
                    name: 'customer.has_financial_distress',
                    className: "data-table-coulmn"
                },
                {
                    data: 'owning_property',
                    name: 'realEstate.owning_property',
                    className: "data-table-coulmn"
                },
                {
                    data: 'birth_date_higri',
                    name: 'customer.birth_date_higri',
                    className: "data-table-coulmn"
                },
                {
                    data: 'source',
                    name: 'source',
                    className: "data-table-coulmn"
                },
                {
                    data: "updated_at",
                    name: "updated_at",
                },
                {
                    data: 'action',
                    name: 'action',
                    className: "data-table-coulmn"
                }
            ],

            initComplete: function() {
                let api = this.api();
                $("#filter-search-req").on('click', function(e) {
                    e.preventDefault();
                    api.draw();
                    $('#myModal').modal('hide');
                });

                $(".paginate_button").addClass("pagination-circle");
                /* To Adaptive with New Design */
                $('#example-search-input').keyup(function() {
                    dt.search($(this).val()).draw();
                });

                //====================draw table when change in display number=====================
                $('#display_number').focusout(function(){
                    dt.page.len( $(this).val()).draw();
                });
                //==================================================================================


                dt.buttons().container()
                    .appendTo('#dt-btns');

                $(".dt-button").last().html('<i class="fas fa-plus"></i>').attr('title', 'اضافة شرط لحظي');
                $('.buttons-search').html('<i class="fas fa-search"></i>').attr('title', 'بحث');

                $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title', 'طباعة');
                $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');
                //
                $('.buttons-print').addClass('no-transition custom-btn');
                $('.buttons-collection').addClass('no-transition custom-btn');

                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)

            },
            "order": [
                [0, "desc"]
            ],
            createdRow: function(row, data, index) {

                $('td', row).eq(0).addClass('reqNum'); // 6 is index of column
                $('td', row).eq(1).addClass('reqType'); // 6 is index of column
                $('td', row).eq(2).addClass('reqType'); // 6 is index of column
                $('td', row).eq(3).addClass('reqType'); // 6 is index of column
                $('td', row).eq(4).addClass('reqType'); // 6 is index of column
                $('td', row).eq(5).addClass('reqType'); // 6 is index of column
                $('td', row).eq(6).addClass('reqType'); // 6 is index of column
                $('td', row).eq(7).addClass('reqType'); // 6 is index of column
                $('td', row).eq(10).addClass('reqDate'); // 6 is index of column
            },
        });
    });
    //

    $(function() {
        $('#source').on('tokenize:tokens:add', function(e, value, text) {



            if (value == 2) {


                document.getElementById("collaboratorDiv").style.display = "block";


            }
        });

        $('#source').on('tokenize:tokens:remove', function(e, value) {

            if (value == 2) {


                document.getElementById("collaboratorDiv").style.display = "none";
                document.getElementById("collaborator").value = "";

            }
        });

    });


    $(function() {
        $('#request_type').on('tokenize:tokens:add', function(e, value, text) {



            if (value == "شراء-دفعة") {


                document.getElementById("paystatusDiv").style.display = "block";


            }
        });

        $('#request_type').on('tokenize:tokens:remove', function(e, value) {

            if (value == "شراء-دفعة") {


                document.getElementById("paystatusDiv").style.display = "none";
                document.getElementById("pay_status").value = "";

            }
        });

    });

    $(document).on('click', '#move', function(e) {

        document.getElementById("salesagent").value = '';
        document.getElementById('salesagentsError').innerHTML = '';

        var id = $(this).attr('data-id');
        $('#frm-update1').find('#id1').val(id);

        document.getElementById("movedReqID").value = id;

        $('#mi-modal7').modal('show');


    });



    //-----------------------------------------------

    $(document).on('click', '#submitMove', function(e) {


        $('#submitMove').attr("disabled", true);
        document.querySelector('#submitMove').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Please Wait') }}";

        var salesAgent = document.getElementById("salesagent").value;
        var id = document.getElementById("movedReqID").value;


        if (salesAgent != '') {

            var url = "{{ route('admin.movePendingReqToAnother')}}";

            $.get(url, {
                salesAgent: salesAgent,
                id: id
            }, function(data) {


                document.querySelector('#submitMove').innerHTML = "تحويل";
                $('#submitMove').attr("disabled", false);

                if (data.status != 0) {
                    $('.data-table').DataTable().ajax.reload();
                    $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                } else
                    $('#msg2').addClass("alert-warning").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);


                $('#mi-modal7').modal('hide');


            });
        } else
            document.getElementById('salesagentsError').innerHTML = 'الرجاء اختيار استشاري';
        document.querySelector('#submitMove').innerHTML = "تحويل";
        $('#submitMove').attr("disabled", false);

    });
    /////////////////////////////////////////
</script>
@endsection
