@extends('layouts.content')


@section('title')
    طلبات بحاجة إلى التحويل - جديدة
@endsection

@section('css_style')

<script src="{{ asset('backend/sweetalert/sweetalert2.min.js') }}"></script>
<link href="{{ asset('backend/sweetalert/sweetalert2.min.css') }}" rel="stylesheet">


<!-- Vendor CSS-->
<link href="{{ url('interface_style/search/vendor/select2/select2.min.css') }}" rel="stylesheet" media="all">
<link href="{{ url('interface_style/search/vendor/datepicker/daterangepicker.css') }}" rel="stylesheet" media="all">

    <style>

        #multiple-sales-managers{
            width: 320px;
        }

        #multiple-sales-managers-transfer-request{
            width: 320px;
        }

        .select2-container--default .select2-selection--multiple{
            position: relative !important;
            min-height: 55px !important;
            max-height: 150px !important;
            height: auto !important;
            padding: 0 0 5px 5px !important;
            margin-top: 3px;
            overflow-y: auto;
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
            text-align: center;
        }

        td {
            width: 15%;
        }

        .reqNum {
            width: 0.5%;
        }

        .reqDate {
            text-align: center;
        }

        .reqType {
            width: 2%;
        }

        tr:hover td {
            background: #d1e0e0
        }

        .newReq {
            background: rgba(98, 255, 0, 0.4) ! important;
        }

        .needFollow {
            background: rgba(12, 211, 255, 0.3) ! important;
        }

        .noNeed {
            background: rgba(0, 0, 0, 0.2) ! important;
        }

        .wating {
            background: rgba(255, 255, 0, 0.2) ! important;
        }

        .watingReal {
            background: rgba(0, 255, 42, 0.2) ! important;
        }

        .rejected {
            background: rgba(255, 12, 0, 0.2) ! important;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">
@endsection

@section('customer')



    @if(!empty($message))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ $message }}
        </div>
    @endif

    @if ( session()->has('message') )
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('message') }}
        </div>
    @endif

    @if ( session()->has('message2') )
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('message2') }}
        </div>
    @endif

    @if($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <ul>
                    <li>{{ $error }}</li>
                </ul>

            </div>
        @endforeach
    @endif

    @if(!empty($message))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ $message }}
        </div>
    @endif

    @if ( session()->has('message') )
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('message') }}
        </div>
    @endif



    <div id="msg2" class="alert alert-dismissible" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>

    </div>

    <div class="addUser my-4">
        <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
            <h3> طلبات بحاجة إلى التحويل - جديدة:</h3>

        </div>
    </div>

    <br>



    @if ($requests>0)
        <div class="tableBar">
            <div class="topRow">
                <div class="row align-items-center text-center text-md-left">
                    <div class="col-lg-2">
                        <div class="selectAll">
                            <div class="form-check">
                                <input type="checkbox" id="allreq" class="form-check-input" onclick="chbx_toggle1(this);"/>
                                <label class="form-check-label" for="allreq">تحديد الكل </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 ">

                        <div class="tableUserOption flex-wrap justify-content-between">
                            <div class="addBtn col-md-5 mt-3 px-2">
                                <button disabled class="mov" style="cursor: not-allowed" id="moveAll" onclick="getReqests1()">
                                    <i class="fas fa-random"></i>
                                    تحويل الطلبات
                                </button>
                            </div>

                            <div class="addBtn col-md-5 mt-3 px-2">
                                <button id="processedAllBtn" onclick="onProcessedClick()">
                                    <i class="fas fa-random"></i>
                                    تمت المعالجة
                                </button>
                            </div>

                           

                            <div class="addBtn col-md-5 mt-3 px-2">
                                <button onclick="onMoveToQuality()" style="background-color: #76e6d6">
                                    <i class="fas fa-random"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Move Reqs To Quality') }}
                                </button>
                            </div>

                            
                            <div class="input-group col-12 mt-3">
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
                        <div id="tableAdminOption" class="tableAdminOption">
                             @include('Admin.datatable_display_number')
                            <div id="dt-btns" class="tableAdminOption">
                                {{-- Here We Will Add Buttons of Datatable  --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dashTable">
                <table id="pendingReqs-table" class="table table-bordred table-striped data-table">
                    <thead>
                    <tr>
                        <th></th>
                        <th>{{__("attributes.request_id")}}</th>
                        <th style="text-align:center">تاريخ إنشاء <br> طلب التحويل</th>
                        <th style="text-align:center">تاريخ دخول <br> الطلب في النظام</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req status') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'mobile') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req classification agent') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</th>

                        <th style="text-align:center">العملية</th>
                        {{--                        <th style="text-align:center">محتوى التذكرة</th>--}}
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

        </div>

        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="quality-modal">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mediumModalLabel">مستخدم الجودة</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <br>
                    <div class="modal-body">
                        <select id="quality_id" class="form-control">
                            <option value=""> ---</option>
                            @foreach($QualitySelect as $quality)
                                <option value="{{$quality['id']}}"> {{ $quality['name'] }} </option>
                            @endforeach
                        </select>
                        <span id="qualityError" style="color: red;"></span>
                    </div>
                    <br>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                        <button type="button" onclick="submitMoveQuality()" class="btn btn-primary">{{ MyHelpers::admin_trans(auth()->user()->id,'Send') }}</button>
                    </div>
                </div>
            </div>
        </div>
@stop

@section('updateModel')
    @include('Admin.Request.filterReqs-NeedActionReq')
    @include('Admin.Request.moveReq')
    @include('Admin.Request.moveReq3-multi')
    @include('Admin.Request.confirmArchMsg')
@endsection

@section('scripts')

<!-- Vendor JS-->
<script src="{{ url('interface_style/search/vendor/select2/select2.min.js') }}"></script>



    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script> --}}


    <script>

        const moveToDone = needID => {
            const need_url = "{{ route('admin.updateNeedActionReq')}}";
            $.get(need_url, {needID}, function (data) {
                if (data) {
                    swal({
                        title: 'تم!',
                        text: data.message,
                        type: 'success',
                        timer: '750'
                    });

                    $('.data-table').DataTable().ajax.reload();
                } else
                    swal({
                        title: 'خطأ',
                        text: 'حاول مجددا',
                        type: 'error',
                        timer: '750'
                    })

            });
        }

        const submitMoveQuality = () => {
            const quality = $("#quality_id").val();
            if (!quality) {
                alert("الرجاء تحديد مستخدم الجودة")
                return;
            }
            const array = []
            const need_ids = []
            $('[name^="chbx["]:checked').each((i, e) => {
                array.push(e.value)
                need_ids.push($(e).attr('data-need-id'))
            })
            // console.log(need_ids)

            $.post("{{ route('admin.postMoveNeedActionsToQuality')}}", {
                array,
                quality,
                need_ids,
            }, function (data) {
                try {
                    const {move_count, request_count} = data || {}
                    alertSuccess(`تم نقل ${move_count} من أصل ${request_count}`)
                    $('.data-table').DataTable().ajax.reload();
                    $("#quality_id").val(null);
                } catch (e) {
                    alertError(e)
                }
            });

        }

        function onMoveToQuality() {
            const ids = []
            $('[name^="chbx["]:checked').each((i, e) => ids.push(e.value))
            if (ids.length < 1) {
                alert("الرجاء قم بتحديد الطلبات")
                return;
            }
            $('#quality-modal').modal('show');
        }

        function disabledButton() {
            if ($(':checkbox[name="chbx[]"]:checked').length > 0) {
                document.getElementById("moveAll").disabled = false;
                document.getElementById("moveAll").style = "";
            } else {
                document.getElementById("moveAll").disabled = true;
                document.getElementById("moveAll").style = "cursor: not-allowed";
            }
        }

        function chbx_toggle1(source) {
            var checkboxes = document.querySelectorAll('input[type="checkbox"]');
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i] != source)
                    checkboxes[i].checked = source.checked;
            }

            disabledButton();
        }

        function getReqests1() {
            document.getElementById("salesagent3").value = '';
            document.getElementById('salesagentsError3').innerHTML = '';
            $('#mi-modal9').modal('show');
        }

        function onProcessedClick() {
            const ids = []
            $('[name^="chbx["]:checked').each((i, e) => ids.push(e.value))
            if (ids.length < 1) {
                alert("الرجاء قم بتحديد الطلبات")
                return;
            }
            if (confirm("هل أنت متأكد ؟")) {
                moveToDone(ids)
            }
        }

        $(document).on('click', '#submitMove3', function (e) {
            var array = []
            var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].value != "on" && checkboxes[i].value != "off") {
                    var val = parseInt(checkboxes[i].value);
                    array.push(val);
                }
            }

            $('#submitMove3').attr("disabled", true);

            document.querySelector('#submitMove3').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Please Wait') }}";

            let agents_ids = $('#salesagent3').data('tokenize2').toArray();
            var id = array;


            var url = "{{ route('admin.moveNeedReqToAnotherArrayAgent')}}";
            //    var need_url = "{{ route('admin.updateNeedActionReqArray')}}";


            $.get(url, {
                agents_ids: agents_ids,
                trans_basket: 1,
                id: id
            }, function (data) {
                // console.log(data);
                if (data.updatereq == 1) {

                    $('.data-table').DataTable().ajax.reload();
                    let slug = "{{ route('admin.needActionRequestsNew') }}";
                    // window.location.replace(slug);
                    $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

                } else if (data.updatereq == 2) {
                    swal({
                        title: 'خطأ',
                        text: data.message,
                        type: 'error',
                        confirmButtonText: 'موافق',
                        confirmButtonColor: '#990000',
                    })
                }
                else{
                    data?.message && alertError(data?.message)
                }
            })


            document.querySelector('#submitMove3').innerHTML = 'تحويل'
            $('#submitMove3').attr("disabled", false);
            document.getElementById("moveAll").disabled = true;
            document.getElementById("moveAll").style = "cursor: not-allowed";
            $('#mi-modal9').modal('hide');


        });

        $(document).on('click', '#moveToDone', function (e) {
            moveToDone($(this).attr('data-id'));
        });


        $(document).on('click', '#move', function (e) {


            document.getElementById("salesagent").value = '';
            document.getElementById('salesagentsError').innerHTML = '';

            var id = $(this).attr('data-id');
            var needID = $(this).attr('data-need');

            document.getElementById("movedReqID").value = id;
            document.getElementById("needReqID").value = needID;

            $('#mi-modal7').modal('show');


        });


        $(document).on('click', '#submitMove', function (e) {


            $('#submitMove').attr("disabled", true);
            document.querySelector('#submitMove').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Please Wait') }}";

            var salesAgent = document.getElementById("salesagent").value;

            var id = document.getElementById("movedReqID").value;
            var needID = document.getElementById("needReqID").value;


            var url = "{{ route('admin.moveReqNeedActionToAnother')}}";
            //   var need_url = "{{ route('admin.updateNeedActionReq')}}";


            if (salesAgent != '') {


                $.get(url, {
                    salesAgent: salesAgent,
                    id: id
                }, function (data) { //data is array with two veribles (request[], ss)

                    if (data.updatereq == 1) {

                        $('.data-table').DataTable().ajax.reload();

                        let slug = "{{ route('admin.needActionRequestsNew') }}";
                        window.location.replace(slug);
                        $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

                    }


                    $('#mi-modal7').modal('hide');


                })

            } else
                document.getElementById('salesagentsError').innerHTML = 'الرجاء اختيار استشاري';
            document.querySelector('#submitMove').innerHTML = "تحويل";
            $('#submitMove').attr("disabled", false);


        });
    </script>



    <script src="{{ asset('js/tokenize2.min.js') }}"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js"></script>


    <script>
        // $('.khaled').tokenize2()
        $('.tokenizeable').tokenize2();
        $(".tokenizeable").on("tokenize:select", function () {
            $(this).trigger('tokenize:search', "");
        });


        var xses = [
            'sa'
        ];

        function getClassifcationX($x) {
            return $("#classifcation_" + $x).data('tokenize2').toArray();
        }

        function getReqTypes() {
            return $("#request_type").data('tokenize2').toArray();
        }

        /*
          function getReqSources() {
              return $("#source").data('tokenize2').toArray();
          }

          */

        $(document).ready(function () {
            var dt = $('.data-table').DataTable({
                "language": {
                    "url": "{{route('datatableLanguage')}}",
                    buttons: {
                        pageLength: "عرض",
                        excelHtml5: "اكسل",
                        print: "طباعة",
                        

                    }
                },
                "lengthMenu": [
                    [10, 25, 50],
                    [10, 25, 50]
                ],
                dom: 'Bfrtip',
                buttons: [
                    'pageLength',
                    // 'copyHtml5',
                    'excelHtml5',
                    // 'csvHtml5',
                    // 'pdfHtml5' ,
                    'print',
                    
                    {
                        text: '{{ MyHelpers::admin_trans(auth()->user()->id,"Search") }}',
                        action: function (e, dt, node, config) {
                            $('#myModal').modal('show');
                        }
                    }
                ],
                scrollY: true,
                scrollX: true,
                scrollCollapse: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': "{{ url('admin/needactionreqs-datatablenew') }}",
                    'method': 'Get',
                    'data': function (data) {
                        let action = $('#action').data('tokenize2').toArray();
                        let agents_ids = $('#agents_ids').data('tokenize2').toArray();
                        let status_of_request = $('#status_of_request').val();

                        xses.forEach(function (item) {
                            if (getClassifcationX(item) != '') {
                                data['class_id_' + item] = getClassifcationX(item)
                            }
                        });


                        if (agents_ids != '') data['agents_ids'] = agents_ids;
                        if (action != '') data['action'] = action;
                        if (status_of_request != '') data['status_of_request'] = status_of_request;
                    },
                },
                columns: [
                    {
                        "targets": 0,
                        "data": "need_id",
                        "render": function (data, type, row, meta) {
                            return '<input data-need-id="' + row.need_id + '" type="checkbox" id="chbx" name="chbx[]" onchange="disabledButton()" value="' + data + '"/>';
                        },
                        searchable: !1,
                        sortable: !1,
                    },
                    {
                        data: "request_id",
                        name: "req_id",
                    },
                    {
                        "targets": 0,
                        "data": "need_created_at",
                        searchable: !1,
                        // sortable:!1,
                        "render": function (data, type, row, meta) {
                            return data.split(" ").join("<br/>");
                        }
                    },
                    {
                        "targets": 0,
                        "data": "created_at",
                        "render": function (data, type, row, meta) {
                            return data.split(" ").join("<br/>");
                        }
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'user_name',
                        name: 'users.name'
                    },
                    {
                        data: 'customer_name',
                        name: 'customers.name'
                    },
                    {
                        data: 'mobile',
                        name: 'customers.mobile'
                    },
                    {
                        data: 'source',
                        name: 'source',
                        searchable: !1,
                        // sortable:!1,
                    },
                    {
                        data: 'class_id_agent',
                        name: 'class_id_agent',
                        searchable: !1,
                        sortable: !1,
                    },
                    {
                        data: 'comment',
                        name: 'comment',
                        searchable: !1,
                        sortable: !1,
                    },
                    {
                        data: 'action',
                        name: 'action',
                    },
                    // {
                    //     data: 'content',
                    //     name: 'content'
                    // },

                    {
                        data: 'actions',
                        name: 'actions',
                        searchable: !1,
                        sortable: !1,
                    }

                ],
                initComplete: function () {
                    let api = this.api();
                    $("#filter-search-req").on('click', function (e) {
                        e.preventDefault();
                        api.draw();
                        //checktable(api);
                        $('#myModal').modal('hide');
                    });

                    $(".paginate_button").addClass("pagination-circle");
                    /* To Adaptive with New Design */
                    // $('#example-search-input').keyup(function () {
                    //     dt.search($(this).val()).draw();
                    // })
                    /* To Adaptive with New Design */
                    $('#example-search-input').keyup(_.debounce(function () {
                        dt.search($(this).val()).draw();
                    }, 500))

                //====================draw table when change in display number=====================
                $('#display_number').focusout(function(){
                        dt.page.len( $(this).val()).draw();
                    });
                    //==================================================================================

                    dt.buttons().container().appendTo('#dt-btns');

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
                createdRow: function (row, data, index) {

                    /*
                                    if (data.statusReq === "طلب جديد") {
                                        $(row).addClass('newReq');

                                    }

                                    if (data.class_id_agent === "يحتاج متابعة") {
                                        $(row).addClass('needFollow');
                                    }

                                    if (data.class_id_agent === "لا يرغب") {
                                        $(row).addClass('noNeed');
                                    }

                                    if (data.class_id_agent === "بانتظار الأوراق") {
                                        $(row).addClass('wating');
                                    }

                                    if (data.class_id_agent === "يبحث عن عقار") {
                                        $(row).addClass('watingReal');
                                    }

                                    if (data.class_id_agent === "مرفوض") {
                                        $(row).addClass('rejected');
                                    }
                    */

                    $('td', row).eq(3).addClass('reqDate');

                    $('td', row).eq(6).addClass('commentStyle');
                    $('td', row).eq(6).attr('title', data.customer_name);

                    $('td', row).eq(10).addClass('commentStyle');
                    $('td', row).eq(10).attr('title', data.comment);

                    // $('td', row).eq(11).addClass('commentStyle');
                    // console.log(data)
                    data.content && $('td', row).eq(11).attr('title', data.action + (data.content ? '\n' + data.content : ''));


                },
            });
        });


        $(function () {
            $('#source').on('tokenize:tokens:add', function (e, value, text) {


                if (value == 2) {


                    document.getElementById("collaboratorDiv").style.display = "block";


                }
            });

            $('#source').on('tokenize:tokens:remove', function (e, value) {

                if (value == 2) {


                    document.getElementById("collaboratorDiv").style.display = "none";
                    document.getElementById("collaborator").value = "";

                }
            });

        });


        $(function () {
            $('#request_type').on('tokenize:tokens:add', function (e, value, text) {


                if (value == "شراء-دفعة") {


                    document.getElementById("paystatusDiv").style.display = "block";


                }
            });

            $('#request_type').on('tokenize:tokens:remove', function (e, value) {

                if (value == "شراء-دفعة") {


                    document.getElementById("paystatusDiv").style.display = "none";
                    document.getElementById("pay_status").value = "";

                }
            });

        });

        // Khaled

        var global_sales_agents;
        $('#multiple-sales-managers').on('change.select2',function(){
            $('#agents_ids').tokenize2().trigger('tokenize:clear')
            var multiple_sales_manager_ids=$(this).val()
            console.log(multiple_sales_manager_ids)
            $.ajax({
                type:'GET',
                url:"{{ route('admin.multipleSalesAgents')}}",
                data:{
                    multiple_sales_manager_ids:multiple_sales_manager_ids,
                    _token: '{{csrf_token()}}'
                },
                success:function(response){
                    console.log(response)
                    $('#agents_ids').empty()
                    $.each(response.salesAgents, function (key, value) {
                            $("#agents_ids").append('<option  value="' + value
                                .id + '">' + value.name + '</option>');
                    });
                    $('#agents_ids option').each(function(){
                        $(this).attr("selected",true);
                    });
                    $('#agents_ids').tokenize2().trigger('tokenize:remap')
                    global_sales_agents=$('#agents_ids').val()
                }
            })
        })

        $(document).on('change','#allow-recived-sales-managers',function(){
            var active_sales_managers
            if(this.checked)
            {
                active_sales_managers = 0
            }else{
                active_sales_managers = 1
            }
            console.log(active_sales_managers)
            $.ajax({
                type:'GET',
                url:"{{ route('admin.allowRecievedSalesManagers')}}",
                data:{
                    active_sales_managers:active_sales_managers,
                    _token: '{{csrf_token()}}'
                },
                success:function(response){
                    console.log(response)
                    $('#multiple-sales-managers').empty()
                    // $("#multiple-sales-managers").append('<option>' + '--' + '</option>');
                    $.each(response.activeSalesManagers, function (key, value) {
                            $("#multiple-sales-managers").append('<option value="' + value
                                .id + '">' + value.name + '</option>');
                    });
                }

            })
        })


        
        $(document).on('change','#allow-recived-sales-agents',function(){
            console.log('outside '+ global_sales_agents)
            var active_sales_agents
            console.log('inside '+ global_sales_agents)
            if(this.checked)
            {
                active_sales_agents = 1
            }else{
                active_sales_agents = 0
            }
            console.log(active_sales_agents)
            $('#agents_ids').tokenize2().trigger('tokenize:clear')

            $.ajax({
                type:'GET',
                url:"{{ route('admin.allowRecievedSalesAgents')}}",
                data:{
                    active_sales_agents:active_sales_agents,
                    sales_agents:global_sales_agents,
                    _token: '{{csrf_token()}}'
                },
                success:function(response){
                    console.log('Response '+response)
                    $('#agents_ids').empty()
                    $.each(response.activeSalesAgents, function (key, value) {
                            $("#agents_ids").append('<option  value="' + value
                                .id + '">' + value.name + '</option>');
                    });
                    $('#agents_ids option').each(function(){
                        $(this).attr("selected",true);
                    });
                    $('#agents_ids').tokenize2().trigger('tokenize:remap')
                }
            })
        })


        // Transfer requests

        // agents_ids => salesagent3
        // allow-recived-sales-managers => allow-recived-sales-managers-transfer-request
        // multiple-sales-managers => multiple-sales-managers-transfer-request
        // allow-recived-sales-agents => allow-recived-sales-agents-transfer-request
        // global_sales_agents => global_sales_agents_transfer_request

        var global_sales_agents_transfer_request;
        $('#multiple-sales-managers-transfer-request').on('change.select2',function(){
            $('#salesagent3').tokenize2().trigger('tokenize:clear')
            var multiple_sales_manager_ids=$(this).val()
            console.log(multiple_sales_manager_ids)
            $.ajax({
                type:'GET',
                url:"{{ route('admin.multipleSalesAgents')}}",
                data:{
                    multiple_sales_manager_ids:multiple_sales_manager_ids,
                    _token: '{{csrf_token()}}'
                },
                success:function(response){
                    console.log(response)
                    $('#salesagent3').empty()
                    $.each(response.salesAgents, function (key, value) {
                            $("#salesagent3").append('<option  value="' + value
                                .id + '">' + value.name + '</option>');
                    });
                    $('#salesagent3 option').each(function(){
                        $(this).attr("selected",true);
                    });
                    $('#salesagent3').tokenize2().trigger('tokenize:remap')
                    global_sales_agents_transfer_request=$('#salesagent3').val()
                }
            })
        })

        $(document).on('change','#allow-recived-sales-managers-transfer-request',function(){
            var active_sales_managers
            if(this.checked)
            {
                active_sales_managers = 0
            }else{
                active_sales_managers = 1
            }
            console.log(active_sales_managers)
            $.ajax({
                type:'GET',
                url:"{{ route('admin.allowRecievedSalesManagers')}}",
                data:{
                    active_sales_managers:active_sales_managers,
                    _token: '{{csrf_token()}}'
                },
                success:function(response){
                    console.log(response)
                    $('#multiple-sales-managers-transfer-request').empty()
                    // $("#multiple-sales-managers-transfer-request").append('<option>' + '--' + '</option>');
                    $.each(response.activeSalesManagers, function (key, value) {
                            $("#multiple-sales-managers-transfer-request").append('<option value="' + value
                                .id + '">' + value.name + '</option>');
                    });
                }

            })
        })

        $(document).on('change','#allow-recived-sales-agents-transfer-request',function(){
            var active_sales_agents
            if(this.checked)
            {
                active_sales_agents = 1
            }else{
                active_sales_agents = 0
            }
            console.log(active_sales_agents)
            $('#salesagent3').tokenize2().trigger('tokenize:clear')

            $.ajax({
                type:'GET',
                url:"{{ route('admin.allowRecievedSalesAgents')}}",
                data:{
                    active_sales_agents:active_sales_agents,
                    sales_agents:global_sales_agents_transfer_request,
                    _token: '{{csrf_token()}}'
                },
                success:function(response){
                    console.log('Response '+response)
                    $('#salesagent3').empty()
                    $.each(response.activeSalesAgents, function (key, value) {
                            $("#salesagent3").append('<option  value="' + value
                                .id + '">' + value.name + '</option>');
                    });
                    $('#salesagent3 option').each(function(){
                        $(this).attr("selected",true);
                    });
                    $('#salesagent3').tokenize2().trigger('tokenize:remap')
                }
            })
        })



    </script>

<script src="{{ url('interface_style/search/vendor/select2/select2.min.js') }}"></script>
<script src="{{ url('interface_style/search/vendor/jquery-validate/jquery.validate.min.js') }}"></script>
{{-- <script src="{{ url('interface_style/search/vendor/bootstrap-wizard/bootstrap.min.js') }}"></script> --}}
<script src="{{ url('interface_style/search/vendor/bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}"></script>
<script src="{{ url('interface_style/search/vendor/datepicker/moment.min.js') }}"></script>
<script src="{{ url('interface_style/search/vendor/datepicker/daterangepicker.js') }}"></script>

<!-- Main JS-->
<script src="{{ url('interface_style/search/js/global.js') }}"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>

@endsection
