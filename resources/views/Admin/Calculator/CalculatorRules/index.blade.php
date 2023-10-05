@extends('layouts.content')
@section('title')
    إشتراطات الحاسبة
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
            <h3>إشتراطات الحاسبة</h3>
        </div>
    </div>
    <br>
    @if ($calculatorRules > 0)
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
                                <a href="{{route('admin.addNewCalculatorRuleItem')}}">
                                    <button class="mr-2 Cloud">
                                        <i class="fas fa-plus"></i>
                                        {{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}
                                    </button>
                                </a>
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
                        <th>جهة التمويل </th>
                        <th>جهة العمل / القطاع </th>
                        <th>نوع الإشتراط</th>
                        <th>برنامج الحاسبة</th>
                        <th>الدعم السكني </th>
                        <th>الضمانات </th>
                        <th>متضامن </th>
                        <th>إظهار النتائج </th>
                        <th>صافي الراتب من </th>
                        <th>صافي الراتب الى </th>
                        <th> الراتب الأساسي من </th>
                        <th> الراتب الأساسي إلي </th>
                        <th> الراتب التقاعدي من </th>
                        <th> الراتب التقاعدي إلي </th>
                        <th> العمر من </th>
                        <th> العمر إلي </th>
                        <th> مدة أشهر التقاعد من </th>
                        <th> مدة أشهر التقاعد إلي </th>
                        <th> مدة الخدمة بالأشهر من </th>
                        <th> مدة الخدمة بالأشهر إلي </th>
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
                <br>
                <div class="addBtn col-md-5 mt-lg-0 mt-3">
                    <a href="{{route('admin.addNewCalculatorRuleItem')}}">
                        <button class="mr-2 Cloud">
                            <i class="fas fa-plus"></i>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}
                        </button>
                    </a>
                </div>
            </h2>
        </div>
    @endif
@endsection
@section('updateModel')
    @include('Admin.Calculator.jobPositions.confirmationDeleteMsg')
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
                columnDefs: [
                    {"className": "dt-center", "targets": "_all"}
                ],
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5',
                    'print',
                    'pageLength'
                ],
                processing: true,
                serverSide: true,
                ajax: "{{ url('admin/calculator-rules-datatable') }}",
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'bank_id_to_string',
                        name: 'bank_id_to_string'
                    },
                    {
                        data: 'job_position_id_to_string',
                        name: 'job_position_id_to_string'
                    },
                    {
                        data: 'rule_type_to_string',
                        name: 'rule_type_to_string'
                    },
                    {
                        data: 'calculator_program_to_string',
                        name: 'calculator_program_to_string'
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
                        data: 'joint_to_string',
                        name: 'joint_to_string'
                    },
                    {
                        data: 'show_result_to_string',
                        name: 'show_result_to_string'
                    },
                    {
                        data: 'from_salary',
                        name: 'from_salary'
                    },
                    {
                        data: 'salaries_to_string',
                        name: 'salaries_to_string'
                    },{
                        data: 'from_basic_salary',
                        name: 'from_basic_salary'
                    },{
                        data: 'basic_salaries_to_string',
                        name: 'basic_salaries_to_string'
                    },
                    {
                        data: 'from_retirement_salary',
                        name: 'from_retirement_salary'
                    },{
                        data: 'retirement_salaries_to_string',
                        name: 'retirement_salaries_to_string'
                    },{
                        data: 'from_age',
                        name: 'from_age'
                    },{
                        data: 'ages_to_string',
                        name: 'ages_to_string'
                    },{
                        data: 'from_retirement_months',
                        name: 'from_retirement_months'
                    },{
                        data: 'retirement_months_to_string',
                        name: 'retirement_months_to_string'
                    },{
                        data: 'from_job_tenure_months',
                        name: 'from_job_tenure_months'
                    },{
                        data: 'job_tenure_months_to_string',
                        name: 'job_tenure_months_to_string'
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
        $(document).on('click','#remove', function (){
            var job_id = $(this).attr('data-id');
            console.log(job_id);
            $('#confirmDeleteModal').modal('show');
            $('#ok_button').click(function (){
                $.ajax({
                    url: "{{ url('/admin/calculator-rules-remove/') }}",
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
