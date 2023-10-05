@extends('layouts.content')

@section('title')
المتعاونين
@endsection

@section('css_style')
<link rel="stylesheet" href="{{ asset('css/tokenize2.min.css') }}">
<link rel='stylesheet' href= 'https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.5/css/bulma.css'>
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
        width: 1%;
    }

    .reqType {
        width: 2%;
    }

    .reqDate {
        text-align: center;
    }

    .commentStyle {
        max-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
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




<div id="msg2" class="alert alert-dismissible" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>

</div>

<h3>المتعاونين:</h3>
<br>

<div class="row">


@if ($users > 0)
    <div class="col-12">
        <div class="table-responsive table--no-card m-b-30 data-table-parent">
            <input type="checkbox" id="allreq" onclick="chbx_toggle1(this);" /> تحديد الكل
            <button disabled style="cursor: not-allowed" id="archAll" class="btn btn-danger" style="margin:0 15px" onclick="getReqests1()"><i class="fa fa-trash"></i> {{ MyHelpers::admin_trans(auth()->user()->id,'Archive User') }} </button>

            <br>
            <hr>
            <table class="table table-borderless table-striped table-earning data-table" id="myusers-table">
                <thead>
                    <tr>
                        <th> </th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Username') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'email') }}</th>
                        <th style="text-align:center">استشاري المبيعات</th>
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
    @else
    <div class="middle-screen">
        <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No Users') }}</h2>
    </div>

    @endif


</div>

@endsection


@section('updateModel')
@include('Admin.Users.filterUsers')
@endsection


@section('scripts')



<script src="{{ asset('js/tokenize2.min.js') }}"></script>

<script>
    $('.tokenizeable').tokenize2();
    $(".tokenizeable").on("tokenize:select", function() {
        $(this).trigger('tokenize:search', "");
    });


    $(document).ready(function() {
        var dt = $('.data-table').DataTable({
            "language": {
                "url": "{{route('datatableLanguage')}}",
                buttons: {
                    print: "طباعة",
                    pageLength: "عرض",

                }
            },
            "lengthMenu": [
                [10, 25, 50],
                [10, 25, 50]
            ],
            dom: 'Bfrtip',
            buttons: [
                // 'copyHtml5',
                'excelHtml5',
                // 'csvHtml5',
                // 'pdfHtml5' ,
                'print',
                'pageLength',
                {
                    text: '{{ MyHelpers::admin_trans(auth()->user()->id,"Search") }}',
                    action: function(e, dt, node, config) {
                        $('#myModal1').modal('show');
                    }
                },
            ],
            scrollY: '50vh',
            scrollX: '50vh',
            processing: true,
            serverSide: true,
            ajax: {
                'url': "{{ url('admin/colloberatorusers-datatable') }}",
                'method': 'Get',
                'data': function(data) {
                    let agents_ids = $('#agents_ids').data('tokenize2').toArray();

                    if (agents_ids != '') data['agents_ids'] = agents_ids;

                    })
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
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'salesAgent',
                    name: 'salesAgent'
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
            initComplete: function() {
                let api = this.api();
                $("#filter-search-agent").on('click', function(e) {
                    e.preventDefault();
                    api.draw();
                    $('#myModal1').modal('hide');
                });
            },
            "order": [
                [6, "desc"]
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


    //



    $(function() {
        $('#source').on('tokenize:tokens:add', function(e, value, text) {



            if (value == 2) {


                document.getElementById("collaboratorDiv").style.display = "block";


            }
        });

        $('#source').on('tokenize:tokens:remove', function(e, value) {

            if (value == 2) {


                document.getElementById("collaboratorDiv").style.display = "none";
                document.getElementById("collaborator").value = "";

            }
        });

    });


    $(function() {
        $('#request_type').on('tokenize:tokens:add', function(e, value, text) {



            if (value == "شراء-دفعة") {


                document.getElementById("paystatusDiv").style.display = "block";


            }
        });

        $('#request_type').on('tokenize:tokens:remove', function(e, value) {

            if (value == "شراء-دفعة") {


                document.getElementById("paystatusDiv").style.display = "none";
                document.getElementById("pay_status").value = "";

            }
        });

    });
</script>
@endsection
