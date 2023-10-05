@extends('layouts.content')

@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'Active Customers') }}
@endsection

@section('css_style')
    <link rel="stylesheet" href="{{ asset('css/tokenize2.min.css') }}">
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
    <link href="{{url('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet"/>
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
            <h3>  {{ MyHelpers::admin_trans(auth()->user()->id,'Active Customers') }} :</h3>
            <div class="addBtn">
                <a href="{{ route('admin.addCustomerWithReq')}}">
                    <button>
                        <i class="fas fa-plus-circle"></i> {{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}
                    </button>
                </a>
            </div>
        </div>
    </div>
    @if ($customers > 0)
        <div class="tableBar">
            <div class="topRow">
                <div class="row align-items-center text-center text-md-left">
                    <div class="col-2">
                        <div class="selectAll">
                            <div class="form-check">
                                <input type="checkbox" id="allreq" class="form-check-input"
                                       onclick="chbx_toggle1(this);"/>
                                <label class="form-check-label" for="allreq">تحديد الكل </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="tableUserOption   flex-wrap justify-content-md-end">
                            <div class="addBtn  mt-lg-0 mt-3 orderBtns spansBtn">
                                <button class="mr-2 Cloud sendCustomer" style="cursor: not-allowed" disabled id="email">
                                    <i class="fa fa-envelope"></i>
                                    ارسال ايميل
                                </button>
                                <button class="mr-2 Pink sendCustomer" style="cursor: not-allowed" disabled id="sms">
                                    <i class="fa fa-commenting"></i>
                                    ارسال رسائل نصية
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-3 mt-lg-0 mt-3">
                        <div id="dt-btns" class="tableAdminOption">
                        </div>
                    </div>
                </div>
            </div>
            <div class="dashTable">
                <table id="mycustomer-table" class="table table-bordred table-striped data-table">
                    <thead>
                    <tr>
                        <th></th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'mobile') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'app_downloaded') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'sms count') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'email count') }}</th>
                        <th>آخر وقت إرسال (الرسائل النصية)</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req classification agent') }}</th>
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
            <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No Customer') }}</h2>
        </div>
    @endif
@endsection
@section('updateModel')
    @include('Admin.Customer.filterReqs')
    @include('Admin.Customer.sendCustomer')
    @include('Admin.Customer.updateCustomer')
@endsection

@section('confirmMSG')
    @include('Admin.Customer.confirmationMsg')
@endsection

@section('scripts')
    <script src="{{ asset('js/tokenize2.min.js') }}"></script>
    <script>
        $(document).on('click', '.sendCustomer', function (e) {
            $("#subject_msg_div").hide();
            var array = []
            var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].value != "on" && checkboxes[i].value != "off") {
                    var val = parseInt(checkboxes[i].value);
                    array.push(val);
                }
            }
            var id = $(this).attr('id');
            if (id == 'email') {
                $("#subject_msg_div").show();
            }
            document.getElementById("text_msg").value = '';
            sendCustomer(array, id);
        });
        function sendCustomer(array, type) {
            var modalConfirm = function (callback) {
                $("#mi-modal3").modal('show');
                $("#modal-btn-si3").on("click", function () {
                    callback(true);
                });
                $("#modal-btn-no3").on("click", function () {
                    callback(false);
                    $("#mi-modal3").modal('hide');
                });
            };
            modalConfirm(function (confirm) {
                if (confirm) {
                    document.getElementById('textError').innerHTML = '';
                    document.getElementById('subjectError').innerHTML = '';
                    var text = document.getElementById("text_msg").value;
                    var subject = document.getElementById("subject_msg").value;
                    if (text != '') {
                        if ((type == 'email' && subject != '') || type == 'sms') {
                            $('#modal-btn-si3').attr("disabled", true);
                            $("#modal-btn-si3").html("<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Please Wait') }}");
                            $.get("{{ route('admin.sendCustomerArray')}}", {
                                array: array,
                                subject: subject,
                                text: text,
                                type: type,
                            }, function (data) {
                                var url = '{{ route("admin.allCustomers") }}';
                                if (data != 0) {
                                    $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> تم إرسال " + data.counter + " من أصل " + data.request_count);
                                    $("#mi-modal3").modal('hide');
                                    window.location.href = url; //using a named route
                                } else
                                    $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id, 'Try Again')}}");
                            });
                        } else {
                            document.getElementById('subjectError').innerHTML = 'العنوان مطلوب';
                        }
                    } else {
                        document.getElementById('textError').innerHTML = 'النص مطلوب';
                    }
                } else {
                    //reject
                }
            });
        };
        function disabledButton() {
            if ($(':checkbox[name="chbx[]"]:checked').length > 0) {
                document.getElementById("email").disabled = false;
                document.getElementById("email").style = "";
                document.getElementById("sms").disabled = false;
                document.getElementById("sms").style = "";
            } else {
                document.getElementById("sms").disabled = true;
                document.getElementById("sms").style = "cursor: not-allowed";
                document.getElementById("email").disabled = true;
                document.getElementById("email").style = "cursor: not-allowed";
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
    </script>
    <script>
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
        $(document).ready(function () {
            var dt = $('#mycustomer-table').DataTable({
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
                    'pageLength',
                    {
                        text: '{{ MyHelpers::admin_trans(auth()->user()->id,"Search") }}',
                        action: function (e, dt, node, config) {
                            $('#myModal2').modal('show');
                        }
                    }
                ],
                processing: true,
                serverSide: true,
                ajax: ({
                    'url': "{{ url('admin/allcustomer-datatable') }}",
                    'method': 'Get',
                    'data': function (data) {
                        let agents_ids = $('#agents_ids').data('tokenize2').toArray();
                        let customer_salary = $('#customer-salary').val();
                        let customer_salary_to = $('#customer-salary-to').val()
                        let customer_phone = $('#customer-phone').val();
                        let req_date_from = $('#request-date-from').val();
                        let req_date_to = $('#request-date-to').val();
                        let source = $('#source').data('tokenize2').toArray();
                        let collaborator = $('#collaborator').data('tokenize2').toArray();
                        let work_source = $('#work_source').data('tokenize2').toArray();
                        let app_downloaded = $('#app_downloaded').data('tokenize2').toArray();
                        if (req_date_from) {
                            data['req_date_from'] = req_date_from;
                        }
                        if (req_date_to) {
                            data['req_date_to'] = req_date_to;
                        }
                        if (customer_salary) data['customer_salary'] = customer_salary;
                        if (customer_salary_to) data['customer_salary_to'] = customer_salary_to;
                        if (customer_phone) data['customer_phone'] = customer_phone;
                        if (source) data['source'] = source;
                        if (collaborator) data['collaborator'] = collaborator;
                        if (work_source) data['work_source'] = work_source;
                        if (app_downloaded) data['app_downloaded'] = app_downloaded;
                        if (agents_ids) data['agents_ids'] = agents_ids;
                        xses.forEach(function (item) {
                            if (getClassifcationX(item)) {
                                data['class_id_' + item] = getClassifcationX(item)
                            }
                        })

                    },
                }),
                columns: [
                    {
                        "targets": 0,
                        "data": "id",
                        "render": function (data, type, row, meta) {
                            return '<input type="checkbox" id="chbx" name="chbx[]"  onchange="disabledButton()"  value="' + data + '"/>';
                        },
                        orderable:!1
                    },
                    {
                        data: 'name',
                        name: 'name',
                        orderable:!1
                    },
                    {
                        data: 'user_name',
                        name: 'user_name',
                        orderable:!1
                    },
                    {
                        data: 'mobile',
                        name: 'mobile',
                        orderable:!1
                    },
                    {
                        data: 'work',
                        name: 'work',
                        orderable:!1
                    },
                    {
                        data: 'salry',
                        name: 'salry',
                        orderable:!1
                    },
                    {
                        data: 'app_downloaded',
                        name: 'app_downloaded',
                        orderable:!1
                    },
                    {
                        data: 'sms_count',
                        name: 'sms_count',
                        orderable:!1
                    },
                    {
                        data: 'send_email_count',
                        name: 'send_email_count',
                        orderable:!1
                    },
                    {
                        data: 'last_sms_date',
                        name: 'last_sms_date',
                        orderable:!1
                    },
                    {
                        data: 'source',
                        name: 'source',
                        orderable:!1
                    },
                    {
                        data: 'class_id_agent',
                        name: 'class_id_agent',
                        orderable:!1
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable:!1
                    }
                ],
                initComplete: function () {
                    let api = this.api();
                    $("#filter-search-req").on('click', function (e) {
                        e.preventDefault();
                        api.draw();
                        $('#myModal2').modal('hide');
                    });
                    $(".paginate_button").addClass("pagination-circle");
                    $('#example-search-input').keyup(function () {
                        dt.search($(this).val()).draw();
                    })
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
                },
                createdRow: function (row, data, index) {
                    $('td', row).eq(1).addClass('commentStyle'); // 6 is index of column
                    $('td', row).eq(1).attr('title', data.name); // to show other text of comment
                    $('td', row).eq(2).addClass('commentStyle'); // 6 is index of column
                    $('td', row).eq(2).attr('title', data.user_name);
                    $('td', row).eq(7).addClass('commentStyle'); // 6 is index of column
                    $('td', row).eq(7).attr('title', data.source); // to show other text of comment
                },
            });
        });
    </script>
    <script src="{{url('js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $("#hijri-date").hijriDatePicker({
                hijri: true,
                format: "YYYY/MM/DD",
                hijriFormat: 'iYYYY-iMM-iDD',
                showSwitcher: false,
                showTodayButton: true,
                showClose: true
            });
        });
    </script>
    <script type="text/javascript">
        $("#convertToHij").click(function () {
            if ($("#birth").val() == "") {
                alert("{{ MyHelpers::admin_trans(auth()->user()->id,'Enter a date') }}");
            } else {
                $.ajax({
                    url: "{{ URL('all/convertToHijri') }}",
                    type: "POST",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "gregorian": $("#birth").val(),
                    },
                    success: function (response) {
                        $("#hijri-date").val($.trim(response));
                    },
                    error: function () {
                        swal({
                            title: "{{ MyHelpers::admin_trans(auth()->user()->id,'Failed') }}!",
                            text: "{{ MyHelpers::admin_trans(auth()->user()->id,'Try Again') }}.",
                            html: true,
                            type: "error",
                        });
                    }
                });
            }
        });
        $("#convertToGreg").click(function () {

            if ($("#hijri-date").val() == "") {
                alert("{{ MyHelpers::admin_trans(auth()->user()->id,'Enter a date') }}");
            } else {


                $.ajax({
                    url: "{{ URL('all/convertToGregorian') }}",
                    type: "POST",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "hijri": $("#hijri-date").val(),
                    },
                    success: function (response) {
                        // alert(response);
                        $("#birth").val($.trim(response));
                        calculate();
                    },
                    error: function (response) {
                        console.log(response);
                        swal({
                            title: "{{ MyHelpers::admin_trans(auth()->user()->id,'Failed') }}!",
                            text: "{{ MyHelpers::admin_trans(auth()->user()->id,'Try Again') }}.",
                            html: true,
                            type: "error",
                        });
                    }
                });
            }
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            var today = new Date().toISOString().split("T")[0];
            $('#birth').attr("max", today);
        });
        $(document).on('click', '#edit', function (e) {
            $('#frm-update').find('#yes').removeAttr("checked");
            $('#frm-update').find('#no').removeAttr("checked");
            document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
            $('#checkMobile').removeClass('btn-success');
            $('#checkMobile').removeClass('btn-danger');
            $('#checkMobile').addClass('btn-info');
            var id = $(this).attr('data-id');
            $.get("{{route('admin.updateCustomer')}}", {
                id: id
            }, function (data) {
                if (data.status != 0) {
                    $('#frm-update').find('#id').val(data[0].id);
                    $('#frm-update').find('#salesagent').val(data[0].user_name);
                    $('#frm-update').find('#name').val(data[0].name);
                    $('#frm-update').find('#mobile').val(data[0].mobile);
                    $('#frm-update').find('#age').val(data[0].age);
                    $('#frm-update').find('#birth').val(data[0].birth_date);
                    $('#frm-update').find('#hijri-date').val(data[0].birth_date_higri);
                    $('#frm-update').find('#work').val(data[0].work);
                    $('#frm-update').find('#sex').val(data[0].sex);
                    $('#frm-update').find('#salary_source').val(data[0].salary_id);
                    $('#frm-update').find('#salary').val(data[0].salary);
                    var support = data[0].is_supported;
                    if (support == 'yes')
                        $('#frm-update').find('#yes').attr('checked', 'checked');
                    else if (support == 'no')
                        $('#frm-update').find('#no').attr('checked', 'checked');
                    else {
                        $('#frm-update').find('#yes').removeAttr("checked");
                        $('#frm-update').find('#no').removeAttr("checked");
                    }
                    $('#myModal').modal('show');
                    calculate();
                } else
                    $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
            })
        });
        $(document).on('click', '#archive', function (e) {
            var id = $(this).attr('data-id');
            var modalConfirm = function (callback) {
                $("#mi-modal").modal('show');
                $("#modal-btn-si").on("click", function () {
                    callback(true);
                    $("#mi-modal").modal('hide');
                });
                $("#modal-btn-no").on("click", function () {
                    callback(false);
                    $("#mi-modal").modal('hide');
                });
            };
            modalConfirm(function (confirm) {
                if (confirm) {
                    $.post("{{ route('admin.archCustomer')}}", {
                        id: id,
                        _token: "{{csrf_token()}}",
                    }, function (data) { // we pass var id in the request with id as name ; we use $.post : because we pass our data to server and got response
                        if (data.status == 1) {
                            var d = ' # ' + id;
                            var test = d.replace(/\s/g, ''); // to remove all spaces in var d , to find the <tr/> that i deleted and reomve it
                            $(test).remove(); // remove by #id
                            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                        } else {
                            $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                        }
                    })
                } else {
                    //No archive
                }
            });
        })
        $('#frm-update').on('submit', function (e) {
            $('#nameError').addClass("d-none");
            $('#mobileError').addClass("d-none");
            $('#birthError').addClass("d-none");
            $('#sexError').addClass("d-none");
            $('#workError').addClass("d-none");
            $('#salary_sourceError').addClass("d-none");
            $('#salaryError').addClass("d-none");
            e.preventDefault();
            var data = $(this).serialize();
            var url = $(this).attr('action');
            $.post(url, data, function (data) { //data is array with two veribles (request[], ss)
                if (data == "existed") {
                    $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'This customer already existed') }}");
                } else if (data.ss == 1) {
                    var optiones = "<div class='table-data-feature'>  <button class='item' id='open' data-toggle='tooltip' data-id= " + data.request.id + "  data-placement='top' title='{{ MyHelpers::admin_trans(auth()->user()->id,'Open') }}'><a  href='/customerprofile/" + data.request.id + "'> <i class='zmdi zmdi-eye'></i></a> </button> <button class='item'  id='edit' type='button' data-id= " + data.request.id + "  data-toggle='tooltip' data-placement='top' title='{{ MyHelpers::admin_trans(auth()->user()->id,'Edit') }}'><i class='zmdi zmdi-edit'></i> </button> <button class='item' data-id= " + data.request.id + "  data-toggle='tooltip' data-placement='top' id='archive' title='{{ MyHelpers::admin_trans(auth()->user()->id,'Archive') }}'><i class='zmdi zmdi-delete'></i> </button> </div>"
                    var is_sup = data.request.support;
                    var support = '';
                    if (is_sup == 'yes')
                        support = 'نعم';
                    else
                        support = 'لا';
                    if (is_sup == 'yes')
                        is_sup_class = "status--process";
                    else
                        is_sup_class = "status--denied";
                    if (data.request.salary == null)
                        $customerSalary = "---";
                    else
                        $customerSalary = data.request.salary + " {{ MyHelpers::admin_trans(auth()->user()->id,'SR') }}";
                } else if (data.ss == 0) {
                    $('#msg2').addClass("alert-warning").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>{{ MyHelpers::admin_trans(auth()->user()->id,'Nothing Change') }}! ");
                }
                $('#mycustomer-table').DataTable().ajax.reload();
                $('#myModal').modal('hide');
            }).fail(function (data) {
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) == false) {
                    $.each(errors.errors, function (key, value) {
                        var ErrorID = '#' + key + 'Error';
                        $(ErrorID).removeClass("d-none");
                        $(ErrorID).text(value);
                    })
                }
            });
        });
        function calculate() {
            var date = new Date(document.getElementById('birth').value);
            var dateString = (((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + date.getFullYear());
            var now = new Date();
            var today = new Date(now.getYear(), now.getMonth(), now.getDate());
            var yearNow = now.getYear();
            var monthNow = now.getMonth();
            var dateNow = now.getDate();
            var dob = new Date(dateString.substring(6, 10),
                dateString.substring(0, 2) - 1,
                dateString.substring(3, 5)
            );
            var yearDob = dob.getYear();
            var monthDob = dob.getMonth();
            var dateDob = dob.getDate();
            var age = {};
            var ageString = "";
            var yearString = "";
            var monthString = "";
            var dayString = "";
            yearAge = yearNow - yearDob;
            if (monthNow >= monthDob)
                var monthAge = monthNow - monthDob;
            else {
                yearAge--;
                var monthAge = 12 + monthNow - monthDob;
            }

            if (dateNow >= dateDob)
                var dateAge = dateNow - dateDob;
            else {
                monthAge--;
                var dateAge = 31 + dateNow - dateDob;

                if (monthAge < 0) {
                    monthAge = 11;
                    yearAge--;
                }
            }

            age = {
                years: yearAge,
                months: monthAge,
                days: dateAge
            };

            if (age.years > 1) yearString = " {{ MyHelpers::admin_trans(auth()->user()->id,'years') }}";
            else yearString = " {{ MyHelpers::admin_trans(auth()->user()->id,'year') }}";
            if (age.months > 1) monthString = "  {{ MyHelpers::admin_trans(auth()->user()->id,'months') }}";
            else monthString = " {{ MyHelpers::admin_trans(auth()->user()->id,'month') }}";
            if (age.days > 1) dayString = " {{ MyHelpers::admin_trans(auth()->user()->id,'days') }}";
            else dayString = " {{ MyHelpers::admin_trans(auth()->user()->id,'day') }}";


            if ((age.years > 0) && (age.months > 0) && (age.days > 0))
                ageString = age.years + yearString + ", " + age.months + monthString + ", {{ MyHelpers::admin_trans(auth()->user()->id,'and') }} " + age.days + dayString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
            else if ((age.years == 0) && (age.months == 0) && (age.days > 0))
                ageString = "{{ MyHelpers::admin_trans(auth()->user()->id,'Only') }} " + age.days + dayString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}";
            else if ((age.years > 0) && (age.months == 0) && (age.days == 0))
                ageString = age.years + yearString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}. ";
            else if ((age.years > 0) && (age.months > 0) && (age.days == 0))
                ageString = age.years + yearString + " {{ MyHelpers::admin_trans(auth()->user()->id,'and') }} " + age.months + monthString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
            else if ((age.years == 0) && (age.months > 0) && (age.days > 0))
                ageString = age.months + monthString + " {{ MyHelpers::admin_trans(auth()->user()->id,'and') }} " + age.days + dayString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
            else if ((age.years > 0) && (age.months == 0) && (age.days > 0))
                ageString = age.years + yearString + " {{ MyHelpers::admin_trans(auth()->user()->id,'and') }} " + age.days + dayString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
            else if ((age.years == 0) && (age.months > 0) && (age.days == 0))
                ageString = age.months + monthString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
            else ageString = "{{ MyHelpers::admin_trans(auth()->user()->id,'Could not calculate age') }}!";


            document.getElementById('age').value = ageString;


        }
        function changeMobile() {
            document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
            $('#checkMobile').removeClass('btn-success');
            $('#checkMobile').removeClass('btn-danger');
            $('#checkMobile').addClass('btn-info');
        }
        $(document).on('click', '#checkMobile', function (e) {
            $('#checkMobile').attr("disabled", true);
            document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Loading') }}";
            var mobile = document.getElementById('mobile').value;
            if (mobile != null /*&& regex.test(mobile)*/) {
                document.getElementById('error').innerHTML = "";
                $.post("{{ route('all.checkMobile') }}", {
                    mobile: mobile,
                    _token: "{{csrf_token()}}",
                }, function (data) {
                    if (data.errors) {
                        if (data.errors.mobile) {
                            $('#mobile-error').html(data.errors.mobile[0])
                        }
                    }
                    if (data == "no") {
                        document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-check'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
                        $('#checkMobile').removeClass('btn-info');
                        $('#checkMobile').addClass('btn-success');
                        $('#checkMobile').attr("disabled", false);
                    } else {
                        document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-times'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Not') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
                        $('#checkMobile').removeClass('btn-info');
                        $('#checkMobile').addClass('btn-danger');
                        $('#checkMobile').attr("disabled", false);
                    }
                }).fail(function (data) {
                });
            } else {
                document.getElementById('error').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Enter Valid Mobile Number (9 digits) and starts 5') }}";
                document.getElementById('error').display = "block";
                document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
                $('#checkMobile').attr("disabled", false);
            }
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
    </script>
@endsection
