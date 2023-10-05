@extends('layouts.content')
@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'extra_funding_years_calculator_settings') }}
@endsection
@section('css_style')
    <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">
@endsection
@section('customer')
    @if(session()->has('message'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('message') }}
        </div>
    @elseif(\Session::has('msg'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {!! \Session::get('msg') !!}
        </div>
    @else
    @endif
    @if(session()->has('message2'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('message2') }}
        </div>
    @endif
    @if(Session::has('errors'))
        <script>
            $(document).ready(function(){
                $('#updateJobPositionModal').modal({show: true});
            });
        </script>
    @endif
    <div id="msg2" class="alert alert-dismissible" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <div class="addUser my-4">
        <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
            <h3> {{ MyHelpers::admin_trans(auth()->user()->id,'extra_funding_years_calculator_settings') }}</h3>
        </div>
    </div>
    <br>
    @if ($extraFundingYears > 0)
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
                        <th> الرقم </th>
                        <th>البنك </th>
                        <th>المستخدم </th>
                        <th>العمل </th>
                        <th>الدعم السكني </th>
                        <th>الضمانات </th>
                        <th>السنوات </th>
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
            <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'no_job_positions_found') }}
            </h2>
        </div>
    @endif
@endsection
@section('updateModel')
    @include('Suggestions.Vote.reason')
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
                    [10, 25, 50],
                    [10, 25, 50]
                ],
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5',
                    'print',
                    'pageLength'
                ],
                processing: true,
                serverSide: true,
                ajax: "{{ url('all/suggestions-years-datatable') }}",
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'text',
                        name: 'text'
                    },
                    {
                        data: 'user',
                        name: 'user'
                    },
                    {
                        data: 'job_position_id_to_string',
                        name: 'job_position_id_to_string'
                    },
                    {
                        data: 'residential_support_to_string',
                        name: 'residential_support_to_string'
                    },
                    {
                        data: 'guarantees_to_string',
                        name: 'guarantees_to_string'
                    },
                    {
                        data: 'years',
                        name: 'years'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
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
                createdRow: function(row, data, index) {
                    $('td', row).eq(0).addClass('reqNum');
                    $('td', row).eq(2).addClass('reqNum');
                },
            });
        });
        //////////////////////////////////////////////////////#
        function RejectForm(id) {
            save_method = "add";
            $('#add-form input[name=_method]').val('POST');
            $('#add-form').modal('show');
            $('#add-form #id').val(id);
            $('#add-form form')[0].reset();
            $('#add-form .modal-title').text('من فضلك وضح سبب الرفض');
        }
        function ApproveForm(id) {
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'هل انت متأكد',
                text: "هل انت موافق على هذا المقترح لن تكون قادر على الرجوع ؟",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                buttons: ["إلغاء","نعم , موافق على المقترح !"],
            }).then(function(inputValue) {
                if (inputValue != null) {
                    $.ajax({
                        url: "{{ route('all.suggestions.vote') }}",
                        type: "POST",
                        data: {
                            '_method': 'POST',
                            '_token': csrf_token,
                            'id':id,
                            'vote':'yes',
                            'suggestable_type': "FundingYear"
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
                    url = "{{ route('all.suggestions.vote') }}";
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: new FormData($("#form-contact")[0]),
                        contentType: false,
                        processData: false,
                        success: function(data) {
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

        $(document).on('click','#remove', function (){
            var job_id = $(this).attr('data-id');
            console.log(job_id);
            $('#confirmDeleteModal').modal('show');
            $('#ok_button').click(function (){
                $.ajax({
                    url: "{{ url('/admin/extra-funding-year/remove/') }}",
                    type: "POST",
                    data: {  _token: '{{csrf_token()}}', id: job_id },

                    beforeSend:function ()
                    {
                        $('#ok_button').text('جاري الحذف ...');
                    },
                    success:function (data)
                    {
                        setTimeout(function (){
                            $('#confirmDeleteModal').modal('hide');
                            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                            window.location.reload();
                            }, 1000);
                    }
                })
            });
        });
        /////////////////////////////////////////////////////////
    </script>
@endsection
