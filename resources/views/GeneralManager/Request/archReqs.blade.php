@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Archived Requests') }}
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
{{--    NEW STYLE   --}}
<style>
    .tFex{
        position: relative !important;
        width: 100% !important;
    }
    .dataTables_filter { display: none; }
    span.redBg{
        background: #E67681;
    }
    .pointer{
        cursor: pointer;
    }
    .dataTables_info{
        margin-left: 15px;
        font-size: smaller;
    }
    .dataTables_paginate {
        color: #333;
        font-size: smaller;
    }
    .dataTables_paginate , .dataTables_info{
        margin-bottom: 10px;
        margin-top: 10px;
    }


</style>
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
        <h3>{{ MyHelpers::admin_trans(auth()->user()->id,'Archived Requests') }}:</h3>
    </div>
</div>
<br>



    @if ($requests > 0)
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
                            <button disabled style="cursor: not-allowed"  id="archAll"  onclick="getReqests1()">
                                <i class="fas fa-reply-all"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Restore Request') }}
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
                    <div  id="dt-btns" class="tableAdminOption">

                    </div>
                </div>
            </div>
        </div>
        <div class="dashTable">
            <table class="table table-bordred table-striped data-table">
                <thead>
                <tr>
                    <th> </th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'req date') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req classification') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'agent comment') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'notes_status_quality') }}</th>
                {{--
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'note') }} <br> {{ MyHelpers::admin_trans(auth()->user()->id,'Sales Manager') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'note') }} <br> {{ MyHelpers::admin_trans(auth()->user()->id,'Funding Manager') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'note') }} <br> {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage Manager') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'comment on website') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'classification of sales manager') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'classification of funding manager') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'classification of mortgage manager') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'req classification quality') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }} {{ MyHelpers::admin_trans(auth()->user()->id,'client') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'salary source') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'civilian_ministry') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'is supported') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'has obligations') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'bank employee') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'bank order num') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'joint name') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }} <br> {{ MyHelpers::admin_trans(auth()->user()->id,'solidarity') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'joint mobile') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'joint salary') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'salary source') }} <br> {{ MyHelpers::admin_trans(auth()->user()->id,'solidarity') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }} <br> {{ MyHelpers::admin_trans(auth()->user()->id,'solidarity') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'civilian_ministry') }} <br> {{ MyHelpers::admin_trans(auth()->user()->id,'solidarity') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }} <br> {{ MyHelpers::admin_trans(auth()->user()->id,'solidarity') }} </th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'owner name') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'owner mobile') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'property_age') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'city') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'region') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate status') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate cost') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'pursuit') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate type') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'has property') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'is real estat evaluated?') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'are there tenants?') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'is real estat mortgaged?') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'funding source') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'funding duration') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'cost personal funding') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'presentage') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'cost real estate funding') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'presentage') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'deduction presentage') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'monthly installment') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'pay status') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'real cost') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'increase value') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'prepayment value') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'prepayment %') }}</th>
                <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</th>
                --}}
                <!-- <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'net to the customer') }}</th>
                       <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'customer deficit') }}</th>
                        <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'visa_card') }}</th>
                        <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'car_loan') }}</th>
                        <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'personal_loan') }}</th>
                        <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'mortgage_loan') }}</th>
                        <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'credit_bank') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'others_type_trust') }}</th>
                        <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'total debt') }}</th>
                        <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage %') }}</th>
                        <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'foreclosure') }}</th>
                        <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'Profit %') }}</th>
                        <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'amount_of_quest') }}</th>
                        <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'value added') }}</th>
                        <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'admin fees') }}</th>-->
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
        <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No Archive Requests') }}</h2>
    </div>
    @endif



@endsection


@section('updateModel')
@include('GeneralManager.Request.filterReqs')
@include('GeneralManager.Request.confirmRestMsg')
@endsection
@section('scripts')


<script>
    //-----------------------------------

    //----------------------------

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
        archiveAllReqs(array);
        //alert(array);
    }

    //


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
          //  console.log(array);

                $.post("{{ route('general.manager.restReqArray')}}", {
                    array: array,
                    _token: "{{csrf_token()}}",
                }, function(data) {
                    console.log(data);
                    var url = '{{ route("general.manager.archRequests") }}';

                    if (data != 0)
                    {
                        $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')}}");
                        window.location.href = url; //using a named route

                    }else
                        $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id, 'Try Again')}}");

                });



            } else {
                //reject
            }
        });



    };

    ///////////////////////////////////////////////



    function disabledButton() {

        if ($(':checkbox[name="chbx[]"]:checked').length > 0){
            document.getElementById("archAll").disabled = false;
            document.getElementById("archAll").style="";
        }
        else{
            document.getElementById("archAll").disabled = true;
            document.getElementById("archAll").style="cursor: not-allowed";
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
                    excelHtml5: "اكسل",
                    print: "طباعة",
                    pageLength: "عرض",

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
                // 'copyHtml5',
                'excelHtml5',
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
            scrollY:'50vh',
            processing: true,
            serverSide: true,
            ajax: {
                'url': "{{ url('generalmanager/archreqs-datatable') }}",
                'method': 'Get',
                'data': function(data) {

                    let agents_ids = $('#agents_ids').data('tokenize2').toArray();
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

                    if (agents_ids != '') data['agents_ids'] = agents_ids;

                     if (req_status != ''){

                        var contain = false;

                        contain = req_status.includes("3"); // because wating for sales manager is equal to 5 (archived in sales maanager)
                        if (contain)
                        req_status.push("5","18");//status of arachived request in sales manager,wating sales manager req mor-pur req


                        contain = false;
                        contain = req_status.includes("4"); // rejected sales manager req
                        if (contain)
                        req_status.push("20");//status of rejected sales manager req mor-pur req


                        contain = false;
                        contain = req_status.includes("7"); // rejected funding manager req
                        if (contain)
                        req_status.push("22");//status of rejected funding manager req mor-pur req


                        contain = false;
                        contain = req_status.includes("6"); // wating funding manager req
                        if (contain)
                        req_status.push("8","21");//archive in funding manager req,wating funding manager req mor-pur req


                        contain = false;
                        contain = req_status.includes("9"); // wating mortgage manager req
                        if (contain)
                        req_status.push("11","30");//archive in mortgage manager req, wating mortgage manager req

                        contain = false;
                        contain = req_status.includes("10"); // rejected mortgage manager req
                        if (contain)
                        req_status.push("31");//rejected mortgage manager req


                        contain = false;
                        contain = req_status.includes("13"); // rejected general manager req
                        if (contain)
                        req_status.push("25");//status of rejected general manager req mor-pur req


                        contain = false;
                        contain = req_status.includes("12"); // wating general manager req
                        if (contain)
                        req_status.push("14","23");//archive in general manager req,,wating general manager req mor-pur req


                        contain = false;
                        contain = req_status.includes("16"); // completed
                        if (contain)
                        req_status.push("26");//completed mor-pur req


                        contain = false;
                        contain = req_status.includes("15"); // Canceled
                        if (contain)
                        req_status.push("27");//Canceled mor-pur req



                        contain = false;
                        contain = req_status.includes("29"); // Rejected and archived
                        if (contain){
                        data['checkExisted'] ="29";
                        req_status.push("2");//archived in sales agent
                        req_status.splice( req_status.indexOf('29'), 1 );
                        }
                        else
                        data['checkExisted'] =null;


                        data['req_status'] = req_status;

                    }


                    if (pay_status != '') data['pay_status'] = pay_status;

                    if (reqTypes != '') data['reqTypes'] = reqTypes;

                    if (notes_status != '') {

                        var contain =false;
                        var empty =false;
                        contain = notes_status.includes("1"); // returns true
                        empty = notes_status.includes("0"); // returns true

                        if (contain && empty) // choose all optiones
                        notes_status=0;
                        else if (contain && !empty) // choose contain only
                        notes_status=1;
                        else if (!contain && empty) // choose empty only
                        notes_status=2;
                        else
                        notes_status=null;
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
                    data: 'user_name',
                    name: 'users.name'
                },
                {
                    data: 'cust_name',
                    name: 'customers.name'
                },
                {
                    data: 'source',
                    name: 'source'
                },
                {
                    data: 'class_id_sm',
                    name: 'class_id_sm'
                },
                {
                    data: 'gm_comment',
                    name: 'gm_comment'
                },
                {
                    data: 'comment',
                    name: 'comment'
                },
                {
                    data: 'quacomment',
                    name: 'quacomment'
                },
                /*
                {
                    data: 'sm_comment',
                    name: 'sm_comment',
                    "visible": false
                },
                {
                    data: 'fm_comment',
                    name: 'fm_comment',
                    "visible": false
                },
                {
                    data: 'mm_comment',
                    name: 'mm_comment',
                    "visible": false
                },
                {
                    data: 'noteWebsite',
                    name: 'noteWebsite',
                    "visible": false
                },
                {
                    data: 'class_id_sm',
                    name: 'class_id_sm',
                    "visible": false
                },
                {
                    data: 'class_id_fm',
                    name: 'class_id_fm',
                    "visible": false
                },
                {
                    data: 'class_id_mm',
                    name: 'class_id_mm',
                    "visible": false
                },
                {
                    data: 'class_id_quality',
                    name: 'class_id_quality',
                    "visible": false
                },
                  {
                    data: 'birth_date_higri',
                    name: 'customers.birth_date_higri',
                    "visible": false
                },
                {
                    data: 'salary',
                    name: 'customers.salary',
                    "visible": false
                },
                {
                    data: 'salary_id',
                    name: 'customers.salary_id',
                    "visible": false
                },
                {
                    data: 'work',
                    name: 'customers.work',
                    "visible": false
                },
                {
                    data: 'madany_id',
                    name: 'customers.madany_id',
                    "visible": false
                },
                {
                    data: 'military_rank',
                    name: 'customers.military_rank',
                    "visible": false
                },
                {
                    data: 'is_supported',
                    name: 'customers.is_supported',
                    "visible": false
                },
                {
                    data: 'has_obligations',
                    name: 'customers.has_obligations',
                    "visible": false
                },
                {
                    data: 'has_obligations',
                    name: 'customers.has_obligations',
                    "visible": false
                },
                {
                    data: 'empBank',
                    name: 'empBank',
                    "visible": false
                },
                {
                    data: 'reqNoBank',
                    name: 'reqNoBank',
                    "visible": false
                },
                {
                    data: 'joint_name',
                    name: 'joints.name',
                    "visible": false
                },
                {
                    data: 'joint_birth_date_higri',
                    name: 'joints.birth_date_higri',
                    "visible": false
                },
                {
                    data: 'joint_mobile',
                    name: 'joints.mobile',
                    "visible": false
                },
                {
                    data: 'joint_salary',
                    name: 'joints.salary',
                    "visible": false
                },
                {
                    data: 'joint_salary_id',
                    name: 'joints.salary_id',
                    "visible": false
                },
                {
                    data: 'joint_work',
                    name: 'joints.work',
                    "visible": false
                },
                {
                    data: 'joint_madany_id',
                    name: 'joints.madany_id',
                    "visible": false
                },
                {
                    data: 'joint_military_rank',
                    name: 'joints.military_rank',
                    "visible": false
                },
                {
                    data: 'real_name',
                    name: 'real_estats.name',
                    "visible": false
                },
                {
                    data: 'real_mobile',
                    name: 'real_estats.mobile',
                    "visible": false
                },
                {
                    data: 'real_age',
                    name: 'real_estats.age',
                    "visible": false
                },
                {
                    data: 'real_city',
                    name: 'real_estats.city',
                    "visible": false
                },
                {
                    data: 'region',
                    name: 'real_estats.region',
                    "visible": false
                },
                {
                    data: 'real_status',
                    name: 'real_estats.status',
                    "visible": false
                },
                {
                    data: 'real_cost',
                    name: 'real_estats.cost',
                    "visible": false
                },
                {
                    data: 'real_pursuit',
                    name: 'real_estats.pursuit',
                    "visible": false
                },
                {
                    data: 'real_type',
                    name: 'real_estats.type',
                    "visible": false
                },
                {
                    data: 'real_has_property',
                    name: 'real_estats.has_property',
                    "visible": false
                },
                {
                    data: 'real_evaluated',
                    name: 'real_estats.evaluated',
                    "visible": false
                },
                {
                    data: 'real_tenant',
                    name: 'real_estats.tenant',
                    "visible": false
                },
                {
                    data: 'real_mortgage',
                    name: 'real_estats.mortgage',
                    "visible": false
                },
                {
                    data: 'funding_funding_source',
                    name: 'fundings.funding_source',
                    "visible": false
                },
                {
                    data: 'funding_funding_duration',
                    name: 'fundings.funding_duration',
                    "visible": false
                },
                {
                    data: 'funding_personalFun_cost',
                    name: 'fundings.personalFun_cost',
                    "visible": false
                },
                {
                    data: 'funding_personalFun_pre',
                    name: 'fundings.personalFun_pre',
                    "visible": false
                },
                {
                    data: 'funding_realFun_cost',
                    name: 'fundings.realFun_cost',
                    "visible": false
                },
                {
                    data: 'funding_realFun_pre',
                    name: 'fundings.realFun_pre',
                    "visible": false
                },
                {
                    data: 'funding_ded_pre',
                    name: 'fundings.ded_pre',
                    "visible": false
                },
                {
                    data: 'funding_monthly_in',
                    name: 'fundings.monthly_in',
                    "visible": false
                },
                {
                    data: 'payStatus',
                    name: 'prepayments.payStatus',
                    "visible": false
                },
                {
                    data: 'realCost',
                    name: 'prepayments.realCost',
                    "visible": false
                },
                {
                    data: 'incValue',
                    name: 'prepayments.incValue',
                    "visible": false
                },
                {
                    data: 'prepaymentVal',
                    name: 'prepayments.prepaymentVal',
                    "visible": false
                },
                {
                    data: 'prepaymentPre',
                    name: 'prepayments.prepaymentPre',
                    "visible": false
                },
              /*
                {
                    data: 'prepaymentCos',
                    name: 'prepayments.prepaymentCos',
                    "visible": false
                },
                {
                    data: 'netCustomer',
                    name: 'prepayments.netCustomer',
                    "visible": false
                },


                {
                    data: 'visa',
                    name: 'prepayments.visa',
                    "visible": false
                },
                {
                    data: 'carLo',
                    name: 'prepayments.carLo',
                    "visible": false
                },

                {
                    data: 'personalLo',
                    name: 'prepayments.personalLo',
                    "visible": false
                },
                {
                    data: 'realLo',
                    name: 'prepayments.realLo',
                    "visible": false
                },
                {
                    data: 'credit',
                    name: 'prepayments.credit',
                    "visible": false
                },
                {
                    data: 'other',
                    name: 'prepayments.other',
                    "visible": false
                },
                {
                    data: 'debt',
                    name: 'prepayments.debt',
                    "visible": false
                },
                {
                    data: 'mortPre',
                    name: 'prepayments.mortPre',
                    "visible": false
                },
                {
                    data: 'mortCost',
                    name: 'prepayments.mortCost',
                    "visible": false
                },
                {
                    data: 'proftPre',
                    name: 'prepayments.proftPre',
                    "visible": false
                },
                {
                    data: 'profCost',
                    name: 'prepayments.profCost',
                    "visible": false
                },
                {
                    data: 'addedVal',
                    name: 'prepayments.addedVal',
                    "visible": false
                },
                {
                    data: 'adminFee',
                    name: 'prepayments.adminFee',
                    "visible": false
                },
                */
                {
                    data: 'action',
                    name: 'action'
                }
            ],
            initComplete: function() {
                let api = this.api();
                $("#filter-search-req").on('click', function(e) {
                    e.preventDefault();
                    api.draw();
                    //checktable(api);
                    $('#myModal').modal('hide');
                });

                $(".paginate_button").addClass("pagination-circle");
                /* To Adaptive with New Design */
                $('#example-search-input').keyup(function(){
                    dt.search($(this).val()).draw() ;
                })


                dt.buttons().container()
                    .appendTo( '#dt-btns' );

                $( ".dt-button" ).last().html('<i class="fas fa-search"></i>').attr('title','بحث') ;
                $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title','تصدير') ;
                $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title','طباعة') ;
                $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title','عرض') ;

                $('.buttons-excel').addClass('no-transition custom-btn');
                $('.buttons-print').addClass('no-transition custom-btn');
                $('.buttons-collection').addClass('no-transition custom-btn');

                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)

                /* To Adaptive with New Design */

            },
            "order": [[ 1, "desc" ]], // Order on init. # is the column, starting at 0
            createdRow: function(row, data, index) {

                $('td', row).eq(7).addClass('commentStyle'); // 6 is index of column
                $('td', row).eq(7).attr('title', data.gm_comment); // to show other text of comment

                $('td', row).eq(8).addClass('commentStyle'); // 6 is index of column
                $('td', row).eq(8).attr('title', data.comment); // to show other text of comment

                $('td', row).eq(9).addClass('commentStyle'); // 6 is index of column
                $('td', row).eq(9).attr('title', data.quacomment); // to show other text of comment

                $('td', row).eq(0).addClass('reqNum'); // 6 is index of column
                $('td', row).eq(1).addClass('reqNum'); // 6 is index of column
                $('td', row).eq(2).addClass('reqType'); // 6 is index of column
                $('td', row).eq(3).addClass('reqType'); // 6 is index of column
                $('td', row).eq(6).addClass('reqType'); // 6 is index of column
            },
        });
    });


    //



$(function() {
    $('#source').on('tokenize:tokens:add', function(e, value, text){



if (value == 2) {


document.getElementById("collaboratorDiv").style.display = "block";


}
});

$('#source').on('tokenize:tokens:remove', function(e, value){

    if (value == 2) {


        document.getElementById("collaboratorDiv").style.display = "none";
    document.getElementById("collaborator").value = "";

    }
    });

});


$(function() {
    $('#request_type').on('tokenize:tokens:add', function(e, value, text){



if (value == "شراء-دفعة") {


document.getElementById("paystatusDiv").style.display = "block";


}
});

$('#request_type').on('tokenize:tokens:remove', function(e, value){

    if (value == "شراء-دفعة") {


        document.getElementById("paystatusDiv").style.display = "none";
    document.getElementById("pay_status").value = "";

    }
    });

});


</script>
@endsection
