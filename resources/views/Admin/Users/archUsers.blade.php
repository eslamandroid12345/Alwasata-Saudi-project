@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Archive users') }}
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
</style>
{{--    NEW STYLE   --}}
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
        <h3> {{ MyHelpers::admin_trans(auth()->user()->id,'Archive users') }} :</h3>
    </div>
</div>
<br>



@if ($users > 0)

    <div class="tableBar">
        <div class="topRow">
            <div class="row   text-center text-lg-left align-items-center">
                <div class="col-lg-2">
                    <div class="selectAll">
                        <div class="form-check">
                            <input type="checkbox" id="allreq" class="form-check-input" onclick="chbx_toggle1(this);" />
                            <label class="form-check-label" for="allreq">تحديد الكل </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-10">
                    <div class="tableUserOption  flex-wrap justify-content-lg-end justify-content-center">
                        <div class="addBtn  mt-lg-0 mt-3 ml-4">
                            <button class="DarkBlue" disabled style="cursor: not-allowed" id="archAll" onclick="getReqests1()">
                                <i class="fas fa-reply-all"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Restore User') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="topRow">
            <div class="row align-items-center text-center text-md-left">
                <div class="col-lg-7 ">
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
                <div class="col-lg-5 mt-lg-0 mt-3">
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
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'email') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'role') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'registered_on') }}</th>
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
@include('Admin.Users.confirmRestMsg')
@endsection

@section('scripts')

<script>
    //-----------------------------------


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
            //console.log(array);

                $.post("{{ route('admin.restUserArray')}}", {
                    array: array,
                    _token: "{{csrf_token()}}",
                }, function(data) {

                    if (data != 0)
                    {
                        $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')}}");
                        $('#myusers-table').DataTable().ajax.reload();

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


<script>


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
            ajax: "{{ url('admin/archusers-datatable') }}",
            columns: [

                {
                    "targets": 0,
                    "data": "id",
                    "render": function(data, type, row, meta) {
                        return '<input type="checkbox" id="chbx" name="chbx[]"  onchange="disabledButton()" value="' + data + '"/>';
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
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'role',
                    name: 'role'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ],
            initComplete: function() {
                $(".paginate_button").addClass("pagination-circle");
                /* To Adaptive with New Design */
                $('#example-search-input').keyup(function(){
                    dt.search($(this).val()).draw() ;
                })

                dt.buttons().container()
                    .appendTo( '#dt-btns' );
                $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title','تصدير') ;
                $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title','طباعة') ;
                $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title','عرض') ;

                $('.buttons-excel').addClass('no-transition custom-btn');
                $('.buttons-print').addClass('no-transition custom-btn');
                $('.buttons-collection').addClass('no-transition custom-btn');

                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)

            },
        });
    });


    ///////////////////////////////////////////////////////
</script>
@endsection
