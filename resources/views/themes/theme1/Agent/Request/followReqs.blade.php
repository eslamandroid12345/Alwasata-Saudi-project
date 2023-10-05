@extends('layouts.content')
@section('nav_actions')

{{-- Grid && List --}}
<div class="table-cell d-flex align-items-center">
    <div class="table-display d-flex align-items-center">
        <a class="table-grid selected ms-3"  href="#">
            <svg xmlns="http://www.w3.org/2000/svg" width="20.428" height="20.428" viewBox="0 0 20.428 20.428">
            <g id="Icon_feather-grid" data-name="Icon feather-grid" transform="translate(1 1)">
                <path id="Path_47" data-name="Path 47" d="M11.666,4.5H4.5v7.166h7.166Z" transform="translate(6.762 -4.5)" fill="#d8d8d8" stroke="#d8d8d8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                <path id="Path_48" data-name="Path 48" d="M28.167,4.5H21v7.166h7.166Z" transform="translate(-21 -4.5)" fill="#d8d8d8" stroke="#d8d8d8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                <path id="Path_49" data-name="Path 49" d="M28.167,21H21v7.166h7.166Z" transform="translate(-21 -9.738)" fill="#d8d8d8" stroke="#d8d8d8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                <path id="Path_50" data-name="Path 50" d="M11.666,21H4.5v7.166h7.166Z" transform="translate(6.762 -9.738)" fill="#d8d8d8" stroke="#d8d8d8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
            </g>
            </svg>
        </a>
        <a class="table-list" href="#">
            <svg xmlns="http://www.w3.org/2000/svg" width="24.348" height="19.783" viewBox="0 0 24.348 19.783">
            <path
                id="Icon_awesome-list-ul"
                data-name="Icon awesome-list-ul"
                d="M22.065,3.375a2.283,2.283,0,1,1-2.283,2.283A2.283,2.283,0,0,1,22.065,3.375Zm0,7.609a2.283,2.283,0,1,1-2.283,2.283A2.283,2.283,0,0,1,22.065,10.984Zm0,7.609a2.283,2.283,0,1,1-2.283,2.283,2.283,2.283,0,0,1,2.283-2.283Zm-21.3.761H15.978a.761.761,0,0,1,.761.761v1.522a.761.761,0,0,1-.761.761H.761A.761.761,0,0,1,0,21.636V20.114A.761.761,0,0,1,.761,19.353Zm0-15.217H15.978a.761.761,0,0,1,.761.761V6.418a.761.761,0,0,1-.761.761H.761A.761.761,0,0,1,0,6.418V4.9A.761.761,0,0,1,.761,4.136Zm0,7.609H15.978a.761.761,0,0,1,.761.761v1.522a.761.761,0,0,1-.761.761H.761A.761.761,0,0,1,0,14.027V12.505A.761.761,0,0,1,.761,11.745Z"
                transform="translate(0 -3.375)"
                fill="#d8d8d8"
            ></path>
            </svg>
        </a>
    </div>
</div>

{{-- Print - Show -Search --}}
<div class="table-cell d-flex align-items-center mt-3 mt-md-0" id="new-dt-btns"></div>
{{-- Dropdown menu --}}
<div class="table-cell d-flex align-items-center mt-3 mt-md-0">

    <div class="table-dropdown">
        <div class="dropdown">
          <button class="btn btn-primary" data-bs-toggle="dropdown">
            <svg xmlns="http://www.w3.org/2000/svg" width="24.236" height="5.302" viewBox="0 0 24.236 5.302">
              <path
                id="menu"
                d="M16.651,44.3a2.765,2.765,0,0,1-1.893-.8A2.638,2.638,0,0,1,14,41.651a2.988,2.988,0,0,1,.757-1.893A2.661,2.661,0,0,1,16.651,39a2.812,2.812,0,0,1,1.856.757A2.67,2.67,0,0,1,16.651,44.3Zm11.323-.8a2.676,2.676,0,0,0,.8-1.856,2.838,2.838,0,0,0-.8-1.893A2.638,2.638,0,0,0,26.118,39a2.908,2.908,0,0,0-1.893.757,2.661,2.661,0,0,0-.757,1.893,2.812,2.812,0,0,0,.757,1.856,2.607,2.607,0,0,0,3.749,0Zm9.467,0a2.676,2.676,0,0,0,.8-1.856,2.838,2.838,0,0,0-.8-1.893A2.638,2.638,0,0,0,35.585,39a2.908,2.908,0,0,0-1.893.757,2.661,2.661,0,0,0-.757,1.893,2.812,2.812,0,0,0,.757,1.856,2.607,2.607,0,0,0,3.749,0Z"
                transform="translate(-14 -39)"
                fill="#fff"
              ></path>
            </svg>
          </button>
          <ul class="dropdown-menu">
            @if ($requests >0)
            <li>
                <button disabled id="restoreAll" class="dropdown-item green" onclick="getReqests1()" style="cursor: not-allowed">
                <svg class="ms-3" xmlns="http://www.w3.org/2000/svg" width="21" height="20.02" viewBox="0 0 21 20.02">
                    <path
                      id="Icon_feather-star"
                      data-name="Icon feather-star"
                      d="M13,3l3.09,6.26L23,10.27l-5,4.87,1.18,6.88L13,18.77,6.82,22.02,8,15.14,3,10.27,9.91,9.26Z"
                      transform="translate(-2.5 -2.5)"
                      fill="none"
                      stroke="#000"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1"
                    ></path>
                  </svg>
                    <span class="font-medium">
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Restore To Recived Reqs') }}
                    </span>
                </button>
            </li>
            @endif
          </ul>
        </div>
      </div>
</div>
@endsection


@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Following Requests') }}
@endsection

@section('css_style')
<link rel="stylesheet" href="{{ asset('css/tokenize2.min.css') }}">
<style>
    .mov, .green{
        background-color: #fff;
    }
    .hidden{
        display: none;
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
    }

    td {
        width: 15%;
    }

    .reqNum {
        width: 1%;
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




<div id="msg2" class="alert alert-dismissible" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>

</div>

{{-- <div class="addUser my-4">
    <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
        <h3> {{ MyHelpers::admin_trans(auth()->user()->id,'Following Requests') }}:</h3>
    </div>
</div> --}}

@if ($requests >0)
<div class="row hidden" id="grid-cont">

</div>

<div class="col-12">
    <div class="portlet">
        <div class="portlet__body">
            <div class="tablee-responsive">
                <div class="dashTable">
                    {{-- <table class="table table-bordred table-striped data-table" id="followingReqs-table"> --}}
                    <table class="table table-custom table-striped table-custom-3 table-resizable data-table">

                        <thead>
                            <tr>
                                <th>
                                    <label class="m-checkbox mb-1">
                                        <input type="checkbox" id="allreq" onclick="chbx_toggle1(this);" />
                                        <span class="checkmark border-white"></span>
                                    </label>
                                 </th>
                                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req num') }}</th>
                                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'req date') }}</th>
                                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</th>
                                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
                                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req status') }}</th>
                                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</th>
                                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req classification') }}</th>
                                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</th>
                                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'notes_status_quality') }}</th>
                                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'assign req date') }} <br>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'in agent') }}</th>
                                <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- </div> --}}
@else
<div class="middle-screen">
    <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No New Requests') }}</h2>
</div>

@endif

@endsection




@section('updateModel')
@include('Agent.Request.filterReqs')
@include('Agent.Request.confirmArchMsg')
@include('Agent.Request.confirmRestorMsg')
@endsection


@section('scripts')



<script>
    ////////////////////////////////////////
    function getReqests1() {
        var array = []
        var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].value != "on" && checkboxes[i].value != "off") {
                var val = parseInt(checkboxes[i].value);
                array.push(val);
            }
        }
        //console.log(array);
        restoreAllReqs(array);
        //  alert(array);
    }



    function getReqests2() {
        var array = []
        var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].value != "on" && checkboxes[i].value != "off") {
                var val = parseInt(checkboxes[i].value);
                array.push(val);
            }
        }
        //console.log(array);
        archiveAllReqs(array);
        //  alert(array);
    }



    /////////////////////////////////////////
    /////////////////////////////////////////

    function archiveAllReqs(array) {


        var modalConfirm = function(callback) {


            $("#mi-modal3").modal('show');


            $("#modal-btn-si3").on("click", function() {

                callback(true);
                $("#mi-modal3").modal('hide');

            });


            $("#modal-btn-no3").on("click", function() {
                callback(false);
                $("#mi-modal3").modal('hide');
            });
        };

        modalConfirm(function(confirm) {
            if (confirm) {

                $.post("{{ route('agent.archReqArray')}}", {
                    array: array,
                    _token: "{{csrf_token()}}",
                }, function(data) {

                    var url = '{{ route("agent.followedRequests") }}';

                    if (data != 0) {
                        $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')}}");
                        window.location.href = url; //using a named route

                    } else
                        $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id, 'Try Again')}}");

                });



            } else {
                //reject
            }
        });



    };

    ///////////////////////////////////////////////

    /////////////////////////////////////////

    function restoreAllReqs(array) {


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

                $.post("{{ route('agent.restoreReqArray')}}", {
                    array: array,
                    _token: "{{csrf_token()}}",
                }, function(data) {

                    var url = '{{ route("agent.followedRequests") }}';

                    if (data != 0) {
                        $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')}}");
                        window.location.href = url; //using a named route

                    } else
                        $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id, 'Try Again')}}");

                });



            } else {
                //reject
            }
        });



    };

    ///////////////////////////////////////////////




    function disabledButton() {

        if ($(':checkbox[name="chbx[]"]:checked').length > 0) {
            document.getElementById("restoreAll").disabled = false;
            document.getElementById("restoreAll").style = "";

            // document.getElementById("archAll").disabled = false;
            // document.getElementById("archAll").style = "";

        } else {
            document.getElementById("restoreAll").disabled = true;
            document.getElementById("restoreAll").style = "cursor: not-allowed";

            // document.getElementById("archAll").disabled = true;
            // document.getElementById("archAll").style = "cursor: not-allowed";


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
    ///////////////////////////////////////////////
</script>


<script src="{{ asset('js/tokenize2.min.js') }}"></script>

<script src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js"></script>

<script>
    $('.tokenizeable').tokenize2();
    $(".tokenizeable").on("tokenize:select", function() {
        $(this).trigger('tokenize:search', "");
    });

    function getCustomerIDS() {
        return $("#customer_ids").data('tokenize2').toArray();
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

    /*
      function getReqSources() {
          return $("#source").data('tokenize2').toArray();
      }

      */

    $(document).ready(function() {
        var dt = $('.data-table').DataTable({
            "language": {
                "url": "{{route('datatableLanguage')}}",
                buttons: {
                    print: "طباعة",
                    pageLength: "عرض",

                }
            },
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                // rightColumns: 1
                rightColumns: 0
            },
            "lengthMenu": [
                [10, 25, 50],
                [10, 25, 50]
            ],
            dom: 'Bfrtip',
            buttons: [
                // 'copyHtml5',
                //'excelHtml5',
                // 'csvHtml5',
                // 'pdfHtml5' ,
                'print',
                'pageLength',
                {
                    text: '{{ MyHelpers::admin_trans(auth()->user()->id,"Search") }}',
                    action: function(e, dt, node, config) {
                        $('#myModal').modal('show');
                    }
                }
            ],
            scrollY: '50vh',
            processing: true,
            serverSide: true,
            ajax: {
                'url': "{{ url('agent/followreqs-datatable') }}",
                'data': function(data) {
                    let customer_ids = $('#customer_ids').data('tokenize2').toArray();
                    let reqTypes = $("#request_type").data('tokenize2').toArray();
                    let customer_salary = $('#customer-salary').val();
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



                    if (customer_birth != '') data['customer_birth'] = customer_birth;

                    if (customer_salary != '') data['customer_salary'] = customer_salary;
                    if (customer_phone != '') data['customer_phone'] = customer_phone;

                    if (source != '') data['source'] = source;
                    if (collaborator != '') data['collaborator'] = collaborator;
                    if (work_source != '') data['work_source'] = work_source;
                    if (salary_source != '') data['salary_source'] = salary_source;
                    if (founding_sources != '') data['founding_sources'] = founding_sources;

                    if (customer_ids != '') data['customer_ids'] = customer_ids;

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
                    data: 'id',
                    name: 'id'
                },
                {
                    "targets": 0,
                    "data": "created_at",
                    "render": function(data, type, row, meta) {
                        return data.split(" ").join("<br/>");
                    }


                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'cust_name',
                    name: 'customers.name'
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
                    "render": function(data, type, row, meta) {
                        return data.split(" ").join("<br/>");
                    }


                },
                {
                    data: 'action',
                    name: 'action'
                }
            ],
            initComplete: function() {
                let api = this.api();
                $('#grid-cont').html('');

                $("#filter-search-req").on('click', function(e) {
                    e.preventDefault();
                    api.draw();
                    $('#grid-cont').html('');

                    $('#myModal').modal('hide');
                });

                $(".paginate_button").addClass("pagination-circle");
                /* To Adaptive with New Design */
                $('#example-search-input').keyup(function() {
                    dt.search($(this).val()).draw();
                })

                $('#nav-search-input').keyup(function(){
                    dt.search($(this).val()).draw() ;
                    $('#grid-cont').html('');
                })



                // dt.buttons().container()
                //     .appendTo('#dt-btns');

                    dt.buttons().container()
                    .appendTo( '#new-dt-btns' );

                $(".dt-button").last().html('<i class="fas fa-search"></i>').attr('title', 'بحث');
                // $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title','تصدير') ;
                $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title', 'طباعة');
                $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');

                $( ".dt-button" ).addClass(' btn-icon');
                // $('.buttons-excel').addClass('no-transition custom-btn');
                $('.buttons-print').addClass('no-transition custom-btn');
                $('.buttons-collection').addClass('no-transition custom-btn');


                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)

            },
            "order": [
                [2, "desc"]
            ], // Order on init. # is the column, starting at 0
            createdRow: function(row, data, index) {

                /*          if ( data.statusReq === "طلب جديد") {
                              $(row).addClass( 'newReq' );

                          }

                          if ( data.class_id_agent === "يحتاج متابعة") {
                              $(row).addClass( 'needFollow' );
                          }

                          if ( data.class_id_agent === "لا يرغب") {
                              $(row).addClass( 'noNeed' );
                          }

                          if ( data.class_id_agent === "بانتظار الأوراق") {
                              $(row).addClass( 'wating' );
                          }

                          if ( data.class_id_agent === "يبحث عن عقار") {
                              $(row).addClass( 'watingReal' );
                          }

                          if ( data.class_id_agent === "مرفوض") {
                              $(row).addClass( 'rejected' );
                          }

                      */


                $('td', row).eq(2).attr('title', data.created_at);
                $('td', row).eq(2).attr('data-title', data.created_at);
                $('td', row).eq(2).attr('data-bs-toggle', 'tooltip');
                $('td', row).eq(2).attr('data-bs-placement', 'top');

                $('td', row).eq(4).attr('title', data.cust_name);
                $('td', row).eq(4).attr('data-title', data.cust_name);
                $('td', row).eq(4).attr('data-bs-toggle', 'tooltip');
                $('td', row).eq(4).attr('data-bs-placement', 'top');

                $('td', row).eq(5).attr('title', data.statusReq);
                $('td', row).eq(5).attr('data-title', data.statusReq);
                $('td', row).eq(5).attr('data-bs-toggle', 'tooltip');
                $('td', row).eq(5).attr('data-bs-placement', 'top');

                $('td', row).eq(6).attr('title', data.source);
                $('td', row).eq(6).attr('data-title', data.source);
                $('td', row).eq(6).attr('data-bs-toggle', 'tooltip');
                $('td', row).eq(6).attr('data-bs-placement', 'top');

                $('td', row).eq(7).attr('title', data.class_id_agent);
                $('td', row).eq(7).attr('data-title', data.class_id_agent);
                $('td', row).eq(7).attr('data-bs-toggle', 'tooltip');
                $('td', row).eq(7).attr('data-bs-placement', 'top');

                $('td', row).eq(8).addClass('commentStyle'); // 6 is index of column
                $('td', row).eq(8).attr('title', data.comment); // to show other text of comment
                $('td', row).eq(8).attr('data-title', data.comment);
                $('td', row).eq(8).attr('data-bs-toggle', 'tooltip');
                $('td', row).eq(8).attr('data-bs-placement', 'top');

                $('td', row).eq(9).addClass('commentStyle'); // 6 is index of column
                $('td', row).eq(9).attr('title', data.quacomment); // to show other text of comment
                $('td', row).eq(9).attr('data-title', data.quacomment);
                $('td', row).eq(9).attr('data-bs-toggle', 'tooltip');
                $('td', row).eq(9).attr('data-bs-placement', 'top');


                $('td', row).eq(8).addClass('commentStyle'); // 6 is index of column
                $('td', row).eq(8).attr('title', data.comment); // to show other text of comment

                $('td', row).eq(9).addClass('commentStyle'); // 6 is index of column
                $('td', row).eq(9).attr('title', data.quacomment);

                $('td', row).eq(0).addClass('reqNum'); // 6 is index of column
                $('td', row).eq(1).addClass('reqType'); // 6 is index of column
                $('td', row).eq(2).addClass('reqDate'); // 6 is index of column
                $('td', row).eq(10).addClass('reqDate'); // 6 is index of column
                $('td', row).eq(3).addClass('reqType'); // 6 is index of column
                $('td', row).eq(6).addClass('reqType'); // 6 is index of column
                console.log("data", data);
                addCardGrid(data);

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
    ///////////////////////////////////
    function addCardGrid(data) {
        var dde = ``;
        for (let index = 0; index < data.action_grid.length; index++) {
            // const element = data.action_grid[index];
            dde += `
            <li>
                                <a class="dropdown-item" href="`+data.action_grid[index]['url']+`">
                                    <i class="`+data.action_grid[index]['icon']+`"></i>
                                  <span class="font-medium">`+data.action_grid[index]['title']+`</span>
                                </a>
                              </li>
            `;

        }
        if(data.statusReq == 'جديد')
        {
            $start = `<i class="fas fa-star"></i>`;
        }else{
            $start = `<i class="fas fa-star-o"></i>`;
        }

        $('#grid-cont').append(`
        <div class="col-lg-3 col-sm-6">
                    <div class="widget__item-order widget-`+data.card_class+`">
                      <div class="d-flex align-items-center justify-content-between mb-1">
                        <div class="d-flex align-items-center">
                          <h6 class="font-medium">`+data.cust_name+`</h6>
                        </div>
                        <div class="d-flex align-items-center">
                          <div class="btn-star ms-3 add-to-special-orders">
                            `+$start+`
                          </div>
                          <div class="dropdown">
                            <button class="btn bg-white p-1" data-bs-toggle="dropdown">
                              <svg xmlns="http://www.w3.org/2000/svg" width="3.548" height="16.219" viewBox="0 0 3.548 16.219">
                                <path
                                  id="menu"
                                  d="M1.774,3.548A1.851,1.851,0,0,1,.507,3.016,1.765,1.765,0,0,1,0,1.774,2,2,0,0,1,.507.507,1.781,1.781,0,0,1,1.774,0,1.882,1.882,0,0,1,3.016.507a1.8,1.8,0,0,1,.532,1.267A1.819,1.819,0,0,1,1.774,3.548Zm7.577-.532a1.791,1.791,0,0,0,.532-1.242A1.9,1.9,0,0,0,9.351.507,1.765,1.765,0,0,0,8.109,0,1.946,1.946,0,0,0,6.842.507a1.781,1.781,0,0,0-.507,1.267,1.882,1.882,0,0,0,.507,1.242,1.744,1.744,0,0,0,2.509,0Zm6.336,0a1.791,1.791,0,0,0,.532-1.242A1.9,1.9,0,0,0,15.687.507,1.765,1.765,0,0,0,14.445,0a1.946,1.946,0,0,0-1.267.507,1.781,1.781,0,0,0-.507,1.267,1.882,1.882,0,0,0,.507,1.242,1.744,1.744,0,0,0,2.509,0Z"
                                  transform="translate(0 16.219) rotate(-90)"
                                  fill="#acacac"
                                ></path>
                              </svg>
                            </button>
                            <ul class="dropdown-menu">
                              `+dde+`
                            </ul>
                          </div>
                        </div>
                      </div>
                      <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6>`+data.req_date+`</h6>
                        <div class="label label-solid-`+data.card_class+`">`+data.statusReq+`</div>
                      </div>
                      <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="font-medium">`+data.source+`</h6>
                        <h5>`+data.type+`</h5>
                      </div>
                      <div class="d-flex align-items-center justify-content-between">
                        <h5>تاريخ النزول</h5>
                        <h5>`+data.agent_date+`</h5>
                      </div>
                      <hr />
                      <h6 class="widget__item-text">`+data.comment+`</h6>
                    </div>
                  </div>
        `);
    }

    $(document).on('click', '.table-grid', function(){
        $('#grid-cont').removeClass('hidden');
        $('.DTFC_ScrollWrapper').addClass('hidden');
    })
    $(document).on('click', '.table-list', function(){
        $('#grid-cont').addClass('hidden');
        $('.DTFC_ScrollWrapper').removeClass('hidden');
    })
</script>
@endsection
