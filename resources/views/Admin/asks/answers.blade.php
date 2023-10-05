@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Active Customers') }}
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

    .modal-backdrop {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: -2 !important;
        background-color: #000;
    }
</style>
{{-- NEW STYLE   --}}
<link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">
@endsection
@section('customer')
<div>
    @if (session('msg'))
    <div id="msg" class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session('msg') }}
    </div>
    @endif
</div>
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
        <h3> عملاء إلغاء الطلب:</h3>

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
                <div id="dt-btns" class="tableAdminOption">

                </div>
            </div>
        </div>
    </div>

    <div class="dashTable">
        <table id="pendingReqs-table" class="table table-bordred table-striped data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>المصدر</th>
                    <th>العميل</th>
                    <th>الموظف المسؤل</th>
                    <th>عدد الأسئلة </th>
                    <th> النسبة </th>
                    <th>نعم </th>
                    <th>لا </th>
                    <th>لم يجيب </th>
                    <th> عدد المرات</th>
                    <th> التاريخ</th>
                    <th>التحكم</th>
                </tr>
            </thead>
            <tbody>

            </tbody>

        </table>
    </div>
</div>

@include('Admin.asks.details')
@endsection
@section('css_style')
{{--Sweet Alert--}}
<script src="{{ asset('backend/sweetalert/sweetalert2.min.js') }}"></script>
<link href="{{ asset('backend/sweetalert/sweetalert2.min.css') }}" rel="stylesheet">
@endsection
@section('scripts')

<script>
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

            processing: true,
            serverSide: true,
            ajax: "{{ route('canclereq.answers') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'source',
                    name: 'source'
                },
                {
                    data: 'customer',
                    name: 'customer'
                },
                {
                    data: 'user',
                    name: 'user'
                },
                {
                    data: 'count',
                    name: 'count'
                },
                {
                    data: 'percentage',
                    name: 'percentage'
                },
                {
                    data: 'yes',
                    name: 'yes'
                },
                {
                    data: 'no',
                    name: 'no'
                },
                {
                    data: 'not',
                    name: 'not'
                },
                {
                    data: 'batch',
                    name: 'batch'
                },
                {
                    data: 'date',
                    name: 'date'
                },

                {
                    data: 'action',
                    name: 'action'
                }
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


    function PrviewForm(id) {
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: "{{ url('admin/survey-answers/') }}" + '/' + id,
            type: "POST",
            data: {
                '_method': 'GET',
                '_token': csrf_token
            },
            success: function(data) {
                $('#preview-form').modal('show');
                $('#preview-form #details').html(" ").html(data);
            },
            error: function() {
                swal({
                    title: 'خطأ',
                    text: data.message,
                    type: 'خطأ',
                    timer: '750'
                })
            }
        });
    }
</script>
@endsection
