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

    .tooltips {
        position: relative;
        display: inline-block;
        border-bottom: 1px dotted black;
    }

    .tooltips .tooltipstext {
        visibility: hidden;
        width: 120px;
        background-color: black;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;
        position: absolute;
        z-index: 1;
        bottom: 150%;
        left: 50%;
        margin-left: -60px;
    }

    .tooltips .tooltipstext::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: black transparent transparent transparent;
    }

    .tooltips:hover .tooltipstext {
        visibility: visible;
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
        <h3> أسئلة إلغاء الطلب:</h3>

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
                    <div class="addBtn col-md-5 mt-lg-0 mt-3">

                        <button class="mr-2 Cloud" data-toggle="modal" onclick="addForm()">
                            <i class="fas fa-plus"></i>
                            إضافة سؤال
                        </button>

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
                    <th> </th>
                    <th>#</th>
                    <th>السؤال</th>
                    <th>الحالة</th>
                    <th>نعم </th>
                    <th>لا </th>
                    <th>لم يجيب </th>
                    <th>التحكم</th>
                </tr>
            </thead>
            <tbody>

            </tbody>

        </table>
    </div>
</div>

@include('Admin.asks.add')
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
            ajax: "{{ route('canclereq.asks') }}",
            columns: [

                {
                    "targets": 0,
                    "data": "id",
                    "render": function(data, type, row, meta) {
                        return '<input type="checkbox" id="chbx" name="chbx[]"  onchange="disabledButton()"  value="' + data + '"/>';
                    }
                },


                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'question',
                    name: 'question'
                },
                {
                    data: 'active',
                    name: 'active'
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

    function addForm() {
        save_method = "add";
        $('#add-form input[name=_method]').val('POST');
        $('#add-form').modal('show');
        $('#add-form form')[0].reset();
        $('#add-form .modal-title').text('إضافــــــــة سؤال');

    }

    function editForm(id) {
        save_method = 'edit';
        $('input[name=_method]').val('PATCH');
        $('#add-form form')[0].reset();
        $('#add-form .modal-title').text('تعديل السؤال');
        $.ajax({
            url: "{{ url('admin/asks') }}" + '/' + id + "/edit",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#add-form').modal('show');
                $('#add-form #id').val(data.id);
                $('#add-form #question').val(data.question);
            },
            error: function() {
                alert("Nothing Data");
            }
        });
    }

    function ApproveData(id) {
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: "{{ url('admin/asks-activate/') }}" + '/' + id,
            type: "POST",
            data: {
                '_method': 'GET',
                '_token': csrf_token
            },
            success: function(data) {
                $('.data-table').DataTable().ajax.reload();
                swal({
                    title: 'تم!',
                    text: data.message,
                    type: 'success',
                    timer: '750'
                })
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

    function deleteData(id) {
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        swal({
            title: 'هل انت متأكد',
            text: "لن تكون قادر على التراجع فى هذا الأمر ؟",
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonColor: '#3085d6',
            buttons: ["إلغاء","نعم , احذف !"],
        }).then(function(inputValue) {
            if (inputValue != null) {
                $.ajax({
                    url: "{{ url('admin/asks/') }}" + '/' + id,
                    type: "POST",
                    data: {
                        '_method': 'DELETE',
                        '_token': csrf_token
                    },
                    success: function(data) {
                        $('.data-table').DataTable().ajax.reload();
                        swal({
                            title: 'تم!',
                            text: 'تم حذف السؤال',
                            type: 'success',
                            timer: '750'
                        })
                    },
                    error: function() {
                        swal({
                            title: 'خطأ',
                            text: data.message,
                            type: 'error',
                            timer: '750'
                        })
                    }
                });
            } else {

            }

        });
    }
    $(function() {
        $('#form-contact').on('submit', function(e) {
            if (!e.isDefaultPrevented()) {
                var id = $('#id').val();
                if (save_method == 'add') url = "{{ url('admin/asks') }}";
                else url = "{{ url('admin/asks') . '/' }}" + id;

                $.ajax({
                    url: url,
                    type: "POST",
                    //                        data : $('#modal-form form').serialize(),
                    data: new FormData($("#form-contact")[0]),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (data.errors) {
                            if (data.errors.question) {
                                $('#question-error').html(data.errors.question[0]);
                            }
                        }
                        if (data.success) {
                            $('#add-form').modal('hide');
                            $('.data-table').DataTable().ajax.reload();
                            swal({
                                title: 'تم!',
                                text: data.message,
                                type: 'success',
                                timer: '750'
                            })
                        }
                    },
                    error: function(data) {
                        swal({
                            title: 'خطأ',
                            text: data.message,
                            type: 'error',
                            timer: '750'
                        })
                    }
                });
                return false;
            }
        });
    });
</script>
@endsection
