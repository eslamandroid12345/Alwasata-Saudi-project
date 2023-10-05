@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Announcements') }}
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

    table {
        text-align: center;
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
        <h3> {{ MyHelpers::admin_trans(auth()->user()->id,'Announcements') }}:</h3>

    </div>
</div>
<br>



@if ($announcements->count() > 0)

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
                        <a href="{{ route('admin.addAnnouncePage')}}">
                            <button class="mr-2 Cloud">
                                <i class="fas fa-plus"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Announce') }}
                            </button>
                        </a>
                    </div>

                </div>
            </div>
            <div class="col-lg-3 mt-lg-0 mt-3">
                <div class="input-group col-md-7 mt-lg-0 mt-3 ">
                    <input class="form-control py-2" type="number" value="10" id="display_number" placeholder=" عدد نتائج العرض" style="margin-bottom: -35px;">
                </div>
                <div id="dt-btns" class="tableAdminOption">

                </div>
            </div>
        </div>
    </div>

    <div class="dashTable">
        <table id="pendingReqs-table" class="table table-bordred table-striped data-table">
            <thead>
                <tr>

                    <th>تاريخ التعميمات</th>
                    <th>نص التعميمات</th>
                    <th>حالة التعميمات</th>
                    <th>تاريخ النهاية</th>
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
    <h2 style=" text-align: center;font-size: 20pt;">لايوجد أي تعميمات</h2>
</div>

@endif


@endsection

@section('updateModel')
@include('Admin.Announcements.confirmDeleteMsg')
@endsection


@section('scripts')


<script>
    $(document).ready(function() {
        var dt = $('.data-table').DataTable({
            "language": {
                "url": "{{route('datatableLanguage')}}",
                buttons: {
                    excelHtml5: "اكسل",
                    print: "طباعة",
                   // pageLength: "عرض",

                }
            },
            // "lengthMenu": [
            //     [10, 25, 50, -1],
            //     [10, 25, 50, "الكل"]
            // ],
            dom: 'Bfrtip',
            buttons: [
                // 'copyHtml5',
                'excelHtml5',
                // 'csvHtml5',
                // 'pdfHtml5' ,
                'print',
             //   'pageLength'
            ],

            processing: true,
            serverSide: true,
            ajax: "{{ url('admin/announcements-datatable') }}",
            columns: [

                {
                    "targets": 0,
                    "data": "created_at", // first history related to the request
                    "name": "created_at",
                    "render": function(data, type, row, meta) {
                        return data.split(" ").join("<br/>");
                    }


                },
                {
                    data: 'content',
                    name: 'content'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'end_at',
                    name: 'end_at'
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ],
            initComplete: function() {
                
                //====================draw table when change in display number=====================
                $('#display_number').focusout(function(){
                    dt.page.len( $(this).val()).draw();
                });
                //==================================================================================
                dt.buttons().container()
                    .appendTo('#dt-btns');

                $(".dt-button").last().html('<i class="fas fa-search"></i>').attr('title', 'بحث');
                $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title', 'تصدير');
                $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title', 'طباعة');
              //  $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');

                $('.buttons-excel').addClass('no-transition custom-btn');
                $('.buttons-print').addClass('no-transition custom-btn');
              //  $('.buttons-collection').addClass('no-transition custom-btn');

                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)

                /* To Adaptive with New Design */

            },
            "order": [
                [0, "desc"]
            ], // Order on init. # is the column, starting at 0
            createdRow: function(row, data, index) {


                $('td', row).eq(1).addClass('commentStyle');
                $('td', row).eq(1).attr('title', data.content);

            },
        });
    });

    //-----------------------------------




    $(document).on('click', '#active', function(e) {


        var id = $(this).attr('data-id');
        $.post("{{route('admin.updateAnnounceStatus')}}", {
            id: id,
            _token: "{{csrf_token()}}",
        }, function(data) {

            if (data != 0) {

                $('.data-table').DataTable().ajax.reload();

            }
        })
    });

    ///////////////////////////////////////////

    $(document).on('click', '#archive', function(e) {
        var id = $(this).attr('data-id');

        var modalConfirm = function(callback) {


            $("#mi-modal3").modal('show');


            $("#modal-btn-si3").on("click", function() {
                callback(true);
                $("#mi-modal3").modal('hide');
            });

            $("#modal-btn-no3").on("click", function() {
                callback(false);
                $("mi-modal3").modal('hide');
            });
        };

        modalConfirm(function(confirm) {
            if (confirm) {


                $.post("{{ route('admin.deleteAnnounce')}}", {
                    id: id,
                    _token: "{{csrf_token()}}",
                }, function(data) { // we pass var id in the request with id as name ; we use $.post : because we pass our data to server and got response

                    //console.log(data);
                    if (data.status == 1) {

                        $('.data-table').DataTable().ajax.reload();

                    } else {

                        $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

                    }



                });



            } else {
                //No delete
            }
        });


    });
</script>
@endsection
