@if (env('NEW_THEME') == '1')
    @include('themes.theme1.layouts.content')
@else

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ url('assest/css/bootstrap-rtl.css') }}">
    <link rel="stylesheet" href="{{ url('assest/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('assest/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ url('assest/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('assest/css/calculater.css') }}">
    <link rel="stylesheet" href="{{ url('assest/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ url('assest/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ url('assest/css/jquery.fancybox.min.css') }}">
    <link rel="stylesheet" href="{{ url('assest/css/animate.css') }}">

    <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>

    <!-- Title Page-->
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/tokenize2.min.css') }}">
    <style>
        .tableUserOption .addBtn button {
            background: #E67681;
        }

        button.mov {
            background-color: #887BFF !important;
        }

        .rounded {
            border-radius: 5px;
        }

        .w-btn.rounded {
            border-radius: 50px !important;
        }

        .w-btn {
            padding: 10px 40px;
            border: 0;
            background: #0f5b94;
            color: #fff;
            border-radius: 5px;
            display: inline-block;
            cursor: pointer;
            outline: 0;
            transition: 0.3s;
            box-shadow: 0px 4px 15px #00000021;
        }

        table.data-table td:last-child {
            /*text-align: left;*/
        }

        button:disabled {
            cursor: not-allowed;
        }

        .HomePage .sidePar {
            z-index: 5;
        }

        .table.table-sm thead th:not(.sorting),
        .table.table-sm thead td:not(.sorting),
        .table.table-sm tbody th:not(.sorting),
        .table.table-sm tbody td:not(.sorting) {
            padding: 5px 10px;
        }

        .table.table-sm thead th.sorting,
        .table.table-sm thead td.sorting,
        .table.table-sm tbody th.sorting,
        .table.table-sm tbody td.sorting {
            padding: 5px 15px;
        }

        .tFex {
            position: relative !important;
            width: 100% !important;
        }

        .dataTables_filter {
            display: none;
        }

        span.redBg {
            background: #E67681;
        }

        .pointer {
            cursor: pointer;
        }

        .dataTables_info {
            margin-left: 15px;
            font-size: smaller;
        }

        .dataTables_paginate {
            color: #333;
            font-size: smaller;
        }

        .dataTables_paginate, .dataTables_info {
            margin-bottom: 10px;
            margin-top: 10px;
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
            /*width: 15%;*/
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

        .commentStyle {
            max-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
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
        .tech-support-button{
            position: fixed;
            bottom: 0px;
            left: 0px;
            background: #0f5b94;
            margin: 30px;
            z-index: 1000;
        }
    </style>
    @if (auth()->check() && auth()->user()->role == 13)
        <style>
            ul.list-unstyled>li.dropdown:hover div.dropdown-menu,
            ul.list-unstyled>li.dropdown div.dropdown-menu:hover{
                margin:0;
                display: inline-block;
                position: relative;
            }
        </style>
    @endif
@stack('styles')
@yield('css_style')

<!-- JQuery JS-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <!-- Select2 Css-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/3.0.0/select2.css"   />
<link rel="stylesheet" type="text/css" href="{{ url('interface_style/vendor/select2/select2.min.css') }}" />
--}}

<!-- Data Tabel -->
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.11.2/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css"/>

    <!--FOR TOGGLE STYLE-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.css">

    <!-- for hijri datepicker-->
    <link href="{{url('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet"/>

    <style>
        .dataTables_paginate {
            direction: rtl;
        }

        .data-table-parent {
            background-color: white;
            padding: 30px;
        }

        .dataTables_filter label input[type="search"] {
            margin: 0 5px;
            border: 1px solid #ccc;
            padding: 0 5px;
        }

        .dataTables_length label select {
            border: 1px solid #ccc;
        }

        table.dataTable {
            width: 100% !important;
        }


        .countBuble {
            padding: 2px 4px 2px 4px;
            background-color: #235C7A;
            color: white;
            font-size: 0.69em;
            border-radius: 50%;
            box-shadow: 1px 1px 1px gray;
        }

        #note {
            position: absolute;
            font-size: medium;
            z-index: 99999;
            top: 0;
            left: 0;
            right: 0;
            color: aliceblue;
            text-align: center;
            line-height: 2.5;
            overflow: hidden;
            -webkit-box-shadow: 0 0 5px black;
            -moz-box-shadow: 0 0 5px black;
            box-shadow: 0 0 5px black;
        }

        @-webkit-keyframes slideDown {

            0%,
            100% {
                -webkit-transform: translateY(-50px);
            }

            10%,
            90% {
                -webkit-transform: translateY(0px);
            }
        }

        @-moz-keyframes slideDown {

            0%,
            100% {
                -moz-transform: translateY(-50px);
            }

            10%,
            90% {
                -moz-transform: translateY(0px);
            }
        }

        .cssanimations.csstransforms #note {
            -webkit-transform: translateY(-50px);
            -webkit-animation: slideDown 2.5s 1.0s 1 ease forwards;
            -moz-transform: translateY(-50px);
            -moz-animation: slideDown 2.5s 1.0s 1 ease forwards;
        }

        .announce-line {
            float: left;
            display: inline-block;
            padding-left: 25px;
            color: black;
            cursor: pointer;
            font-size: medium;
            text-decoration: underline;
            font-weight: bold;
            margin: auto;
        }

        .announce-line:hover {
            float: left;
            display: inline-block;
            padding-left: 25px;
            color: white;
            text-decoration: underline;
            font-weight: bold;
            margin: auto;

        }

        #attach {
            text-align: center;
            font-size: medium;
            color: cornsilk;
            text-decoration: underline;

        }

        #attach:hover .text-white {
            color: white !important;
        }

        table th[rowspan],
        table td[rowspan] {
            vertical-align: middle;
        }
    </style>

    <style>
        .clearBoth {
            clear: both;
        }

        .select2-request + .select2-container .select2-selection--single {
            height: 43px !important;
        }

        .select2-request + .select2-container {
            width: 100% !important;
        }

        .missedFiledInput {
            border: 3px solid #e67681;
            border-radius: 4px;
            background-color: #ffe6e6;
        }
    </style>
    {{--pwa--}}
    @laravelPWA

</head>

@auth
    <script src="{{ asset('js/enable-push.js') }}" defer></script>
@endauth

<body>

@if (session('EnsureThereIsNoCalculaterSuggestion'))
    <div id="EnsureThereIsNoCalculaterSuggestion"></div>
@endif

<!-----------Start Home Page---------->
<section class="HomePage mb-5">

    <!-- =========show pop up when agent has unreceived tasks========== -->
    @if(auth()->user()->role == 0 && count(auth()->user()->agent_tasks)>0 && Route::currentRouteName()!='all.recivedtask')
        @foreach((auth()->user()->agent_tasks) as $agent_tasks)
            @foreach(($agent_tasks->task_content) as $at)
                @if($at->user_note==NULL)
                    @include('layouts.un_received_task_popup')
                @endif
            @endforeach
        @endforeach
    @endif

    <!-----------Announcement---------->
    @isset($announces)
        @foreach($announces as $announces)
           {{-- @if (auth()->user()->role != 13)--}}
                @if( App\Http\Controllers\AdminController::ifThereRoleAndUsersAnnounce($announces->id) == 'true')
                    @if( App\Http\Controllers\AdminController::getSeenAnnounce($announces->id) == 'true')
                        @if( App\Http\Controllers\AdminController::getRoleAnnounce($announces->id) == 'true' || App\Http\Controllers\AdminController::getUsersAnnounce($announces->id) == 'true')
                            @include('layouts.announce_block')
                            @break;
                        @endif

                    @endif
                @else
                    @if( App\Http\Controllers\AdminController::getSeenAnnounce($announces->id) == 'true')
                        @include('layouts.announce_block')
                        @break;
                    @endif
                @endif
          {{--  @endif--}}
        @endforeach
    @endisset
<!-----------Announcement---------->


    @include('layouts.sideBar')

    <div class="homeCont">

        @include('layouts.topSide')

        <div class="toogle">
            <i class="fas fa-exchange-alt"></i>
        </div>

        <div class="container-fluid px-lg-5">
            <div class="ContTabelPage">
                {{--@if(isset($message) && !empty($message))
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
                </div>--}}
                @yield('customer')
            </div>
        </div>
    </div>
</section>

<div class="clearBoth"></div>
<div class="footerLast d-block">

    @if (auth()->user() && in_array(auth()->user()->role,  [0,1,2,3,5,6]) && false)
        <button type="button" class="btn btn-primary tech-support-button " data-toggle="modal" data-target="#exampleModal">
            الدعم الفني
            <i class="fa fa-comments" aria-hidden="true"></i>
        </button>

    @endif
    <div class="container  py-5 text-center">
        {{ MyHelpers::admin_trans(auth()->user()->id,'Copyright © 2020 Alwasat. All rights reserved.') }}
        {{ MyHelpers::admin_trans(auth()->user()->id,'Alwasata') }}

    </div>
</div>
<div class="clearBoth"></div>
{{-- support modal --}}
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">الدعم الفني</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="/techniqal-support" id="techniqal-support" enctype="multipart/form-data" method="post">
            @csrf
            <div class="modal-body">
                {{-- <div class="form-group">
                    {!! Form::label('title', "عنوان الرساله", []) !!}
                    {!! Form::text('title', null, ['class' => 'form-control']) !!}
                </div> --}}
                <div class="form-group">
                    {!! Form::label('title', "نوع الرساله", []) !!}
                    {!! Form::select('msg_type', MyHelpers::TechniqalMsgType(), null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('descrebtion', "نص الرساله", []) !!}
                    {!! Form::textarea('descrebtion', null, ['class' => 'form-control', 'required' => '']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('files', "ارفاق ملفات", []) !!}
                    {!! Form::file('files[]', ['class' => 'form-control', 'multiple' => "multiple", 'accept' => 'jpeg,png,bmp,gif,svg,mp4,qt']) !!}
                </div>
                <p id="message-tech"></p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
              <button type="submit" class="btn btn-primary send-btn-tech">ارسال</button>
            </div>
        </form>
      </div>
    </div>
  </div>
{{-- support modal end --}}
@yield('updateModel')
@yield('confirmMSG')

<!-- Jquery JS-->
<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- for hijri datepicker-->
<script src="{{url('js/bootstrap-hijri-datetimepicker.min.js')}}"></script>

<!-- App JS-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="{{ url('assest/js/bootstrap.bundle.js') }}"></script>
<script src="{{ url('assest/js/owl.carousel.min.js') }}"></script>
<script src="{{ url('assest/js/owl-Function.js') }}"></script>
<script src="{{ url('assest/js//jquery.fancybox.min.js') }}"></script>
<script src="{{ url('assest/js/function.js') }}"></script>
<script src="{{ url('assest/js/wow.min.js') }}"></script>

<script src="{{ url('assest/js/popper.min.js') }}"></script>
<script src="{{ url('assest/js/bootstrap.min.js') }}"></script>

<!-- Sweet Alerts-->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<!-- DataTabel JS-->
<script src="https://cdn.datatables.net/1.11.2/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>

<!-- TOGGLE STYLE-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js"></script>
<script src="{{ asset('js/tokenize2.min.js') }}"></script>

<script>
    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    $(document).ready(function () {
        $('.select2-request').select2();
        if ($("#EnsureThereIsNoCalculaterSuggestion").length != 0) {

            swal({
                title: "تنبيه!",
                text: " {{ session('EnsureThereIsNoCalculaterSuggestion') }}",
                icon: 'error',
                button: 'موافق',
            });

        }
    });

    function chbx_toggle(source) {
        var checkboxes = document.querySelectorAll('input[type="checkbox"]');
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i] != source)
                checkboxes[i].checked = source.checked;
        }
    }

    function getReqests() {
        var array = []
        var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].value != "on" && checkboxes[i].value != "off") {
                var val = parseInt(checkboxes[i].value);
                array.push(val);
            }
        }
        console.log(array);
        alert(array);
    }

    $(document).on('click', '#close', function (e) {

        var id = $(this).attr('data-id');

        $.get("{{ route('seenAnnounce') }}", {
            id: id
        }, function (data) { // we pass var id in the request with id as name ; we use $.post : because we pass our data to server and got response

            if (data.status == 1) {

                console.log(data.message);
                note = document.getElementById("note");
                note.style.display = 'none';

            } else {

                alert(data.message);
            }


        })


    });

    function numberWithCommas(number) {
        return number
            .replace(/\D/g, "")
            .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function check_rejection(that) {
        var val = that.value;
        if (val == 16) {
            $('#reasonOfCancelation').css('display', 'block');
        } else {
            $('#reasonOfCancelation').css('display', 'none');
        }
    }

    function addReqToNeedActionReqFromAdmin(id) {
        swal({
            title: 'هل أنت متأكد',
            icon: 'warning',
            buttons: ["إلغاء", "نعم"],
        }).then(function (inputValue) {
            if (inputValue != null) {
                $.ajax({
                    url: "{{ route('admin.addToNeedActionReq') }}",
                    type: "GET",
                    data: {
                        'id': id
                    },
                    success: function (data) {

                        swal({
                            title: 'تم!',
                            text: data.message,
                            icon: 'success',
                            timer: '2500'
                        })
                    },
                    error: function () {
                        swal({
                            title: 'خطأ',
                            text: data.message,
                            icon: 'error',
                            timer: '2500'
                        })
                    }
                });
            } else {
            }

        });
    }

    function addReqToNeedActionReqFromAdminArray(array) {
        swal({
            title: 'هل انت متأكد',
            icon: 'warning',
            buttons: ["إلغاء", "نعم"],
        }).then(function (inputValue) {
            if (inputValue != null) {
                $.ajax({
                    url: "{{ route('admin.addToNeedActionReqArray') }}",
                    type: "GET",
                    data: {
                        'array': array
                    },
                    beforeSend: function () {
                        $('#submitMove3').attr("disabled", true);
                        $("#submitMove3").html("<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Please Wait') }}");
                    },
                    success: function (data) {

                        console.log(data);

                        swal({
                            title: 'تم!',
                            text: data.message,
                            icon: 'success',
                            timer: '2500'
                        });

                        $("#submitMove3").html("تحويل");
                        $('#submitMove3').attr("disabled", false);
                    },
                    error: function (data) {
                        console.log(data);

                        swal({
                            title: 'خطأ',
                            text: data.message,
                            icon: 'error',
                            timer: '2500'
                        });

                        $("#submitMove3").html("تحويل");
                        $('#submitMove3').attr("disabled", false);
                    }
                });
            } else {

            }

        });
    }

    function changeRole(val) {
        const elm = $('.bank-delegate')
        const bank = $("#bank_id")
        const subdomain = $("#subdomain")

        if (parseInt(val) === 13) {
            elm.removeClass('d-none')
            // bank.prop('required', !0)
            // subdomain.prop('required', !0)
        } else {
            elm.addClass('d-none')
            bank.val(null)
            subdomain.val(null)
            // bank.prop('required', !1)
        }
    }

    window.confirmMessage = function (text, title) {
        return swal({
            title: title || "@lang('messages.areYouSure')",
            text,
            icon: "warning",
            dangerMode: true,
            closeOnClickOutside: false,
            closeOnEsc: false,
            buttons: {
                confirm: {
                    text: "@lang('global.yes')",
                    value: true,
                    closeModal: false
                },
                cancel: {
                    text: "@lang('global.cancel')",
                    value: null,
                    closeModal: true,
                    visible: true,
                },
            },
        })
    }
    window.alertSuccess = function (text) {
        swal({
            icon: "success",
            text,
            buttons: {
                confirm: "@lang('global.done')",
            }
        })
    }
    window.alertError = function (text, title) {
        swal({
            icon: "error",
            title,
            text,
            buttons: {
                confirm: "@lang('global.done')",
            }
        })
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Access-Control-Allow-Headers': '*',
        }
    });

    $(document).on('submit', '#techniqal-support', function(e){
        e.preventDefault();
        // var postData = new FormData($('#techniqal-support')[0]);
        $('.send-btn-tech').html(`جاري الارسال ...`);
        $('.send-btn-tech').prop('disabled', true);
        var postData = new FormData(this);
        $.ajax({
            url : "{{url('/techniqal-support')}}",
            type: "POST",
            data : postData,
            processData: false,
            contentType: false,
            success:function(res, textStatus, jqXHR){
                $('.send-btn-tech').html(`ارسال`);
                $('#exampleModal').modal('toggle');
                swal(res['message'], '', "success");
                // $('#message-tech').html(res['message']);
                $('.send-btn-tech').prop('disabled', false);
                $("#techniqal-support input").val(null);
                $("#techniqal-support textarea").val('');
            },
            error: function(res){
                //if fails
                $('#message-tech').html(res['message']);
                $('.send-btn-tech').html(`ارسال`);
                $('.send-btn-tech').prop('disabled', false);
            }
        });
    });
</script>

{{-- By Doaa Alastal  --}}
<!-- This Section created because any script in scripts section can't be implemented  for unknown reason !! -->
<!-- Because this yield not stack A.Fayez !! -->
@yield('js')
{{-- By Doaa Alastal  --}}


@yield('scripts')
@stack('scripts')
</body>

</html>

@endif
