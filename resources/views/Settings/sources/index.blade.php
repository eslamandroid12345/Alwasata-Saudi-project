@extends('layouts.content')

@section('title')
جميع مصادر المعاملات
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


    td {
        text-align: center;
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
        <h3> مصادر المعاملات :</h3>

    </div>
</div>
<br>


@if ($classes > 0)

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
                        <a href="{{route('admin.add.source')}}">
                            <button class="mr-2 Cloud">
                                <i class="fas fa-plus"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}  مصدر معاملة
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
                    <th style="text-align:center">#</th>
                    <th style="text-align:center">الإسم </th>
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
    <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No Classification') }}</h2>
</div>

@endif



@endsection

@section('updateModel')
@include('Settings.sources.edit',['RoleSelected' => $RoleSelected])
@include('Settings.sources.alert')
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
            ajax: "{{ url('admin/setting-sources-datatable') }}",
            columns: [

                {
                    data: 'idn',
                    name: 'idn'
                },
                {
                    data: 'value',
                    name: 'value'
                },
                {
                    data: 'action',
                    name: 'action'
                }
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
            createdRow: function(row, data, index) {


                $('td', row).eq(0).addClass('commentStyle');
                $('td', row).eq(0).attr('title', data.value);


            },
        });
    });


    //////////////////////////////////////////////////////#

    $(document).on('click', '#edit', function(e) {


        $('#classError').addClass("d-none");
        $('#roleError').addClass("d-none");


        var id = $(this).attr('data-id');

        $.get("{{route('admin.get.source')}}", {
            id: id
        }, function(data) {

            // console.log(data);

            if (data.status != 0) {

                $('#frm-update').find('#id').val(data.class.id);
                $('#frm-update').find('#source').val("").val(data.class.value);
                $('#frm-update').find('#role').val(data.class.role);
                $('#myModal').modal('show');


            } else
                $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);


        });



    });
    ///////////////////////////////////////////

    $('#frm-update').on('submit', function(e) {


        $('#classError').addClass("d-none");
        $('#roleError').addClass("d-none");


        e.preventDefault();
        var data = $(this).serialize();
        var url = $(this).attr('action');



        $.post(url, data, function(data) { //data is array with two veribles (request[], ss)

            // console.log(data);

            if (data.status == 1) {

                $('.data-table').DataTable().ajax.reload();

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
                $.post("{{ route('admin.delete.source')}}", {
                    id: id,
                    _token: "{{csrf_token()}}",
                }, function(data) {
                    if (data.status == 1) {
                        $('.data-table').DataTable().ajax.reload();
                        $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                    } else {
                        $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                    }
                });
            } else {
            }
        });
    });
</script>
@endsection
