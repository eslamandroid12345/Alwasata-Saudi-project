@extends('testWebsite.layouts.master')

@section('title') اطلب استشارة

{{--@if( str_contains(url()->current(), '/ar/col/'))
     - {{$user->name}}
@endif--}}
@endsection

@section('style')

<!-- Google -->
<!-- Global site tag (gtag.js) - Google Ads: 831160911 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-831160911"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'AW-831160911');
</script>
<script>
    function gtag_report_conversion(url) {
        var callback = function() {
            if (typeof(url) != 'undefined') {
                window.location = url;
            }
        };
        gtag('event', 'conversion', {
            'send_to': 'AW-831160911/gokUCPj0--0BEM-EqowD',
            'event_callback': callback
        });
        return false;
    }
</script>
<!-- Google -->

<!-- Twitter universal website tag code -->
<script>
    ! function(e, t, n, s, u, a) {
        e.twq || (s = e.twq = function() {
                s.exe ? s.exe.apply(s, arguments) : s.queue.push(arguments);
            }, s.version = '1.1', s.queue = [], u = t.createElement(n), u.async = !0, u.src = '//static.ads-twitter.com/uwt.js',
            a = t.getElementsByTagName(n)[0], a.parentNode.insertBefore(u, a))
    }(window, document, 'script');
    // Insert Twitter Pixel ID and Standard Event data below
    twq('init', 'o5ari');
    twq('track', 'PageView');
</script>
<!-- End Twitter universal website tag code -->

@endsection


@section('pageMenu')
@include('testWebsite.layouts.menuOfPages')
@endsection

@section('content')
<section class="calc">
    <div class="container">
        <div class="head-div text-center">
            <h1>طلب استشارة
               {{-- @if( str_contains(url()->current(), '/ar/col/'))
                    <span style="font-size: 15px;font-weight: bold;display: block">{{$user->name}}</span>
                @endif--}}
            </h1>

        </div>
        <p style="text-align: center;">لتقديم طلب استشارة برجاء تعبئة البيانات التالية ليقوم أحد مستشارينا بالتواصل معك والعمل على طلبك في أسرع وقت.</p>
        <div class="calc-form funding_request_wrapper">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="calc-cont">

                        <form>
                            <p class="message-box alert"></p>
                            {{ csrf_field() }}
                            @if(session('source'))
                            <input type="hidden" name="source" value="ويب - عقار" />
                            <input type="hidden" name="property_id" value="{{$property}}" />
                            @else
                            <input type="hidden" name="source" value="7" />

                            @endif

                            <div class="row first-calc">

                                @if( App\Http\Controllers\SettingsController::getOptionValue('askforfunding_name') == 'show')
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                                    <label for="name">{{ MyHelpers::guest_trans('Full name') }}<small id="requiredname"></small><small style="color:red;">*</small></label>
                                    <input type="text" name="name" class="form-control" id="inputnameError" placeholder="{{ MyHelpers::guest_trans('Full name') }}">
                                    <span class="text-danger" style="color:red;" id="nameError" role="alert"> </span>
                                </div>
                                @endif

                                {{--

                                @if( App\Http\Controllers\SettingsController::getOptionValue('askforfunding_birthDate') == 'show')
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <label for="birth_date">{{ MyHelpers::guest_trans('birth date type') }} </label>
                                <select disabled class="form-control @error('birthType') is-invalid @enderror" onchange=' check(this);' id="inputbirth_date_typeError" name="birth_date_type">

                                    <option value="هجري" selected>هجري</option>
                                    <option value="ميلادي">ميلادي</option>

                                </select>

                                <span class="text-danger" style="color:red;" id="birth_date_typeError" role="alert"> </span>

                            </div>
                            @endif

                            @if( App\Http\Controllers\SettingsController::getOptionValue('askforfunding_birthDate') == 'show')
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="display: none;" id="birth_gerous">
                                <label for="birth_date">{{ MyHelpers::guest_trans('birth date') }} </label>
                                <input type="date" class="form-control" id="inputbirth_dateError" name="birth_date" placeholder="Birthday">
                                <span class="text-danger" style="color:red;" id="birth_dateError" role="alert"> </span>
                            </div>
                            @endif
                            --}}



                            @if( App\Http\Controllers\SettingsController::getOptionValue('askforfunding_birthDate') == 'show')
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="birth_hijri">
                                <label for="birth_date">تاريخ الميلاد / هجري
                                @if( App\Http\Controllers\SettingsController::getValidationValue('request_validation_from_birth_hijri') != null || App\Http\Controllers\SettingsController::getValidationValue('request_validation_to_birth_hijri') != null )
                                <small id="requiredwork"></small><small style="color:red;">*</small>
                                @endif
                                </label>
                                <input type='text' class="form-control" name="birth_hijri" style="text-align: right;" placeholder="يوم/شهر/سنة" id="inputbirth_hijriError" />
                                <span class="text-danger" style="color:red;" id="birth_hijriError" role="alert"> </span>
                            </div>
                            @endif
                            @if( str_contains(url()->current(), '/ar/col/'))
                                <input type="hidden" name="col_id" value="{{$user->id}}">
                            @endif

                            @if( App\Http\Controllers\SettingsController::getOptionValue('askforfunding_mobile') == 'show')
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                                <label for="mobile">{{ MyHelpers::guest_trans('mobile') }}<small id="requiredmobile"></small><small style="color:red;">*</small></label>
                                <input id="inputmobileError" name="mobile" class="form-control" type="number" autocomplete="mobile" placeholder="5xxxxxxxxx">
                                <span class="text-danger" style="color:red;" id="mobileError" role="alert"> </span>
                            </div>
                            @endif


                            <input id="forMobileCheck" type="hidden">


                            @if( App\Http\Controllers\SettingsController::getOptionValue('askforfunding_email') == 'show')
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                                <label for="email">{{ MyHelpers::guest_trans('Email') }}</label>
                                <input type="email" name="email" class="form-control" id="inputemailError" placeholder="example@example.com">
                                <span class="text-danger" style="color:red;" id="emailError" role="alert"> </span>
                            </div>
                            @endif




                            @if( App\Http\Controllers\SettingsController::getOptionValue('askforfunding_work') == 'show')
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                                <label for="work">{{ MyHelpers::guest_trans('work') }}

                                    @if( App\Http\Controllers\SettingsController::getValidationValue('request_validation_to_work') != null )
                                    <small id="requiredwork"></small><small style="color:red;">*</small>
                                    @endif

                                </label>

                                <select class="form-control" name="work" id="inputworkError">

                                <option value="">---</option>

                                    @foreach ($worke_sources as $worke_source )
                                    <option value="{{$worke_source->id}}">{{$worke_source->value}}</option>
                                    @endforeach

                                </select>
                                <span class="text-danger" style="color:red;" id="workError" role="alert"> </span>

                            </div>
                            @endif

                            @if( App\Http\Controllers\SettingsController::getOptionValue('askforfunding_salary') == 'show')
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <label for="salary">{{ MyHelpers::guest_trans('salary') }}
                                    @if( App\Http\Controllers\SettingsController::getValidationValue('request_validation_from_salary') != null || App\Http\Controllers\SettingsController::getValidationValue('request_validation_to_salary') != null )
                                    <small id="requiredsalary"></small><small style="color:red;">*</small>
                                    @endif
                                </label>
                                <input type="text" name="salary" id="inputsalaryError" class="form-control" placeholder="{{ MyHelpers::guest_trans('salary') }}">
                                <span class="text-danger" style="color:red;" id="salaryError" role="alert"> </span>
                            </div>
                            @endif


                            @if( App\Http\Controllers\SettingsController::getOptionValue('askforfunding_salaryId') == 'show')
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <label for="salary_id">{{ MyHelpers::guest_trans('salary source') }}</label>
                                @php
                                $sources = \DB::table('salary_sources')->get();
                                @endphp
                                <select name="salary_id" id="inputsalary_idError" class="form-control">
                                    <option value="" disabled selected>{{ MyHelpers::guest_trans('salary source') }} *</option>
                                    @foreach($sources as $source)
                                    <option value="{{$source->id}}">{{@$source->value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                    </div>




                    <div class="row sec-calc mx-0">

                        @if( App\Http\Controllers\SettingsController::getOptionValue('askforfunding_isSupported') == 'show')

                        @if( App\Http\Controllers\SettingsController::getOptionValue('askforfunding_has_obligations') == 'show' && App\Http\Controllers\SettingsController::getOptionValue('askforfunding_has_financial_distress') == 'show')
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            @elseif( App\Http\Controllers\SettingsController::getOptionValue('askforfunding_has_obligations') == 'show' || App\Http\Controllers\SettingsController::getOptionValue('askforfunding_has_financial_distress') == 'show')
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                @else
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    @endif

                                    <label for="supported" style="font-size: small;">هل أنت من مستحقي دعم الإسكان؟
                                        @if( App\Http\Controllers\SettingsController::getValidationValue('request_validation_to_support') != null)
                                        <small id="requiredis_supported"></small><small style="color:red;">*</small>
                                        @endif

                                    </label>

                                    <select class="form-control @error('is_supported') is-invalid @enderror" name="is_supported" id="inputis_supportedError">

                                        <option value="" selected>---</option>
                                        <option value="yes">{{ MyHelpers::guest_trans('Yes') }}</option>
                                        <option value="no">{{ MyHelpers::guest_trans('No') }}</option>

                                    </select>
                                    <span class="text-danger" style="color:red;" id="is_supportedError" role="alert"> </span>

                                </div>

                                @endif


                                @if( App\Http\Controllers\SettingsController::getOptionValue('askforfunding_has_obligations') == 'show')

                                @if( App\Http\Controllers\SettingsController::getOptionValue('askforfunding_isSupported') == 'show' && App\Http\Controllers\SettingsController::getOptionValue('askforfunding_has_financial_distress') == 'show')
                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                    @elseif( App\Http\Controllers\SettingsController::getOptionValue('askforfunding_isSupported') == 'show' || App\Http\Controllers\SettingsController::getOptionValue('askforfunding_has_financial_distress') == 'show')
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        @else
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            @endif

                                            <label for="has_obligations">{{ MyHelpers::guest_trans('has you obligations') }}

                                                @if( App\Http\Controllers\SettingsController::getValidationValue('request_validation_to_has_obligations') != null)
                                                <small id="requiredhas_obligations"></small><small style="color:red;">*</small>
                                                @endif

                                            </label>

                                            <select class="form-control @error('has_obligations') is-invalid @enderror" name="has_obligations" id="inputhas_obligationsError">

                                                <option value="" selected>---</option>
                                                <option value="yes">{{ MyHelpers::guest_trans('Yes') }}</option>
                                                <option value="no">{{ MyHelpers::guest_trans('No') }}</option>

                                            </select>
                                            <span class="text-danger" style="color:red;" id="has_obligationsError" role="alert"> </span>

                                        </div>



                                        @endif

                                        @if( App\Http\Controllers\SettingsController::getOptionValue('askforfunding_has_financial_distress') == 'show')

                                        @if( App\Http\Controllers\SettingsController::getOptionValue('askforfunding_has_obligations') == 'show' && App\Http\Controllers\SettingsController::getOptionValue('askforfunding_isSupported') == 'show')
                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                            @elseif( App\Http\Controllers\SettingsController::getOptionValue('askforfunding_has_obligations') == 'show' || App\Http\Controllers\SettingsController::getOptionValue('askforfunding_isSupported') == 'show')
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                @else
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    @endif

                                                    <label for="has_financial_distress" class="control-label mb-1">{{ MyHelpers::guest_trans('has you financial distress') }}
                                                        @if( App\Http\Controllers\SettingsController::getValidationValue('request_validation_to_has_financial_distress') != null )
                                                        <small id="requiredhas_financial_distress"></small><small style="color:red;">*</small>
                                                        @endif
                                                    </label>
                                                    <select class="form-control @error('has_financial_distress') is-invalid @enderror" name="has_financial_distress" id="inputhas_financial_distressError">

                                                        <option value="" selected>---</option>
                                                        <option value="yes">{{ MyHelpers::guest_trans('Yes') }}</option>
                                                        <option value="no">{{ MyHelpers::guest_trans('No') }}</option>

                                                    </select>
                                                    <span class="text-danger" style="color:red;" id="has_financial_distressError" role="alert"> </span>


                                                </div>

                                                @endif


                                                @if( App\Http\Controllers\SettingsController::getOptionValue('askforfunding_owning_property') == 'show')

                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">


                                                    <label for="owning_property" class="control-label mb-1">هل تمتلك عقار حاليا؟
                                                        @if( App\Http\Controllers\SettingsController::getValidationValue('request_validation_to_owningProperty') != null )
                                                        <small id="requiredowning_property"></small><small style="color:red;">*</small>
                                                        @endif
                                                    </label>
                                                    <select class="form-control @error('owning_property') is-invalid @enderror" name="owning_property" id="inputowning_propertyError">

                                                        <option value="" selected>---</option>
                                                        <option value="yes">{{ MyHelpers::guest_trans('Yes') }}</option>
                                                        <option value="no">{{ MyHelpers::guest_trans('No') }}</option>

                                                    </select>
                                                    <span class="text-danger" style="color:red;" id="owning_propertyError" role="alert"> </span>


                                                </div>

                                                @endif

                                            </div>

                                            {{--
                                                <div class="row sec-calc mx-0" style="padding:1% ; text-align:center">

                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                </div>

                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                    <a href="{{url('ar/privacy_policy')}}" style="color:black">
                                            <h6>السياسة والخصوصية</h6>
                                            </a>
                                        </div>

                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                        </div>

                                    </div>
                                    --}}



                                    </form>

                                    <div class="send-btn">
                                        <button id="saveBtn"><i class="fas fa-paper-plane ml-2"></i> تقديم</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
</section>

@endsection


@section('modal')
@include('testWebsite.Pages.mobileNumberCheckModal')
@endsection

@section('scripts')
<script src="{{url('js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
<script type="text/javascript">
    $(function() {
        $("#inputbirth_hijriError").hijriDatePicker({
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
    $(document).ready(function() {
        var today = new Date().toISOString().split("T")[0];
        $('#inputbirth_dateError').attr("max", today);
        /*
        document.getElementById("saveBtn").disabled = true;
        document.getElementById("saveBtn").style.cursor = "not-allowed";
        */
    });

    $('#inputsalaryError').keyup(function(event) {
        // skip for arrow keys
        if (event.which >= 37 && event.which <= 40) return;
        // format number
        $(this).val(function(index, value) {
            return value
                .replace(/\D/g, "")
                .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        });
    });


    function check(that) {

        if (that.value == "ميلادي") {

            document.getElementById("birth_gerous").style.display = "block";

            document.getElementById("inputbirth_hijriError").value = "";
            document.getElementById("birth_hijri").style.display = "none";

        } else {
            document.getElementById("birth_hijri").style.display = "block";

            document.getElementById("birth_gerous").style.display = "none";
            document.getElementById("inputbirth_dateError").value = "";

        }
    }

    jQuery(function(e) {

        $(document).on('click', '#saveBtn', function(e) {

            $('#nameError').addClass("d-none");
            $('#mobileError').addClass("d-none");
            $('#birth_hijriError').addClass("d-none");
            $('#emailError').addClass("d-none");
            $('#workError').addClass("d-none");
            $('#salaryError').addClass("d-none");
            $('#is_supportedError').addClass("d-none");
            $('#has_obligationsError').addClass("d-none");
            $('#has_financial_distressError').addClass("d-none");
            $('#owning_propertyError').addClass("d-none");

            e.preventDefault();
            var loader = $('.message-box');
            var btn = $(this);

            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: "{{ route('frontend.page.funding_request') }}",
                data: $('.funding_request_wrapper form').serialize(),
                beforeSend: function() {
                    btn.attr('disabled', true);
                    loader.html("<i class='fa fa-spinner fa-spin fa-lg'></i> {{ MyHelpers::guest_trans('loading Send') }}").removeClass('hide alert-danger').addClass('alert-success');
                },

                error: function(data) {

                    if (data.status == 2) {
                        loader.html('').removeClass('alert-danger').addClass('hide');
                        $('.funding_request_wrapper form')[0].reset();
                        let slug = "{{ route('duplicateCustomer') }}";
                        window.location.replace(slug);
                    }

                    loader.html('').removeClass('alert-success');

                    btn.attr('disabled', false);

                    var errors = data.responseJSON;

                    if ($.isEmptyObject(errors) == false) {

                        $.each(errors.errors, function(key, value) {

                            var ErrorID = '#' + key + 'Error';
                            var ErrorFiledID = '#input' + key + 'Error';
                            var ErrorMandotoryID = '#required' + key;

                            $(ErrorFiledID).addClass("errorFiled");

                            $(ErrorID).removeClass("d-none");
                            $(ErrorID).text(value);

                            $(ErrorMandotoryID).text("({{MyHelpers::guest_trans('Mandatory') }})");

                        })

                    }


                },

                success: function(data) {

                    //console.log(data);

                    btn.attr('disabled', false);
                    if(data.status === 'freeze'){
                        alert("شكراً لك، سوف نقوم بالتواصل معك")
                        window.location.reload();
                    }
                    if (data.status == 1) {
                        loader.html(data.msg).removeClass('alert-danger').addClass('hide');
                        $('.funding_request_wrapper form')[0].reset();
                        document.getElementById("mobileNumberChek").innerHTML = data.data.customer;
                        document.getElementById("forMobileCheck").value = data.data.customer;
                        $("#mi-modal").modal({
                            backdrop: "static"
                        });
                    } else if (data.status == 11) {
                        let slug = "{{ route('thankyou') }}";
                        window.location.replace(slug);

                        //duplicateCustomer//
                    } else if (data.status == 2) { //pending
                        loader.html(data.msg).removeClass('alert-danger').addClass('hide');
                        $('.funding_request_wrapper form')[0].reset();
                        let slug = "{{ route('duplicateCustomer') }}";
                        window.location.replace(slug);

                    } else if (data.status == 3) { //unpending

                        var verified = data.customer.isVerified;
                        if (verified == 0) {
                            var mobile = data.customer.mobile;
                            $.get("{{ route('setSessionMobileNumber')}}", {
                                mobile: mobile,
                            }, function(data) {});

                            $.get("{{ route('mobileOTP')}}", {}, function(data) {
                                if (data.status == 1) {
                                    let slug = "{{ route('mobileOTPPage') }}";
                                    window.location.replace(slug);
                                } else {
                                    $("#mi-modal").modal('hide');
                                    alert('لاتستطيع إكمال العملية.. نعتذر على ذلك');
                                    let slug = "{{ url('/') }}";
                                    window.location.replace(slug);

                                }
                            });
                        } else {
                            let slug = "{{ url('/login/customer') }}";
                            window.location.replace(slug);
                        }
                    }
                    //duplicateCustomer//
                    else {
                        var message = formatErrorMessageFromJSON(data.errors);
                        loader.html(message).removeClass('alert-success').addClass('alert-danger');
                    }
                }
            });
        });
    });

    $(document).on('click', '#yes_mobileNumberChek', function(e) {

        $.get("{{ route('mobileOTP')}}", {}, function(data) {
            console.log(data);
            if (data.status == 1) {
                let slug = "{{ route('mobileOTPPage') }}";
                //let slug = "{{ route('thankyou') }}?key=mobileOTPPage";
                window.location.replace(slug);
            } else {
                $("#mi-modal").modal('hide');
                alert('لاتستطيع إكمال العملية.. نعتذر على ذلك');
                let slug = "{{ url('/') }}";
                window.location.replace(slug);

            }
        });

    });

    $(document).on('click', '#no_mobileNumberChek', function(e) {

        let slug = "{{ route('modifyMobilePage') }}";
        window.location.replace(slug);

    });


    function setNewPassword(mobile) {


    }
</script>
@endsection
