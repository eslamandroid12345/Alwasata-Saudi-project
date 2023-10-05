@extends('layouts.content')

@section('title')
المستخدمين
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
        <h3>تم رؤية التعميمات من قبل:</h3>
    </div>
</div>
<br>




<div class="tableBar">
    <div class="topRow">
        <div class="row align-items-center text-center text-md-left">
            <div class="col-lg-6 ">

            </div>
            <div class="col-lg-3"></div>
            <div class="col-lg-3 mt-lg-0 mt-3">
                <div id="dt-btns" class="tableAdminOption">

                </div>
            </div>
        </div>
    </div>
    <div>
        <h6><b>محتوى التعميمات</b></h6>
        {{$announce->content}}
    </div>
    <div class="dashTable">
        <table class="table table-bordred table-striped data-table" id="announce-table">
            <thead>
                <tr>
                    <th>التاريخ</th>
                    <th>المستخدم</th>
                </tr>
            </thead>


            @foreach($users_seen as $user_seen)
            <tr>
                <td>{{$user_seen->created_at}}</td>
                <td>{{$user_seen->name}}</td>
            </tr>
            @endforeach

            <tbody>
            </tbody>
        </table>
    </div>
</div>



@endsection



@section('scripts')


<script>
    $(document).ready(function() {

        var dt = $('#announce-table').DataTable({
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
                    data: 'name',
                    name: 'name'
                },
            ],
            initComplete: function() {

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

                $('#announce-table').DataTable().ajax.reload();

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

                        $('#announce-table').DataTable().ajax.reload();

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
