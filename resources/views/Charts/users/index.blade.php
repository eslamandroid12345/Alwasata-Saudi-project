@extends('layouts.content')

@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'Charts') }}  طلبات (مرفوع ، مكتمل): للمستخدم {{$user->name}}
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

        .loadingButton {
            background-color: #0088cc;
            color: azure;
            cursor: not-allowed;
        }

        .reqType {
            width: 2%;
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

    {{-- NEW STYLE   --}}
    <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">

@endsection


@section('customer')

    <!-- MAIN CONTENT-->
    <div class="addUser my-4">
        <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
            <h3> طلبات (مرفوع ، مكتمل): للمستخدم {{$user->name}}</h3>

        </div>
    </div>
    {{-- For Search Parameters   --}}
    @if ($moved->count() > 0)
        <div class="tableBar">
            <div class="topRow">
            <div class="row align-items-center text-center text-md-left">
                <div class="col-lg-2 mt-lg-0 mt-3">
                    <div  id="dt-btns" class="tableAdminOption">

                    </div>
                </div>
            </div>
            <div class="dashTable">
                <table id="data-table" class="table table-bordred table-striped data-table">
                    <thead>
                    <tr>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req num') }}</th>
                        <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'req date') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
                        <th>الرسم الشجري</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($moved as $move)
                            <tr>
                                <th>{{$move->id}}</th>
                                <th style="text-align:center">{{date("d-M-Y",strtotime($move->created_at))}}</th>
                                <th>{{$move->type ?? 'غير محدد'}}</th>
                                <th>{{ $move->user->name}}</th>
                                <th>{{ $move->customer->name}}</th>
                                <th><a href="{{route('admin.request.tree',$move->id)}}" class="btn btn-success btn-sm">
                                        الرسم الشجرى
                                    </a></th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        </div>
        @else
        <div class="middle-screen">
            <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No Users') }}</h2>
        </div>
    @endif

    @if ($completed->count() > 0)
        <div class="tableBar">
            <div class="topRow">
                <div class="row align-items-center text-center text-md-left">
                    <div class="col-lg-2 mt-lg-0 mt-3">
                        <div  id="dt-btnss" class="tableAdminOption">

                        </div>
                    </div>
                </div>
                <div class="dashTable">
                    <table id="completed-table" class="table table-bordred table-striped data-table">
                        <thead>
                        <tr>
                            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req num') }}</th>
                            <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'req date') }}</th>
                            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</th>
                            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</th>
                            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
                            <th>الرسم الشجري</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($completed as $move)
                            <tr>
                                <th>{{$move->id}}</th>
                                <th style="text-align:center">{{date("d-M-Y",strtotime($move->created_at))}}</th>
                                <th>{{$move->type ?? 'غير محدد'}}</th>
                                <th>{{ $move->user->name}}</th>
                                <th>{{ $move->customer->name}}</th>
                                <th><a href="{{route('admin.request.tree',$move->id)}}" class="btn btn-success btn-sm">
                                        الرسم الشجرى
                                    </a></th>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="middle-screen">
            <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No Users') }}</h2>
        </div>
    @endif


@endsection

@section('scripts')

    <script>
        var dt = $('#data-table').DataTable({
            "language": {
                "url": "{{route('datatableLanguage')}}",
                buttons: {
                    excelHtml5: "اكسل",
                    pageLength: "عرض",

                }
            },
            "lengthMenu": [
                [10, 25, 50],
                [10, 25, 50]
            ],
            scrollX: true,
            scrollY: true,
            dom: 'Bfrtip',
            buttons: [
                // 'copyHtml5',
                'excelHtml5',
                // 'csvHtml5',
                // 'pdfHtml5' ,
                'print',
                'pageLength'
            ],

            "initComplete": function(settings, json) {
                $(".paginate_button").addClass("pagination-circle");
                /* To Adaptive with New Design */
                $('#example-search-input').keyup(function(){
                    dt.search($(this).val()).draw() ;
                })

                dt.buttons().container()
                    .appendTo( '#dt-btns' );

                $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr( 'title', 'تصدير' );
                $('.buttons-print').html('<i class="fas fa-print"></i>').attr( 'title', 'طباعة' ) ;
                $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr( 'title', 'عرض' );

                $('.buttons-excel').addClass('no-transition custom-btn');
                $('.buttons-print').addClass('no-transition custom-btn');
                $('.buttons-collection').addClass('no-transition custom-btn');


                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)

                /* To Adaptive with New Design */
            }


        });

        var dt2 = $('#completed-table').DataTable({
            "language": {
                "url": "{{route('datatableLanguage')}}",
                buttons: {
                    excelHtml5: "اكسل",
                    pageLength: "عرض",

                }
            },
            "lengthMenu": [
                [10, 25, 50],
                [10, 25, 50]
            ],
            scrollX: true,
            scrollY: true,
            dom: 'Bfrtip',
            buttons: [
                // 'copyHtml5',
                'excelHtml5',
                // 'csvHtml5',
                // 'pdfHtml5' ,
                'print',
                'pageLength'
            ],

            "initComplete": function(settings, json) {
                $(".paginate_button").addClass("pagination-circle");
                /* To Adaptive with New Design */
                $('#example-search-input').keyup(function(){
                    dt2.search($(this).val()).draw() ;
                })

                dt2.buttons().container()
                    .appendTo( '#dt-btnss' );

                $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr( 'title', 'تصدير' );
                $('.buttons-print').html('<i class="fas fa-print"></i>').attr( 'title', 'طباعة' ) ;
                $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr( 'title', 'عرض' );

                $('.buttons-excel').addClass('no-transition custom-btn');
                $('.buttons-print').addClass('no-transition custom-btn');
                $('.buttons-collection').addClass('no-transition custom-btn');


                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)

                /* To Adaptive with New Design */
            }


        });
    </script>

    <!-- Jquery JS-->
    <script src="{{ url('interface_style/search/vendor/jquery/jquery.min.js') }}"></script>
    <!-- Vendor JS-->
    <script src="{{ url('interface_style/search/vendor/select2/select2.min.js') }}"></script>
    <script src="{{ url('interface_style/search/vendor/jquery-validate/jquery.validate.min.js') }}"></script>
    <script src="{{ url('interface_style/search/vendor/bootstrap-wizard/bootstrap.min.js') }}"></script>
    <script src="{{ url('interface_style/search/vendor/bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}"></script>
    <script src="{{ url('interface_style/search/vendor/datepicker/moment.min.js') }}"></script>
    <script src="{{ url('interface_style/search/vendor/datepicker/daterangepicker.js') }}"></script>

    <!-- Main JS-->
    <script src="{{ url('interface_style/search/js/global.js') }}"></script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
@endsection
