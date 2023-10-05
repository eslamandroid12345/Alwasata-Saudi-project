@extends('testWebsite.layouts.master')

@section('title') شركة الوساطة العقارية @endsection



@section('pageMenu')
@include('testWebsite.layouts.menuOfHomePage')
@endsection

@section('content')

@include('testWebsite.Pages.appsection') 
<section class="calc " id="calc">
    <div class="container">

        <div class="head-div text-center wow fadeInUp">
            <h1>الوساطة العقارية</h1>
            <p>
                من أوائل الشركات التي أوجدت حلول  لكافة شرائح المجتمع وشرفنا بخدمة آلاف العملاء بمسيرة ١٥ عامًا حظينا فيها على رضاء عملائنا وحصولهم على منزل أحلامهم .
            </p>

            <p>
                <a href="{{url('ar/about-us')}}">المزيد</a>
            </p>
        </div>

    </div>
</section>


<section class="slide" id="slide"  style="display: none;">
    <div class="container">
        <div class="row align-items-end">
            <div class="col-md-6 mb-5">
                <div class="apps-text wow fadeInRight" data-wow-duration="2s">
                    <h3>برامجنا التمويلية</h3>
                    <p>
                        تقدم شركة الوساطة الخدمات الاستشارية وحلول التمويل من خلال برامج تمويل البناء ، برامج شراء العقار ، إضافةً إلى برنامج الرهن العقاري.
                    </p>
                </div>
            </div>
            <div class="col-md-6 tex-center text-md-right wow fadeInLeft" data-wow-duration="2s">
                <img style="height: auto;max-width:100%;" src="{{ asset('newWebsiteStyle/images/fundingprogramm.png') }}" alt="">

            </div>
        </div>

        <div class="slider-lab owl-carousel owl_1 wow fadeInUp" data-wow-duration="2s">
            <div class="single-slide">
                <div class="head-icon mb-2">
                    <i class="fas fa-building"></i>
                </div>
                <div class="headSlide-text mb-2">
                    <h5>تمويل البناء</h5>
                </div>
                <div class="slide-cont">
                    <p>برنامج للحصول على سيولة لبناء أرض أو استكمال بناء.
                    </p>

                    <br>
                    <div class="btn-order mt-2 mt-sm-0" style="text-align: left;">
                        <a class="nav-link order-price" href="{{url('ar/request_service')}}" data-scroll="contact">
                            سجل</a>
                    </div>
                </div>
            </div>

            <div class="single-slide">
                <div class="head-icon mb-2">
                    <i class="fab fa-gg-circle"></i>
                </div>
                <div class="headSlide-text mb-2">
                    <h5>برنامج الرهن العقاري</h5>
                </div>
                <div class="slide-cont">
                    <p>برنامج للحصول على سيولة بضمان العقار.

                    </p>
                    <br><br>
                    <div class="btn-order mt-2 mt-sm-0" style="text-align: left;">
                        <a class="nav-link order-price" href="{{url('ar/request_service')}}" data-scroll="contact">
                            سجل</a>
                    </div>
                </div>
            </div>

            <div class="single-slide">
                <div class="head-icon mb-2">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="headSlide-text mb-2">
                    <h5>برنامج شراء العقار</h5>
                </div>
                <div class="slide-cont">
                    <p>برنامج لشراء العقارات (المكتملة، غير المكتملة) في جميع أنحاء المملكة وبالتوافق مع الشريعة الإسلامية.

                    </p>

                    <div class="btn-order mt-2 mt-sm-0" style="text-align: left;">
                        <a class="nav-link order-price" href="{{url('ar/request_service')}}" data-scroll="contact">
                            سجل</a>
                    </div>
                </div>
            </div>



        </div>
    </div>
</section>

<section class="goal">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="goal-text wow fadeInRight" data-wow-duration="2s">
                    <h3>هدفنا</h3>
                    <p>
                        ابتكار مزيد من حلول الاستشارة العقارية لتمكين الأسر السعودية من تملك مسكن مناسب لاحتياجاتهم وتعزيز قدراتهم المادية من خلال دراسة البرامج المتاحة المتاحة وتوفير الحل الأمثل لهم.
                    </p>

                </div>
            </div>
            <div class="col-md-6">
                <div class="goal-img wow fadeInLeft" data-wow-duration="2s">
                    <img src="{{ asset('newWebsiteStyle/images/ourgoal.png') }}" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>
<section class="vision">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 d-md-block wow fadeInUp" data-wow-duration="2s">
                <div class="goal-img">
                    <img src="{{ asset('newWebsiteStyle/images/ourvesion.png') }}" alt="" class="img-fluid">
                </div>
            </div>
            <div class="col-md-6">
                <div class="goal-text">
                    <h3>رؤيتنا</h3>
                    <p>
                        أن نكون الوجهة الأولى لراغبي الاستشارة العقارية  في المملكة العربية السعودية .
                        و تقديم خدمة مميزة لعملائنا في المملكة .
                    </p>
                </div>
            </div>
            <div class="col-md-6 d-none d-md-none">
                <div class="goal-img">
                    <img src="{{ asset('newWebsiteStyle/images/Screen.png') }}" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>


<section class="partners" style="display: none;">
    <div class="container">
        <div class="head-div text-center">
            <h1>شركاء النجاح</h1>

        </div>
        <div class="partners-cont owl-carousel owl_2 wow fadeInDown" data-wow-duration="2s">
            <div class="single-part">
                <img src="{{ asset('newWebsiteStyle/images/img_client4.png') }}" class="img-fluid" alt="">
            </div>

            <div class="single-part">
                <img src="{{ asset('newWebsiteStyle/images/alrajhi-logo.png') }}" class="img-fluid" alt="">
            </div>

            <div class="single-part">
                <img src="{{ asset('newWebsiteStyle/images/alahli-bank.jpg') }}" class="img-fluid" alt="">
            </div>

            <div class="single-part">
                <img src="{{ asset('newWebsiteStyle/images/albelad-bank.jpg') }}" class="img-fluid" alt="">
            </div>

            <div class="single-part">
                <img src="{{ asset('newWebsiteStyle/images/alfaranci-bank.jpg') }}" class="img-fluid" alt="">
            </div>

            <div class="single-part">
                <img src="{{ asset('newWebsiteStyle/images/alethmar-bank.png') }}" class="img-fluid" alt="">
            </div>

            <div class="single-part">
                <img src="{{ asset('newWebsiteStyle/images/riyadh-bank.jpg') }}" class="img-fluid" alt="">
            </div>

            <div class="single-part">
                <img src="{{ asset('newWebsiteStyle/images/alarbi-bank.jpg') }}" class="img-fluid" alt="">
            </div>

            <div class="single-part">
                <img src="{{ asset('newWebsiteStyle/images/saab-bank.jpg') }}" class="img-fluid" alt="">
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


        $('#property_value_slider').val(100).css('color', '#b3cccc');
        $('#monthly_salary_slider').val(50000).css('color', '#b3cccc');
        $('#funding_duration_slider').val(15).css('color', '#b3cccc');
        $('#annual_interest_slider').val(5).css('color', '#b3cccc');
        $('#first_batch_slider').val(2000000).css('color', '#b3cccc');

        $("#calculate_fund_cust_info").fadeOut();

    });



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
                    var message = data.errors;
                    loader.html(message).removeClass('alert-success').addClass('alert-danger');
                }

            }
        });
    });


    $(document).on('click', '#calculate_fund', function(e) {

        var fund = document.getElementById('calculate_fund_info');
        fund.style.display = 'none';

        $("#calculate_fund_cust_info").fadeIn();

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
            error: function(data) {


                btn.replaceWith(actualBtn);
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
                btn.replaceWith(actualBtn);

                /*
                                if (data.status == 1) {
                                    let slug = "{{ route('thankyou') }}";
                                    window.location.replace(slug);
                                } else if (data.status == 2) {
                                    let slug = "{{ route('duplicateCustomer') }}";
                                    window.location.replace(slug);
                                } else {
                                    var message = formatErrorMessageFromJSON(data.errors);
                                    message = message.replace(/<p>/g, '<p class="err_msg">');
                                    $('.calculator-form .registerFormInner').append(message);
                                }

                */

                if (data.status == 1) {
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
                    message = message.replace(/<p>/g, '<p class="err_msg">');
                    $('.calculator-form .registerFormInner').append(message);
                }
            }
        });
    });


    $(document).on('click', '#yes_mobileNumberChek', function(e) {

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

    });

    $(document).on('click', '#no_mobileNumberChek', function(e) {

        let slug = "{{ route('modifyMobilePage') }}";
        window.location.replace(slug);

    });
</script>

@endsection