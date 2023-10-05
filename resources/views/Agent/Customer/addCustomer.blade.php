@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}
@endsection
@section('css_style')

<style>

</style>
<!--NEW 2/2/2020 for hijri datepicker-->
<link href="{{url('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" />

@endsection

@section('customer')


<div>
  @if (session('msg'))
  <div id="msg" class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session('msg') }}
  </div>
  @endif
</div>


<section class="new-content mt-5">
    <div class="container-fluid">
        <div class="row "  >
            <div class="col-md-8 offset-md-2">
                <div class="row">
                    <div class="col-lg-12   mb-md-0">
                        <div class="userFormsInfo  ">
                            <div class="headER topRow text-center">
                                <i class="fas fa-user"></i>
                                <h4>{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</h4>
                            </div>
                            <form action="{{ route('agent.addCustomer')}}" method="post" class="">
                                @csrf
                                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                                <div class="userFormsContainer mb-3">
                                    <div class="userFormsDetails topRow">
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <div class="form-group">
                                                    <label for="name">{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}</label>
                                                    <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" autocomplete="name" autofocus placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}">
                                                </div>
                                                @if ($errors->has('name'))
                                                    <span class="help-block col-md-12">
                                                        <strong style="color:red ;font-size:10pt">{{ $message }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="col-12 mb-3">
                                                <div class="form-group">
                                                    <label for="sex">{{ MyHelpers::admin_trans(auth()->user()->id,'gender') }}</label>

                                                    <select class="form-control @error('sex') is-invalid @enderror" name="sex">
                                                        <option value="">---</option>
                                                        <option value="ذكر">{{ MyHelpers::admin_trans(auth()->user()->id,'male') }}</option>
                                                        <option value="أنثى">{{ MyHelpers::admin_trans(auth()->user()->id,'female') }}</option>
                                                    </select>
                                                </div>
                                                @if ($errors->has('sex'))
                                                    <span class="help-block col-md-12">
                                                        <strong style="color:red ;font-size:10pt">{{ $message }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="col-12 mb-3">
                                                <div class="form-group">
                                                    <label for="mobile">
                                                        {{ MyHelpers::admin_trans(auth()->user()->id,'mobile') }}
                                                        <small id="checkMobile" role="button" type="button" class="item badge badge-info pointer has-tooltip "  title="{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}">
                                                            {{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}
                                                        </small>
                                                    </label>
                                                    <input id="mobile" name="mobile" type="tel" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile') }}" onchange="changeMobile()" autocomplete="mobile" autofocus placeholder="5xxxxxxxx">
                                                </div>
                                                <span class="text-danger" id="error" role="alert"> </span>
                                                @if ($errors->has('mobile'))
                                                    <span class="help-block col-md-12">
                                                        <strong style="color:red ;font-size:10pt">{{ $message }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="col-6 mb-3">
                                                <div class="form-group">
                                                    <label for="birth">
                                                        {{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}
                                                        <small id="convertToHij" role="button" type="button" class="item badge badge-info pointer has-tooltip "  title="{{ MyHelpers::admin_trans(auth()->user()->id,'To Hijri') }}">
                                                            {{ MyHelpers::admin_trans(auth()->user()->id,'To Hijri') }}
                                                        </small>
                                                    </label>
                                                    <input id="birth" name="birth" type="date" class="form-control @error('birth') is-invalid @enderror" value="{{ old('birth') }}" autocomplete="birth" onblur="calculate()" autofocus>
                                                </div>
                                                @if ($errors->has('birth'))
                                                    <span class="help-block col-md-12">
                                                        <strong style="color:red ;font-size:10pt">{{ $message }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="col-6 mb-3">
                                                <div class="form-group">
                                                    <label for="mobile">
                                                        {{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }} ({{ MyHelpers::admin_trans(auth()->user()->id,'hijri') }})
                                                        <small id="convertToGreg" role="button" type="button" class="item badge badge-info pointer has-tooltip "  title="{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}">
                                                            {{ MyHelpers::admin_trans(auth()->user()->id,'To Greg') }}
                                                        </small>
                                                    </label>
                                                    <input type='text' name="birth_hijri" class="form-control" placeholder="يوم/شهر/سنة" id="hijri-date" />
                                                </div>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <div class="form-group">
                                                    <label for="age">{{ MyHelpers::admin_trans(auth()->user()->id,'age') }}</label>
                                                    <input id="age" name="age" type="text" class="form-control @error('age') is-invalid @enderror" value="{{ old('age') }}" autocomplete="age" autofocus readonly>
                                                </div>
                                                @if ($errors->has('age'))
                                                    <span class="help-block col-md-12">
                                                        <strong style="color:red ;font-size:10pt">{{ $message }}</strong>
                                                    </span>
                                                @endif
                                            </div>


                                            <div class="col-12 mb-3">
                                                <div class="form-group">
                                                    <label for="work">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</label>
                                                    <select class="form-control @error('work') is-invalid @enderror" name="work">
                                                        <option value="">---</option>
                                                        <option value="عسكري">عسكري</option>
                                                        <option value="مدني">مدني</option>
                                                        <option value="قطاع خاص">قطاع خاص</option>
                                                        <option value="متقاعد">متقاعد</option>
                                                    </select>
                                                </div>
                                                @if ($errors->has('work'))
                                                    <span class="help-block col-md-12">
                                                        <strong style="color:red ;font-size:10pt">{{ $message }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="col-12 mb-3">
                                                <div class="form-group">
                                                    <label for="work">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</label>
                                                    <select class="form-control @error('work') is-invalid @enderror" name="work">
                                                        <option value="">---</option>
                                                        <option value="عسكري">عسكري</option>
                                                        <option value="مدني">مدني</option>
                                                        <option value="قطاع خاص">قطاع خاص</option>
                                                        <option value="متقاعد">متقاعد</option>
                                                    </select>
                                                </div>
                                                @if ($errors->has('work'))
                                                    <span class="help-block col-md-12">
                                                        <strong style="color:red ;font-size:10pt">{{ $message }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="col-12 mb-3">
                                                <div id="madany" class="form-group" style="display: none;">
                                                    <label for="madany_work" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'madany') }}</label>
                                                    <select id="madany_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control @error('madany_work') is-invalid @enderror" value="{{ old('madany_work') }}" name="madany_work">

                                                        <option value="">---</option>
                                                        @foreach ($madany_works as $madany_work )
                                                            <option value="{{$madany_work->id}}">{{$madany_work->value}}</option>
                                                        @endforeach
                                                    </select>

                                                    @error('madany_work')
                                                    <span class="invalid-feedback" role="alert">
                                                      <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-12 mb-3">
                                            <div class="form-group" id="madany1" style="display: none;">
                                                <label for="job_title" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'job title') }}</label>
                                                <input id="job_title" name="job_title" type="text" class="form-control @error('job_title') is-invalid @enderror" value="{{ old('job_title') }}" autocomplete="job_title" autofocus placeholder="">
                                                @error('job_title')
                                                <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
                                                @enderror
                                            </div>
                                            </div>
                                            <div class="col-12 mb-3">
                                            <div id="askary" class="form-group" style="display: none;">
                                                <label for="askary_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'askary') }}</label>
                                                <select id="askary_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control @error('askary_work') is-invalid @enderror" value="{{ old('askary_work') }}" name="askary_work">

                                                    <option value="">---</option>
                                                    @foreach ($askary_works as $askary_work )
                                                        <option value="{{$askary_work->id}}">{{$askary_work->value}}</option>
                                                    @endforeach
                                                </select>

                                                @error('askary_work')
                                                <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
                                                @enderror
                                            </div>
                                            </div>
                                            <div class="col-12 mb-3">
                                            <div id="askary1" class="form-group" style="display: none;">
                                                <label for="rank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }}</label>

                                                <select id="rank" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control @error('rank') is-invalid @enderror" name="rank">

                                                    <option value="">---</option>
                                                    <option value="جندي">جندي</option>
                                                    <option value="رقيب">رقيب</option>


                                                </select>

                                                @error('rank')
                                                <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
                                                @enderror

                                            </div>
                                            </div>
                                            <div class="col-12 mb-3">
                                            <div class="form-group">
                                                <label for="salary_source" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary source') }}</label>
                                                <select onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control @error('salary_source') is-invalid @enderror" value="{{ old('salary_source') }}" name="salary_source">

                                                    <option value="" selected>---</option>
                                                    @foreach ($salary_sources as $salary_source )
                                                        <option value="{{$salary_source->id}}">{{$salary_source->value}}</option>
                                                    @endforeach
                                                </select>

                                                @error('salary_source')
                                                <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
                                                @enderror
                                            </div>
                                            </div>

                                            <div class="col-12 mb-3">
                                            <div class="form-group">
                                                <label for="salary" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}</label>
                                                <input id="salary" name="salary" min="0" type="number" class="form-control @error('salary') is-invalid @enderror" value="{{ old('salary') }}" autocomplete="salary" autofocus>
                                                @error('salary')
                                                <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
                                                @enderror
                                            </div>
                                            </div>
                                            <div class="col-12 mb-3">
                                            <div class="form-group">
                                                <label for="is_support" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'is supported') }}?</label>
                                                <div class="row">
                                                    <div class="col-6">

                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class="custom-control-input" value="yes" id="yes" name="support" checked>
                                                            <label style=" position: relative; padding-left: 1.8rem;" class="custom-control-label" for="yes">نعم</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" class="custom-control-input" value="no" id="no" name="support">
                                                            <label style=" position: relative; padding-left: 1.8rem;" class="custom-control-label" for="no">لا</label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            </div>




                                            <div class="col-12">
                                                <button type="submit" class="Green d-block border-0 w-100 py-2 rounded text-light addUserClient">{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</section>



@endsection

@section('scripts')
<script>
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  /////////////////////////////////////////////////////////////////

  $(document).ready(function() {

    var today = new Date().toISOString().split("T")[0];
    // alert (today);
    $('#birth').attr("max", today);

  });

  function check(that) {

    if (that.value == "عسكري") {

      document.getElementById("askary").style.display = "block";
      document.getElementById("askary1").style.display = "block";

      document.getElementById("madany_work").value = "";
      document.getElementById("job_title").value = "";
      document.getElementById("madany").style.display = "none";
      document.getElementById("madany1").style.display = "none";

    } else if (that.value == "مدني") {

      document.getElementById("askary_work").value = "";
      document.getElementById("rank").value = "";
      document.getElementById("askary").style.display = "none";
      document.getElementById("askary1").style.display = "none";


      document.getElementById("madany").style.display = "block";
      document.getElementById("madany1").style.display = "block";
    } else {
      document.getElementById("askary_work").value = "";
      document.getElementById("rank").value = "";

      document.getElementById("askary").style.display = "none";
      document.getElementById("askary1").style.display = "none";
      document.getElementById("madany_work").value = "";
      document.getElementById("job_title").value = "";
      document.getElementById("madany").style.display = "none";
      document.getElementById("madany1").style.display = "none";
    }
  }


  calculate = function() {
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

  //--------------CHECK MOBILE------------------------

  function changeMobile() {
        document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
        $('#checkMobile').removeClass('btn-success');
        $('#checkMobile').removeClass('btn-danger');
        $('#checkMobile').addClass('btn-info');

    }

    $(document).on('click', '#checkMobile', function(e) {



        $('#checkMobile').attr("disabled", true);
        document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Loading') }}";


        var mobile = document.getElementById('mobile').value;
       /* var regex = new RegExp(/^(5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/);

        console.log(regex.test(mobile));*/

        if (mobile != null /*&& regex.test(mobile)*/) {
            document.getElementById('error').innerHTML = "";

            $.post("{{ route('all.checkMobile') }}", {
                mobile: mobile
            }, function(data) {
                if (data.errors) {
                    if (data.errors.mobile) {
                        $('#mobile-error').html(data.errors.mobile[0])
                    }
                } if (data == "no") {
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


            }).fail(function(data) {


            });



        } else {
          document.getElementById('error').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Enter Valid Mobile Number (9 digits) and starts 5') }}";
          document.getElementById('error').display = "block";
            document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
            $('#checkMobile').attr("disabled", false);

        }



    });

  //--------------END CHECK MOBILE------------------------
</script>


<!--  NEW 2/2/2020 hijri datepicker  -->
<script src="{{url('js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
<script type="text/javascript">
  $(function() {
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
  $("#convertToHij").click(function() {
    // alert($("#birth").val());
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
        success: function(response) {
          // alert(response);
          $("#hijri-date").val($.trim(response));
        },
        error: function() {
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

  $("#convertToGreg").click(function() {

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
        success: function(response) {
          // alert(response);
           $("#birth").val($.trim(response));
          calculate();
        },
        error: function() {
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

@endsection
