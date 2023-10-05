@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Reminders Calendar') }}
@endsection

@section('css_style')
<link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' rel='stylesheet' />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" integrity="sha512-KXkS7cFeWpYwcoXxyfOumLyRGXMp7BTMTjwrgjMg0+hls4thG2JGzRgQtRfnAuKTn2KWTDZX4UdPg+xTs8k80Q==" crossorigin="anonymous" />
<style>
     .fc-day-top.fc-sat,
     .fc-day-top.fc-fri ,
     .fc-day-header.fc-widget-header.fc-sat ,
     .fc-day-header.fc-widget-header.fc-fri
     {
         color: white;
         background-color: #f4516c !important;
     }
</style>
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




<div id="msg2" class="alert alert-dismissible" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>

</div>

<div class="addUser my-4">
    <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
        <h3>  {{ MyHelpers::admin_trans(auth()->user()->id,'Reminders Calendar') }} :</h3>
    </div>
</div>

@if(auth()->user()->role != '7' && auth()->user()->role != '11')

{{--
        <div class="table-data__tool">
        <div class="table-data__tool-right">
            <a href="" data-toggle="modal" data-target="#create_reminder" >
                <button class="au-btn au-btn-icon au-btn--blue au-btn--small">
                    <i class="zmdi zmdi-plus"></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Reminder') }}</button></a>
</div>
</div>

--}}

@endif

<div class="tableBar">
    <div class="topRow">
        <form action="" method="get" class="filter" id="filter">
        <div class="row align-items-center text-center text-md-left">

            <div class="col-md-6">
                <div class="form-group">
                    <label for="customer_mobile" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer mobile') }}</label>
                    <input id="customer_mobile" type="text" class="form-control" onchange="checkCustomerMobile(this.value)" value="" onkeypress="return isNumber(event)" maxlength="9" onsubmit="checkCustomerMobile(this.value)">
                    <div class="mobile-check-filter">
                    </div>
                </div>
            </div>
            @if(auth()->user()->role == '7')
            <div class="col-md-6">
                <div class="form-group">
                    <label for="users" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}</label>
                    <select id="users" class="form-control users" onchange="filter('byUser',this.value)">
                    </select>
                </div>
            </div>
            @endif
        </div>
        </form>
    </div>
    <div class="dashTable" style="padding: 20px">
        {!! $calendar->calendar() !!}
    </div>
</div>



@endsection
@section('updateModel')
@include('Calendar.create-reminder')
@include('Calendar.edit-reminder')
@include('Calendar.confirmDelMsg')
@endsection
@section('scripts')

<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js" integrity="sha512-o0rWIsZigOfRAgBxl4puyd0t6YKzeAw9em/29Ag7lhCQfaaua/mDwnpE2PVzwqJ08N7/wqrgdjc2E0mwdSY2Tg==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/locale/ar-sa.min.js" integrity="sha512-9fpmrBEzOHcCCuRbWbJPYfSK8DczBzNT8VL09dx2fiVxiWFCpvQqNfZlJIQe+d3Rv3wW6fPACDQoBTFnq5z3Wg==" crossorigin="anonymous"></script>

{!! $calendar->script() !!}

<script>
    $(document).ready(function() {
        divs = $('.fc-event')
        divs.each(function(index, object) {
            var url = $(divs[index]).attr('href');
            var id = url.substring(url.lastIndexOf('/') + 1);
            if (url.includes('#')) {
                $(divs[index]).removeAttr("href");
            }
            if (url && !url.includes('#')) {
                $(divs[index]).attr("data-toggle", "modal");
                $(divs[index]).attr("data-target", "#edit_reminder");
                $(divs[index]).attr("type", "button");
                $(divs[index]).attr("data-id", id);
                $(divs[index]).css('cursor', 'pointer');
            }

        });

        $(window).keydown(function(event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                var mobile = $('#customer_mobile').val();
                // console.log(mobile);
                return checkCustomerMobile(mobile);
            }
        });

        $('#users').select2();
        $.ajax({
            type: "GET",
            url: "{{route('jsonUsers')}}",
            data: '',
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(result) {
                // console.log(result);
                var select_user = "{{ MyHelpers::admin_trans(auth()->user()->id,'select user') }}";
                var users = result;
                $("#users").append('<option selected disabled value="">' + select_user + '</option>');
                $.each(users, function(index, user) {
                    $("#users")
                        .append('<option value=' + user.id + '>' + user.name + '</option>');
                });
            },
            error: function(result) {}
        });
    });

    function filter(type, id) {
        // console.log(type);
        // console.log(id);
        $('#filter').attr("action", '/reminders/' + type + '/' + id);
        $('#filter').submit();
    }

    function isNumber(evt) {
        $('.mobile-check-filter').html('');
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    function getReqType(id) {
        $.ajax({
            type: "GET",
            url: '/get-req-Type/' + id,
            data: '',
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(result) {
                var url='';
                var role="{{auth()->user()->role}}";
                if (role !=13){
                    if (result.type != null && result.type != 'شراء'){
                        url= "{{route ('agent.morPurRequest', "id" ) }}";
                        url = url.replace("id", id);
                        url = url.replace("agent", getUserRole());
                        $("#openReq").attr("href", url);
                    } else{
                            url= "{{route ('agent.fundingRequest', "id" ) }}";
                            url = url.replace("id", id);
                            url = url.replace("agent", getUserRole());
                            $("#openReq").attr("href", url);
                    }
                }else{
                    if (result.type != null && result.type != 'شراء'){
                        url= "{{route ('V2.BankDelegate.request.show', "id" ) }}";
                        url = url.replace("id", id);
                        $("#openReq").attr("href", url);
                    } else{
                        url= "{{route ('V2.BankDelegate.request.show', "id" ) }}";
                        url = url.replace("id", id);
                        $("#openReq").attr("href", url);
                    }
                }


            },
            error: function(result) {}
        });
    }
    function getUserRole() {

        var role={{auth()->user()->role}};
        var roleName='';

        if (role == 0)
        roleName='agent';
        else if (role == 1)
        roleName='salesManager';
        else if (role == 2)
        roleName='fundingManager';
        else if (role == 3)
        roleName='mortgageManager';
        else if (role == 4)
        roleName='generalmanager';
        else if (role == 5)
        roleName='qualityManager';
        else if (role == 7)
        roleName='admin';
        else if (role == 8)
        roleName='accountant';
        return roleName;

    }

    function checkCustomerMobile(mobile) {
        $.ajax({
            type: "GET",
            url: "/check-customer-mobile/" + mobile,
            data: '',
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                if ($('.btn-save-send').length > 0) {
                    $('.mobile-check-create').html('');
                    $('.mobile-check-create').html('<span><i class="fa fa-spinner fa-spin fa-lg"></i> ' + " {{MyHelpers::admin_trans(auth()->user()->id, 'Phone number verification in progress')}} " + '</span>');
                } else {
                    $('.mobile-check-filter').html('');
                    $('.mobile-check-filter').html('<span><i class="fa fa-spinner fa-spin fa-lg"></i> ' + " {{MyHelpers::admin_trans(auth()->user()->id, 'Phone number verification in progress')}} " + '</span>');
                }
            },
            success: function(result) {
                // console.log(result);
                if (result.status == 'success') {
                    if ($('.btn-save-send').length > 0) {
                        $('.mobile-check-create').html('');
                        $('.mobile-check-create').append('<span class="text-success"> <i class="fa fa-check"></i> ' + result.msg + ' </span>');
                        $('.btn-save-send').attr('disabled', false);
                    } else {
                        $('.mobile-check-filter').html('');
                        $('.mobile-check-filter').append('<span class="text-success"> <i class="fa fa-check"></i> ' + result.msg + ' </span>');
                        $('.mobile-check-filter').append('<br><span><i class="fa fa-spinner fa-spin fa-lg"></i> ' + "{{MyHelpers::admin_trans(auth()->user()->id, 'filter in progress')}} " + ' </span>');
                        return filter('byRequest', result.req_id);
                    }

                } else if (result.status == 'error') {
                    if ($('.btn-save-send').length > 0) {
                        $('.mobile-check-create').html('');
                        $('.mobile-check-create').append('<span class="text-danger"> <i class="fa fa-times-circle-o"></i> ' + result.msg + ' </span>');
                    } else {
                        $('.mobile-check-filter').html('');
                        $('.mobile-check-filter').append('<span class="text-danger"> <i class="fa fa-times-circle-o"></i> ' + result.msg + ' </span>');
                    }

                }
            },
            error: function(result) {}
        });
    }

    /* Create New Reminder */
    $('#create_reminder').on('show.bs.modal', function(event) {
        $("#modal-btn-si3").addClass("btn-save-send");
        $('.btn-save-send').attr('disabled', true);
        document.getElementById("createReminder").reset();
        $('.error-note').html('');
    });
    $("#create_reminder").on('hidden.bs.modal', function() {
        $("#modal-btn-si3").removeClass("btn-save-send");
    });
    $('.createReminder').submit(function(event) {
        event.preventDefault();
        var btn = $(this);
        var loader = $('.btn-save-send');
        var btn_trans = "{{MyHelpers::admin_trans(auth()->user()->id, 'Save')}}";
        var sending = "{{MyHelpers::admin_trans(auth()->user()->id, 'sending')}}"
        //  console.log($('.createReminder').attr("action"));
        $.ajax({
            type: "POST",
            url: $('.createReminder').attr("action"),
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                btn.attr('disabled', true);
                loader.html("<i class='fa fa-spinner fa-spin fa-lg'></i> " + sending);
            },
            success: function(data) {
                swal.fire({
                    title: data.msg,
                    type: data.type
                }).then((result) => {
                    if (result.value) {
                        loader.html(btn_trans);
                        btn.attr('disabled', false);
                        $('.modal').trigger("click");

                        /* Calendar refresh does not work */
                        /*
                        $('.fc').fullCalendar('refresh'); //add the newly created event into fullCalendar
                        $('.fc').fullCalendar('refetchEvents'); //add the newly created event into fullCalendar
                        $('.fc').fullCalendar('rerenderEvents'); //add the newly created event into fullCalendar
                        $("#calendar-{{ $calendar->getId() }}").fullCalendar("refresh");
                        $("#calendar-{{ $calendar->getId() }}").fullCalendar("refetchEvents");
                        $("#calendar-{{ $calendar->getId() }}").fullCalendar("rerenderEvents");
                        */

                        /* JS Page Refresh */
                        location.reload();

                        $('html, body').animate({
                            scrollTop: 0
                        }, 'fast');
                    }
                });

            },
            error: function(data) {
                loader.html(btn_trans);
                btn.attr('disabled', false);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) == false) {
                    $.each(errors.errors, function(key, value) {
                        var ErrorID = '#' + key + 'SaveError';
                        $(ErrorID).text(value);
                        // console.log(value);
                        // console.log(ErrorID);
                    })
                }
            },
            complete: function() {
                $("body").css("padding-right", "0px !important");
            }
        });
    });


    /* Update Existing Reminder */
    $('#edit_reminder').on('show.bs.modal', function(event) {
        document.getElementById("updateReminder").reset();
        $('.error-note').html('');
        var button = $(event.relatedTarget); // Button that triggered the modal
        let id = button.data('id'); // Extract info from data-* attributes

        $.ajax({
            type: "GET",
            url: '/get-reminder/' + id,
            data: '',
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(result) {
                console.log(result);
                if (result.req_id == null && result.external_customer_id != null) {
                    let id=result.external_customer_id
                    let url= "{{route ('V2.ExternalCustomer.show',"id") }}";
                    url = url.replace("id", id);
                    $("#openReq").attr("href", url);
                } else {
                    getReqType(result.req_id);
                }
                // getReqType(result.req_id);
                formated_date = (result.reminder_date).split(' ')[0];
                // console.log(formated_date);
                $('#not_id').val(id);
                $('#reminder_date').val(formated_date);
                $('#reminder_msg').val(result.value);
                $('.deleteBtn').attr('href', "/delete-reminder/" + id);
            },
            error: function(result) {}
        });
    });
    $('.updateReminder').submit(function(event) {
        event.preventDefault();
        var btn = $(this);
        var loader = $('.btn-update-send');
        var btn_trans = "{{MyHelpers::admin_trans(auth()->user()->id, 'Save')}}";
        var sending = "{{MyHelpers::admin_trans(auth()->user()->id, 'sending')}}"
        // console.log($('.createReminder').attr("action"));
        $.ajax({
            type: "POST",
            url: $('.updateReminder').attr("action"),
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                btn.attr('disabled', true);
                loader.html("<i class='fa fa-spinner fa-spin fa-lg'></i> " + sending);
            },
            success: function(data) {
                swal.fire({
                    title: data.msg,
                    type: data.type
                }).then((result) => {
                    if (result.value) {
                        loader.html(btn_trans);
                        btn.attr('disabled', false);
                        $('.modal').trigger("click");

                        /* Calendar refresh does not work */
                        /*
                        $('.fc').fullCalendar('refresh'); //add the newly created event into fullCalendar
                        $('.fc').fullCalendar('refetchEvents'); //add the newly created event into fullCalendar
                        $('.fc').fullCalendar('rerenderEvents'); //add the newly created event into fullCalendar
                        $("#calendar-{{ $calendar->getId() }}").fullCalendar("refresh");
                        $("#calendar-{{ $calendar->getId() }}").fullCalendar("refetchEvents");
                        $("#calendar-{{ $calendar->getId() }}").fullCalendar("rerenderEvents");
                        */

                        /* JS Page Refresh */
                        location.reload();

                        $('html, body').animate({
                            scrollTop: 0
                        }, 'fast');
                    }
                });

            },
            error: function(data) {
                loader.html(btn_trans);
                btn.attr('disabled', false);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) == false) {
                    $.each(errors.errors, function(key, value) {
                        var ErrorID = '#' + key + 'SaveError';
                        $(ErrorID).text(value);
                        //   console.log(value);
                        //   console.log(ErrorID);
                    })
                }
            },
            complete: function() {
                $("body").css("padding-right", "0px !important");
            }
        });
    });


    /* Delete Existing Reminder */
    $(document).on("click", ".deleteBtn", function() {
        $("#Confirm").modal("show");
        $("#Confirm .deleteForm").attr("action", $(this).attr("href"));
        return false;
    })
    $('#Confirm').on('show.bs.modal', function(event) {
        // console.log($('.deleteBtn').attr("href"));
        var btn_trans = "{{ MyHelpers::admin_trans(auth()->user()->id,'Delete') }}";
        var loader = $('.btn-send');
        loader.html(btn_trans);
    });
    $('.delete_form').submit(function(event) {
        event.preventDefault();
        var btn = $(this);
        var loader = $('.btn-delete-send');
        var btn_trans = "{{ MyHelpers::admin_trans(auth()->user()->id,'Delete') }}";
        var sending = "{{ MyHelpers::admin_trans(auth()->user()->id,'sending') }}"
        //console.log($('.delete_form').attr("action"));
        $.ajax({
            type: "GET",
            url: $('.delete_form').attr("action"),
            data: '',
            beforeSend: function() {
                btn.attr('disabled', true);
                loader.html("<i class='fa fa-spinner fa-spin fa-lg'></i> " + sending);
            },
            success: function(data) {
                swal.fire({
                    title: data.msg,
                    type: data.type
                }).then((result) => {
                    if (result.value) {
                        loader.html(btn_trans);
                        btn.attr('disabled', false);
                        $('.modal').trigger("click");

                        /* Calendar refresh does not work */
                        /*
                        $('.fc').fullCalendar('refresh'); //add the newly created event into fullCalendar
                        $('.fc').fullCalendar('refetchEvents'); //add the newly created event into fullCalendar
                        $('.fc').fullCalendar('rerenderEvents'); //add the newly created event into fullCalendar
                        $("#calendar-{{ $calendar->getId() }}").fullCalendar("refresh");
                        $("#calendar-{{ $calendar->getId() }}").fullCalendar("refetchEvents");
                        $("#calendar-{{ $calendar->getId() }}").fullCalendar("rerenderEvents");
                        */

                        /* JS Page Refresh */
                        location.reload();
                        $('html, body').animate({
                            scrollTop: 0
                        }, 'fast');
                    }
                });

            },
            error: function(data) {
                loader.html(btn_trans);
                btn.attr('disabled', false);
                var errors = data.responseJSON;
            },
            complete: function() {
                $("body").css("padding-right", "0px !important");
            }
        });
    });
</script>


@endsection
