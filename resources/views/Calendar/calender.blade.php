@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Reminders Calendar') }}
@endsection

@section('css_style')
<link rel="stylesheet" href="{{asset('calender/simple-calendar.css')}}">
<link rel="stylesheet" href="{{asset('calender/demo.css')}}">
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
<style>
    textarea,input[type=text],input[type=password]{
        text-align: start;
        unicode-bidi: plaintext;
    }
    .calendar .day {
        position: relative;
        display: inline-block;
        width: 2.5em;
        height: 2.5em;
        line-height: 2.5em;
        border-radius: 50%;
        border: 2px solid transparent;
        cursor: pointer;
        padding-left: 8px;
    }
    table tr,td{
        font-family: "Cairo", sans-serif;
    }
    .calendar .day.has-event::after {
        content: '';
        position: absolute;
        top: calc(55% + .6em);
        left: calc(50% - 2px);
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #1C8D71;
    }
    .calendar header .month {
        padding: 0;
        margin: 0;
        font-size: 30px;
    }
    .profile-right li.nav-item a {
        padding: 10px 30px;
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
    <div class="dashTable" style="padding: 20px">

    </div>
</div>
<div id="container"></div>


@endsection
@section('updateModel')
@endsection
@section('scripts')

<script src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
        crossorigin="anonymous"></script>
<script src="{{asset('calender/jquery.simple-calendar.js')}}"></script>

<script>
    $(document).ready(function () {
        $("#container").simpleCalendar({
            fixedStartDay: 0, // begin weeks by sunday
            disableEmptyDetails: true,
            events: [
               @foreach($reminders as $session)
                {
                @if ($session->status == 0)
                    color: '#ff0000',
                    summary: '<a href="#">{{'Req ID : #'.$session->req_id}}</a>',
                @elseif($session->status == 1)
                    color: '#47d147',
                    summary: '<a href="#">{{'Req ID : #'.$session->req_id}}</a>',
                @elseif($session->status == 2)
                    color: '#3399ff',
                    summary: '<a href="{{$session->reminder_date >= date('Y-m-d') ? route('getReminder',$session->req_id) : '#'}}">{{'Req ID : #'.$session->req_id}}</a>',
                @else
                    color: '#e0e0d1',
                    summary: '<a href="{{route('getReminder',$session->req_id)}}">{{'Req ID : #'.$session->req_id}}</a>',
                @endif
                startDate: '{{$session->reminder_date}}',

                endDate: '{{$session->reminder_date}}',
                startTime: '{{date("A h:m",strtotime($session->reminder_date))}}',
                endTime: '{{date("A h:m",strtotime($session->reminder_date))}}'

            },
            @endforeach
        ],
        months: ['january','february','march','april','may','june','july','august','september','october','november','december'],
        days: ['sunday','monday','tuesday','wednesday','thursday','friday','saturday'],
        displayYear: true,              // Display year in header
        displayEvent: true,             // Display existing event
        disableEventDetails: false, // disable showing event details
        onInit: function (calendar) {}, // Callback after first initialization
        onMonthChange: function (month, year) {}, // Callback on month change
        onDateSelect: function (date, events) {}, // Callback on date selection
        onEventSelect: function() {}, // Callback on event selection - use $(this).data('event') to access the event
        onEventCreate: function( $el ) {},          // Callback fired when an HTML event is created - see $(this).data('event')
        onDayCreate:   function( $el, d, m, y ) {}  // Callback fired when an HTML day is created   - see $(this).data('today'), .data('todayEvents')


    });
    });
</script>


@endsection
