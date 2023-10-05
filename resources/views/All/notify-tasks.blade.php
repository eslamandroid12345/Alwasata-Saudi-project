@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'tasks') }}
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
        <h3> {{ MyHelpers::admin_trans(auth()->user()->id,'tasks') }}  @if (auth()->user()->role == '7') - جديدة @endif:</h3>

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

    <table id="" class="table table-bordred table-striped data-table">
        <thead>
        <tr>
            <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'task date') }}</th>
            <th> نص التنبية</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'status task') }}</th>
            <th>مرسل التذكرة</th>
            <th>مستلم التذكرة</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Content') }} {{ MyHelpers::admin_trans(auth()->user()->id,'the task') }}</th>
            <th>الرد على <br> التذكرة</th>
            <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>
        </tr>
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
        var dt = $('.data-table').DataTable({
            "language": {
                "url": "{{route('datatableLanguage')}}",
                buttons: {
                    print: "طباعة",
                    pageLength: "عرض",

                }
            },
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "lengthMenu": [
                [10, 25, 50],
                [10, 25, 50]
            ],
            dom: 'Bfrtip',
            buttons: [
                // 'copyHtml5',
                //'excelHtml5',
                // 'csvHtml5',
                // 'pdfHtml5' ,
                'print',
                'pageLength'
            ],
            processing: true,
            serverSide: true,
            ajax: {
                'url': "{{ url('all/notify-tasks_datatable') }}",
                'data': function(data) {


                },
            },
            columns: [

                {
                    "targets": 0,
                    "data": "created_at",
                    "render": function(data, type, row, meta) {
                        return data.split(" ").join("<br/>");
                    }


                },
                {
                    data: 'notify_type',
                    name: 'notify_type'
                },
                {
                    data: 'status',
                    name: 'tasks.status'
                },
                {
                    data: 'user_name',
                    name: 'user.name'
                },
                {
                    data: 'recive_name',
                    name: 'recive.name'
                },
                {
                    data: 'name',
                    name: 'customers.name'
                },
                {
                    data: 'content',
                    name: 'content'
                },
                {
                    data: 'user_note',
                    name: 'user_note'
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ],
            "order": [
                [0, "desc"]
            ],
            createdRow: function(row, data, index) {

                $('td', row).eq(5).addClass('commentStyle'); // 6 is index of column
                $('td', row).eq(5).attr('title', data.content); // to show other text of comment

                $('td', row).eq(6).addClass('commentStyle'); // 6 is index of column
                $('td', row).eq(6).attr('title', data.user_note); // to show other text of comment

                $('td', row).eq(0).addClass('reqDate'); // 6 is index of column
            },
            initComplete: function() {
                let api = this.api();
                $("#filter-search-req").on('click', function(e) {
                    e.preventDefault();
                    api.draw();
                    $('#myModal').modal('hide');
                });

                $(".paginate_button").addClass("pagination-circle");
                /* To Adaptive with New Design */
                $('#example-search-input').keyup(function(){
                    dt.search($(this).val()).draw() ;
                })



                dt.buttons().container()
                    .appendTo( '#dt-btns' );


                $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title','طباعة') ;
                $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title','عرض') ;
                //
                $('.buttons-print').addClass('no-transition custom-btn');
                $('.buttons-collection').addClass('no-transition custom-btn');

                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)
            },
        });
    });


    //
</script>
@endsection
