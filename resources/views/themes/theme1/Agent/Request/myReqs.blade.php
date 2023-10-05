@extends('themes.theme1.layouts.content')
@php
    $new_theme = true;
@endphp
{{-- @extends('layouts.content') --}}
@section('nav_actions')


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
<div class="table-cell d-flex align-items-center mt-3 mt-md-0" id="new-dt-btns"></div>
@endsection
@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'All Requests') }}
@endsection

@section('css_style')
<link rel="stylesheet" href="{{ asset('css/tokenize2.min.css') }}">
<script src="{{ asset('backend/sweetalert/sweetalert2.min.js') }}"></script>
<link href="{{ asset('backend/sweetalert/sweetalert2.min.css') }}" rel="stylesheet">
<style>
.hidden{
    display: none;
}
.switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    .activeColor {
        color: green;
    }

    .notactiveColor {
        color: gray;
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

    .reqDate {
        text-align: center;
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

    .afnantable {

        border: 1px solid black;
    }

</style>

{{--    NEW STYLE   --}}
{{-- <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}"> --}}

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

@if(session()->has('message2'))
<div class="alert alert-danger">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  {{ session()->get('message2') }}
</div>
@endif


<!--CANNOT OPEN ANOTHER REQ UNTIL FINSH FROM FIRST ONE-->
@if(session()->has('message7'))
<div class="alert alert-danger">
  <button type="button" class="close" data-dismiss="alert" id="message7">&times;</button>
  {{ session()->get('message7') }}
</div>
@endif
<!--CANNOT OPEN ANOTHER REQ UNTIL FINSH FROM FIRST ONE-->

@if ( session()->has('agentAssments') )


<div class="alert alert-info">
    <button type="button" class="close" data-dismiss="alert">&times;</button>


    <div class="row">

        <div class="table-responsive table--no-card m-b-30 data-table-parent">

            <h4>نتيجة تقييم أدائك : <span style="text-align: left;float:left; text-decoration: underline; font-size: medium;"><a href="{{ route('agent.finalResultChartForAgent') }}">تفاصييل التقييم</a></span>
            </h4>

            <br>
            <table class="table table-borderless table-striped table-earning afnantable ">
                <thead>
                    <tr style="text-align: center; background-color:#FF0000">


                        <th>تقييم الجودة</th>

                        <th>الطلبات المحولة</th>

                        <th> التجاوب مع الجودة</th>
                        <th> التذاكر المكتملة</th>

                        <th>التحديث على <br> الطلب</th>



                        <th>النتيجة النهائية</th>


                    </tr>
                </thead>

                <tbody style="text-align: center;">


                    <tr>


                        <td>{{$assments['servayResult']}} %</td>
                        <td>{{$assments['move_present']}} %</td>
                        <td>{{$assments['updateTask_present']}} %</td>
                        <td>{{$assments['completeTask_present']}} %</td>
                        <td>{{$assments['updateReq_present']}} %</td>
                        <td>{{$assments['finalResult']}} %</td>



                    </tr>


                </tbody>
            </table>
        </div>

    </div>


</div>
@endif




<div id="msg2" class="alert alert-dismissible" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>

</div>


@if (!isset($new_theme))
<div class="addUser my-4">
    <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
        <h3>{{ MyHelpers::admin_trans(auth()->user()->id,'All Requests') }}:</h3>
    </div>
</div>
<br>
@endif

<!--
<div class="table-data__tool">
    <div class="table-data__tool-right">
        <a href="{{ route('agent.purchasePage', ['title' => 'funding', 'id' => 'null'])}}">
            <button class="au-btn au-btn-icon au-btn--blue au-btn--small">
                <i class="zmdi zmdi-plus"></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Request') }}</button></a>
    </div>
</div>
-->


{{--
    <div class="row">
    <div class="col-5">
        @if (auth()->user()->ready_receive == 0)
        <span id="toggleText" style="color: grey;">غير جاهز لاستقبال الطلبات</span>
        @else
        <span id="toggleText" style="color: green;">جاهز لاستقبال الطلبات</span>
        @endif

        <label class="switch">
            <input name="isActive" type="checkbox" {{auth()->user()->ready_receive == 1 ? 'checked' : ''}}>
            <span class="slider round"></span>
        </label>
    </div>

    <p class="d-none" id="waiting_req_error_msg" style="color:red">عير مسموح لك بتفعيل الخاصية</p>
</div>


    --}}


@if ($requests >0)

    <div class="tableBar ">
        <div class="topRow" style="display: none;">
            <div class="row align-items-center text-center text-md-left">
                <div class="col-lg-6 ">
                    <div class="tableUserOption  flex-wrap ">
                        <div class="input-group mt-lg-0 mt-3">
                            <input class="form-control py-2" type="search" placeholder="ابحث هنا" id="example-search-input">
                            <span class="input-group-append">
                              <button class="btn btn-outline-info" type="button">
                                  <i class="fa fa-search"></i>
                              </button>
                            </span>
                        </div>

                    </div>
                </div>
                <div class="col-lg-3"></div>
                <div class="col-lg-3 mt-lg-0 mt-3">
                    <div  id="dt-btns" class="tableAdminOption">

                    </div>
                </div>
            </div>
        </div>
        <div class="row hidden" id="grid-cont">

        </div>
        @if (env('NEW_THEME') == '1')
        <div class="col-12">
            <div class="portlet">
                <div class="portlet__body">
                    <div class="tablee-responsive">
                        <div class="dashTable" style="margin-top: 0;">
                            <table class="table table-custom table-striped table-custom-3 table-resizable data-table" id="myreqs-table">
                                <thead>
                                <tr>
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

        @else
        <div class="dashTable">
            <table class="table table-bordred table-striped data-table" id="myreqs-table">
                <thead>
                <tr>
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

        @endif
    </div>
@else
    <div class="middle-screen">
        <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No New Requests') }}</h2>
    </div>

@endif



@endsection




@section('updateModel')
@include('Agent.Request.filterReqs')
@endsection


@section('scripts')


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
                rightColumns: 0
                // rightColumns: 1
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
                'url': "{{ url('agent/myreqs-datatable') }}",
                'data': function(data) {

                    // console.log(data);

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


                    // console.log(req_date_to);
                    //console.log(req_date_from);

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
                $('#grid-cont').html('');
                let api = this.api();
                $("#filter-search-req").on('click', function(e) {
                    e.preventDefault();
                    api.draw();
                $('#grid-cont').html('');
                    $('#myModal').modal('hide');
                });

                $(".paginate_button").addClass("pagination-circle");
                /* To Adaptive with New Design */
                $('#example-search-input').keyup(function(){
                    dt.search($(this).val()).draw() ;
                $('#grid-cont').html('');
                })
                $('#nav-search-input').keyup(function(){
                    dt.search($(this).val()).draw() ;
                $('#grid-cont').html('');
                })



                // dt.buttons().container()
                //     .appendTo( '#dt-btns' );
                dt.buttons().container()
                    .appendTo( '#new-dt-btns' );

                $( ".dt-button" ).last().html('<i class="fas fa-filter"></i>').attr('title','بحث') ;
                $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title','تصدير') ;
                $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title','طباعة') ;
                $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title','عرض') ;

                $( ".dt-button" ).last().addClass(' btn-icon');
                $('.buttons-excel').addClass('no-transition custom-btn btn-icon');
                $('.buttons-print').addClass('no-transition custom-btn btn-icon');
                $('.buttons-collection').addClass('no-transition custom-btn btn-icon');


                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)

            },
            "order": [
                [1, "desc"]
            ], // Order on init. # is the column, starting at 0
            createdRow: function(row, data, index) {
                /*
                                if (data.statusReq === "طلب جديد") {
                                    $(row).addClass('newReq');

                                }

                                if (data.class_id_agent === "يحتاج متابعة") {
                                    $(row).addClass('needFollow');
                                }

                                if (data.class_id_agent === "لا يرغب") {
                                    $(row).addClass('noNeed');
                                }

                                if (data.class_id_agent === "بانتظار الأوراق") {
                                    $(row).addClass('wating');
                                }

                                if (data.class_id_agent === "يبحث عن عقار") {
                                    $(row).addClass('watingReal');
                                }

                                if (data.class_id_agent === "مرفوض") {
                                    $(row).addClass('rejected');
                                }
                */


                $('td', row).eq(7).addClass('commentStyle'); // 6 is index of column
                $('td', row).eq(7).attr('title', data.comment); // to show other text of comment
                $('td', row).eq(7).attr('data-title', data.comment);
                $('td', row).eq(7).attr('data-bs-toggle', 'tooltip');
                $('td', row).eq(7).attr('data-bs-placement', 'top');

                $('td', row).eq(8).addClass('commentStyle'); // 6 is index of column
                $('td', row).eq(8).attr('title', data.quacomment); // to show other text of comment
                $('td', row).eq(8).attr('data-title', data.quacomment);
                $('td', row).eq(8).attr('data-bs-toggle', 'tooltip');
                $('td', row).eq(8).attr('data-bs-placement', 'top');

                $('td', row).eq(0).addClass('reqNum'); // 6 is index of column
                $('td', row).eq(1).addClass('reqDate'); // 6 is index of column
                $('td', row).eq(1).attr('title', data.created_at);
                $('td', row).eq(1).attr('data-title', data.created_at);
                $('td', row).eq(1).attr('data-bs-toggle', 'tooltip');
                $('td', row).eq(1).attr('data-bs-placement', 'top');

                $('td', row).eq(4).attr('title', data.statusReq);
                $('td', row).eq(4).attr('data-title', data.statusReq);
                $('td', row).eq(4).attr('data-bs-toggle', 'tooltip');
                $('td', row).eq(4).attr('data-bs-placement', 'top');

                $('td', row).eq(5).attr('title', data.source);
                $('td', row).eq(5).attr('data-title', data.source);
                $('td', row).eq(5).attr('data-bs-toggle', 'tooltip');
                $('td', row).eq(5).attr('data-bs-placement', 'top');

                $('td', row).eq(6).attr('title', data.class_id_agent);
                $('td', row).eq(6).attr('data-title', data.class_id_agent);
                $('td', row).eq(6).attr('data-bs-toggle', 'tooltip');
                $('td', row).eq(6).attr('data-bs-placement', 'top');

                $('td', row).eq(3).attr('title', data.cust_name);
                $('td', row).eq(3).attr('data-title', data.cust_name);
                $('td', row).eq(3).attr('data-bs-toggle', 'tooltip');
                $('td', row).eq(3).attr('data-bs-placement', 'top');

                $('td', row).eq(9).addClass('reqDate'); // 6 is index of column
                $('td', row).eq(10).addClass('dropdown'); // 6 is index of column
                $('td', row).eq(2).addClass('reqType'); // 6 is index of column
                $('td', row).eq(3).addClass('reqType'); // 6 is index of column
                $('td', row).eq(5).addClass('reqType'); // 6 is index of column
                $('td', row).eq(5).addClass('reqType'); // 6 is index of column
                // *********************************
                console.log("data", data);
                addCardGrid(data);
            },
        });

        if ($("#message7").length != 0) {

swal({
    title: "خطأ!",
    text: "{{ MyHelpers::admin_trans(auth()->user()->id,'You have open request without comment and class') }}",
    type: 'error',
    confirmButtonText: 'موافق',
    confirmButtonColor: '#990000',
});

}
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


    var checkbox = document.querySelector("input[name=isActive]");
    var toggleText = document.getElementById("toggleText");
    (toggleText&&checkbox)&&checkbox.addEventListener('change', function() {


        checkbox_status=this.checked;
        toggleText.style.color = '';
        toggleText.classList.remove("activeColor");
        toggleText.classList.remove("notactiveColor");
        $('#waiting_req_error_msg').addClass("d-none");

            $.get("{{route('agent.updatereadyrecive')}}", {}, function(data) {

                if (data.status != 0) {
                    if (checkbox_status){
                        toggleText.innerHTML = 'جاهز لاستقبال الطلبات';
                        toggleText.classList.add("activeColor");
                    }
                    else{
                        toggleText.innerHTML = 'غير جاهز لاستقبال الطلبات';
                        toggleText.classList.add("notactiveColor");
                    }

                }
                else{
                    $('#waiting_req_error_msg').removeClass("d-none");
                }

            });


    });


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
