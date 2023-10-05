@extends('layouts.content')
@section('title')
المتعاونين
@endsection
@section('css_style')

    {{--    OLD STYLE --}}
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

    {{--    NEW STYLE   --}}
    <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">
    <script src="{{ asset('backend/sweetalert/sweetalert2.min.js') }}"></script>
    <link href="{{ asset('backend/sweetalert/sweetalert2.min.css') }}" rel="stylesheet">
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
        <h3>المتعاونين:</h3>

    </div>
</div>
@if ($users > 0)
    <div class="tableBar">

        </div>


        <div class="topRow">
            <div class="row align-items-center text-center text-md-left">
                <div class="col-lg-6">
                    <div class="tableUserOption  flex-wrap ">
                        <div class="input-group col-md-12 mt-lg-0 mt-3">
                            <input class="form-control py-2" type="search" placeholder="ابحث هنا" id="example-search-input">
                            <span class="input-group-append">
                          <button class="btn btn-outline-info" type="button">
                              <i class="fa fa-search"></i>
                          </button>
                        </span>
                        </div>

                    </div>
                </div>
                <div class="col-lg-6 mt-lg-0 mt-3">
                    <div  id="dt-btns" class="tableAdminOption">

                    </div>
                </div>
            </div>
        </div>

        <div class="dashTable">
            <table id="myusers-table" class="table table-bordred table-striped data-table">
                <thead>
                <tr>
                    <th> </th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Username') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'user status') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'registered_on') }}</th>
                    <th>عدد الطلبات النشطة</th>
                    <th>عدد الطلبات المعلقة</th>
                    <th>عدد الطلبات المكررة</th>
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
        <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No Users') }}</h2>
    </div>

@endif

@endsection

@section('updateModel')
@include('Admin.Users.confirmationMsg')
@include('Admin.Users.updateUser')
@include('Admin.Users.confirmArchMsg')
@include('Admin.Users.confirmationSwitchMsg')
@include('Admin.Users.filterUsers')
@endsection


@section('scripts')

<script src="{{ asset('js/tokenize2.min.js') }}"></script>

<script>
    $('.tokenizeable').tokenize2();
    $(".tokenizeable").on("tokenize:select", function() {
        $(this).trigger('tokenize:search', "");
    });

</script>
<script>
    function getReqests1() {
        var array = []
        var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].value != "on" && checkboxes[i].value != "off") {
                var val = parseInt(checkboxes[i].value);
                array.push(val);
            }
        }
        archiveAllReqs(array);
    }
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
                $.post("{{ route('admin.archUserArray')}}", {
                    array: array,
                    _token: "{{csrf_token()}}",
                }, function(data) {
                    var url = '{{ route("admin.users") }}';
                    if (data != 0) {
                        $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')}}");
                        window.location.href = url;
                    } else
                        $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id, 'Try Again')}}");
                });
            }
        });
    };
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
</script>
<script>
    $(document).ready(function() {
        var role = $('#role').val();
        var tsaheel = $('#isTsaheel').val();
        if ($("#role")[0].selectedIndex <= 0)
        {
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
            document.getElementById("tsaheelDiv").style.display = "none";
            document.getElementById("isTsaheel").value = "0";
            document.getElementById("accountantDiv").style.display = "none";
            document.getElementById("accountant_type").value = "";
        }
        else
        {
            if (role == 'sa') {
                document.getElementById("salesmanagerDiv").style.display = "block";
                document.getElementById("tsaheelDiv").style.display = "block";
            }
            if (role == 'sa' && tsaheel == 'yes')
            {
                document.getElementById("mortgagemanagerDiv").style.display = "block";
                document.getElementById("mortgage_label").innerHTML = " {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage Manager') }} (طلب تساهيل)";
                document.getElementById("salesmanagerDiv").style.display = "none";
                document.getElementById("salesmanager").value = "";
            }
            if (role == 6)
                document.getElementById("salesagentDiv").style.display = "block";
            if (role == 1)
            {
                document.getElementById("fundingmanagerDiv").style.display = "block";
                document.getElementById("mortgagemanagerDiv").style.display = "block";
            }
            if (role == 2 || role == 3)
            {
                document.getElementById("generalmanagerDiv").style.display = "block";
            }
            if (role == 8)
            {
                document.getElementById("accountantDiv").style.display = "block";
            }
        }
    });
    function myFunction()
    {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
    function check(that)
    {
        if (($("#role")[0].selectedIndex <= 0 == false) && (that.value == 'sa')) { // sales agent should has sales maanger
            document.getElementById("salesmanagerDiv").style.display = "block";
            document.getElementById("tsaheelDiv").style.display = "block";
            document.getElementById("salesagentDiv").style.display = "none";
            document.getElementById("salesagent").value = "";
            document.getElementById("mortgagemanagerDiv").style.display = "none";
            document.getElementById("mortgagemanager").value = "";
            document.getElementById("fundingmanagerDiv").style.display = "none";
            document.getElementById("fundingmanager").value = "";
            document.getElementById("generalmanagerDiv").style.display = "none";
            document.getElementById("generalmanager").value = "";
            document.getElementById("accountantDiv").style.display = "none";
            document.getElementById("accountant_type").value = "";
        }
        else if (that.value == 6)
        { // colloberatot should has sales agents
            document.getElementById("salesagentDiv").style.display = "block";
            document.getElementById("salesmanagerDiv").style.display = "none";
            document.getElementById("salesmanager").value = "";
            document.getElementById("fundingmanagerDiv").style.display = "none";
            document.getElementById("fundingmanager").value = "";
            document.getElementById("mortgagemanagerDiv").style.display = "none";
            document.getElementById("mortgagemanager").value = "";
            document.getElementById("generalmanagerDiv").style.display = "none";
            document.getElementById("generalmanager").value = "";
            document.getElementById("tsaheelDiv").style.display = "none";
            document.getElementById("isTsaheel").value = "0";
            document.getElementById("accountantDiv").style.display = "none";
            document.getElementById("accountant_type").value = "";
        }
        else if (that.value == 1)
        { // sales should has funding & mortgage managers

            document.getElementById("fundingmanagerDiv").style.display = "block";
            document.getElementById("mortgagemanagerDiv").style.display = "block";
            document.getElementById("mortgage_label").innerHTML = " {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage Manager') }}";


            document.getElementById("salesmanagerDiv").style.display = "none";
            document.getElementById("salesmanager").value = "";

            document.getElementById("salesagentDiv").style.display = "none";
            document.getElementById("salesagent").value = "";

            document.getElementById("generalmanagerDiv").style.display = "none";
            document.getElementById("generalmanager").value = "";

            /*  document.getElementById("qualtyDiv").style.display = "none";
            document.getElementById("quality").value = "";
            */

            document.getElementById("tsaheelDiv").style.display = "none";
            document.getElementById("isTsaheel").value = "0";

            document.getElementById("accountantDiv").style.display = "none";
            document.getElementById("accountant_type").value = "";

        } else if (that.value == 5) { // sales should has funding & mortgage managers

            document.getElementById("fundingmanagerDiv").style.display = "none";
            document.getElementById("mortgagemanagerDiv").style.display = "none";

            document.getElementById("salesmanagerDiv").style.display = "none";
            document.getElementById("salesmanager").value = "";

            document.getElementById("salesagentDiv").style.display = "none";
            document.getElementById("salesagent").value = "";


            document.getElementById("generalmanagerDiv").style.display = "none";
            document.getElementById("generalmanager").value = "";

            // document.getElementById("qualtyDiv").style.display = "block";

            document.getElementById("tsaheelDiv").style.display = "none";
            document.getElementById("isTsaheel").value = "0";

            document.getElementById("accountantDiv").style.display = "none";
            document.getElementById("accountant_type").value = "";

        } else if (that.value == 2 || that.value == 3) { // funding & mortgage managers shpuld has general manager



            document.getElementById("generalmanagerDiv").style.display = "block";


            document.getElementById("salesmanagerDiv").style.display = "none";
            document.getElementById("salesmanager").value = "";

            document.getElementById("salesagentDiv").style.display = "none";
            document.getElementById("salesagent").value = "";

            document.getElementById("fundingmanagerDiv").style.display = "none";
            document.getElementById("fundingmanager").value = "";

            document.getElementById("mortgagemanagerDiv").style.display = "none";
            document.getElementById("mortgagemanager").value = "";

            /*  document.getElementById("qualtyDiv").style.display = "none";
            document.getElementById("quality").value = "";
            */


            document.getElementById("tsaheelDiv").style.display = "none";
            document.getElementById("isTsaheel").value = "0";

            document.getElementById("accountantDiv").style.display = "none";
            document.getElementById("accountant_type").value = "";


        } else if (that.value == 8) {

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


            document.getElementById("tsaheelDiv").style.display = "none";
            document.getElementById("isTsaheel").value = "0";


            document.getElementById("accountantDiv").style.display = "block";

        } else {

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

    ////////////////////////////////////////

    $(document).ready(function() {

        var dt = $('#myusers-table').DataTable({
            "language": {
                "url": "{{route('datatableLanguage')}}",
                buttons: {
                    excelHtml5: "اكسل",
                    print: "طباعة",
                    pageLength: "عرض",

                }
            },
            scrollY: '50vh',
            scrollX: true,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "الكل"]
            ],
            dom: 'Bfrtip',
            buttons: [

                'excelHtml5', ,
                'print',
                'pageLength',
                {
                    text: '{{ MyHelpers::admin_trans(auth()->user()->id,"Search") }}',
                    action: function(e, dt, node, config) {
                        $('#myModal1').modal('show');
                    }
                },
            ],
            processing: true,
            serverSide: true,
            ajax:{
                'url': "{{ url('admin/colloberatorusers-datatable') }}",
                'method': 'GET',
                'data': function(data) {
                    let agents_ids = $('#agents_ids').data('tokenize2').toArray();
                    if (agents_ids != '')
                    {
                        data['agents_ids'] = agents_ids;
                    }
                },
            },
            columns: [
                {
                    "targets": 0,
                    "data": "id",
                    "render": function(data, type, row, meta) {
                        return '<input type="checkbox" id="chbx" name="chbx[]" onchange="disabledButton()"  value="' + data + '"/>';
                    }
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'username',
                    name: 'username'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'req_count',
                    name: 'req_count'
                },
                {
                    data: 'pen_count',
                    name: 'pen_count'
                },
                {
                    data: 'repeated_count',
                    name: 'repeated_count'
                },
                {
                    data: 'actions',
                    name: 'actions'
                }
            ],
            initComplete: function() {
                let api = this.api();
                $("#filter-search-users").on('click', function(e) {
                    e.preventDefault();
                    api.draw();
                    $('#myModal1').modal('hide');
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
            "order": [
                [2, "desc"]
            ], // Order on init. # is the column, starting at 0
            createdRow: function(row, data, index) {
                $('td', row).eq(3).addClass('commentStyle');
                $('td', row).eq(3).attr('title', data.email);
                $('td', row).eq(1).addClass('reqNum'); // 6 is index of column
                $('td', row).eq(2).addClass('reqNum'); // 6 is index of column
                $('td', row).eq(4).addClass('reqNum'); // 6 is index of column
                $('td', row).eq(5).addClass('reqNum'); // 6 is index of column
            },
        });
    });

    //-----------------------------------






    $(document).on('click', '#copy-my-url', function(e) {
        var id = $(this).attr('data-url');
        copyToClipboard(id)
        swal({
            title: 'تم',
            text: 'تم نسخ الرابط الخاص بالمتعاون ',
            type: 'success',
            timer: '7500'
        })
    });
    function copyToClipboard(textToCopy) {
        // navigator clipboard api needs a secure context (https)
        if (navigator.clipboard && window.isSecureContext) {
            // navigator clipboard api method'
            return navigator.clipboard.writeText(textToCopy);
        } else {
            // text area method
            let textArea = document.createElement("textarea");
            textArea.value = textToCopy;
            // make the textarea out of viewport
            textArea.style.position = "fixed";
            textArea.style.left = "-999999px";
            textArea.style.top = "-999999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            return new Promise((res, rej) => {
                // here the magic happens
                document.execCommand('copy') ? res() : rej();
                textArea.remove();
            });
        }
    }
    ///////////////////////////////////////////

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


                $.post("{{ route('admin.deleteUser')}}", {
                    id: id,
                    _token: "{{csrf_token()}}",
                }, function(data) { // we pass var id in the request with id as name ; we use $.post : because we pass our data to server and got response

                    //console.log(data);
                    if (data.status == 1) {
                        var d = ' # ' + id;
                        var test = d.replace(/\s/g, ''); // to remove all spaces in var d , to find the <tr/> that i deleted and reomve it
                        $(test).remove(); // remove by #id

                        $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

                    } else {

                        $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

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
        $('#mobileError').addClass("d-none");
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

           // console.log(data);

            if (data.status != 0) {

                $('#frm-update').find('#id').val(data.user.id);
                $('#frm-update').find('#name').val(data.user.name);
                $('#frm-update').find('#username').val(data.user.username);
                $('#frm-update').find('#mobile').val(data.user.mobile);
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

                            if (selectobject.options[j].value == arrData[i].user_id) {

                                var name = selectobject.options[j].text;
                                selectobject.remove(j);
                                $('#salesagent').tokenize2().trigger('tokenize:tokens:add', [arrData[i].user_id, name, true]);
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
        $('#mobileError').addClass("d-none");
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

            //console.log(data);
            $('#myusers-table').DataTable().ajax.reload();


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
        else if (r == 11)
            role = "{{MyHelpers::admin_trans(auth()->user()->id, 'Training') }}";
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
