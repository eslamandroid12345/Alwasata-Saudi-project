@extends('layouts.content')


@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage Reports') }}
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
        font-weight: 600;
    }


    .textarea {
        width: 220px;
        height: 33px;
        margin: 0 auto;
        display: block;
        resize: none;
        border: solid 1px #e0ebeb;

    }

    .data-table-coulmn {
        text-align: center;
    }


    .reqdate {
        text-align: center;
    }

    .reqType {
        width: 2%;
    }

    .commentStyle {
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

<div id="msg" class="alert alert-success" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>

</div>

<div id="msg2" class="alert alert-warning" style="display:none;">
    <button type="button" class="alert-warning" data-dismiss="alert">&times;</button>

</div>

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
        <h3>{{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage Reports') }}:</h3>
    </div>
</div>
<br>


@if (!empty($requests))

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
                <div class="col-lg-4 text-md-right mt-lg-0 mt-3">

                    <div id="dt-btns" class="tableAdminOption">
                        {{--  Here We Will Add Buttons of Datatable  --}}

                    </div>

                </div>
            </div>
        </div>
        <div class="dashTable">
            <table class="table table-bordred table-striped data-table">
                <thead>
                <tr>

                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'mortgage cost') }}</th>
                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'prepay cost') }}</th>


                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'mobile') }}</th>


                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate type') }}</th>
                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'state') }}</th>


                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'owner_name') }}</th>
                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'owner mobile') }}</th>
                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'amount_of_check') }}</th>


                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</th>
                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</th>
                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</th>


                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'funding source') }}</th>

                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'bank employee') }}</th>
                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'bank order num') }}</th>


                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'recived date') }}</th>
                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'req classification') }}</th>
                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</th>



                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'salary source') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }} {{ MyHelpers::admin_trans(auth()->user()->id,'client') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'civilian_ministry') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'is supported') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'has obligations') }}</th>

                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'joint name') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }} <br> {{ MyHelpers::admin_trans(auth()->user()->id,'solidarity') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'joint mobile') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'joint salary') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'salary source') }} <br> {{ MyHelpers::admin_trans(auth()->user()->id,'solidarity') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }} <br> {{ MyHelpers::admin_trans(auth()->user()->id,'solidarity') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'civilian_ministry') }} <br> {{ MyHelpers::admin_trans(auth()->user()->id,'solidarity') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }} <br> {{ MyHelpers::admin_trans(auth()->user()->id,'solidarity') }} </th>

                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'property_age') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate status') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'is real estat evaluated?') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'are there tenants?') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'is real estat mortgaged?') }}</th>


                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'funding duration') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'cost personal funding') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'presentage') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'cost real estate funding') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'presentage') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'deduction presentage') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'monthly installment') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'pay status') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'increase value') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'prepayment value') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'prepayment %') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</th>

                    <th style="text-align:left;font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>




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
@include('MortgageManager.Request.filterReqs')
@include('MortgageManager.Request.editCoulmn')
@endsection


@section('scripts')



<script>
    //-----------------------------------


    function saveit(reqID) {

        var id = reqID; //to pass req id

        var empBank = $("#empBank" + reqID).text();
        // var reqNoBank = $("#reqNoBank" + reqID).val;
        var reqNoBank = $("#reqNoBank" + reqID).text();

        //alert(reqNoBank);



        $.ajax({
            url: "{{ URL('mortgageManager/updatebank') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                empBank: empBank,
                reqNoBank: reqNoBank,
            },
            success: function(data) {
                if (data == 0)
                    $('#msg2').removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                else
                    $('#msg').removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");



            },

        });
    }

    function savereal(reqID) {

        var id = reqID; //to pass req id

        var realname = $("#realname" + reqID).text();
        var realmobile = $("#realmobile" + reqID).text();


        $.ajax({
            url: "{{ URL('mortgageManager/updatereal') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                realname: realname,
                realmobile: realmobile,
            },
            success: function(data) {
                if (data == 0)
                    $('#msg2').removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                else
                    $('#msg').removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");



            },

        });
    }

    function savecost(reqID) {

        var id = reqID; //to pass req id

        var reqpre = $("#reqpre" + reqID).text();
        var reqmor = $("#reqmor" + reqID).text();


        $.ajax({
            url: "{{ URL('mortgageManager/updatecost') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                reqpre: reqpre,
                reqmor: reqmor,
            },
            success: function(data) {
                if (data == 0)
                    $('#msg2').removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                else
                    $('#msg').removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");



            },

        });
    }

    function savecomm(reqID) {

        var id = reqID; //to pass req id

        var reqComm = $("#reqComment" + reqID).val();


        $.ajax({
            url: "{{ URL('mortgageManager/updatecomm') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                reqComm: reqComm,
            },
            success: function(data) {
                if (data.status == 0)
                    $('#msg2').removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                else {
                    $('#msg').removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");
                    $("#reqComment" + reqID).attr('title', data.newComm);
                }

            },

        });
    }

    function saveclass(data, reqID) {

        var id = reqID; //to pass req id

        var reqClass = data.value;



        $.ajax({
            url: "{{ URL('mortgageManager/updateclass') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                reqClass: reqClass,
            },
            success: function(data) {
                if (data == 0)
                    $('#msg2').removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                else
                    $('#msg').removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");



            },

        });
    }

    function savecheck(reqID) {

        var id = reqID; //to pass req id

        var reqCheck = $("#realcost" + reqID).text();


        //console.log(reqCheck);

        $.ajax({
            url: "{{ URL('mortgageManager/updatecheck') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                reqCheck: reqCheck,
            },
            success: function(data) {

                console.log(data);
                if (data == 0)
                    $('#msg2').removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                else
                    $('#msg').removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");

            },

        });
    }


    function saverealcity(data, reqID) {

        var id = reqID; //to pass req id

        var realcity = data.value;

        //console.log(funSour);


        $.ajax({
            url: "{{ URL('mortgageManager/updaterealcity') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                realcity: realcity,
            },
            success: function(data) {
                //    console.log(data);
                if (data == 0)
                    $('#msg2').removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                else
                    $('#msg').removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");



            },

        });
    }


    function saverealType(data, reqID) {

        var id = reqID; //to pass req id

        var reqType = data.value;



        $.ajax({
            url: "{{ URL('mortgageManager/updaterealType') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                reqType: reqType,
            },
            success: function(data) {
                if (data == 0)
                    $('#msg2').removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                else
                    $('#msg').removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");



            },

        });
    }


    function savefunSour(data, reqID) {

        var id = reqID; //to pass req id

        var funSour = data.value;

        //console.log(funSour);


        $.ajax({
            url: "{{ URL('mortgageManager/updatefunsour') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                funSour: funSour,
            },
            success: function(data) {
                //    console.log(data);
                if (data == 0)
                    $('#msg2').removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                else
                    $('#msg').removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");



            },

        });
    }
</script>




<script src="{{ asset('js/tokenize2.min.js') }}"></script>

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

      var buttonCommon = {
            exportOptions: {
                columns: ':visible', // only visible coumns will extracted
                format: {
                    body: function (data, column, row, node) {

                        if ((data).substring(0,7) == '<select')
                            return $(data).find("option:selected").text();
                        else if ((data).substring(0,2) == '<p' || (data).substring(0,9) == '<textarea')
                        return $(data).text();
                        else if ((data).substring(0,32) == '<div class="table-data-feature">')
                        return '';
                        else
                        return data;

                    }
                }
            }
        };

    $(document).ready(function() {
        var dt = $('.data-table').DataTable({
            "language": {
                "url": "{{route('datatableLanguage')}}",
                buttons: {
                    // print: "طباعة",
                    pageLength: "عرض",
                    excel: "إكسل",

                }
            },
            "lengthMenu": [
                [10, 25, 50],
                [10, 25, 50]
            ],
            dom: 'Bfrtip',
            buttons: [
                // 'copyHtml5',
               // 'excelHtml5',
                // 'csvHtml5',
                // 'pdfHtml5' ,
                //'print',
                $.extend(true, {}, buttonCommon, {
                    extend: "excel"
                }),
                {
                    text: '{{ MyHelpers::admin_trans(auth()->user()->id,"Edit Columns") }}',
                    action: function(e, dt, node, config) {
                        $('#myModal1').modal('show');
                    }
                },
                'pageLength',
                {
                    text: '{{ MyHelpers::admin_trans(auth()->user()->id,"Search") }}',
                    action: function(e, dt, node, config) {
                        $('#myModal').modal('show');
                    }
                }
            ],
            //scrollY:'50vh',
            processing: true,
            serverSide: true,
            ajax: ({
                'url': "{{ url('mortgageManager/underpage-datatable') }}",
                'method': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                'data': function(data) {
                    let customer_ids = $('#customer_ids').data('tokenize2').toArray();
                    let agents_ids = $('#agents_ids').data('tokenize2').toArray();
                    let reqTypes = $("#request_type").data('tokenize2').toArray();
                    let customer_salary = $('#customer-salary').val();
                    let customer_phone = $('#customer-phone').val();
                    let bank_employee = $('#bank-employee').val();
                    let customer_birth = $('#customer-birth').val();
                    let req_date_from = $('#request-date-from').val();
                    let req_date_to = $('#request-date-to').val();
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


                    if (customer_birth != '') data['customer_birth'] = customer_birth;

                    if (customer_salary != '') data['customer_salary'] = customer_salary;
                    if (customer_phone != '') data['customer_phone'] = customer_phone;
                    if (bank_employee != '') data['bank_employee'] = bank_employee;

                    if (source != '') data['source'] = source;
                    if (collaborator != '') data['collaborator'] = collaborator;
                    if (work_source != '') data['work_source'] = work_source;
                    if (salary_source != '') data['salary_source'] = salary_source;
                    if (founding_sources != '') data['founding_sources'] = founding_sources;

                    if (customer_ids != '') data['customer_ids'] = customer_ids;

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

                    // console.log(customer_phone);

                    xses.forEach(function(item) {
                        if (getClassifcationX(item) != '') {
                            data['class_id_' + item] = getClassifcationX(item)
                        }
                    })
                },
            }),
            columns: [

                {
                    data: 'mortCost',
                    name: 'prepayments.mortCost',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'mortgage_cost')->count() > 0 || $editCoulmns-> count() == 0) ? 'true': 'false' }}

                },
                {
                    data: 'profCost',
                    name: 'prepayments.profCost',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'profit_cost')->count() > 0 || $editCoulmns-> count() == 0) ? 'true': 'false' }}

                },
                {
                    data: 'cust_name',
                    name: 'customers.name',
                    className: "data-table-coulmn",
                    visible:{{($editCoulmns->where('coulmnName', 'Customer')->count() > 0 || $editCoulmns-> count() == 0) ? 'true': 'false' }}

                },
                {
                    data: 'mobile',
                    name: 'customers.mobile',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'mobile')->count() > 0 || $editCoulmns-> count() == 0) ? 'true': 'false' }}

                },
                {
                    data: 'realtype',
                    name: 'real_estats.type',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'real_estate_type')->count() > 0 || $editCoulmns-> count() == 0) ? 'true': 'false' }}

                },
                {
                    data: 'realcity',
                    name: 'real_estats.city',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'state')->count() > 0 || $editCoulmns-> count() == 0) ? 'true': 'false' }}

                },
                {
                    data: 'realname',
                    name: 'real_estats.name',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'owner_name')->count() > 0 || $editCoulmns-> count() == 0) ? 'true': 'false' }}

                },
                {
                    data: 'realmobile',
                    name: 'real_estats.mobile',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'owner_mobile')->count() > 0 || $editCoulmns-> count() == 0) ? 'true': 'false' }}

                },
                {
                    data: 'realcost',
                    name: 'real_estats.cost',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'real_cost')->count() > 0 || $editCoulmns-> count() == 0) ? 'true': 'false' }}

                },
                {
                    data: 'user_name',
                    name: 'users.name',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'Sales_Agent')->count() > 0 || $editCoulmns-> count() == 0) ? 'true': 'false' }}

                },
                {
                    data: 'source',
                    name: 'source',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'req_source')->count() > 0 || $editCoulmns-> count() == 0) ? 'true': 'false' }}

                },
                {
                    data: 'type',
                    name: 'type',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'type')->count() > 0 || $editCoulmns-> count() == 0) ? 'true': 'false' }}

                },
                {
                    data: 'funding_source',
                    name: 'fundings.funding_source',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'funding_source')->count() > 0 || $editCoulmns-> count() == 0) ? 'true': 'false' }}

                },
                {
                    data: 'empBank',
                    name: 'empBank',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'bank_employee')->count() > 0 || $editCoulmns-> count() == 0) ? 'true': 'false' }}

                },
                {
                    data: 'reqNoBank',
                    name: 'reqNoBank',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'bank_order_num')->count() > 0 || $editCoulmns-> count() == 0) ? 'true': 'false' }}

                },
                {
                    data: 'recived_date_report_mor',
                    name: 'recived_date_report_mor',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'recived_date')->count() > 0 || $editCoulmns-> count() == 0) ? 'true': 'false' }}

                },
                {
                    data: 'class_id_mm',
                    name: 'class_id_mm',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'req_classification')->count() > 0 || $editCoulmns-> count() == 0) ? 'true': 'false' }}

                },
                {
                    data: 'mm_comment',
                    name: 'mm_comment',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'comment')->count() > 0 || $editCoulmns-> count() == 0) ? 'true': 'false' }}

                },
                {
                    data: 'salary_id',
                    name: 'customers.salary_id',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'salary_source')->count() > 0) ? 'true': 'false' }}

                },
                 {
                    data: 'birth_date_higri',
                    name: 'customers.birth_date_higri',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'birth_date')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'salary',
                    name: 'customers.salary',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'salary')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'work',
                    name: 'customers.work',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'work')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'madany_id',
                    name: 'customers.madany_id',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'madany_id')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'military_rank',
                    name: 'customers.military_rank',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'military_rank')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'is_supported',
                    name: 'customers.is_supported',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'is_supported')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'has_obligations',
                    name: 'customers.has_obligations',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'has_obligations')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'joint_name',
                    name: 'joints.name',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'joint_name')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'joint_birth_date_higri',
                    name: 'joints.birth_date_higri',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'joint_birth_date_higri')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'joint_mobile',
                    name: 'joints.mobile',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'joint_mobile')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'joint_salary',
                    name: 'joints.salary',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'joint_salary')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'joint_salary_id',
                    name: 'joints.salary_id',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'joint_salary_id')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'joint_work',
                    name: 'joints.work',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'joint_work')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'joint_madany_id',
                    name: 'joints.madany_id',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'joint_madany_id')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'joint_military_rank',
                    name: 'joints.military_rank',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'joint_military_rank')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'real_age',
                    name: 'real_estats.age',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'real_age')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'real_status',
                    name: 'real_estats.status',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'real_status')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'real_evaluated',
                    name: 'real_estats.evaluated',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'real_evaluated')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'real_tenant',
                    name: 'real_estats.tenant',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'real_tenant')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'real_mortgage',
                    name: 'real_estats.mortgage',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'real_mortgage')->count() > 0) ? 'true': 'false' }}

                },

                {
                    data: 'funding_funding_duration',
                    name: 'fundings.funding_duration',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'funding_funding_duration')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'funding_personalFun_cost',
                    name: 'fundings.personalFun_cost',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'funding_personalFun_cost')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'funding_personalFun_pre',
                    name: 'fundings.personalFun_pre',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'funding_personalFun_pre')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'funding_realFun_cost',
                    name: 'fundings.realFun_cost',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'funding_realFun_cost')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'funding_realFun_pre',
                    name: 'fundings.realFun_pre',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'funding_realFun_pre')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'funding_ded_pre',
                    name: 'fundings.ded_pre',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'funding_ded_pre')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'funding_monthly_in',
                    name: 'fundings.monthly_in',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'funding_monthly_in')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'payStatus',
                    name: 'prepayments.payStatus',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'payStatus')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'incValue',
                    name: 'prepayments.incValue',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'incValue')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'prepaymentVal',
                    name: 'prepayments.prepaymentVal',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'prepaymentVal')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'prepaymentPre',
                    name: 'prepayments.prepaymentPre',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'prepaymentPre')->count() > 0) ? 'true': 'false' }}

                },
                {
                    data: 'prepaymentCos',
                    name: 'prepayments.prepaymentCos',
                    className: "data-table-coulmn" ,
                    visible:{{($editCoulmns->where('coulmnName', 'prepaymentCos')->count() > 0) ? 'true': 'false' }}

                },
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
                    $('#myModal').modal('hide');
                });

                $("#frm-update_coulmn").on('submit', function(e) {


                    document.getElementById("error").display = "none";

                    $('#filter-edit-column').attr("disabled", true);
                    document.querySelector('#filter-edit-column').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Please Wait') }}";


                    e.preventDefault();
                    var data = $(this).serialize();
                    var url = $(this).attr('action');

                    $.post(url, data, function(data) { //data is array with two veribles (request[], ss)

                        //console.log(data);
                        document.querySelector('#filter-edit-column').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Edit') }}";
                        $('#filter-edit-column').attr("disabled", false);
                        $('#myModal1').modal('hide');

                        $('#msg2').addClass("alert-info").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);


                        if (data.visibleCoulmns != null){
                            dt.columns(  '.data-table-coulmn' ).visible( false );
                            dt.columns( data.visibleCoulmns ).visible( true );
                        }




                    }).fail(function(jqXHR, textStatus, errorThrown) {

                        document.querySelector('#filter-edit-column').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Edit') }}";
                        $('#filter-edit-column').attr("disabled", false);

                        document.getElementById("error").innerHTML = "<span style='color:red;'>حاول مرة أخرى</span>";
                        document.getElementById("error").display = "block";


                    });



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

            createdRow: function(row, data, index) {},
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


    $(function() {

$("#select_all").change(function(){  //"select all" change

    $("input[type=checkbox]").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status

});


//".checkbox" change
$("input[type=checkbox]").change(function(){

	//uncheck "select all", if one of the listed checkbox item is unchecked
    if(false == $(this).prop("checked")){ //if this item is unchecked
        $("#select_all").prop('checked', false); //change "select all" checked status to false
    }
	//check "select all" if all checkbox items are checked
	if ($('input[type="checkbox"]:checked').length == $("input[type=checkbox]").length ){
        $("#select_all").prop('checked', true);

	}
});


    });

</script>
@endsection
