@extends('layouts.content')

@section('title',__("global.hasbah_requests"))

@push('styles')
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


        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .activeColor {
            color: green;
        }

        .notactiveColor {
            color: red;
        }

    </style>
    <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">
    {{--Sweet Alert--}}
    <script src="{{ asset('backend/sweetalert/sweetalert2.min.js') }}"></script>
    <link href="{{ asset('backend/sweetalert/sweetalert2.min.css') }}" rel="stylesheet">
@endpush

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
            <h3>@lang("global.hasbah_requests")</h3>
        </div>
    </div>
    <br>
    <div id="msg2" class="alert alert-dismissible" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>

    <div>

        <label for="toggle" style="padding-left:2%">التحويل التلقائي: </label>
        @if ($moveCondition->option_value == 0)
            <span id="toggleText" style="color: red;">غير مفعل</span>
        @else
            <span id="toggleText" style="color: green;">مفعل</span>

        @endif

        <label class="switch">
            <input name="isActive" type="checkbox" {{$moveCondition->option_value == 1 ? 'checked' : ''}}>
            <span class="slider round"></span>
        </label>

        <div class="row {{$moveCondition->option_value == 0? 'd-none' : ''}}" id="hours_movement">
            <div class="col-2"><label class="control-label">ساعات التحويل :</label></div>
            <div class="col-6">
                <input id="hours_movement_input" name="hours_movement_input" type="number" class="form-control" value="{{$move_hours_Condition->option_value}}" placeholder="مثال : 48 ساعة">
            </div>
            <div class="col-4">
                <button type="button" class="btn btn-primary" id="update_hours_movement_input">
                    تحديث
                </button>
            </div>

        </div>

    </div>
    <div class="tableBar pt-5">
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

                <div class="col-lg-3">

                    <div class="tableUserOption   flex-wrap justify-content-md-end">

                        <div class="addBtn  mt-lg-0 mt-3 orderBtns spansBtn">


                            <button class="mr-2 Cloud" style="cursor: not-allowed" disabled id="moveAll" onclick="getReqests()">
                                <i class="fas fa-random"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Move Reqs') }}
                            </button>
                        </div>
                    </div>

                </div>

                <div class="col-lg-4">
                    <div class="addBtn  mt-lg-0 mt-3 orderBtns spansBtn">
                        <button class="mr-2 Cloud" onclick="searchOpen()">
                            <i class="fa fa-search"></i>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Search') }}
                        </button>
                    </div>
                </div>

                <div class="col-lg-3 mt-lg-0 mt-3">
                    <div id="dt-btns" class="tableAdminOption">

                    </div>
                </div>

            </div>
        </div>
        <div id="dashTableValue">
            <div class="dashTable">
                <table id="data-table" class="table table-bordred table-striped data-table table-sm">
                    <thead>
                    <tr>
                        <th></th>
                        <th>التاريخ</th>
                        <th>الإسم</th>
                        <th style="width: 50px">العدد</th>
                        <th>البريد الإلكترونى</th>
                        <th>رقم الجوال</th>
                        <th>جهه العمل</th>
                        <th>الراتب</th>{{--
                        <th>هل أكمل الطلب</th>--}}
                        <th>هل لديه طلب</th>
                        <th>التحكم</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>

                </table>
            </div>
        </div>
    </div>
    <br>
    <div class="tab-content" id="nav-tabContent">

        <div class="tab-pane fade" id="nav-export" role="tabpanel" aria-labelledby="nav-export-tab">
            <div class="row pt-5">
                <div class="form-group col-lg-4">
                    <div class="form-check">
                        <input class="form-check-input" style="height:20px" name="status" type="checkbox" value="1" id="completed">
                        <label class="form-check-label" style="margin-right: 8px;" for="completed">
                            الإسم
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" style="height:20px" name="status" type="checkbox" value="0" id="uncompleted">
                        <label class="form-check-label" style="margin-right: 8px;" for="uncompleted">
                            البريد الإلكترونى
                        </label>
                    </div>
                </div>
                <div class="form-group col-lg-4">
                    <div class="form-check">
                        <input class="form-check-input" style="height:20px" name="status" type="checkbox" value="1" id="completed">
                        <label class="form-check-label" style="margin-right: 8px;" for="completed">
                            رقم الجوال
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" style="height:20px" name="status" type="checkbox" value="0" id="uncompleted">
                        <label class="form-check-label" style="margin-right: 8px;" for="uncompleted">
                            الراتب
                        </label>
                    </div>
                </div>
                <div class="form-group col-lg-4">
                    <div class="form-check">
                        <input class="form-check-input" style="height:20px" name="status" type="checkbox" value="1" id="completed">
                        <label class="form-check-label" style="margin-right: 8px;" for="completed">
                            جهه العمل (الرتبة)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" style="height:20px" name="status" type="checkbox" value="0" id="uncompleted">
                        <label class="form-check-label" style="margin-right: 8px;" for="uncompleted">
                            تاريخ الطلب
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">إستخراج</button>
            </div>
        </div>
    </div>
    @include("Admin.Guests.filter")
@endsection

@section('updateModel')
    @include('Admin.Guests.moveReq-multi')
@endsection

@push('scripts')
    <script src="{{ asset('js/tokenize2.min.js') }}"></script>
    <script>
        $('#form-data')[0].reset();
        function searchOpen(){
            $('#myModal').modal('show');
        }
        $('.tokenizeable').tokenize2();
        $(".tokenizeable").on("tokenize:select", function () {
            $(this).trigger('tokenize:search', "");
        });
        $(document).ready(function () {
            var table = $('#data-table').DataTable({
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
                ajax: {
                    url:"{{ route('api.guests.index') }}",
                    data:function(data){
                        let start_date =  $('#start_date').val();
                        let end_date =  $('#end_date').val();
                        let from_salary =  $('#from_salary').val();
                        let to_salary =  $('#to_salary').val();
                        let searches =  $('#searches').val();
                        var status=[];
                        var has_request=[];
                        var works=[];
                        var ranks=[];
                        $('input[name="status[]"]:checked').each(function() {
                            status.push($(this).val())
                        });
                        $('input[name="has_request[]"]:checked').each(function() {
                            has_request.push($(this).val())
                        });
                        $('input[name="works[]"]:checked').each(function() {
                            works.push($(this).val())
                        });
                        $('input[name="ranks[]"]:checked').each(function() {
                            ranks.push($(this).val())
                        });
                        if (start_date != '') {
                            data['start_date'] = start_date;
                        }
                        if (end_date != '') {
                            data['end_date'] = end_date;
                        }
                        if (from_salary != '') {
                            data['from_salary'] = from_salary;
                        }
                        if (searches != '') {
                            data['searches'] = searches;
                        }
                        if (to_salary != '') {
                            data['to_salary'] = to_salary;
                        }
                        if (status != '') {
                            data['status'] = status;
                        }
                        if (has_request != '') {
                            data['has_request'] = has_request;
                        }
                        if (works != '') {
                            data['works'] = works;
                        }
                        if (ranks != '') {
                            data['ranks'] = ranks;
                        }
                    }
                },

                columns: [
                    {
                        "targets": 0,
                        "data": "id",
                        "render": function (data, type, row, meta) {
                            return '<input type="checkbox" id="chbx" name="chbx[]" onchange="disabledButton()"  value="' + data + '"/>';
                        }
                    },


                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'count',
                        name: 'count'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'mobile',
                        name: 'mobile'
                    },
                    {
                        data: 'work',
                        name: 'work'
                    },
                    {
                        data: 'salary',
                        name: 'salary'
                    },/*
                    {
                        data: 'status',
                        name: 'status'
                    },*/
                    {
                        data: 'has_request',
                        name: 'has_request'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],
                "order": [
                    [2, "desc"]
                ], // Order on init. # is the column, starting at 0
                createdRow: function (row, data, index) {


                    $('td', row).eq(3).addClass('commentStyle');
                    $('td', row).eq(3).attr('title', data.email);
                    $('td', row).eq(1).addClass('reqNum'); // 6 is index of column
                    $('td', row).eq(2).addClass('reqNum'); // 6 is index of column
                    $('td', row).eq(4).addClass('reqNum'); // 6 is index of column
                    $('td', row).eq(5).addClass('reqNum'); // 6 is index of column
                },

                "initComplete": function (settings, json) {
                    $(".paginate_button").addClass("pagination-circle");
                    /* To Adaptive with New Design */
                    let api = this.api();
                    $("#filter-search-req").on('click', function (e) {
                        e.preventDefault();
                        api.draw();
                        //checktable(api);
                        $('#myModal').modal('hide');
                    });
                    table.buttons().container()
                        .appendTo('#dt-btns');

                    $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title', 'تصدير');
                    $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title', 'طباعة');
                    $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');

                    $('.buttons-excel').addClass('no-transition custom-btn');
                    $('.buttons-print').addClass('no-transition custom-btn');
                    $('.buttons-collection').addClass('no-transition custom-btn');


                    $('.tableAdminOption span').tooltip(top)
                    $('button.dt-button').tooltip(top)

                    /* To Adaptive with New Design */
                }

            });

        });
        function deleteData(id) {
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'هل انت متأكد',
                text: "لن تكون قادر على التراجع فى هذا الأمر ؟",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                buttons: ["إلغاء", "نعم , احذف !"],
            }).then(function (inputValue) {
                var url = "{{ url('admin/guests-users-delete') }}" + '/' + id;
                $.get(url, function (data) {
                    if (inputValue != null) {
                        // window.location.replace(url)
                        $('#data-table').DataTable().ajax.reload();

                    } else {
                        swal({
                            title: 'خطأ',
                            text: data.message,
                            icon: 'error',
                            timer: '2500'
                        })
                    }
                });
            });
        }
        function addForm() {
            save_method = "add";
            $('#add-form input[name=_method]').val('POST');
            $('#add-form').modal('show');
            $('#add-form form')[0].reset();
            $('#add-form .modal-title').text('البحث المتقدم ');

        }

        $("#checkAllMillitary").click(function () {
            $('.military input:checkbox').not(this).prop('checked', this.checked);
        });
        $("#checkAllWorks").click(function () {
            $('.works input:checkbox').not(this).prop('checked', this.checked);
            check();
        });

        /* $('#norequest').click(function(){
             if(this.checked == false){
                 $('#request').prop('checked',true)
             }
         })

         $('#request').click(function(){
             if(this.checked == false){
                 $('#norequest').prop('checked',true)
             }
         })

         $('#uncompleted').click(function(){
             if(this.checked == false){
                 $('#completed').prop('checked',true)
             }
         })

         $('#completed').click(function(){
             if(this.checked == false){
                 $('#uncompleted').prop('checked',true)
             }
         })*/
        function check() {

            @foreach($works as $work)
                @if($work->value == 'عسكري')
            if ($('#work{{$work->id}}').prop('checked') == true) {
                $('#militiaryService').css('display', 'block')
            } else {
                $('#militiaryService').css('display', 'none')
            }
            @endif
            @endforeach
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

        function getReqests() {
            document.getElementById("salesagent3").value = '';
            document.getElementById('salesagentsError3').innerHTML = '';
            $('#mi-modal9').modal('show');
        }

        $(document).on('click', '#update_hours_movement_input', function (e) {
            $(this).attr("disabled", true);
            $(this).html("<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Loading') }}");

            var hours = document.getElementById("hours_movement_input").value;
            var url = "{{ route('admin.updatemovmenthours')}}";

            $.get(url, {
                hours: hours,
            }, function (data) {

                if (data.status == 1) {

                    $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                    $('#data-table').DataTable().ajax.reload();
                } else {
                    $('#update_hours_movement_input').attr("disabled", false);
                    $('#update_hours_movement_input').html("تحديث");
                    swal({
                        title: 'خطأ',
                        text: 'لم يتم تحديث شي',
                        type: 'error',
                        confirmButtonText: 'موافق',
                        confirmButtonColor: '#990000',
                    })

                }

            });
        });

        $(document).on('click', '#submitMove3', function (e) {
            var array = []
            var checkboxes = document.querySelectorAll('[name="chbx[]"]:checked')
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
            var not_complete = $('#not_complete').is(":checked");

            var url = "{{ route('admin.moveguestusers')}}";

            $.get(url, {
                agents_ids: agents_ids,
                id: id,
                not_complete: not_complete
            }, function (data) {

                console.log(data);


                if (data.updatereq == 1) {

                    $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                    $('#data-table').DataTable().ajax.reload();
                    $('#salesagent3').data('tokenize2').trigger('tokenize:clear')
                } else if (data.updatereq == 2) {
                    swal({
                        title: 'خطأ',
                        text: data.message,
                        type: 'error',
                        confirmButtonText: 'موافق',
                        confirmButtonColor: '#990000',
                    })
                }


            })

            document.querySelector('#submitMove3').innerHTML = 'تحويل';
            $('#not_complete').prop('checked', false);
            $("#salesagent3 option:selected").removeAttr("selected");
            $('#submitMove3').attr("disabled", false);
            document.getElementById("moveAll").disabled = true;
            document.getElementById("moveAll").style = "cursor: not-allowed";
            $('#mi-modal9').modal('hide');


        });

        var checkbox = document.querySelector("input[name=isActive]");
        var toggleText = document.getElementById("toggleText");
        checkbox.addEventListener('change', function () {

            toggleText.style.color = '';
            toggleText.classList.remove("activeColor");
            toggleText.classList.remove("notactiveColor");

            if (this.checked) {

                $.get("{{route('admin.updatehasbah_net_movment')}}", {}, function (data) {
                    //  console.log(data);
                    if (data.status != 0) {
                        toggleText.innerHTML = 'مفعل';
                        toggleText.classList.add("activeColor");
                        $('#hours_movement').removeClass("d-none");
                        $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                    } else
                        $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                });

            } else {
                $.get("{{route('admin.updatehasbah_net_movment')}}", {}, function (data) {
                    if (data.status != 0) {
                        //   console.log(data);
                        toggleText.innerHTML = 'غير مفعل';
                        toggleText.classList.add("notactiveColor");
                        $('#hours_movement').addClass("d-none");
                        $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                    } else
                        $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                });
            }
        });

        ///////////////////////////////////

    </script>
@endpush
