@extends('Frontend.layouts.master')

@section('title') {{ MyHelpers::guest_trans('Alwasata') }} @endsection

@section('content')


<section class="sliderSection">
    <img src="{{ asset('website_style/frontend/images/img_slider.jpg') }}">
    <div class="slider-caption" id="sliderCaptions">
        <div class="container">
            <div class="maangecalculator">
                <div class="meterSect">
                    <form id="calculator-customer-form">
                        {{ csrf_field() }}
                        <input type="hidden" name="source" value="9" />
                        <div class="col-xs-12 col-md-8 col-sm-8 col-lg-8">
                            <div class="meterSect">
                                <div>
                                    @if( App\Http\Controllers\SettingsController::getOptionValue('realEstateCalculator_propretyValue') == 'show')
                                    <div class="loan-ammount-form range-slider">
                                        <div>
                                            <div class="float-left">
                                                <h3 for="amount">{{ MyHelpers::guest_trans('Property Value') }}</h3>
                                            </div>
                                        </div>
                                        <div class="startPoint"><strong>{{ MyHelpers::guest_trans('sar') }}</strong></div>
                                        <div class="midPoint">
                                            <div id="property_value_slider"></div>
                                        </div>
                                        <div class="endPoint">
                                            <div class="float-right text-right range-ammount">
                                                <input type="text" name="property_value" id="property_value_slider_text" class="positive-integer" />
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if( App\Http\Controllers\SettingsController::getOptionValue('realEstateCalculator_salary') == 'show')
                                    <div class="loan-ammount-form range-slider">
                                        <div>
                                            <div class="float-left">
                                                <h3 for="amount">{{ MyHelpers::guest_trans('Monthly Salary') }}
                                                    @if(App\Http\Controllers\SettingsController::getValidationValue('request_validation_from_salary') != null || App\Http\Controllers\SettingsController::getValidationValue('request_validation_to_salary') != null )
                                                    <small style="font-weight: bold; font-size:small;">({{ MyHelpers::guest_trans('Mandatory') }})</small><small style="color:red;font-weight: bold; font-size:medium;">*</small>
                                                    @endif
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="startPoint"><strong>{{ MyHelpers::guest_trans('sar') }}</strong></div>
                                        <div class="midPoint">
                                            <div id="monthly_salary_slider"></div>
                                        </div>
                                        <div class="endPoint">
                                            <div class="float-right text-right range-ammount">
                                                <input type="text" name="salary" id="monthly_salary_slider_text" class="positive-integer" />
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if( App\Http\Controllers\SettingsController::getOptionValue('realEstateCalculator_durationOfFunding') == 'show')
                                    <div class="loan-ammount-form range-slider">
                                        <div>
                                            <div class="float-left">
                                                <h3 for="amount">{{ MyHelpers::guest_trans('funding duration') }}</h3>
                                            </div>
                                        </div>
                                        <div class="startPoint"><strong>{{ MyHelpers::guest_trans('year') }}</strong></div>
                                        <div class="midPoint">
                                            <div id="funding_duration_slider"></div>
                                        </div>
                                        <div class="endPoint">
                                            <div class="float-right text-right range-ammount">
                                                <input type="text" name="funding_duration" id="funding_duration_slider_text" class="positive-integer" />
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if( App\Http\Controllers\SettingsController::getOptionValue('realEstateCalculator_annualInterest') == 'show')
                                    <div class="loan-ammount-form range-slider">
                                        <div>
                                            <div class="float-left">
                                                <h3 for="amount">{{ MyHelpers::guest_trans('Annual Interest') }}</h3>
                                            </div>
                                        </div>
                                        <div class="startPoint"><strong> %</strong></div>
                                        <div class="midPoint">
                                            <div id="annual_interest_slider"></div>
                                        </div>
                                        <div class="endPoint">
                                            <div class="float-right text-right range-ammount">
                                                <input type="text" name="annual_interest" id="annual_interest_slider_text" data-slider-step="0.10" class="positive-decimal" />
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if( App\Http\Controllers\SettingsController::getOptionValue('realEstateCalculator_firstBatch') == 'show')
                                    <div class="loan-ammount-form range-slider">
                                        <div>
                                            <div class="float-left">
                                                <h3 for="amount">{{ MyHelpers::guest_trans('The first batch') }}</h3>
                                            </div>
                                        </div>
                                        <div class="startPoint"><strong>{{ MyHelpers::guest_trans('sar') }}</strong></div>
                                        <div class="midPoint">
                                            <div id="first_batch_slider"></div>
                                        </div>
                                        <div class="endPoint">
                                            <div class="float-right text-right range-ammount">
                                                <input type="text" name="first_batch" id="first_batch_slider_text" class="positive-integer" />
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="sumitbtn">
                                        <input id="calculate_fund" type="button" value="{{ MyHelpers::guest_trans('Calculate the value of funding') }}" class="srchbtn">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4 col-sm-4 col-lg-4">
                            <div class="calculator-form">
                                <div class="financeValue">
                                    <h4>{{ MyHelpers::guest_trans('Financing Value') }}</h4>
                                    <h5>{{ MyHelpers::guest_trans('sar') }} <span id="payment_calculated">0</span></h5>
                                </div>
                                <div class="financeValue">
                                    <h4>{{ MyHelpers::guest_trans('monthly installment') }}</h4>
                                    <h5>{{ MyHelpers::guest_trans('sar') }} <span id="monthly_payment_calculated">0</span></h5>
                                </div>

                                <div class="registerFormInner">
                                    @if( App\Http\Controllers\SettingsController::getOptionValue('realEstateCalculator_name') == 'show')
                                    <div class="col-xs-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <input type="text" placeholder="{{ MyHelpers::guest_trans('Full name') }}" name="name" class="form-control name">
                                        </div>
                                    </div>
                                    @endif
                                    @if( App\Http\Controllers\SettingsController::getOptionValue('realEstateCalculator_mobile') == 'show')
                                    <div class="col-xs-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <input id="mobile" name="mobile" type="number" class="form-control phone @error('mobile') is-invalid @enderror" autocomplete="mobile" placeholder="5xxxxxxxxx">
                                        </div>
                                    </div>
                                    @endif
                                    @if( App\Http\Controllers\SettingsController::getOptionValue('realEstateCalculator_birthDate') == 'show')
                                    <div class="col-xs-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <input type="date" id="birth" placeholder="Birthday" name="birth_date" class="form-control">
                                        </div>
                                    </div>
                                    @endif

                                    @if( App\Http\Controllers\SettingsController::getOptionValue('realEstateCalculator_work') == 'show')
                                    <div class="col-xs-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <label for="work" class="control-label mb-1" style="color:white;">{{ MyHelpers::guest_trans('work') }}

                                                @if( App\Http\Controllers\SettingsController::getValidationValue('request_validation_to_work') != null )
                                                <small style="font-weight: bold; font-size:small;">({{ MyHelpers::guest_trans('Mandatory') }})</small><small style="color:white;font-weight: bold; font-size:medium;">*</small>
                                                @endif

                                            </label>

                                            <select class="form-control @error('work') is-invalid @enderror" name="work" style="color:black;">

                                                <option value="" selected>---</option>
                                                <option value="عسكري">عسكري</option>
                                                <option value="مدني">مدني</option>
                                                <option value="متقاعد">متقاعد</option>
                                                <option value="شبه حكومي">شبه حكومي</option>
                                                <option value="قطاع خاص">قطاع خاص</option>
                                                <option value="قطاع خاص غير معتمد">قطاع خاص غير معتمد</option>
                                                <option value="قطاع خاص معتمد">قطاع خاص معتمد</option>





                                            </select>

                                        </div>
                                        <span class="text-danger" style="color:red;" id="workError" role="alert"> </span>
                                    </div>
                                    @endif

                                    @if( App\Http\Controllers\SettingsController::getOptionValue('realEstateCalculator_isSupported') == 'show')
                                    <div class="col-xs-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <label for="work" class="control-label mb-1" style="color:white;">{{ MyHelpers::guest_trans('are you belong to supported') }}

                                                @if( App\Http\Controllers\SettingsController::getValidationValue('request_validation_to_support') != null )
                                                <small style="font-weight: bold; font-size:small;">({{ MyHelpers::guest_trans('Mandatory') }})</small><small style="color:white;font-weight: bold; font-size:medium;">*</small>
                                                @endif

                                            </label>

                                            <select class="form-control @error('is_supported') is-invalid @enderror" name="is_supported" style="color:black;">

                                                <option value="" selected>---</option>
                                                <option value="yes">{{ MyHelpers::guest_trans('Yes') }}</option>
                                                <option value="no">{{ MyHelpers::guest_trans('No') }}</option>


                                            </select>

                                        </div>
                                        <span class="text-danger" style="color:red;" id="is_supportedError" role="alert"> </span>
                                    </div>
                                    @endif

                                    @if( App\Http\Controllers\SettingsController::getOptionValue('realEstateCalculator_has_obligations') == 'show')
                                    <div class="col-xs-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <label class="control-label mb-1" style="color:white;">{{ MyHelpers::guest_trans('has you obligations') }}

                                                @if( App\Http\Controllers\SettingsController::getValidationValue('request_validation_to_has_obligations') != null)
                                                <small style="font-weight: bold; font-size:small;">({{ MyHelpers::guest_trans('Mandatory') }})</small><small style="color:white;font-weight: bold; font-size:medium;">*</small>
                                                @endif

                                            </label>

                                            <select class="form-control @error('has_obligations') is-invalid @enderror" name="has_obligations" style="color:black;">

                                                <option value="" selected>---</option>
                                                <option value="yes">{{ MyHelpers::guest_trans('Yes') }}</option>
                                                <option value="no">{{ MyHelpers::guest_trans('No') }}</option>


                                            </select>

                                        </div>
                                        <span class="text-danger" style="color:red;" id="has_obligationsError" role="alert"> </span>
                                    </div>
                                    @endif

                                    @if( App\Http\Controllers\SettingsController::getOptionValue('realEstateCalculator_has_financial_distress') == 'show')
                                    <div class="col-xs-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <label class="control-label mb-1" style="color:white;">{{ MyHelpers::guest_trans('has you financial distress') }}

                                                @if( App\Http\Controllers\SettingsController::getValidationValue('request_validation_to_has_financial_distress') != null)
                                                <small style="font-weight: bold; font-size:small;">({{ MyHelpers::guest_trans('Mandatory') }})</small><small style="color:white;font-weight: bold; font-size:medium;">*</small>
                                                @endif

                                            </label>

                                            <select class="form-control @error('has_financial_distress') is-invalid @enderror" name="has_financial_distress" style="color:black;">

                                                <option value="" selected>---</option>
                                                <option value="yes">{{ MyHelpers::guest_trans('Yes') }}</option>
                                                <option value="no">{{ MyHelpers::guest_trans('No') }}</option>


                                            </select>

                                        </div>
                                        <span class="text-danger" style="color:red;" id="has_financial_distressError" role="alert"> </span>
                                    </div>
                                    @endif

                                    <div class="col-xs-12 col-md-12 col-lg-12">
                                        <button id="loanRequestBtn" type="button" class="btn sbtn" disabled>{{ MyHelpers::guest_trans('Send') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


{{--<section class="serviceSect wow fadeInUp">--}}
{{--    <div class="container-fluid">--}}
{{--        <div class="row">--}}
{{--            <div class="counterBlk">--}}
{{--                <ul>--}}
{{--                    <li>--}}
{{--                        <figure><img src="{{ asset('website_style/frontend/images/service1.png') }}"></figure>--}}
{{--                        <div class="txt">--}}
{{--                            <strong><span class="count">1000</span> + </strong>--}}
{{--                            <p>{{ MyHelpers::guest_trans('Office Spaces') }}</p>--}}
{{--                        </div>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <figure><img src="{{ asset('website_style/frontend/images/service2.png') }}"></figure>--}}
{{--                        <div class="txt">--}}
{{--                            <strong><span class="count">1500</span> + </strong>--}}
{{--                            <p>{{ MyHelpers::guest_trans('Shop & Showrooms') }}</p>--}}
{{--                        </div>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <figure><img src="{{ asset('website_style/frontend/images/service3.png') }}"></figure>--}}
{{--                        <div class="txt">--}}
{{--                            <strong><span class="count">1200</span> + </strong>--}}
{{--                            <p>{{ MyHelpers::guest_trans('warehouse') }}</p>--}}
{{--                        </div>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <figure><img src="{{ asset('website_style/frontend/images/service4.png') }}"></figure>--}}
{{--                        <div class="txt">--}}
{{--                            <strong><span class="count">1600</span> + </strong>--}}
{{--                            <p>{{ MyHelpers::guest_trans('Commercial Land') }}</p>--}}
{{--                        </div>--}}
{{--                    </li>--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</section>--}}


<section class="serviceSect wow fadeInUp">
    <div class="container">
        <div class="row">

            <div class="col-xs-12 col-md-12 col-lg-12 service-heading wow fadeInUp">

                <span>{{ MyHelpers::guest_trans('Real Estate offers') }}</span>

                <h3>{{ MyHelpers::guest_trans('handicraft_properties_for_you') }}</h3>

            </div>
            @if(!empty($properties))
                <div class=" serviceList  wow fadeInUp">
                    @foreach($properties as $Property)
                        <div class="col-xs-12 col-md-4 col-sm-6 col-lg-4">

                            <div class="sirviceBlk">
                                <figure>
                                    <a href="{{route('propertyDetails',$Property->id)}}" title="">
                                        <img src="{{asset(@$Property->image()->first()->image_path)}}" alt="">
                                        <figcaption>
                                            <strong>{{ $Property->type->value }}</strong>
                                        </figcaption>
                                    </a>
                                </figure>
                                <div class="txt">

                                    <h3><a href="{{route('propertyDetails',$Property->id)}}" title="">{{ $Property->type->value }}</a></h3>

                                    <span class="loc">{{$Property->city->value }}</span>

                                    <div class="priceBlk">
                                        <span>{{ MyHelpers::guest_trans('sar') }}</span>
                                        <strong>{{ $Property->price_type === 'fixed' ?$Property->fixed_price : $Property->max_price . ' -- ' . $Property->min_price  }}</strong>
                                    </div>
                                </div>

                                <div class="btmList">

                                    <ul>
                                        @foreach($Property->image() as $images)
                                            <li>
                                                <img src="{{asset($images->image_path)}}" alt="">
                                                <span>2</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>



<section class="serviceSect wow fadeInUp">

</section>

<br><br><br>

<section class="clientsection wow fadeInUp">

    <div class="container">

        <div class="row">

            <h3>{{ MyHelpers::guest_trans('Sucess partners') }}</h3>

            <div class="partnerList">

                <div class="imgb">

                    <img src="{{asset('website_style/frontend/images/img_client1.png')}}">

                </div>

                <div class="imgb">

                    <img src="{{asset('website_style/frontend/images/img_client2.png')}}">

                </div>

                <div class="imgb">

                    <img src="{{asset('website_style/frontend/images/img_client3.png')}}">

                </div>

                <div class="imgb">

                    <img src="{{asset('website_style/frontend/images/img_client4.png')}}">

                </div>

                <div class="imgb">

                    <img src="{{asset('website_style/frontend/images/img_client5.png')}}">

                </div>

                <div class="imgb">

                    <img src="{{asset('website_style/frontend/images/img_client6.png')}}">

                </div>

                <div class="imgb">

                    <img src="{{asset('website_style/frontend/images/img_client1.png')}}">

                </div>

                <div class="imgb">

                    <img src="{{asset('website_style/frontend/images/img_client2.png')}}">

                </div>

                <div class="imgb">

                    <img src="{{asset('website_style/frontend/images/img_client3.png')}}">

                </div>

                <div class="imgb">

                    <img src="{{asset('website_style/frontend/images/img_client4.png')}}">

                </div>

                <div class="imgb">

                    <img src="{{asset('website_style/frontend/images/img_client5.png')}}">

                </div>

                <div class="imgb">

                    <img src="{{asset('website_style/frontend/images/img_client6.png')}}">

                </div>

            </div>

        </div>

    </div>

</section>



@endsection

@section('scripts')


<script type="text/javascript">
    $(document).ready(function() {

        var today = new Date().toISOString().split("T")[0];
        $('#birth').attr("max", today);
    });

    jQuery(function(e) {
        function customInitTooltip(target, step) {
            var tooltip = $(`<div id="${target}_tooltip" class="slider-tooltip" />`).css({
                position: 'absolute',
                top: 0,
                left: 40
            }).hide();
            var value = $(`#${target}_text`).val();
            if (step == 1) {
                var range = 0;
                var sym = ',';
            } else {
                var range = 1;
                var sym = '.';
            }
            value = number_format(value, range, sym, ',');
            tooltip.text(value);
            return tooltip;
        }

        function customInitSlider(target, min, max, value, step) {
            $(`#${target}_text`).val(value);
            var tooltip = customInitTooltip(target, step);
            setMinMaxValidation(`#${target}_text`, min, max);

            $(`#${target}`).slider({
                range: "max",
                min: min,
                step: step,
                max: max,
                value: value,
                slide: function(event, ui) {

                    $(`#${target}_text`).val(+ui.value);

                    var val = $(`#${target}_text`).val();
                    if (step == 1) {
                        var range = 0;
                        var sym = ',';
                    } else {
                        var range = 1;
                        var sym = '.';
                    }
                    val = number_format(val, range, sym, ',');

                    tooltip.text(val);
                    $(ui.value).val(val);
                },
                change: function(event, ui) {}
            }).find(".ui-slider-handle").append(tooltip).hover(function() {
                tooltip.show()
            }, function() {
                tooltip.hide()
            })
            $(`#${target}_text`).keyup(function() {
                $(`#${target}`).slider("value", $(this).val())
            });
        }

        customInitSlider('property_value_slider', 0, 10000000, 5000000, 1);
        customInitSlider('monthly_salary_slider', 0, 50000, 25000, 1);
        customInitSlider('funding_duration_slider', 0, 30, 15, 1);
        customInitSlider('annual_interest_slider', 0, 10, 5, 0.10);
        customInitSlider('first_batch_slider', 0, 2000000, 1000000, 1);

        function number_format(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        $(document).on('click', '#inquirySaveBtn', function(e) {
            e.preventDefault();
            var loader = $('.inquiry-message-box');
            var btn = $(this);
            $('p.err_msg').remove();
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: "#",
                data: $('#inquiry_form').serialize(),
                beforeSend: function() {
                    btn.attr('disabled', true);
                    loader.html('<i class="fa fa-spinner fa-spin fa-lg"></i>').removeClass('hide alert-danger').addClass('alert-success');
                },
                error: function(jqXHR, exception) {
                    btn.attr('disabled', false);

                    var msg = formatErrorMessage(jqXHR, exception);
                    loader.html(msg).removeClass('alert-success').addClass('alert-danger');
                },
                success: function(data) {
                    btn.attr('disabled', false);
                    if (data.status == 1) {
                        loader.html('').addClass('hide');
                        $('#inquiry_form')[0].reset();
                        $('.registerFormInner').prepend('<p class="err_msg">' + data.msg + '</p>');
                    } else {
                        var message = formatErrorMessageFromJSON(data.errors);
                        loader.html(message).removeClass('alert-success').addClass('alert-danger');
                    }

                }
            });
        });

        $('.partnerList').slick({
            dots: false,
            infinite: true,
            autoplay: true,
            arrows: false,
            speed: 150,
            slidesToShow: 4,
            slidesToScroll: 1,
            responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }

            ]
        });

        $(document).on('click', '#calculate_fund', function(e) {
            e.preventDefault();
            var btn = $('#loanRequestBtn');

            // calculate the monthly payment:
            var user_salary = $('[name="salary"]').val();

            var monthly_payment = 0;
            if (user_salary >= 5000 && user_salary <= 10000) {
                monthly_payment = user_salary * .55;
            } else if (user_salary > 10000 && user_salary <= 15000) {
                monthly_payment = user_salary * .6;
            } else if (user_salary > 15000) {
                monthly_payment = user_salary * .65;
            }

            monthly_payment = monthly_payment.toString();
            if (monthly_payment.indexOf('.') > -1) {
                monthly_payment = monthly_payment.substring(0, monthly_payment.indexOf('.') + 4);
            }
            monthly_payment = parseInt(monthly_payment).toFixed(0);

            var disp_monthly_payment = number_format(monthly_payment, 0, ',', ',');
            $("#monthly_payment_calculated").html(disp_monthly_payment);




            // now caculate the fund amount:
            var years = $('[name="funding_duration"]').val();
            var months = years * 12;
            var ratio = $('[name="annual_interest"]').val();

            var makam = ratio * years;

            makam = makam.toString();
            if (makam.indexOf('.') > -1) {
                makam = makam.replace('.', '');
            }

            makam = '1.' + makam;

            var bast = months * monthly_payment;

            var fund = bast / makam;
            fund = fund.toString();

            if (fund.indexOf('.') > -1) {
                fund = fund.substring(0, fund.indexOf('.') + 4);
            }
            fund = parseInt(fund).toFixed(0);
            var disp_fund = number_format(fund, 0, ',', ',');
            $("#payment_calculated").html(disp_fund);

            if (monthly_payment != 0 && fund != 0) {
                btn.prop('disabled', false);
            }
        });

        $(document).on('focus', '.positive-integer, .positive-decimal', function() {
            $(this).val('');
        });

        $(document).on('focusout', '.positive-integer, .positive-decimal', function(e) {
            var fieldName = $(this).attr('name');
            let value = $.trim($(`#${fieldName}_slider_tooltip`).text()).replace(/,/g, '');
            if ($(this).val() == '') {
                $(this).val(value);
            }
        });

        $(document).on('click', '#loanRequestBtn', function(e) {
            e.preventDefault();
            var loader = $('.message-box');
            var actualBtn = $(this).clone();
            var btn = $(this);

            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: "{{ route('frontend.page.save_loan_request') }}",
                data: $('#calculator-customer-form').serialize(),
                beforeSend: function() {
                    $('.registerFormInner').find('p.err_msg').remove();
                    btn.attr('disabled', true).html('<center><i class="fa fa-spinner fa-spin fa-2x"></i></center>');
                },
                error: function(jqXHR, exception) {

                    btn.replaceWith(actualBtn);
                    var msg = formatErrorMessage(jqXHR, exception);
                    msg = msg.replace(/<p>/g, '<p class="err_msg">');
                    $('.calculator-form .registerFormInner').append(msg);
                },


                success: function(data) {
                    btn.replaceWith(actualBtn);
                    if (data.status == 1) {
                        let slug = "{{ route('thankyou') }}/" + data.data.id;
                        window.location.replace(slug);
                    } else if (data.status == 2) {
                        let slug = "{{ route('duplicateCustomer') }}/" + data.request.searching_id;
                        window.location.replace(slug);
                    } else {
                        var message = formatErrorMessageFromJSON(data.errors);
                        message = message.replace(/<p>/g, '<p class="err_msg">');
                        $('.calculator-form .registerFormInner').append(message);
                    }
                }
            });
        });
    });
</script>
@endsection
