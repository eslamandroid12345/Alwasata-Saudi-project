@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Accounting Report') }} {{ MyHelpers::admin_trans(auth()->user()->id,'to tsaheel') }}  - مفرغة
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
        text-align: center;
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
</style>

{{--    NEW STYLE   --}}
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


<div class="addUser my-4">
    <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
        <h3>{{ MyHelpers::admin_trans(auth()->user()->id,'Accounting Report') }} {{ MyHelpers::admin_trans(auth()->user()->id,'to tsaheel') }}  - مفرغة :</h3>
    </div>
</div>


@if ($requests >0)
    <div class="tableBar">
        <div class="topRow">
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

        <div class="dashTable">
            <table id="tsaheelTable" class="table table-bordred table-striped data-table">
                <thead>
                <tr>
                    <th style=" font-weight: 400; font-size:medium;text-align: center;">{{ MyHelpers::admin_trans(auth()->user()->id,'Request_id') }}</th>

                    <th style=" font-weight: 400; font-size:medium;text-align: center;">{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>

                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'customer mobile') }}</th>

                    <th style=" font-weight: 400; font-size:medium;text-align: center;">{{ MyHelpers::admin_trans(auth()->user()->id,'payment cost') }}</th>

                    <th style=" font-weight: 400; font-size:medium;text-align: center;">رهن تساهيل</th>
                    <th style=" font-weight: 400; font-size:medium;text-align: center;">رهن الوساطة</th>

                    <th style=" font-weight: 400; font-size:medium;text-align: center;">{{ MyHelpers::admin_trans(auth()->user()->id,'start date of request') }}</th>
                    <th style=" font-weight: 400; font-size:medium;text-align: center;">{{ MyHelpers::admin_trans(auth()->user()->id,'to date') }}</th>
                    <th style=" font-weight: 400; font-size:medium;text-align: center;">{{ MyHelpers::admin_trans(auth()->user()->id,'online_time') }}</th>


                    <th style=" font-weight: 400; font-size:medium;text-align: center;">طبيعة المعاملة</th>


                    {{--

                    <th style=" font-weight: 400; font-size:medium;text-align: center;">{{ MyHelpers::admin_trans(auth()->user()->id,'agreement cost') }}</th>
                    <th style=" font-weight: 400; font-size:medium;text-align: center;">{{ MyHelpers::admin_trans(auth()->user()->id,'request profit') }}</th>




                    <th style=" font-weight: 400; font-size:medium;text-align: center;">{{ MyHelpers::admin_trans(auth()->user()->id,'Marckting Company') }}</th>
                    <th style=" font-weight: 400; font-size:medium;text-align: center;">{{ MyHelpers::admin_trans(auth()->user()->id,'Funder') }}</th>

                    <th style=" font-weight: 400; font-size:medium;text-align: center;">{{ MyHelpers::admin_trans(auth()->user()->id,'request status') }}</th>
                    <th style=" font-weight: 400; font-size:medium;text-align: center;">{{ MyHelpers::admin_trans(auth()->user()->id,'request type') }}</th>

                    <th style=" font-weight: 400; font-size:medium;text-align: center;">{{ MyHelpers::admin_trans(auth()->user()->id,'Profit Presentage') }}</th>

                    <th style=" font-weight: 400; font-size:medium;text-align: center;">{{ MyHelpers::admin_trans(auth()->user()->id,'Marckter') }}</th>


                    --}}

                    <th style=" font-weight: 400; font-size:medium;text-align:center">اعتماد محاسب <br> الوساطة</th>
                    <th style=" font-weight: 400; font-size:medium;text-align:center">اعتماد محاسب <br> تساهيل</th>


                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>


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
@include('Accountant.Reports.filterReqs')
@endsection


@section('scripts')



<script>
    //-----------------------------------


    //----------------------------



    function saveProfit(reqID) {

        var id = reqID; //to pass req id

        var request_profit = $("#request_profit" + reqID).text();

        $.ajax({
            url: "{{ URL('report/updaterequestProfit') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                request_profit: request_profit,
            },
            success: function(data) {
                if (data == 0)
                    $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                else
                    $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");



            },

        });
    }

    function savecustname(reqID) {

        var id = reqID; //to pass req id

        var cust_name = $("#cust_name" + reqID).text();

        $.ajax({
            url: "{{ URL('report/updatecustName') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                cust_name: cust_name,
            },
            success: function(data) {
                if (data == 0)
                    $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                else
                    $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");



            },

        });
    }

    function saveAgreement(reqID) {

        var id = reqID; //to pass req id

        var agreement_cost = $("#agreement_cost" + reqID).text();

        $.ajax({
            url: "{{ URL('report/updateagreementCost') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                agreement_cost: agreement_cost,
            },
            success: function(data) {
                if (data == 0)
                    $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                else
                    $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");



            },

        });
    }

    function saveAccountStatus(reqID) {

        var id = reqID; //to pass req id

        var account_status = $("#account_status" + reqID).text();

        $.ajax({
            url: "{{ URL('report/updateAccountStatus') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                account_status: account_status,
            },
            success: function(data) {
                if (data == 0)
                    $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                else
                    $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");



            },

        });
    }

    function saveMarkter(reqID) {

        var id = reqID; //to pass req id

        var markter = $("#markter" + reqID).text();

        $.ajax({
            url: "{{ URL('report/updateMarkter') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                markter: markter,
            },
            success: function(data) {
                if (data == 0)
                    $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                else
                    $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");


            },

        });
    }

    function saveStartDate(reqID) {

var id = reqID; //to pass req id

var recived_date_report_mor = $("#recived_date_report_mor" + reqID).val();

$.ajax({
    url: "{{ URL('report/updatestartdate') }}",
    type: "POST",
    data: {
        "_token": "{{csrf_token()}}",
        id: id,
        recived_date_report_mor: recived_date_report_mor,
    },
    success: function(data) {
        if (data.request == 0)
            $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
        else{

            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");
            $("#counter_report_mor" + reqID).text(data.counter+ ' يوم ');
        }

    },

});
}

function saveEndDate(reqID) {

var id = reqID; //to pass req id

var to_date = $("#to_date" + reqID).val();

$.ajax({
    url: "{{ URL('report/updateenddate') }}",
    type: "POST",
    data: {
        "_token": "{{csrf_token()}}",
        id: id,
        to_date: to_date,
    },
    success: function(data) {
        if (data.request == 0)
            $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
        else{

            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");
            $("#counter_report_mor" + reqID).text(data.counter+ ' يوم ');
        }


    },

});
}
    function saveFunder(reqID) {

        var id = reqID; //to pass req id

        var funder = $("#funder" + reqID).text();

        $.ajax({
            url: "{{ URL('report/updateFunder') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                funder: funder,
            },
            success: function(data) {
                if (data == 0)
                    $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                else
                    $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");


            },

        });
    }

    function saveProfitPres(reqID) {

        var id = reqID; //to pass req id

        var account_profit_presntage = $("#account_profit_presntage" + reqID).text();

        $.ajax({
            url: "{{ URL('report/updateAccountProfitPresentage') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                account_profit_presntage: account_profit_presntage,
            },
            success: function(data) {
                if (data == 0)
                    $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                else
                    $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");



            },

        });
    }

    function savemortCost(reqID) {

        var id = reqID; //to pass req id

        var mortCost = $("#mortCost" + reqID).text();

        $.ajax({
            url: "{{ URL('report/updatemortCost') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                mortCost: mortCost,
            },
            success: function(data) {
                if (data == 0)
                    $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                else
                    $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");




            },

        });
    }

    function saveWsataMortgage(reqID) {

        var id = reqID; //to pass req id

        var tsaheel_mortgage_value = $("#tsaheel_mortgage_value" + reqID).text();

        $.ajax({
            url: "{{ URL('report/updateTsaheelMortgageValue') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                tsaheel_mortgage_value : tsaheel_mortgage_value,
            },
            success: function(data) {
                if (data == 0)
                    $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                else
                    $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");

            },

        });
    }

    function saveMortgageValue(reqID) {

var id = reqID; //to pass req id

var mortgage_value = $("#mortgage_value" + reqID).text();

$.ajax({
    url: "{{ URL('report/updateMortgageValue') }}",
    type: "POST",
    data: {
        "_token": "{{csrf_token()}}",
        id: id,
        mortgage_value: mortgage_value,
    },
    success: function(data) {
        if (data == 0)
            $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
        else
            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");

    },

});
}


function savenatureRequest(data, reqID) {

var id = reqID; //to pass req id

var natureRequest = data.value;


$.ajax({
    url: "{{ URL('report/updatenatureRequest') }}",
    type: "POST",
    data: {
        "_token": "{{csrf_token()}}",
        id: id,
        natureRequest: natureRequest,
    },
    success: function(data) {
        if (data.status == 0)
            $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                else
                    $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");

    },

});
}

    function saveMarktingCompany(reqID) {

        var id = reqID; //to pass req id

        var marckting_company = $("#marckting_company" + reqID).text();

        $.ajax({
            url: "{{ URL('report/updatemarktingCompany') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                marckting_company: marckting_company,
            },
            success: function(data) {
                if (data == 0)
                    $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                else
                    $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");


            },

        });
    }
</script>


<script src="{{ asset('js/tokenize2.min.js') }}"></script>

<script src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js"></script>

<script>
    $('.tokenizeable').tokenize2();
    $(".tokenizeable").on("tokenize:select", function() {
        $(this).trigger('tokenize:search', "");
    });



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
                    excel: "إكسل",

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
            processing: true,
            serverSide: true,
            ajax: {
                'url': "{{ url('report/tsaheelAccountingReport-datatable') }}",
                'data': function(data) {
                    let agents_ids = $('#agents_ids').data('tokenize2').toArray();
                    let customer_phone = $('#customer-phone').val();
                    let req_date_from = $('#request-date-from').val();
                    let req_date_to = $('#request-date-to').val();
                    let collaborator = $('#collaborator').data('tokenize2').toArray();
                    let founding_sources = $('#founding_sources').data('tokenize2').toArray();
                    let notes_status = $('#notes_status').data('tokenize2').toArray();


                    if (req_date_from != '') {
                        data['req_date_from'] = req_date_from;
                    }
                    if (req_date_to != '') {
                        data['req_date_to'] = req_date_to;
                    }


                    if (customer_phone != '') data['customer_phone'] = customer_phone;
                    if (collaborator != '') data['collaborator'] = collaborator;
                    if (founding_sources != '') data['founding_sources'] = founding_sources;

                    if (agents_ids != '') data['agents_ids'] = agents_ids;


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
            },
            columns: [

                {
                    data: 'id',
                    name: 'requests.id',
                    className: "data-table-coulmn",
                },
                {
                    data: 'cust_name',
                    name: 'customers.name',
                    className: "data-table-coulmn",
                },

                {
                    data: 'mobile',
                    name: 'customers.mobile',
                    className: "data-table-coulmn",
                },
                {
                    data: 'mortCost',
                    name: 'prepayments.mortCost',
                    className: "data-table-coulmn2",

                },
                {
                    data: 'tsaheel_mortgage_value',
                    name: 'real_estats.tsaheel_mortgage_value',
                    className: "data-table-coulmn2",
                },
                {
                    data: 'mortgage_value',
                    name: 'real_estats.mortgage_value',
                    className: "data-table-coulmn2",
                },
                {
                    data: 'recived_date_report_mor',
                    name: 'recived_date_report_mor',
                    className: "data-table-coulmn",

                },
                {
                    data: 'to_date',
                    name: 'to_date',
                    className: "data-table-coulmn",

                },
                {
                    data: 'counter_report_mor',
                    name: 'counter_report_mor',
                    className: "data-table-coulmn",

                },
                {
                    data: 'natureRequest',
                    name: 'natureRequest',
                    className: "data-table-coulmn",

                },
                /*
                {
                    data: 'agreement_cost',
                    name: 'prepayments.agreement_cost',
                    className: "data-table-coulmn2",

                },
                {
                    data: 'request_profit',
                    name: 'prepayments.request_profit',
                    className: "data-table-coulmn2",

                },

                {
                    data: 'marckting_company',
                    name: 'marckting_company',
                    className: "data-table-coulmn2",

                },
                {
                    data: 'funder',
                    name: 'funder',
                    className: "data-table-coulmn2",

                },
                {
                    data: 'account_status',
                    name: 'prepayments.account_status',
                    className: "data-table-coulmn2",

                },
                {
                    data: 'type',
                    name: 'type',
                    className: "data-table-coulmn2",

                },
                {
                    data: 'account_profit_presntage',
                    name: 'prepayments.account_profit_presntage',
                    className: "data-table-coulmn2",

                },
                {
                    data: 'markter',
                    name: 'requests.markter',
                    className: "data-table-coulmn2",
                },
                */
                {
                    data: 'is_approved_by_wsata_acc',
                    name: 'requests.is_approved_by_wsata_acc',
                    className: "data-table-coulmn2",
                },
                {
                    data: 'is_approved_by_tsaheel_acc',
                    name: 'requests.is_approved_by_tsaheel_acc',
                    className: "data-table-coulmn2",
                },
                {
                    data: 'action',
                    name: 'action',
                    className: "data-table-coulmn",
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
            },
            "order": [
                [0, "desc"]
            ], // Order on init. # is the column, starting at 0
            createdRow: function(row, data, index) {
                $('td', row).eq(1).addClass('reqdate');
                if ({{auth()-> user()->role}} == 4)
                    if (data.is_approved_by_tsaheel_acc == 0 || data.is_approved_by_wsata_acc == 0)
                        $(row).addClass('rejected');
            },
        });
    });


    //
</script>
@endsection
