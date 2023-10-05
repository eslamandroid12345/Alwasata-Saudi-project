@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Accounting Report') }} {{ MyHelpers::admin_trans(auth()->user()->id,'to wsata') }} - مفرغة
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

    .data-table-coulmn {
        text-align: center;
    }

    .textarea {
        width: 220px;
        height: 33px;
        margin: 0 auto;
        display: block;
        resize: none;
        border: solid 1px #e0ebeb;

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
        <h3>{{ MyHelpers::admin_trans(auth()->user()->id,'Accounting Report') }} {{ MyHelpers::admin_trans(auth()->user()->id,'to wsata') }}  - مفرغة :</h3>
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
            <table id="" class="table table-bordred table-striped data-table">
                <thead>
                <tr>
                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'Request_id') }}</th>
                    <th style=" font-weight: 400; font-size:medium;text-align:center;">{{ MyHelpers::admin_trans(auth()->user()->id,'Date') }}</th>

                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'customer mobile') }}</th>

                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'funding source') }}</th>
                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'service_type') }}</th>

                    <th style=" font-weight: 400; font-size:medium;text-align: center;">{{ MyHelpers::admin_trans(auth()->user()->id,'payment cost') }}</th>
                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'property_type') }}</th>
                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'property_value') }}</th>

                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'amount_of_quest') }}</th>
                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'value added') }}</th>
                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'assment fees') }}</th>
                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'collobreator cost') }}</th>
                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'net') }}</th>

                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'collaborator name') }}</th>

                    <th style=" font-weight: 400; font-size:medium">{{ MyHelpers::admin_trans(auth()->user()->id,'sales_agent') }}</th>
                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'notes') }}</th>

                    <th style=" font-weight: 400; font-size:medium;text-align: center;">رهن الوساطة</th>

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

    function updateNet(reqID) {

        var id = reqID; //to pass req id

        $.ajax({
            url: "{{ URL('report/updateNet') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
            },
            success: function(data) {

                if (data.status == 1) {
                    $('#net' + id).text(data.newData.toFixed(2));
                    $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");

                  }

            },

        });
    }

    function saveit(reqID) {

        var id = reqID; //to pass req id

        var value_added = $("#value_added" + reqID).text();

        $.ajax({
            url: "{{ URL('report/updatevalueAdded') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                value_added: value_added,
            },
            success: function(data) {
                if (data == 0)
                $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                  else
                  $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");



            },

        });
    }

    function savepursuit(reqID) {
        var id = reqID; //to pass req id
        var pursuit = $("#pursuit" + reqID).text();
        $.ajax({
            url: "{{ URL('report/updatepursuit') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                pursuit: pursuit,
            },
            success: function(data) {
                if (data == 0)
                $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                  else
                  $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");



            },

        });
    }



    function saveassment(reqID) {

        var id = reqID; //to pass req id

        var assment_fees = $("#assment_fees" + reqID).text();

        $.ajax({
            url: "{{ URL('report/updateassmentFees') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                assment_fees: assment_fees,
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

    function savecollcost(reqID) {

        var id = reqID; //to pass req id

        var collobreator_cost = $("#collobreator_cost" + reqID).text();

        $.ajax({
            url: "{{ URL('report/updatecollobreatorCost') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                collobreator_cost: collobreator_cost,
            },
            success: function(data) {
                if (data == 0)
                $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                  else
                  $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");



            },

        });
    }


    function savecomm(reqID) {

        var id = reqID; //to pass req id

        var reqComm = $("#reqComment" + reqID).val();

        // alert(reqComm);


        $.ajax({
            url: "{{ URL('report/updatecomm') }}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                id: id,
                reqComm: reqComm,
            },
            success: function(data) {
                //    console.log(data);
                if (data.status == 0)
                $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Nothing Change')}}");
                 else {
                    $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id,'Update Successfully')}}");
                   $("#reqComment" + reqID).attr('title', data.newComm);
                }


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
                'url': "{{ url('report/wsataAccountingReport_datatable') }}",
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
                    "targets": 0,
                    "data": "created_at",
                    "name": 'requests.created_at',
                    "render": function(data, type, row, meta) {
                        return data.split(" ").join("<br/>");
                    }
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
                    data: 'funding_source',
                    name: 'fundings.funding_source',
                    className: "data-table-coulmn",

                },
                {
                    data: 'type',
                    name: 'type',
                    className: "data-table-coulmn",

                },
                {
                    data: 'mortCost',
                    name: 'prepayments.mortCost',
                    className: "data-table-coulmn2",

                },
                {
                    data: 'realtype',
                    name: 'real_estats.type',
                    className: "data-table-coulmn",
                },
                {
                    data: 'real_cost',
                    name: 'real_estats.cost',
                    className: "data-table-coulmn",
                },
                {
                    data: 'pursuit',
                    name: 'real_estats.pursuit',
                    className: "data-table-coulmn",
                },
                {
                    data: 'value_added',
                    name: 'real_estats.value_added',
                    className: "data-table-coulmn",
                },
                {
                    data: 'assment_fees',
                    name: 'real_estats.assment_fees',
                    className: "data-table-coulmn",
                },
                {
                    data: 'collobreator_cost',
                    name: 'real_estats.collobreator_cost',
                    className: "data-table-coulmn",
                },
                {
                    data: 'net',
                    name: 'real_estats.net',
                    className: "data-table-coulmn",
                },
                {
                    data: 'collaborator_id',
                    name: 'requests.collaborator_id',
                    className: "data-table-coulmn",
                },
                {
                    data: 'user_name',
                    name: 'users.name',
                    className: "data-table-coulmn",
                },
                {
                    data: 'accountcomment',
                    name: 'requests.accountcomment',
                    className: "data-table-coulmn",
                },
                {
                    data: 'mortgage_value',
                    name: 'real_estats.mortgage_value',
                    className: "data-table-coulmn2",
                },
                {
                    data: 'is_approved_by_wsata_acc',
                    name: 'requests.is_approved_by_wsata_acc',
                    className: "data-table-coulmn",
                },
                {
                    data: 'is_approved_by_tsaheel_acc',
                    name: 'requests.is_approved_by_tsaheel_acc',
                    className: "data-table-coulmn",
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
                $('td', row).eq(1).addClass('data-table-coulmn');


                if ({{auth()->user()->role}} == 4)
                    if (data.is_approved_by_tsaheel_acc == 0 || data.is_approved_by_wsata_acc == 0)
                        $(row).addClass('rejected');
            },
        });
    });


    //
</script>
@endsection
