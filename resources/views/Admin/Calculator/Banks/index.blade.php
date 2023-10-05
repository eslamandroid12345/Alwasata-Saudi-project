@extends('layouts.content')
@section('title')
    جهات التمويل
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
            <h3> جهات التمويل</h3>
        </div>
    </div>
    <br>
    @if ($banks > 0)
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
                                <a href="{{ route('admin.addNewBankPage')}}">
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
                        <th> #</th>
                        <th>الإسم العربي </th>
                        <th>عقار مكتمل </th>
                        <th>عقار غير مكتمل </th>
                        <th>متضامن </th>
                        <th>الضمانات </th>
                        <th>شيك سعي </th>
                        <th>تحمل الضريبة </th>
                        <th> آلية سهل </th>
                        <th>فعال </th>
                        <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($banks['data'] as $bank)
                        <tr>
                            <td>{{$bank['id']}}</td>
                            <td>{{ $bank['name_ar'] }}</td>
                            <td>
                                <input type="checkbox"  data-id="{{ $bank['id'] }}" name="property_completed" class="property_completed" {{  $bank['property_completed']  == 1 ? 'checked' : '' }}>
                            </td>
                            <td>
                                <input type="checkbox"  data-id="{{ $bank['id'] }}" name="property_uncompleted" class="property_uncompleted" {{  $bank['property_uncompleted']  == 1 ? 'checked' : '' }}>
                            </td>
                            <td>
                                <input type="checkbox"  data-id="{{ $bank['id'] }}" name="joint" class="joint" {{  $bank['joint']  == 1 ? 'checked' : '' }}>
                            </td>
                            <td>
                                <input type="checkbox"  data-id="{{ $bank['id'] }}" name="guarantees" class="guarantees" {{  $bank['guarantees']  == 1 ? 'checked' : '' }}>
                            </td>
                            <td>
                                <input type="checkbox"  data-id="{{ $bank['id'] }}" name="quest_check" class="quest_check" {{  $bank['quest_check']  == 1 ? 'checked' : '' }}>
                            </td>
                            <td>
                                <input type="checkbox"  data-id="{{ $bank['id'] }}" name="bear_tax" class="bear_tax" {{  $bank['bear_tax']  == 1 ? 'checked' : '' }}>
                            </td>
                            <td>
                                <input type="checkbox"  data-id="{{ $bank['id'] }}" name="shl" class="shl" {{  $bank['shl']  == 1 ? 'checked' : '' }}>
                            </td>
                            <td>
                                <input type="checkbox"  data-id="{{ $bank['id'] }}" name="active" class="js-switch" {{  $bank['active']  == 1 ? 'checked' : '' }}>
                            </td>
                            <td>
                                <div class="tableAdminOption">
                                    <span  class="item pointer" data-toggle="tooltip" data-placement="top" title="تعديل">
                                        <a id="editBank" data-id="{{$bank['id']}}"><i class="fas fa-edit"></i></a>
                                    </span>
                                    <span class="item pointer" data-toggle="modal" data-placement="top" title="حذف">
                                        <a id="removeBank" data-id="{{$bank['id']}}"><i class="fas fa-trash-alt"></i></a>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="middle-screen">
            <h2 style=" text-align: center;font-size: 20pt;">لم يتم العثور على أية سجلات </h2>
        </div>
    @endif
@endsection
@section('confirmMSG')
    @include('Settings.Forms.confirmationMsg')
    @include('Admin.Calculator.Banks.update_bank_modal')
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
                    [10, 25, 50, -1],
                    [10, 25, 50, "الكل"]
                ],
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5',
                    'print',
                    'pageLength'
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
                },
            });
        });
        //////////////////////////////////////////////////////#
        $(document).on('click','#removeBank', function (){
            var job_id = $(this).attr('data-id');
            console.log(job_id);
            $('#confirmDeleteModal').modal('show');
            $('#ok_button').click(function (){
                $.ajax({
                    url: "{{ url('admin/bank-remove') }}",
                    type: "DELETE",
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


        $(document).on('click', '#editBank', function(e) {
            $(".print-error-msg").find("ul").html('');
            $('.print-error-msg').addClass("alert-success");
            var id = $(this).attr('data-id');
            $.get("{{route('admin.getBank')}}", {
                id: id,
            }, function(data) {
                if (data.status != 0) {
                    $('#form-bank-update').find('#id').val(data.bank.id);
                    $('#form-bank-update').find('#name_ar').val(data.bank.name_ar);
                    $('#form-bank-update').find('#name_en').val(data.bank.name_en);
                    $('#form-bank-update').find('#code').val(data.bank.code);
                    $('#form-bank-update').find('#sort_order').val(data.bank.sort_order);
                    $('#update_bank_modal').modal('show');
                } else
                    $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
            });
        });
        $('#form-bank-update').on('submit', function(e) {
            e.preventDefault();
            var id = $('#id').val();
            console.log(id);
            $.ajax({
                type: "PUT",
                url: "bank/update/" +id,
                data: $('#form-bank-update').serialize(),
                success: function(data) {
                    if($.isEmptyObject(data.error)){
                        if (data.status == 1) {
                            $('#updateJobPositionModal').modal('hide');
                            window.location.reload();
                            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                        } else if (data.status == 0) {
                            $('#msg2').addClass("alert-warning").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message2);
                        }else{
                            $('#update_job_position_modal').modal('hide');
                        }
                    }else{
                        printErrorMsg(data.error);
                    }
                }
            });
        });
        function printErrorMsg (msg) {
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display','block');
            $.each( msg, function( key, value ) {
                $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
            });
        }
    </script>

    <script>let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        elems.forEach(function(html) {
            let switchery = new Switchery(html,  { color: '#186abd', secondaryColor: '#323538', jackColor: '#fff', jackSecondaryColor: '#fff' , size:'small'});

        });
        $(document).ready(function(){
            $('.js-switch').change(function () {
                let active = $(this).prop('checked') === true ? 1 : 0;
                let id = $(this).data('id');
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{route('admin.changeBankStatus')}}',
                    data: {'active': active, 'id': id},
                    success: function (data) {
                        var banksUrl = '{{ route("admin.banks") }}';
                        if (data.status == 1) {
                            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                            window.location.href = banksUrl;
                        } else if (data.status == 0) {
                            $('#msg2').addClass("alert-warning").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                        }
                    }
                });
            });
        });
    </script>

    <script>
        let property_completed = Array.prototype.slice.call(document.querySelectorAll('.property_completed'));
        property_completed.forEach(function(html) {
            let switchery = new Switchery(html,  { color: '#186abd', secondaryColor: '#323538', jackColor: '#fff', jackSecondaryColor: '#fff' , size:'small'});
        });
        $(document).ready(function(){
            $('.property_completed').change(function () {
                let property_completed = $(this).prop('checked') === true ? 1 : 0;
                let id = $(this).data('id');
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{route('admin.changePropertyCompleted')}}',
                    data: {'property_completed': property_completed, 'id': id},
                    success: function (data) {
                        var banksUrl = '{{ route("admin.banks") }}';
                        if (data.status == 1) {
                            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                            window.location.href = banksUrl;
                        } else if (data.status == 0) {
                            $('#msg2').addClass("alert-warning").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                        }
                    }
                });
            });
        });
    </script>

    <script>
        let property_uncompleted = Array.prototype.slice.call(document.querySelectorAll('.property_uncompleted'));
        property_uncompleted.forEach(function(html) {
            let switchery = new Switchery(html,  { color: '#186abd', secondaryColor: '#323538', jackColor: '#fff', jackSecondaryColor: '#fff' , size:'small'});
        });
        $(document).ready(function(){
            $('.property_uncompleted').change(function () {
                let property_uncompleted = $(this).prop('checked') === true ? 1 : 0;
                let id = $(this).data('id');
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{route('admin.changePropertyUnCompleted')}}',
                    data: {'property_uncompleted': property_uncompleted, 'id': id},
                    success: function (data) {
                        var banksUrl = '{{ route("admin.banks") }}';
                        if (data.status == 1) {
                            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                            window.location.href = banksUrl;
                        } else if (data.status == 0) {
                            $('#msg2').addClass("alert-warning").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                        }
                    }
                });
            });
        });
    </script>

    <script>
        let joint = Array.prototype.slice.call(document.querySelectorAll('.joint'));
        joint.forEach(function(html) {
            let switchery = new Switchery(html,  { color: '#186abd', secondaryColor: '#323538', jackColor: '#fff', jackSecondaryColor: '#fff' , size:'small'});
        });
        $(document).ready(function(){
            $('.joint').change(function () {
                let joint = $(this).prop('checked') === true ? 1 : 0;
                let id = $(this).data('id');
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{route('admin.changeJoint')}}',
                    data: {'joint': joint, 'id': id},
                    success: function (data) {
                        var banksUrl = '{{ route("admin.banks") }}';
                        if (data.status == 1) {
                            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                            window.location.href = banksUrl;
                        } else if (data.status == 0) {
                            $('#msg2').addClass("alert-warning").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                        }
                    }
                });
            });
        });
    </script>

    <script>
        let guarantees = Array.prototype.slice.call(document.querySelectorAll('.guarantees'));
        guarantees.forEach(function(html) {
            let switchery = new Switchery(html,  { color: '#186abd', secondaryColor: '#323538', jackColor: '#fff', jackSecondaryColor: '#fff' , size:'small'});
        });
        $(document).ready(function(){
            $('.guarantees').change(function () {
                let guarantees = $(this).prop('checked') === true ? 1 : 0;
                let id = $(this).data('id');
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{route('admin.changeGuarantees')}}',
                    data: {'guarantees': guarantees, 'id': id},
                    success: function (data) {
                        var banksUrl = '{{ route("admin.banks") }}';
                        if (data.status == 1) {
                            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                            window.location.href = banksUrl;
                        } else if (data.status == 0) {
                            $('#msg2').addClass("alert-warning").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                        }
                    }
                });
            });
        });
    </script>

    <script>
        let quest_check = Array.prototype.slice.call(document.querySelectorAll('.quest_check'));
        quest_check.forEach(function(html) {
            let switchery = new Switchery(html,  { color: '#186abd', secondaryColor: '#323538', jackColor: '#fff', jackSecondaryColor: '#fff' , size:'small'});
        });
        $(document).ready(function(){
            $('.quest_check').change(function () {
                let quest_check = $(this).prop('checked') === true ? 1 : 0;
                let id = $(this).data('id');
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{route('admin.changeQuestCheck')}}',
                    data: {'quest_check': quest_check, 'id': id},
                    success: function (data) {
                        var banksUrl = '{{ route("admin.banks") }}';
                        if (data.status == 1) {
                            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                            window.location.href = banksUrl;
                        } else if (data.status == 0) {
                            $('#msg2').addClass("alert-warning").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                        }
                    }
                });
            });
        });
    </script>

    <script>
        let bear_tax = Array.prototype.slice.call(document.querySelectorAll('.bear_tax'));
        bear_tax.forEach(function(html) {
            let switchery = new Switchery(html,  { color: '#186abd', secondaryColor: '#323538', jackColor: '#fff', jackSecondaryColor: '#fff' , size:'small'});
        });
        $(document).ready(function(){
            $('.bear_tax').change(function () {
                let bear_tax = $(this).prop('checked') === true ? 1 : 0;
                let id = $(this).data('id');
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{route('admin.changeBearTax')}}',
                    data: {'bear_tax': bear_tax, 'id': id},
                    success: function (data) {
                        var banksUrl = '{{ route("admin.banks") }}';
                        if (data.status == 1) {
                            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                            window.location.href = banksUrl;
                        } else if (data.status == 0) {
                            $('#msg2').addClass("alert-warning").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                        }
                    }
                });
            });
        });
    </script>

    <script>
        let shl = Array.prototype.slice.call(document.querySelectorAll('.shl'));
        shl.forEach(function(html) {
            let switchery = new Switchery(html,  { color: '#186abd', secondaryColor: '#323538', jackColor: '#fff', jackSecondaryColor: '#fff' , size:'small'});
        });
        $(document).ready(function(){
            $('.shl').change(function () {
                let shl = $(this).prop('checked') === true ? 1 : 0;
                let id = $(this).data('id');
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{route('admin.changeShl')}}',
                    data: {'shl': shl, 'id': id},
                    success: function (data) {
                        var banksUrl = '{{ route("admin.banks") }}';
                        if (data.status == 1) {
                            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                            window.location.href = banksUrl;
                        } else if (data.status == 0) {
                            $('#msg2').addClass("alert-warning").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                        }
                    }
                });
            });
        });
    </script>
@endsection
