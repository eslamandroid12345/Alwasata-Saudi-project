@include("V2.app.master_scripts_window")
<script src="{{asset('myth-plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('myth-plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('myth-plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('myth-plugins/toastr/toastr.min.js')}}"></script>
<script src="{{asset('myth-plugins/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('myth-plugins/pace-progress/pace.min.js')}}"></script>
<script src="{{asset('myth-plugins/select2/js/select2.full.js')}}"></script>
<script src="{{asset("myth-plugins/select2/js/i18n/{$locale}.js")}}"></script>
<script src="{{asset('myth-plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
<script src="{{asset('myth-plugins/moment/moment.min.js')}}"></script>
<script src="{{asset("myth-plugins/moment/locale/{$locale}.js")}}"></script>

<script src="{{asset('myth-plugins/semantic/semantic.js')}}"></script>
<script src="{{asset('myth-plugins/semantic/calendar.js')}}"></script>
<script>
    $.fn.calendar.settings.monthFirst = true;
    $.fn.calendar.settings.formatter.date = function (date, settings) {
        if (!date) return '';

        let separator = '-';
        let day = date.getDate();
        let month = date.getMonth() + 1;
        let year = date.getFullYear();
        day < 9 && (day = "0" + day.toString());
        month < 9 && (month = "0" + month.toString());
        // console.log(date.getMonth());
        return settings.type === 'year' ? year :
            settings.type === 'month' ? year + separator + month :
                year + separator + month + separator + day;
        // (settings.monthFirst ? month + separator + day : day + separator + month) + separator + year;
    };
    $.fn.calendar.settings.formatter.time = function (date, settings, forCalendar) {
        if (!date) {
            return '';
        }
        let hour = date.getHours();
        let minute = date.getMinutes();
        let seconds = date.getSeconds();
        let ampm = '';
        if (settings.ampm) {
            ampm = ' ' + (hour < 12 ? settings.text.am : settings.text.pm);
            hour = hour === 0 ? 12 : hour > 12 ? hour - 12 : hour;
        }
        // return (hour < 10 ? '0' : '') + hour + ':' + (minute < 10 ? '0' : '') + minute + ':' + (seconds < 10 ? '0' : '') + seconds + ampm;
        return hour + ':' + (minute < 10 ? '0' : '') + minute + ampm;
    };
    $.fn.calendar.settings.formatter.datetime = function (date, settings) {
        if (!date) {
            return '';
        }
        // console.log(settings);
        //
        // return '';
        let day = settings.type === 'time' ? '' : settings.formatter.date(date, settings);
        let time = settings.type.indexOf('time') < 0 ? '' : settings.formatter.time(date, settings, false);
        let separator = settings.type === 'datetime' ? ' ' : '';
        return day + separator + time;
    };
    $.fn.calendar.settings.today = true;
    $.fn.calendar.settings.ampm = true;
    const calendarClone = {...window.languages};
    // calendarClone.am = 'AM';
    // calendarClone.pm = 'PM';
    $.fn.calendar.settings.text = calendarClone;
    $.fn.search.settings.error = @json(__('messages.search_error'));
</script>
<script src="{{asset('myth-plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
{{--<script src="{{asset('myth-plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>--}}
{{--<script src="{{asset('myth-plugins/daterangepicker/daterangepicker.js')}}"></script>--}}
<script src="{{asset('myth-plugins/bootstrap-hijri-datepicker-master/js/bootstrap-hijri-datepicker.js')}}"></script>
<script src="{{asset('myth-plugins/bootstrap-hijri-datepicker-master/js/moment-hijri.js')}}"></script>

<script src="{{asset('dist/js/adminlte.js')}}"></script>
<script src="{{asset('myth-plugins/myth/js/myth.js')}}"></script>

{{--<script src="{{asset('js/qr_packed.js')}}"></script>--}}

<script src="{{ asset('myth-plugins/fullcalendar/main.min.js') }}"></script>
<script src="{{ asset('myth-plugins/fullcalendar-daygrid/main.min.js') }}"></script>
<script src="{{ asset('myth-plugins/fullcalendar-timegrid/main.min.js') }}"></script>
<script src="{{ asset('myth-plugins/fullcalendar-interaction/main.min.js') }}"></script>
<script src="{{ asset('myth-plugins/fullcalendar-bootstrap/main.min.js') }}"></script>

{{--<script src="{{ asset('js/jquery.serializejson.js') }}"></script>--}}
