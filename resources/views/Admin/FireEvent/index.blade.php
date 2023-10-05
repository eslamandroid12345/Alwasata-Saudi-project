@extends('layouts.content')

@section('title')
 ادوات القياس - التفاعل مع العقارات

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
        <h3> ادوات القياس - التفاعل مع العقارات: </h3>

    </div>
</div>
<br>




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
                </div>
            </div>
            <div class="col-lg-3 mt-lg-0 mt-3">
                {{-- @include('Admin.datatable_display_number') --}}
                <div id="dt-btns" class="tableAdminOption">

                </div>
            </div>
        </div>
    </div>
<!-- data-page-length='5' -->
    <div class="dashTable">
        <table id="pendingReqs-table" class="table table-bordred table-striped data-table" data-page-length='10'>
            <thead>
                <tr>

                    <th> العقار</th>
                    <th> العميل </th>
                    <th> المتعاون </th>
                    <th> نوع التفاعل</th>
                    {{-- <th> عدد مرات التفاعل</th> --}}
                    <th> تاريخ التفاعل</th>
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
@include('Admin.Calculator.jobPositions.confirmationDeleteMsg')
@endsection

@section('scripts')


<script>
    $(document).ready(function() {
        var dt = $('.data-table').DataTable({
           // "pageLength": 50,
            "language": {
                "url": "{{route('datatableLanguage')}}",
                buttons: {
                    excelHtml5: "اكسل",
                    print: "طباعة",
                    // pageLength: "عرض",

                }
            },
            // "lengthMenu": [
            //     [10, 25, 50, -1],
            //     [10, 25, 50, "الكل"]
            // ],
            dom: 'Bfrtip',
            buttons: [
                // 'copyHtml5',
                'excelHtml5',
                // 'csvHtml5',
                // 'pdfHtml5' ,
                'print',
                // 'pageLength'
            ],

            processing: true,
            serverSide: true,
            ajax: "{{ url('admin/fire-events_datatable') }}",
            columns: [
                {data: 'real_state', name: 'real_state',searchable:true},
                {data: 'customer_name', name: 'customer_name',searchable:true},
                {data: 'user_name', name: 'user_name'},
                {data: 'event_name', name: 'event_name',searchable:true},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action'},
            ],
            initComplete: function() {

                // $(".paginate_button").addClass("pagination-circle");
                /* To Adaptive with New Design */
                $('#example-search-input').keyup(function(){
                    dt.search($(this).val()).draw() ;
                });

                //====================draw table when change in display number=====================
                $('#display_number').focusout(function(){
                    dt.page.len( $(this).val()).draw();
                });
                //==================================================================================

                dt.buttons().container().appendTo('#dt-btns');

                // $(".dt-button").last().html('<i class="fas fa-search"></i>').attr('title', 'بحث');
                $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title', 'تصدير');
                $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title', 'طباعة');
                // $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');

                $('.buttons-excel').addClass('no-transition custom-btn');
                $('.buttons-print').addClass('no-transition custom-btn');
                // $('.buttons-collection').addClass('no-transition custom-btn');

                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)

                /* To Adaptive with New Design */

            },
            "order": [
                [4, "desc"]
            ], // Order on init. # is the column, starting at 0
            createdRow: function(row, data, index) {
                // do something

            },
        });
    });

</script>
@endsection
