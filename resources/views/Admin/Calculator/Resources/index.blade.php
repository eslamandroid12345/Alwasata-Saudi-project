@extends('layouts.content')

@section('title')
    المراجع
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
            <h3> المراجع:</h3>

        </div>
    </div>
    <br>


    @if ($resources > 0)

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

                        <th> #</th>
                        <th>اســــم البنك </th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($resources['data'] as $res)
                        <tr>
                            <td>{{$res['id']}}</td>
                            <td>{{ $res['text'] }}</td>

                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    @else
        <div class="middle-screen">
            <h2 style=" text-align: center;font-size: 20pt;">لا توجد ايا مراجع </h2>
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
    </script>
@endsection
