@extends('layouts.content')


@section('title')
    الطلبات المكررة من نفس الأى بى
@endsection

@section('css_style')
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
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

        .reqType {
            width: 2%;
        }

        tr:hover td {
            background: #d1e0e0
        }
        .swal-text{
            text-align: center;
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
    <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">
@endsection

@section('customer')



    @if(!empty($message))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ $message }}
        </div>
    @endif

    @if ( session()->has('message') )
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('message') }}
        </div>
    @endif

    @if ( session()->has('message2') )
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('message2') }}
        </div>
    @endif

    @if($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <ul>
                    <li>{{ $error }}</li>
                </ul>

            </div>
        @endforeach
    @endif

    @if(!empty($message))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ $message }}
        </div>
    @endif

    @if ( session()->has('message') )
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('message') }}
        </div>
    @endif



    <div id="msg2" class="alert alert-dismissible" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>

    </div>

    <div class="addUser my-4 row">
        <div class="userBlock d-flex align-items-center justify-content-between flex-wrap col-lg-10">
            <h3> الطلبات المكررة من نفس الأى بى:</h3>
        </div>
    </div>

    <br>



    <div class="tableBar">
        <div class="topRow">
            <div class="row align-items-center text-center text-md-left">
                <div class="col-lg-7 ">

                    <div class="tableUserOption  flex-wrap ">

                        <div class="input-group col-md-6 mt-lg-0 mt-3">
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
                    <div id="tableAdminOption" class="tableAdminOption">
                        <div id="dt-btns" class="tableAdminOption">
                            {{-- Here We Will Add Buttons of Datatable  --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashTable">
            <table id="pendingReqs-table" class="table table-bordred table-striped data-table">
                <thead>
                <tr>

                    <th style="max-width: 5%">#</th>
                    <th>ip</th>
                    <th>عدد مرات الطلب</th>
                    <th>عدد الطلبات الفعلى</th>
                    <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>



                </tr>
                </thead>
                <tbody>

                </tbody>

            </table>
        </div>
    </div>

@endsection

@section('scripts')

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/tokenize2.min.js') }}"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>


    <script>
        $('.tokenizeable').tokenize2();
        $(".tokenizeable").on("tokenize:select", function() {
            $(this).trigger('tokenize:search', "");
        });
        $(document).ready(function() {
            $('#example-search-input').val('');
            var dt = $('.data-table').DataTable({
                "language": {
                    "url": "{{route('datatableLanguage')}}",
                    buttons: {
                        // excelHtml5: "اكسل",
                        // print: "طباعة",
                        // pageLength: "عرض",

                    }
                },
                "lengthMenu": [
                    [10, 25, 50],
                    [10, 25, 50]
                ],
                dom: 'Bfrtip',
                buttons: [
                    // 'copyHtml5',
                    // 'excelHtml5',
                    // 'csvHtml5',
                    // 'pdfHtml5' ,
                    // 'print',
                    // 'pageLength',
                    {{--{--}}
                    {{--    text: '{{ MyHelpers::admin_trans(auth()->user()->id,"Search") }}',--}}
                    {{--    action: function(e, dt, node, config) {--}}
                    {{--        $('#myModal').modal('show');--}}
                    {{--    }--}}
                    {{--}--}}
                ],
                scrollY: true,
                scrollX: true,
                scrollCollapse: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': "{{ url('admin/ip-addresses-datatable') }}",
                    'method': 'Get'
                },
                columns: [
                    {
                        data: 'idn',
                        name: 'idn'
                    },
                    {
                        data: 'ip',
                        name: 'ip'
                    },
                    {
                        data: 'counts',
                        name: 'counts'
                    },
                    {
                        data: 'count',
                        name: 'count'
                    }
                    ,{
                        data: 'actions',
                        name: 'actions'
                    }

                ],
                rowCallback: function( row, data, index ) {
                    console.log(data['count'])
                    if (data['count'] <= 1) {
                        $(row).hide();
                    }
                },
                initComplete: function() {
                    let api = this.api();

                    $("#filter-search-req").on('click', function(e) {
                        e.preventDefault();
                        api.draw();
                        //checktable(api);
                        $('#myModal').modal('hide');
                    });

                    $(".paginate_button").addClass("pagination-circle");
                    /* To Adaptive with New Design */
                    $('#example-search-input').change(_.debounce(function() {
                        dt.search($(this).val()).draw();
                    },500))


                    // dt.buttons().container().appendTo('#dt-btns');
                    // $(".dt-button").last().html('<i class="fas fa-search"></i>').attr('title', 'بحث');
                    // $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title', 'تصدير');
                    // $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title', 'طباعة');
                    // $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');
                    // $('.buttons-excel').addClass('no-transition custom-btn');
                    // $('.buttons-print').addClass('no-transition custom-btn');
                    // $('.buttons-collection').addClass('no-transition custom-btn');
                    // $('.tableAdminOption span').tooltip(top)
                    // $('button.dt-button').tooltip(top)

                },
                createdRow: function(row, data, index) {
                    $('td', row).eq(9).addClass('commentStyle'); // 6 is index of column
                    $('td', row).eq(9).attr('title', data.comment); // to show other text of comment
                    $('td', row).eq(5).addClass('commentStyle'); // 6 is index of column
                    $('td', row).eq(5).attr('title', data.customer_name); // to show other text of comment
                    $('td', row).eq(1).addClass('reqDate'); // 6 is index of column
                },
            });
        });

    </script>
@endsection
