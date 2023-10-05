@extends('layouts.content')


@section('title')
طلبات قائمة الإنتظار - تمت معالجتها
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
        <h3>طلبات قائمة الإنتظار - تمت معالجتها:</h3>

    </div>
</div>

<br>

    @if ($requests>0)

    <div class="tableBar">
        <div class="topRow">
            <div class="row align-items-center text-center text-md-left">

                <div class="col-lg-9">
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

                        <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'req date') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req status') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'mobile') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req classification agent') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</th>

                        <th style="text-align:center">العملية</th>


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
@include('Admin.Request.moveReq')
@include('Admin.Request.confirmArchMsg')
@endsection

@section('scripts')

<script>
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
        //  alert(array);
    }

    //

    //-----------------------------------------------


    $(document).on('click', '#move', function(e) {



        document.getElementById("salesagent").value = '';
        document.getElementById('salesagentsError').innerHTML = '';

        var id = $(this).attr('data-id');
        var needID = $(this).attr('data-need');

        document.getElementById("movedReqID").value = id;
        document.getElementById("needReqID").value = needID;

        $('#mi-modal7').modal('show');


    });



    //-----------------------------------------------

    $(document).on('click', '#submitMove', function(e) {



        $('#submitMove').attr("disabled", true);
        document.querySelector('#submitMove').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Please Wait') }}";

        var salesAgent = document.getElementById("salesagent").value;

        var id = document.getElementById("movedReqID").value;
        var needID = document.getElementById("needReqID").value;



        var url = "{{ route('admin.moveReqToAnother')}}";
        var need_url = "{{ route('admin.updateWaitingReq')}}";


        if (salesAgent != '') {


            $.get(need_url, {
                needID: needID
            }, function(data) {
                console.log(data);
            })

            $.get(url, {
                salesAgent: salesAgent,
                id: id
            }, function(data) { //data is array with two veribles (request[], ss)

                if (data.updatereq == 1) {

                    $('.data-table').DataTable().ajax.reload();

                    $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

                }


                $('#mi-modal7').modal('hide');



            })

        } else
            document.getElementById('salesagentsError').innerHTML = 'الرجاء اختيار استشاري';
        document.querySelector('#submitMove').innerHTML = "تحويل";
        $('#submitMove').attr("disabled", false);


    });
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

                $.post("{{ route('admin.archReqArray')}}", {
                    array: array,
                    _token: "{{csrf_token()}}",
                }, function(data) {

                    var url = '{{ route("admin.myRequests") }}';

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
            document.getElementById("archAll").disabled = false;
            document.getElementById("archAll").style = "";
        } else {
            document.getElementById("archAll").disabled = true;
            document.getElementById("archAll").style = "cursor: not-allowed";
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
            "lengthMenu": [
                [10, 25, 50],
                [10, 25, 50]
            ],
            dom: 'Bfrtip',
            buttons: [
                'pageLength',
                // 'copyHtml5',
                'excelHtml5',
                // 'csvHtml5',
                // 'pdfHtml5' ,
                'print',
               
            ],
            scrollY: true,
            scrollX: true,
            scrollCollapse: true,
            processing: true,
            serverSide: true,
            ajax: {
                'url': "{{ url('admin/waiting-reqs-datatable-done') }}",
                'method': 'Get',
                'data': function(data) {

                },
            },
            columns: [{
                    "targets": 0,
                    "data": "need_created_at",
                    "render": function(data, type, row, meta) {
                        return data.split(" ").join("<br/>");
                    }


                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'user_name',
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

                $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title', 'تصدير');
                $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title', 'طباعة');
                $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');

                $('.buttons-excel').addClass('no-transition custom-btn');
                $('.buttons-print').addClass('no-transition custom-btn');
                $('.buttons-collection').addClass('no-transition custom-btn');

                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)

            },
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

                $('td', row).eq(3).addClass('commentStyle'); // 6 is index of column
                $('td', row).eq(3).attr('title', data.customer_name); // to show other text of comment


                $('td', row).eq(0).addClass('reqDate'); // 6 is index of column

            },
        });
    });


    $(function() {
        $('#source').on('tokenize:tokens:add', function(e, value, text) {



            if (value == "متعاون") {


                document.getElementById("collaboratorDiv").style.display = "block";


            }
        });

        $('#source').on('tokenize:tokens:remove', function(e, value) {

            if (value == "متعاون") {


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
</script>
@endsection
