@extends('layouts.content')

@section('nav_actions')

{{-- Grid && List --}}
<div class="table-cell d-flex align-items-center">
    <div class="table-display d-flex align-items-center">
        <a class="table-grid selected ms-3"  href="#">
            <svg xmlns="http://www.w3.org/2000/svg" width="20.428" height="20.428" viewBox="0 0 20.428 20.428">
            <g id="Icon_feather-grid" data-name="Icon feather-grid" transform="translate(1 1)">
                <path id="Path_47" data-name="Path 47" d="M11.666,4.5H4.5v7.166h7.166Z" transform="translate(6.762 -4.5)" fill="#d8d8d8" stroke="#d8d8d8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                <path id="Path_48" data-name="Path 48" d="M28.167,4.5H21v7.166h7.166Z" transform="translate(-21 -4.5)" fill="#d8d8d8" stroke="#d8d8d8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                <path id="Path_49" data-name="Path 49" d="M28.167,21H21v7.166h7.166Z" transform="translate(-21 -9.738)" fill="#d8d8d8" stroke="#d8d8d8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                <path id="Path_50" data-name="Path 50" d="M11.666,21H4.5v7.166h7.166Z" transform="translate(6.762 -9.738)" fill="#d8d8d8" stroke="#d8d8d8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
            </g>
            </svg>
        </a>
        <a class="table-list" href="#">
            <svg xmlns="http://www.w3.org/2000/svg" width="24.348" height="19.783" viewBox="0 0 24.348 19.783">
            <path
                id="Icon_awesome-list-ul"
                data-name="Icon awesome-list-ul"
                d="M22.065,3.375a2.283,2.283,0,1,1-2.283,2.283A2.283,2.283,0,0,1,22.065,3.375Zm0,7.609a2.283,2.283,0,1,1-2.283,2.283A2.283,2.283,0,0,1,22.065,10.984Zm0,7.609a2.283,2.283,0,1,1-2.283,2.283,2.283,2.283,0,0,1,2.283-2.283Zm-21.3.761H15.978a.761.761,0,0,1,.761.761v1.522a.761.761,0,0,1-.761.761H.761A.761.761,0,0,1,0,21.636V20.114A.761.761,0,0,1,.761,19.353Zm0-15.217H15.978a.761.761,0,0,1,.761.761V6.418a.761.761,0,0,1-.761.761H.761A.761.761,0,0,1,0,6.418V4.9A.761.761,0,0,1,.761,4.136Zm0,7.609H15.978a.761.761,0,0,1,.761.761v1.522a.761.761,0,0,1-.761.761H.761A.761.761,0,0,1,0,14.027V12.505A.761.761,0,0,1,.761,11.745Z"
                transform="translate(0 -3.375)"
                fill="#d8d8d8"
            ></path>
            </svg>
        </a>
    </div>
</div>

{{-- Print - Show -Search --}}
<div class="table-cell d-flex align-items-center mt-3 mt-md-0" id="new-dt-btns"></div>

@endsection

@section('title')
    {{ $title }}
@endsection


@section('css_style')
<link rel="stylesheet" href="{{ asset('css/tokenize2.min.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ url("/") }}/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
<script src="{{ asset('backend/sweetalert/sweetalert2.min.js') }}"></script>
<link href="{{ asset('backend/sweetalert/sweetalert2.min.css') }}" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<style>
    .mov, .green{
        background-color: #fff;
    }
    .hidden{
        display: none;
    }
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

    <div class="row hidden" id="grid-cont"></div>

    <div class="col-12">
        <div class="portlet">
            <div class="portlet__body">
                <div class="tablee-responsive">
                    <div class="dashTable">
                        <table class="table table-custom table-striped table-custom-3 table-resizable data-table">
                            <thead>
                                <tr>
                                    <th>
                                        <label class="m-checkbox mb-1">
                                            <input type="checkbox" onclick="chbx_toggle1(this);" />
                                            <span class="checkmark border-white"></span>
                                        </label>
                                    </th>
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
                                    <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
<script src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js"></script>



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

    /////////////////////////////////////////

    // function disabledButton() {
    //     if ($(':checkbox[name="chbx[]"]:checked').length > 0) {
    //         document.getElementById("restoreAll").disabled = false;
    //         document.getElementById("restoreAll").style = "";
    //     } else {
    //         document.getElementById("restoreAll").disabled = true;
    //         document.getElementById("restoreAll").style = "cursor: not-allowed";
    //     }
    // }


    function chbx_toggle1(source) {
        var checkboxes = document.querySelectorAll('input[type="checkbox"]');
        for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != source)
            checkboxes[i].checked = source.checked;
        }
        // disabledButton();
    }
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
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                // rightColumns: 1
                rightColumns: 0
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
                'pageLength',
                {
                    text: '{{ MyHelpers::admin_trans(auth()->user()->id,"Search") }}',
                    className: 'buttons-search',
                    action: function(e, dt, node, config) {
                        $('#myModal').modal('show');
                    }
                },
            ],
            scrollY: '50vh',
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

                // {
                //     "targets": 0,
                //     "data": "created_at",
                //     "name": 'pending_requests.created_at',
                //     "className": "data-table-coulmn",
                //     "render": function(data, type, row, meta) {
                //         return data.split(" ").join("<br/>");
                //     }


                // },
                {
                    "targets": 0,
                    "data": "id",
                    "render": function(data, type, row, meta) {
                        return '<input type="checkbox" id="chbx" name="chbx[]" value="' + data + '"/>';
                    }
                },

                {
                    data: 'req_date',
                    name: 'req_date',
                    // className: "data-table-coulmn"
                },
                {
                    data: 'name',
                    name: 'name',
                    // className: "data-table-coulmn"
                },
                {
                    data: 'birth_date_higri',
                    name: 'customers.birth_date_higri',
                    // className: "data-table-coulmn"
                },
                {
                    data: 'work',
                    name: 'customers.work',
                    // className: "data-table-coulmn"
                },
                {
                    data: 'salary',
                    name: 'customers.salary',
                    // className: "data-table-coulmn"
                },
                {
                    data: 'is_supported',
                    name: 'customers.is_supported',
                    // className: "data-table-coulmn"
                },
                {
                    data: 'has_property',
                    name: 'real_estats.has_property',
                    // className: "data-table-coulmn"
                },
                {
                    data: 'has_joint',
                    name: 'customers.has_joint',
                    // className: "data-table-coulmn"
                },
                {
                    data: 'has_obligations',
                    name: 'customers.has_obligations',
                    // className: "data-table-coulmn"
                },
                {
                    data: 'has_financial_distress',
                    name: 'customers.has_financial_distress',
                    // className: "data-table-coulmn"
                },
                {
                    data: 'owning_property',
                    name: 'real_estats.owning_property',
                    // className: "data-table-coulmn"
                },
                {
                    data: 'action',
                    name: 'action',
                    // className: "data-table-coulmn"
                }
            ],

            initComplete: function() {
                let api = this.api();
                $('#grid-cont').html('');

                $("#filter-search-req").on('click', function(e) {
                    e.preventDefault();
                    api.draw();
                    $('#grid-cont').html('');
                    $('#myModal').modal('hide');
                });

                $(".paginate_button").addClass("pagination-circle");
                /* To Adaptive with New Design */
                $('#example-search-input').keyup(function() {
                    dt.search($(this).val()).draw();
                })

                $('#nav-search-input').keyup(function(){
                    dt.search($(this).val()).draw() ;
                    $('#grid-cont').html('');
                })

                dt.buttons().container().appendTo( '#new-dt-btns' );

                // dt.buttons().container()
                //     .appendTo('#dt-btns');

                $('.buttons-search').html('<i class="fas fa-search"></i>').attr('title', 'بحث');

                $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title', 'طباعة');
                $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');
                //
                $( ".dt-button" ).addClass(' btn-icon');
                $('.buttons-print').addClass('no-transition custom-btn');
                $('.buttons-collection').addClass('no-transition custom-btn');

                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)

            },
            "order": [
                [2, "desc"]
            ],
            createdRow: function(row, data, index) {

                $('td', row).eq(2).attr('title', data.name);
                $('td', row).eq(2).attr('data-title', data.name);
                $('td', row).eq(2).attr('data-bs-toggle', 'tooltip');
                $('td', row).eq(2).attr('data-bs-placement', 'top');

                $('td', row).eq(0).addClass('reqNum'); // 6 is index of column
                $('td', row).eq(1).addClass('reqType'); // 6 is index of column
                $('td', row).eq(2).addClass('reqType'); // 6 is index of column
                $('td', row).eq(3).addClass('reqType'); // 6 is index of column
                $('td', row).eq(4).addClass('reqType'); // 6 is index of column
                $('td', row).eq(5).addClass('reqType'); // 6 is index of column
                $('td', row).eq(6).addClass('reqType'); // 6 is index of column
                $('td', row).eq(7).addClass('reqType'); // 6 is index of column
                $('td', row).eq(10).addClass('reqDate'); // 6 is index of column
                console.log(data);
                addCardGrid(data);

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



    ///////////////////////////////////
    function addCardGrid(data) {
            var dde = ``;
            for (let index = 0; index < data.action_grid.length; index++) {
                // const element = data.action_grid[index];
                dde += `
                <li>
                                    <a class="dropdown-item" href="`+data.action_grid[index]['url']+`">
                                        <i class="`+data.action_grid[index]['icon']+`"></i>
                                    <span class="font-medium">`+data.action_grid[index]['title']+`</span>
                                    </a>
                                </li>
                `;

            }
            if(data.pending_request_status == 'جديد')
            {
                $start = `<i class="fas fa-star"></i>`;
            }else{
                $start = `<i class="fas fa-star-o"></i>`;
            }

            $('#grid-cont').append(`
            <div class="col-lg-4 col-sm-6">
                        <div class="widget__item-order widget-`+data.card_class+`">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <div class="d-flex align-items-center">
                            <h6 class="font-medium">`+data.name+`</h6>
                            </div>
                            <div class="d-flex align-items-center">
                            <div class="btn-star ms-3 add-to-special-orders">
                                `+$start+`
                            </div>
                            <div class="dropdown">
                                <button class="btn bg-white p-1" data-bs-toggle="dropdown">
                                <svg xmlns="http://www.w3.org/2000/svg" width="3.548" height="16.219" viewBox="0 0 3.548 16.219">
                                    <path
                                    id="menu"
                                    d="M1.774,3.548A1.851,1.851,0,0,1,.507,3.016,1.765,1.765,0,0,1,0,1.774,2,2,0,0,1,.507.507,1.781,1.781,0,0,1,1.774,0,1.882,1.882,0,0,1,3.016.507a1.8,1.8,0,0,1,.532,1.267A1.819,1.819,0,0,1,1.774,3.548Zm7.577-.532a1.791,1.791,0,0,0,.532-1.242A1.9,1.9,0,0,0,9.351.507,1.765,1.765,0,0,0,8.109,0,1.946,1.946,0,0,0,6.842.507a1.781,1.781,0,0,0-.507,1.267,1.882,1.882,0,0,0,.507,1.242,1.744,1.744,0,0,0,2.509,0Zm6.336,0a1.791,1.791,0,0,0,.532-1.242A1.9,1.9,0,0,0,15.687.507,1.765,1.765,0,0,0,14.445,0a1.946,1.946,0,0,0-1.267.507,1.781,1.781,0,0,0-.507,1.267,1.882,1.882,0,0,0,.507,1.242,1.744,1.744,0,0,0,2.509,0Z"
                                    transform="translate(0 16.219) rotate(-90)"
                                    fill="#acacac"
                                    ></path>
                                </svg>
                                </button>
                                <ul class="dropdown-menu">
                                `+dde+`
                                </ul>
                            </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6>حالة طلب الاضافة'</h6>
                            <div class="label label-solid-`+data.card_class+`">`+data.pending_request_status+`</div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between">
                            <h5>تاريخ الطلب</h5>
                            <h5>`+data.req_date+`</h5>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <h5>تاريخ النزول</h5>
                            <h5>`+data.created_at+`</h5>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <h5>تاريخ الميلاد هجري</h5>
                            <h5>`+data.birth_date_higri+`</h5>
                        </div>
                        <hr />
                        <div class="d-flex align-items-center justify-content-between">
                            <h5>هل يمتلك عقار</h5>
                            <h5>`+data.owning_property+`</h5>
                        </div>
                        </div>
                    </div>
            `);
    }

    $(document).on('click', '.table-grid', function(){
        $('#grid-cont').removeClass('hidden');
        $('.DTFC_ScrollWrapper').addClass('hidden');
        // $('.dataTables_scroll').addClass('hidden');
    })
    $(document).on('click', '.table-list', function(){
        $('#grid-cont').addClass('hidden');
        $('.DTFC_ScrollWrapper').removeClass('hidden');
        // $('.dataTables_scroll').removeClass('hidden');
    })



</script>
@endsection
