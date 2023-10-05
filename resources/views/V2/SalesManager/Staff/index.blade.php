@extends('layouts.content')
@section('title')
   فريق العمل
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
            <h3>  فريق العمل :</h3>
        </div>
    </div>

    <div class="tableBar">
        <div class="topRow">
            <div class="row align-items-center text-center text-md-left">
                <div class="col-lg-2">

                </div>
                <div class="col-lg-8 ">
                    <div class="tableUserOption  flex-wrap ">

                        <div class="input-group col-md-7 mt-lg-0 mt-3">
                            <input class="form-control py-2" type="search" placeholder="ابحث هنا" id="example-search-input">
                            <span class="input-group-append">
                            <button class="btn btn-outline-info" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                        </div>

                    </div>
                </div>
                <div class="col-lg-2 mt-lg-0 mt-3">
                    <div id="dt-btns" class="tableAdminOption">

                    </div>
                </div>
            </div>
        </div>
        <div class="dashTable">
            <table id="myusers-table" class="table table-bordred table-striped data-table">
                <thead>
                <tr>
                    <th></th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'email') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'role') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'user status') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'registered_on') }}</th>
                    <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>
                </tr>
                </thead>
                <tbody>

                </tbody>

            </table>
        </div>
    </div>
@endsection

@section('updateModel')

@endsection


@section('scripts')
    <script>
        $(document).ready(function () {
            var table = $('#myusers-table').DataTable({
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
                    'excelHtml5',
                    'print',
                    'pageLength'
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url:"{{ url('salesManager/staff-index') }}",
                },
                scrollY: '50vh',
                columns: [
                    {
                        "targets": 0,
                        "data": "id",
                        "render": function (data, type, row, meta) {
                            return '<input type="checkbox" id="chbx" name="chbx[]" onchange="disabledButton()"  value="' + data + '"/>';
                        }
                    },
                    {
                        data: 'name',
                        name: 'name'
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
                        data: 'status',
                        name: 'status'
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
                "order": [
                    [6, "desc"]
                ],
                createdRow: function (row, data, index) {
                    $('td', row).eq(3).addClass('commentStyle');
                    $('td', row).eq(3).attr('title', data.email);
                    $('td', row).eq(1).addClass('reqNum'); // 6 is index of column
                    $('td', row).eq(2).addClass('reqNum'); // 6 is index of column
                    $('td', row).eq(4).addClass('reqNum'); // 6 is index of column
                    $('td', row).eq(5).addClass('reqNum'); // 6 is index of column
                },
                "initComplete": function (settings, json) {
                    $(".paginate_button").addClass("pagination-circle");
                    /* To Adaptive with New Design */
                    $('#example-search-input').keyup(function () {
                        table.search($(this).val()).draw();
                    })
                    $('#role-of-user').change(function(){
                        table.draw();
                    });
                    $('input:radio').on('click', function(e) {
                        table.draw();
                    });
                    table.buttons().container()
                        .appendTo('#dt-btns');
                    $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title', 'تصدير');
                    $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title', 'طباعة');
                    $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');

                    $('.buttons-excel').addClass('no-transition custom-btn');
                    $('.buttons-print').addClass('no-transition custom-btn');
                    $('.buttons-collection').addClass('no-transition custom-btn');
                    $('.tableAdminOption span').tooltip(top)
                    $('button.dt-button').tooltip(top)
                }
            });
        });
        @if(isset($errorSms) && $errorSms)
        window.alertError('{!! $errorSms !!}')
        @endif
    </script>
@endsection
