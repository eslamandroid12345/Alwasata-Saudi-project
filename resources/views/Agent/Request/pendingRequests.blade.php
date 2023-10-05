@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Additional Requests') }}
@endsection


@section('css_style')
<link rel="stylesheet" href="{{ asset('css/tokenize2.min.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ url("/") }}/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
<script src="{{ asset('backend/sweetalert/sweetalert2.min.js') }}"></script>
<link href="{{ asset('backend/sweetalert/sweetalert2.min.css') }}" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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
    }

    td {
        width: 15%;
    }

    .reqNum {
        width: 1%;
    }

    .data-table-coulmn {
        text-align: center;
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
</style>

{{-- NEW STYLE   --}}
<link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">


@endsection

@section('customer')


<div id="msg" class="alert alert-dismissible" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>


<div class="addUser my-4">
    <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
        <h3> {{ MyHelpers::admin_trans(auth()->user()->id,'Additional Requests') }}:</h3>
    </div>
    <p style="text-align: right; color:black; ">بإمكانك اختيار الطلب ، من خلال الضغط على خيار "سحب الطلب" :</p>
</div>
<br>

@if (count($pending_requests) > 0)

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

                    <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'req date') }}</th>
                    <th> اسم العميل</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'birth_date') .' '.
                            MyHelpers::admin_trans(auth()->user()->id,'hijri') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'is supported') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'has_property') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'has_joint') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'has obligations') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'has financial distress') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'has_owning_property') }}</th>
                    {{-- <th>{{ MyHelpers::admin_trans(auth()->user()->id,'birth_date') }}</th>--}}
                    {{-- <th>{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</th> --}}
                    {{-- <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th> --}}
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
    <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No New Requests') }}</h2>
</div>

@endif


@endsection


@section('updateModel')
@include('Agent.Request.filterReqsPending')
@include('Agent.Request.confirmPendingMsg')
@endsection

@section('scripts')
<script src="{{ asset('js/tokenize2.min.js') }}"></script>
<script src="{{ url('/') }}/js/bootstrap-hijri-datetimepicker.min.js"></script>
<script src="{{ url('/') }}/js/notify.min.js"></script>



<script type="text/javascript">
    $(document).ready(function() {
        $.notify.defaults({
            globalPosition: 'top left'
        })


        $("#customer-birth-from").hijriDatePicker({
            hijri: true,
            format: "YYYY/MM/DD",
            hijriFormat: 'iYYYY-iMM-iDD',
            showSwitcher: false,
            showTodayButton: true,
            showClose: true
        });

        $("#customer-birth-to").hijriDatePicker({
            hijri: true,
            format: "YYYY/MM/DD",
            hijriFormat: 'iYYYY-iMM-iDD',
            showSwitcher: false,
            showTodayButton: true,
            showClose: true
        });


    });
</script>

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
            scrollX: true,
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
                'pageLength',
                {
                    text: '{{ MyHelpers::admin_trans(auth()->user()->id,"Search") }}',
                    className: 'buttons-search',
                    action: function(e, dt, node, config) {
                        $('#myModal').modal('show');
                    }
                },
            ],
            processing: true,
            serverSide: true,
            ajax: {
                'url': "{{ url('agent/additional/request/datatable') }}",
                'data': function(data) {

                    let work_source = $('#work_source').data('tokenize2').toArray();

                    let customer_salary_from = $('#customer-salary-from').val();
                    let customer_salary_to = $('#customer-salary-to').val();


                    let is_supported = $('#is_supported').val();
                    let has_property = $('#has_property').val();
                    let has_joint = $('#has_joint').val();

                    let has_obligations = $('#has_obligations').val();
                    let has_financial_distress = $('#has_financial_distress').val();
                    let has_owning_property = $('#has_owning_property').val();


                    if (work_source != '') data['work_source'] = work_source;

                    if (has_property != '') data['has_property'] = has_property;
                    if (has_joint != '') data['has_joint'] = has_joint;
                    if (is_supported != '') data['is_supported'] = is_supported;

                    if (has_obligations != '') data['has_obligations'] = has_obligations;
                    if (has_financial_distress != '') data['has_financial_distress'] = has_financial_distress;
                    if (has_owning_property != '') data['has_owning_property'] = has_owning_property;

                    if (customer_salary_from != '') {
                        data['customer_salary_from'] = customer_salary_from;
                    }
                    if (customer_salary_to != '') {
                        data['customer_salary_to'] = customer_salary_to;
                    }

                },
            },
            columns: [

                {
                    "targets": 0,
                    "data": "created_at",
                    "name": 'pending_requests.created_at',
                    "className": "data-table-coulmn",
                    "render": function(data, type, row, meta) {
                        return data.split(" ").join("<br/>");
                    }


                },

                {
                    data: 'name',
                    name: 'name',
                    className: "data-table-coulmn"
                },
                {
                    data: 'birth_date_higri',
                    name: 'customers.birth_date_higri',
                    className: "data-table-coulmn"
                },
                {
                    data: 'work',
                    name: 'customers.work',
                    className: "data-table-coulmn"
                },
                {
                    data: 'salary',
                    name: 'customers.salary',
                    className: "data-table-coulmn"
                },
                {
                    data: 'is_supported',
                    name: 'customers.is_supported',
                    className: "data-table-coulmn"
                },
                {
                    data: 'has_property',
                    name: 'real_estats.has_property',
                    className: "data-table-coulmn"
                },
                {
                    data: 'has_joint',
                    name: 'customers.has_joint',
                    className: "data-table-coulmn"
                },
                {
                    data: 'has_obligations',
                    name: 'customers.has_obligations',
                    className: "data-table-coulmn"
                },
                {
                    data: 'has_financial_distress',
                    name: 'customers.has_financial_distress',
                    className: "data-table-coulmn"
                },
                {
                    data: 'owning_property',
                    name: 'real_estats.owning_property',
                    className: "data-table-coulmn"
                },
                {
                    data: 'action',
                    name: 'action',
                    className: "data-table-coulmn"
                }
            ],

            initComplete: function() {
                let api = this.api();
                $("#filter-search-req").on('click', function(e) {
                    e.preventDefault();
                    api.draw();
                    $('#myModal').modal('hide');
                });

                $(".paginate_button").addClass("pagination-circle");
                /* To Adaptive with New Design */
                $('#example-search-input').keyup(function() {
                    dt.search($(this).val()).draw();
                })



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
            "order": [
                [0, "desc"]
            ],
            createdRow: function(row, data, index) {

                $('td', row).eq(0).addClass('reqNum'); // 6 is index of column
                $('td', row).eq(1).addClass('reqType'); // 6 is index of column
                $('td', row).eq(2).addClass('reqType'); // 6 is index of column
                $('td', row).eq(3).addClass('reqType'); // 6 is index of column
                $('td', row).eq(4).addClass('reqType'); // 6 is index of column
                $('td', row).eq(5).addClass('reqType'); // 6 is index of column
                $('td', row).eq(6).addClass('reqType'); // 6 is index of column
                $('td', row).eq(7).addClass('reqType'); // 6 is index of column
                $('td', row).eq(10).addClass('reqDate'); // 6 is index of column
            },
        });
    });
    //

    var currentRequest = null;
    $(document).on('click', '#move', function(e) {

        var pending_id = $(this).attr('data-id');
        $('#msg').removeClass("alert-success alert-danger").attr('style', 'display:none');

        var modalConfirm = function(callback) {
            $("#pending_modal").modal('show');

            $("#pending_modal-btn-yes").on("click", function() {

                callback(true);
                $("#pending_modal").modal('hide');

            });


            $("#pending_modal-btn-no").on("click", function() {
                callback(false);
                $("#pending_modal").modal('hide');
            });
        };

        modalConfirm(function(confirm) {
            if (confirm) {

                $('.moveButtons').attr("disabled", true);
                $('#pending_modal-btn-yes').attr("disabled", true);
                document.querySelector('#pending_modal-btn-yes').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Please Wait') }}";

                var url = "{{ route('agent.moveAdditionalReqs')}}";

                currentRequest = jQuery.ajax({
                    url: url,
                    type: "GET",
                    cache: false,
                    data: {
                        pending_id: pending_id
                    },
                    beforeSend: function() {
                        if (currentRequest != null) {
                            currentRequest.abort(); // to remove all previouse request of ajax
                        }
                    },
                    success: function(data) {

                        if (data.status == 4) {
                            alertSuccess(data.message);
                        } else if (data.status == false || data.status == 3)
                            alertSuccess(data.message);
                        else if (data.status == -1) {
                            alertSuccess(data.message);
                            $('#pendingReqs-table').DataTable().ajax.reload();
                        } else {
                            $('#msg').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                            $('#pendingReqs-table').DataTable().ajax.reload();
                        }



                    },

                });

            }

            $('#pending_modal').modal('hide');
            $('.moveButtons').attr("disabled", false);
            $('#pending_modal-btn-yes').attr("disabled", false);
            document.querySelector('#pending_modal-btn-yes').innerHTML = "متأكد";


        });


    });

    function ajaxCall() {
        swal({
            title: "خطأ!",
            text: content,
            icon: 'error',
            button: 'موافق',
        });
    }

    function alertSuccess(content) {
        swal({
            title: "خطأ!",
            text: content,
            icon: 'error',
            button: 'موافق',
        });
    }
    /////////////////////////////////////////
</script>
@endsection
