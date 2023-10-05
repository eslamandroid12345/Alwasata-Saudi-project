@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Notifications') }}
@endsection

@section('css_style')
{{-- NEW STYLE   --}}
<link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">

<style>
    .middle-screen {
        height: 100%;
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: center;
    }
</style>
@endsection


@section('customer')



<div id="msg2" class="alert alert-dismissible" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>

</div>

@if(session()->has('message2'))
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session()->get('message2') }}
</div>
@endif


<div class="addUser my-4">
    <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
        <h3> {{ MyHelpers::admin_trans(auth()->user()->id,'Notifications') }}  @if (auth()->user()->role == '7') - جديدة @endif:</h3>

    </div>
</div>
@if ($notifiys >0)

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
                    <button disabled style="cursor: not-allowed" id="archAll" onclick="getReqests1()">
                        <i class="fas fa-trash-alt"></i>
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Delete Notificationes') }}
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
            <div id="dt-btns" class="tableAdminOption">

            </div>
        </div>
    </div>
</div>

<div class="dashTable">
    <table id="notifys-table" class="table table-bordred table-striped data-table">
        <thead>
            <th> </th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req num') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'content') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Date') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'notifi status') }}</th>
            <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>
        </thead>
        <tbody>
        </tbody>

    </table>
</div>
@else
<div class="middle-screen">
    <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No Notification') }}</h2>
</div>

@endif


@endsection




@section('updateModel')
@include('All.confirmDelMsg')
@endsection

@section('scripts')
<script>
    //---------NOTIFICATION IS DONE--------------------------


    $(document).on('click', '#Done', function(e) {

        var id = $(this).attr('data-id');


        $.get("{{route('all.NotificationToDone')}}", {
            id: id,
        }, function(data) {

            if (data.status != 0) {
                $('#notifys-table').DataTable().ajax.reload();
                disabledButton();
                $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> تم بنجاح");

            } else
                $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> حاول مرة أخرى");

        });



    });



    function doneAllReqs(array) {


        var modalConfirm = function(callback) {


            $("#mi-modal").modal('show');


            $("#modal-btn-si").on("click", function() {

                callback(true);
                $("#mi-modal").modal('hide');

            });


            $("#modal-btn-no").on("click", function() {
                callback(false);
                $("#mi-modal").modal('hide');
            });
        };

        modalConfirm(function(confirm) {
            if (confirm) {
                //console.log(array);

                $.get("{{ route('all.NotificationToDoneArray')}}", {
                    array: array,
                }, function(data) {


                    if (data != 0) {
                        $('#notifys-table').DataTable().ajax.reload();
                        disabledButton();
                        $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')}}");

                    } else
                        $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id, 'Try Again')}}");

                });



            } else {
                //reject
            }
        });



    };

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
        doneAllReqs(array);
        //alert(array);
    }


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
                //console.log(array);

                $.post("{{ route('all.delNotifys')}}", {
                    array: array,
                    _token: "{{csrf_token()}}",
                }, function(data) {

                    console.log(data);

                    if (data != 0) {
                        $('#notifys-table').DataTable().ajax.reload();
                        disabledButton();
                        $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')}}");

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

            document.getElementById("doneAll").disabled = false;
            document.getElementById("doneAll").style = "";
        } else {
            document.getElementById("archAll").disabled = true;
            document.getElementById("archAll").style = "cursor: not-allowed";

            document.getElementById("doneAll").disabled = true;
            document.getElementById("doneAll").style = "cursor: not-allowed";
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

    $(document).on('click', '#Delete', function(e) {
        var id = $(this).attr('data-id');

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

                $.post("{{ route('all.delNotify')}}", {
                    id: id,
                    _token: "{{csrf_token()}}",
                }, function(data) { // we pass var id in the request with id as name ; we use $.post : because we pass our data to server and got response


                    if (data != 0) {
                        $('#notifys-table').DataTable().ajax.reload();
                        disabledButton();
                        $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id, 'Delete Succesffuly')}}");
                    } else {
                        $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id, 'Try Again')}}");

                    }


                })



            } else {
                //No archive
            }
        });


    });
</script>

<script>
    $(document).ready(function() {
        var dt = $('#notifys-table').DataTable({
            "language": {
                "url": "{{route('datatableLanguage')}}",
                buttons: {
                    excelHtml5: "اكسل",
                    print: "طباعة",
                    pageLength: "عرض",

                }
            },
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "الكل"]
            ],
            dom: 'Bfrtip',
            buttons: [
                // 'copyHtml5',
                'excelHtml5',
                // 'csvHtml5',
                // 'pdfHtml5' ,
                'print',
                'pageLength'
            ],
            processing: true,
            serverSide: true,
            ajax: "{{ url('all/notifiys-datatable') }}",
            columns: [{
                    "targets": 0,
                    "data": "id",
                    "render": function(data, type, row, meta) {
                        return '<input type="checkbox" id="chbx" name="chbx[]" onchange="disabledButton()" value="' + data + '"/>';
                    }
                },
                {
                    data: 'req_id',
                    name: 'req_id'
                },
                {
                    data: 'name',
                    name: 'customers.name'
                },
                {
                    data: 'value',
                    name: 'value'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ],
            initComplete: function() {

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
        });
    });
</script>
@endsection
