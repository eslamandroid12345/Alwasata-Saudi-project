@extends('layouts.content')

@section('title')
الأيميلات
@endsection


@section('css_style')

<style>
    .middle-screen {
        height: 100%;
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: center;
    }

    .commentStyle {
        max-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .reqNum {
        width: 1%;
    }

    .reqType {
        width: 2%;
    }
</style>
{{-- NEW STYLE   --}}
<link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">
@endsection

@section('customer')



@if(session()->has('message'))
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




<div id="msg2" class="alert alert-dismissible" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>

</div>

<div class="addUser my-4">
    <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
        <h3> الأيميلات:</h3>

    </div>
</div>
<br>


@if ($users > 0)

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
                    <div class="addBtn col-md-5 mt-lg-0 mt-3">
                        <a href="{{ route('admin.addEmailPage')}}">
                            <button class="mr-2 Cloud">
                                <i class="fas fa-plus"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} تنبيه رسائل
                            </button>
                        </a>
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
        <table id="pendingReqs-table" class="table table-bordred table-striped data-table">
            <thead>
                <tr>

                    <th> </th>
                    <th>اســــم الأيميل </th>
                    <th>الحالة </th>
                    <th>الإســــــم</th>
                    <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>

                </tr>
            </thead>
            <tbody>
                @foreach($emails as $email)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$email->email_name}}</td>
                    <td>{{$email->status}}</td>
                    <td>{{$email->display_name}}</td>
                    <td>
                        <div class="tableAdminOption">
                            <span class="item pointer" data-toggle="tooltip" data-placement="top" title="حذف">
                                <a href="{{route('admin.deleteEmail')}}?id={{$email->id}}"><i class="fas fa-trash-alt"></i></a></span>

                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>
@else
<div class="middle-screen">
    <h2 style=" text-align: center;font-size: 20pt;">لا يوجد تنبيهات إيميلات </h2>
</div>

@endif



@endsection

@section('scripts')


<script>
    ////////////////////////////////////////

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
            initComplete: function() {


                dt.buttons().container()
                    .appendTo('#dt-btns');

                $(".dt-button").last().html('<i class="fas fa-search"></i>').attr('title', 'بحث');
                $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title', 'تصدير');
                $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title', 'طباعة');
                $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');

                $('.buttons-excel').addClass('no-transition custom-btn');
                $('.buttons-print').addClass('no-transition custom-btn');
                $('.buttons-collection').addClass('no-transition custom-btn');

                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)

                /* To Adaptive with New Design */

            },
        });
    });

    $(document).on('click', '#archive', function(e) {
        var id = $(this).attr('data-id');


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


                $.post("{{ route('admin.deleteEmail')}}", {
                    id: id,
                    _token: "{{csrf_token()}}",
                }, function(data) { // we pass var id in the request with id as name ; we use $.post : because we pass our data to server and got response

                    //console.log(data);
                    if (data.status == 1) {
                        $('.data-table').DataTable().ajax.reload();
                        $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

                    } else {

                        $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

                    }



                });



            } else {
                //No delete
            }
        });


    });



    //////////////////////////////////////////////////////#

    //////////////////////////////////////////////////////#

    $(document).on('click', '#edit', function(e) {


        $('#nameError').addClass("d-none");
        $('#usernameError').addClass("d-none");
        $('#passwordError').addClass("d-none");
        $('#emailError').addClass("d-none");
        $('#localeError').addClass("d-none");
        $('#roleError').addClass("d-none");
        $('#salesmanagerError').addClass("d-none");
        $('#fundingmanagerError').addClass("d-none");
        $('#mortgagemanagerError').addClass("d-none");
        $('#salesagentsError').addClass("d-none");
        $('#generalmanagerError').addClass("d-none");
        $('#qualityError').addClass("d-none");
        $('#accountant_typeError').addClass("d-none");


        var id = $(this).attr('data-id');

        $.get("{{route('admin.getUser')}}", {
            id: id
        }, function(data) {

            console.log(data);

            if (data.status != 0) {

                $('#frm-update').find('#id').val(data.user.id);
                $('#frm-update').find('#name').val(data.user.name);
                $('#frm-update').find('#username').val(data.user.username);
                $('#frm-update').find('#email2').val(data.user.email);
                $('#frm-update').find('#locale').val(data.user.locale);
                // $('#frm-update').find('#password').val('data.user.password');

                if (data.user.role != 0)
                    $('#frm-update').find('#role').val(data.user.role);
                else
                    $('#frm-update').find('#role').val('sa'); //because sales agent ll not appear if i just pass 0 value


                if (data.user.role != null)
                    appearDiv(data.user.role); // to know which div has to be appeared

                $('#frm-update').find('#isTsaheel').val(data.user.isTsaheel);
                checkTsaheel(data.user.isTsaheel);

                $('#frm-update').find('#salesmanager').val(data.user.manager_id);
                $('#frm-update').find('#fundingmanager').val(data.user.funding_mnager_id);
                $('#frm-update').find('#mortgagemanager').val(data.user.mortgage_mnager_id);
                $('#frm-update').find('#generalmanager').val(data.user.manager_id);
                $('#frm-update').find('#accountant_type').val(data.user.accountant_type);



                //-------------------TO RETRIVE SALES AGENTS IN SELECT2 ---------------------------

                var lengthArr = data.quality.length;

                if (lengthArr > 0) { // for collobreator user and thier salesagents

                    var arrData = data.quality;

                    for (i = 0; i < lengthArr; ++i) {
                        //console.log(arrData[i]);

                        var selectobject = document.getElementById("quality");

                        for (var j = 0; j < selectobject.length; j++) {

                            // console.log(arrData[i].user_id);

                            if (selectobject.options[j].value == arrData[i].Agent_id) {

                                var name = selectobject.options[j].text;
                                // console.log(name);
                                selectobject.remove(j);
                                $('#quality').append($("<option selected></option>").attr("value", arrData[i].Agent_id).text(name));
                                break;

                            }


                        }

                    }

                }


                //-------------------TO RETRIVE SALES AGENTS IN SELECT2 ---------------------------

                var lengthArr = data.salesagents.length;

                if (lengthArr > 0) { // for collobreator user and thier salesagents

                    var arrData = data.salesagents;

                    for (i = 0; i < lengthArr; ++i) {
                        //console.log(arrData[i]);

                        var selectobject = document.getElementById("salesagent");

                        for (var j = 0; j < selectobject.length; j++) {

                            // console.log(arrData[i].user_id);

                            if (selectobject.options[j].value == arrData[i].user_id) {

                                var name = selectobject.options[j].text;
                                // console.log(name);
                                selectobject.remove(j);
                                $('#salesagent').append($("<option selected></option>").attr("value", arrData[i].user_id).text(name));
                                break;

                            }


                        }

                    }

                }

                //-------------------END RETRIVE SALES AGENTS IN SELECT2 ---------------------------

                $('#myModal').modal('show');

            } else
                $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);


        });



    });
    //////////////////////////////////////////////////////


    $('#frm-update').on('submit', function(e) {


        $('#nameError').addClass("d-none");
        $('#usernameError').addClass("d-none");
        $('#passwordError').addClass("d-none");
        $('#emailError').addClass("d-none");
        $('#localeError').addClass("d-none");
        $('#roleError').addClass("d-none");
        $('#salesmanagerError').addClass("d-none");
        $('#fundingmanagerError').addClass("d-none");
        $('#mortgagemanagerError').addClass("d-none");
        $('#salesagentsError').addClass("d-none");
        $('#generalmanagerError').addClass("d-none");
        $('#accountant_typeError').addClass("d-none");


        e.preventDefault();
        var data = $(this).serialize();
        var url = $(this).attr('action');



        $.post(url, data, function(data) { //data is array with two veribles (request[], ss)

            // console.log(data);

            if (data.status == 1) {

                //
                if (data.user.allow_recived == 0)
                    var optiones = "<div class='table-data-feature'>  <button class='item btn btn-danger' id='active' data-id= " + data.user.id + " data-toggle='tooltip' data-placement='top' title= '{{MyHelpers::admin_trans(auth()->user()->id, 'allow')}}' > <i class='zmdi zmdi-alert-circle-o'> </i> </a> </button> <button class='item' id='switch' data-id= " + data.user.id + " data-toggle='tooltip' data-placement='top' title= '{{MyHelpers::admin_trans(auth()->user()->id, 'Switch Login')}}' > <i class='zmdi zmdi-swap'> </i> </a> </button> <button class='item' id='edit' data-id= " + data.user.id + " data-toggle='tooltip' data-placement='top' title= '{{MyHelpers::admin_trans(auth()->user()->id, 'Edit')}}' > <i class='zmdi zmdi-edit'> </i> </a> </button>  <button class='item' id='archive' data-id= " + data.user.id + " data-toggle='tooltip' data-placement='top' title= '{{MyHelpers::admin_trans(auth()->user()->id, 'Archive')}}' > <i class='zmdi zmdi-delete'> </i> </a> </button> </div>";
                else
                    var optiones = "<div class='table-data-feature'>  <button class='item btn btn-success' id='active' data-id= " + data.user.id + " data-toggle='tooltip' data-placement='top' title= '{{MyHelpers::admin_trans(auth()->user()->id, 'not_allow')}}' > <i class='zmdi zmdi-alert-circle-o'> </i> </a> </button> <button class='item' id='switch' data-id= " + data.user.id + " data-toggle='tooltip' data-placement='top' title= '{{MyHelpers::admin_trans(auth()->user()->id, 'Switch Login')}}' > <i class='zmdi zmdi-swap'> </i> </a> </button><button class='item' id='edit' data-id= " + data.user.id + " data-toggle='tooltip' data-placement='top' title= '{{MyHelpers::admin_trans(auth()->user()->id, 'Edit')}}' > <i class='zmdi zmdi-edit'> </i> </a> </button>  <button class='item' id='archive' data-id= " + data.user.id + " data-toggle='tooltip' data-placement='top' title= '{{MyHelpers::admin_trans(auth()->user()->id, 'Archive')}}' > <i class='zmdi zmdi-delete'> </i> </a> </button> </div>";


                //
                if (data.user.allow_recived == 0)
                    var st = "{{MyHelpers::admin_trans(auth()->user()->id,'not active')}}";
                else
                    var st = "{{MyHelpers::admin_trans(auth()->user()->id,'active')}}";

                //




                var typeAccountant = '';

                if (data.user.role == 8 && data.user.accountant_type != null) {

                    if (data.user.accountant_type == 0)
                        typeAccountant = 'تساهيل';
                    if (data.user.accountant_type == 1)
                        typeAccountant = 'وساطة';

                }


                var role = getRole(data.user.role);

                if (typeAccountant != '')
                    role = role + ' - ' + typeAccountant;



                var fn = $("<tr/>").attr('id', data.user.id);
                fn.append($("<td/>", {
                    html: " <input type='checkbox' id='chbx' name='chbx[]' value=' " + data.user.id + " '/>"
                })).append($("<td/>", {
                    text: data.user.name
                })).append($("<td/>", {
                    text: data.user.username
                })).append($("<td/>", {
                    text: data.user.email
                })).append($("<td/>", {
                    text: role
                })).append($("<td/>", {
                    text: st
                })).append($("<td/>", {
                    text: data.user.created_at
                })).append($("<td/>", {
                    html: optiones
                }));

                var d = ' # ' + data.user.id;
                var test = d.replace(/\s/g, ''); //#data.id

                $(test).replaceWith(fn); //to replace a new row with an old row based on var test


                $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

            } else if (data.status == 0) {
                $('#msg2').addClass("alert-warning").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
            }


            $('#myModal').modal('hide');



        }).fail(function(data) {

            var errors = data.responseJSON;

            if ($.isEmptyObject(errors) == false) {

                $.each(errors.errors, function(key, value) {

                    var ErrorID = '#' + key + 'Error';
                    $(ErrorID).removeClass("d-none");
                    $(ErrorID).text(value);

                })

            }
        });

    });



    //////////////////////////////////////#

    function getRole(r) {

        if (r == 0)
            role = "{{MyHelpers::admin_trans(auth()->user()->id, 'Sales Agent') }}";
        else if (r == 1)
            role = "{{MyHelpers::admin_trans(auth()->user()->id, 'Sales Manager') }}";
        else if (r == 2)
            role = "{{MyHelpers::admin_trans(auth()->user()->id, 'Funding Manager') }}";
        else if (r == 3)
            role = "{{MyHelpers::admin_trans(auth()->user()->id, 'Mortgage Manager') }}";
        else if (r == 4)
            role = "{{MyHelpers::admin_trans(auth()->user()->id, 'General Manager') }}";
        else if (r == 5)
            role = "{{MyHelpers::admin_trans(auth()->user()->id, 'Quality Manager') }}";
        else if (r == 6)
            role = "{{MyHelpers::admin_trans(auth()->user()->id, 'Collaborator') }}";
        else if (r == 7)
            role = "{{MyHelpers::admin_trans(auth()->user()->id, 'Admin') }}";
        else if (r == 8)
            role = "{{MyHelpers::admin_trans(auth()->user()->id, 'Accountant') }}";
        else
            role = "{{MyHelpers::admin_trans(auth()->user()->id, 'Undefined') }}";


        return role;
    }
    ///////////////////////////////////////////////////////

    /////////////////////////////////////////

    function appearDiv(that) {

        if (that == 0) { // sales agent should has sales maanger

            document.getElementById("salesmanager").value = "";
            document.getElementById("salesmanagerDiv").style.display = "block";
            document.getElementById("tsaheelDiv").style.display = "block";
            document.getElementById("isTsaheel").value = "";
            // document.getElementById("mortgage_label").innerHTML  = " {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage Manager') }} (طلب تساهيل)";




            document.getElementById("salesagentDiv").style.display = "none";
            document.getElementById("salesagent").value = "";

            document.getElementById("mortgagemanagerDiv").style.display = "none";
            document.getElementById("mortgagemanager").value = "";

            document.getElementById("generalmanagerDiv").style.display = "none";
            document.getElementById("generalmanager").value = "";

            document.getElementById("fundingmanagerDiv").style.display = "none";
            document.getElementById("fundingmanager").value = "";

            document.getElementById("accountantDiv").style.display = "none";
            document.getElementById("accountant_type").value = "";


        } else if (that == 6) { // colloberatot should has sales agents

            document.getElementById("salesagent").value = "";
            document.getElementById("salesagentDiv").style.display = "block";


            document.getElementById("salesmanagerDiv").style.display = "none";
            document.getElementById("salesmanager").value = "";

            document.getElementById("generalmanagerDiv").style.display = "none";
            document.getElementById("generalmanager").value = "";

            document.getElementById("fundingmanagerDiv").style.display = "none";
            document.getElementById("fundingmanager").value = "";

            document.getElementById("mortgagemanagerDiv").style.display = "none";
            document.getElementById("mortgagemanager").value = "";


            document.getElementById("accountantDiv").style.display = "none";
            document.getElementById("accountant_type").value = "";


        } else if (that == 1) { // sales should has funding & mortgage managers


            document.getElementById("fundingmanager").value = "";
            document.getElementById("mortgagemanager").value = "";
            document.getElementById("fundingmanagerDiv").style.display = "block";
            document.getElementById("mortgagemanagerDiv").style.display = "block";
            document.getElementById("mortgage_label").innerHTML = " {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage Manager') }}";



            document.getElementById("generalmanagerDiv").style.display = "none";
            document.getElementById("generalmanager").value = "";

            document.getElementById("salesmanagerDiv").style.display = "none";
            document.getElementById("salesmanager").value = "";

            document.getElementById("salesagentDiv").style.display = "none";
            document.getElementById("salesagent").value = "";


            document.getElementById("accountantDiv").style.display = "none";
            document.getElementById("accountant_type").value = "";

        } else if (that == 2 || that == 3) { //  funding & mortgage manager shold has general maanger

            document.getElementById("generalmanager").value = "";
            document.getElementById("generalmanagerDiv").style.display = "block";

            document.getElementById("salesmanagerDiv").style.display = "none";
            document.getElementById("salesmanager").value = "";

            document.getElementById("salesagentDiv").style.display = "none";
            document.getElementById("salesagent").value = "";


            document.getElementById("fundingmanagerDiv").style.display = "none";
            document.getElementById("fundingmanager").value = "";

            document.getElementById("mortgagemanagerDiv").style.display = "none";
            document.getElementById("mortgagemanager").value = "";

            document.getElementById("accountantDiv").style.display = "none";
            document.getElementById("accountant_type").value = "";

        } else if (that == 5) { // sales should has funding & mortgage managers

            document.getElementById("fundingmanagerDiv").style.display = "none";
            document.getElementById("mortgagemanagerDiv").style.display = "none";

            document.getElementById("salesmanagerDiv").style.display = "none";
            document.getElementById("salesmanager").value = "";

            document.getElementById("salesagentDiv").style.display = "none";
            document.getElementById("salesagent").value = "";


            document.getElementById("generalmanagerDiv").style.display = "none";
            document.getElementById("generalmanager").value = "";


            document.getElementById("accountantDiv").style.display = "none";
            document.getElementById("accountant_type").value = "";
            // document.getElementById("qualtyDiv").style.display = "block";
            // document.getElementById("quality").value = "";

        } else if (that == 8) {

            document.getElementById("salesmanagerDiv").style.display = "none";
            document.getElementById("salesmanager").value = "";

            document.getElementById("salesagentDiv").style.display = "none";
            document.getElementById("salesagent").value = "";

            document.getElementById("generalmanagerDiv").style.display = "none";
            document.getElementById("generalmanager").value = "";


            document.getElementById("fundingmanagerDiv").style.display = "none";
            document.getElementById("fundingmanager").value = "";

            document.getElementById("mortgagemanagerDiv").style.display = "none";
            document.getElementById("mortgagemanager").value = "";

            document.getElementById("tsaheelDiv").style.display = "none";
            document.getElementById("isTsaheel").value = "";

            document.getElementById("accountantDiv").style.display = "block";

        } else {

            document.getElementById("salesmanagerDiv").style.display = "none";
            document.getElementById("salesmanager").value = "";

            document.getElementById("salesagentDiv").style.display = "none";
            document.getElementById("salesagent").value = "";

            document.getElementById("generalmanagerDiv").style.display = "none";
            document.getElementById("generalmanager").value = "";


            document.getElementById("fundingmanagerDiv").style.display = "none";
            document.getElementById("fundingmanager").value = "";

            document.getElementById("mortgagemanagerDiv").style.display = "none";
            document.getElementById("mortgagemanager").value = "";

            document.getElementById("tsaheelDiv").style.display = "none";
            document.getElementById("isTsaheel").value = "";

            document.getElementById("accountantDiv").style.display = "none";
            document.getElementById("accountant_type").value = "";

        }
    }
    ////////////////////////////////////////


    ///////////////////////////////////////////

    $(document).on('click', '#switch', function(e) {
        var id = $(this).attr('data-id');


        //  alert(id);

        var modalConfirm = function(callback) {


            $("#mi-modal4").modal('show');


            $("#modal-btn-si4").on("click", function() {
                callback(true);
                $("#mi-modal4").modal('hide');
            });

            $("#modal-btn-no4").on("click", function() {
                callback(false);
                $("#mi-modal4").modal('hide');
            });
        };

        modalConfirm(function(confirm) {
            if (confirm) {


                $.post("{{ route('switch.userSwitch')}}", {
                    id: id,
                    _token: "{{csrf_token()}}",
                }, function(data) {

                    //console.log(data);

                    var url = data;
                    window.location.href = url; //using a named route


                });



            } else {
                //No
            }
        });


    });


    function checkTsaheel(that) {

        var role = $('#role').val();

        if (that == 'yes' && role == 'sa') {

            document.getElementById("mortgagemanagerDiv").style.display = "block";
            document.getElementById("mortgage_label").innerHTML = " {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage Manager') }} (طلب تساهيل)";



            document.getElementById("salesmanagerDiv").style.display = "none";
            document.getElementById("salesmanager").value = "";

            document.getElementById("salesagentDiv").style.display = "none";
            document.getElementById("salesagent").value = "";

            document.getElementById("fundingmanagerDiv").style.display = "none";
            document.getElementById("fundingmanager").value = "";

            document.getElementById("generalmanagerDiv").style.display = "none";
            document.getElementById("generalmanager").value = "";


            document.getElementById("accountantDiv").style.display = "none";
            document.getElementById("accountant_type").value = "";

            /*  document.getElementById("qualtyDiv").style.display = "none";
            document.getElementById("quality").value = "";
            */

        } else if (that == 'no' && role == 'sa') {

            document.getElementById("salesmanagerDiv").style.display = "block";


            document.getElementById("mortgagemanagerDiv").style.display = "none";
            document.getElementById("mortgagemanager").value = "";

            document.getElementById("salesagentDiv").style.display = "none";
            document.getElementById("salesagent").value = "";


            document.getElementById("fundingmanagerDiv").style.display = "none";
            document.getElementById("fundingmanager").value = "";

            document.getElementById("mortgagemanagerDiv").style.display = "none";
            document.getElementById("mortgagemanager").value = "";

            document.getElementById("generalmanagerDiv").style.display = "none";
            document.getElementById("generalmanager").value = "";

            document.getElementById("accountantDiv").style.display = "none";
            document.getElementById("accountant_type").value = "";

            /*  document.getElementById("qualtyDiv").style.display = "none";
            document.getElementById("quality").value = "";
            */
        }
    }


    function checkTsaheel2(that) {

        var role = $('#role').val();

        if (that.value == 'yes' && role == 'sa') {

            document.getElementById("mortgagemanagerDiv").style.display = "block";
            document.getElementById("mortgage_label").innerHTML = " {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage Manager') }} (طلب تساهيل)";



            document.getElementById("salesmanagerDiv").style.display = "none";
            document.getElementById("salesmanager").value = "";

            document.getElementById("salesagentDiv").style.display = "none";
            document.getElementById("salesagent").value = "";

            document.getElementById("fundingmanagerDiv").style.display = "none";
            document.getElementById("fundingmanager").value = "";

            document.getElementById("generalmanagerDiv").style.display = "none";
            document.getElementById("generalmanager").value = "";

            document.getElementById("accountantDiv").style.display = "none";
            document.getElementById("accountant_type").value = "";

            /*  document.getElementById("qualtyDiv").style.display = "none";
            document.getElementById("quality").value = "";
            */

        } else if (that.value == 'no' && role == 'sa') {

            document.getElementById("salesmanagerDiv").style.display = "block";


            document.getElementById("mortgagemanagerDiv").style.display = "none";
            document.getElementById("mortgagemanager").value = "";

            document.getElementById("salesagentDiv").style.display = "none";
            document.getElementById("salesagent").value = "";


            document.getElementById("fundingmanagerDiv").style.display = "none";
            document.getElementById("fundingmanager").value = "";

            document.getElementById("mortgagemanagerDiv").style.display = "none";
            document.getElementById("mortgagemanager").value = "";

            document.getElementById("generalmanagerDiv").style.display = "none";
            document.getElementById("generalmanager").value = "";

            document.getElementById("accountantDiv").style.display = "none";
            document.getElementById("accountant_type").value = "";

            /*  document.getElementById("qualtyDiv").style.display = "none";
            document.getElementById("quality").value = "";
            */
        } else if (that.value == 'no' && role != 'sa') {

            document.getElementById("salesmanagerDiv").style.display = "none";
            document.getElementById("salesmanager").value = "";

            document.getElementById("salesagentDiv").style.display = "none";
            document.getElementById("salesagent").value = "";


            document.getElementById("fundingmanagerDiv").style.display = "none";
            document.getElementById("fundingmanager").value = "";

            document.getElementById("mortgagemanagerDiv").style.display = "none";
            document.getElementById("mortgagemanager").value = "";

            document.getElementById("generalmanagerDiv").style.display = "none";
            document.getElementById("generalmanager").value = "";

            /*  document.getElementById("qualtyDiv").style.display = "none";
            document.getElementById("quality").value = "";
            */

            document.getElementById("tsaheelDiv").style.display = "none";
            document.getElementById("isTsaheel").value = "0";

            document.getElementById("accountantDiv").style.display = "none";
            document.getElementById("accountant_type").value = "";

        }
    }


    //////////////////////////////////////////////////////#
</script>
@endsection
