@extends('layouts.content')

@section('title')
تنبيهات الدعم الفني
@endsection

@section('css_style')
{{-- NEW STYLE   --}}
<link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">

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
@endsection


@section('customer')



<div id="msg2" class="alert alert-dismissible" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>

</div>

@if(session()->has('message2'))
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session()->get('message2') }}
</div>
@endif


<div class="addUser my-4">
    <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
        <h3> تنبيهات الدعم الفني  @if (auth()->user()->role == '7') - جديدة @endif:</h3>

    </div>
</div>
@if ($notifiys >0)

<div class="topRow">
    <div class="row align-items-center text-center text-md-left">
        {{-- <div class="col-lg-2">
            <div class="selectAll">
                <div class="form-check">
                    <input type="checkbox" id="allreq" class="form-check-input" onclick="chbx_toggle1(this);" />
                    <label class="form-check-label" for="allreq">تحديد الكل </label>
                </div>
            </div>
        </div> --}}
        <div class="col-lg-9 ">
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
        <div class="col-lg-3 mt-lg-0 mt-3">
            <div id="dt-btns" class="tableAdminOption">

            </div>
        </div>
    </div>
</div>

<div class="dashTable">
    <table id="notifys-table" class="table table-bordred table-striped data-table">
        <thead>
            {{-- <th> </th> --}}
            <th>تاريخ الانشاء</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'content') }}</th>
            <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>
        </thead>
        <tbody>
        </tbody>

    </table>
</div>
@else
<div class="middle-screen">
    <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No Notification') }}</h2>
</div>

@endif


@endsection




@section('updateModel')
@include('All.confirmDelMsg')
@endsection

@section('scripts')

<script>
    $(document).ready(function() {
        var dt = $('#notifys-table').DataTable({
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
            ajax: "{{ url('all/notify-helpdesk_datatable') }}",
            columns: [
                // {
                //     "targets": 0,
                //     "data": "id",
                //     "render": function(data, type, row, meta) {
                //         return '<input type="checkbox" id="chbx" name="chbx[]" onchange="disabledButton()" value="' + data + '"/>';
                //     }
                // },
                {
                    data: 'created_at',
                    name: 'created_at'
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

                $('.buttons-search').html('<i class="fas fa-search"></i>').attr('title', 'بحث');

                $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title', 'طباعة');
                $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');
                //
                $('.buttons-print').addClass('no-transition custom-btn');
                $('.buttons-collection').addClass('no-transition custom-btn');

                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)

            },
        });
    });
</script>
@endsection
