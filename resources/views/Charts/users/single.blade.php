@extends('layouts.content')

@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'Charts') }}  طلب
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
            <h3> ألرسم الشجرى للطلب</h3>

        </div>
    </div>
    {{-- For Search Parameters   --}}
    <div class="tableBar">
        <div class="row">
            <div class="col-lg-12 center-content">
                <div class="text-center">
                    <button class="card-body b-dark col-lg-3" style="background: #eee">
                        بداية الطلب من العميل
                        <h4>{{$requests->customer->name}}</h4>
                        <small>
                            {{$requests->source}}
                        </small>
                        <span class="badge badge-light">{{date("Y-d-m",strtotime($requests->req_date))}}</span>
                    </button>

                    <span class="fa fa-arrow-down fa-3x d-block"></span>

                </div>
            </div>
        </div>
        @foreach($histories as $key => $history)
            @if($key == 0)
                <div class="row">
                    <div class="col-lg-12 center-content">
                        <div class="text-center">
                            <button class="btn btn-{{$key==0 ? 'danger' :($count != $key ? 'dark bg-gray' : 'success')}} col-lg-3">
                                <h4>{{\App\User::find($history->user_id)->name}}

                                </h4>
                                <small>
                                    {{$history->title}}
                                    <span class="badge badge-light">{{date("Y-d-m",strtotime($history->history_date))}}</span>
                                </small>
                            </button>
                            <span class="fa fa-arrow-down fa-3x d-block"></span>
                        </div>

                    </div>

                </div>
            @endif
            <div class="row">
                <div class="col-lg-12 center-content">

                    <div class="text-center">

                        <button class="btn btn-{{$requests->user_id==$history->recive_id ? 'success dark bg-gray' : 'dark bg-gray'}} col-lg-3">
                            <h4>
                                {{\App\User::find($history->recive_id)->name}}</h4>
                            <small>
                                {{$history->title}}
                                <span class="badge badge-light">{{date("Y-d-m",strtotime($history->history_date))}}</span>
                            </small>
                        </button>
                        <span class="fa fa-arrow-down fa-3x d-block"></span>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="row">
            <div class="col-lg-12 center-content">
                <div class="text-center">
                    <button class="card-body  bt-dark col-lg-3" style="background: #eee">

                        {{($requests->class_id_agent == 58 || $requests->statusReq == 16 || $requests->statusReq ==26) == 1 ? 'طلب مكتمل' : 'طلب مرفوع'}}
                        <h4>{{$requests->customer->name}}</h4>
                        <small>
                            {{$requests->source}}
                        </small>
                        <span class="badge badge-light">{{date("Y-d-m",strtotime($requests->req_date))}}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('scripts')

    <script>
        var dt = $('.data-table').DataTable({
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
