@extends('testWebsite.layouts.master')

@section('title') التحقق من رقم الجوال @endsection


@section('style')
<style>
    #countdown {
        padding-top: 2%;
        color: #396f8f;
    }

    #timeleft {
        font-weight: bold;
    }

    #waiting {
        color: #4e6f49;
    }

    #sent {
        color: #38a149;
    }

    #error {
        color: #800000;
    }

    .exceedsms {
        color: #800000;
    }
</style>
@endsection

@section('pageMenu')
@include('testWebsite.layouts.menuOfPages')
@endsection

@section('content')
<div class="myOrders">
    <div class="container">
        <div class="head-div text-center">
            <h1>التحقق من رقم الجوال</h1>

            <div class="order-my requstform">
                <p>
                    أدخل رمز التحقق المٌرسل إلى رقم جوالك:
                </p>

                <h4> {{$mobileNumber}} </h4>

                <a href="{{route('modifyMobilePage')}}" style="color:darkcyan">تغيير رقم الجوال؟</a>


                <form id="otpForm">

                    {{ csrf_field() }}

                    <input type="hidden" name="mobileNumber" id="mobileNumber" value="{{$mobileNumber}}">

                    <input type="number" name="otp_number" id="otp_number" placeholder="xxxx">
                    <br>
                    <span class="text-danger" style="color:red;" id="otp_numberError" role="alert"> </span>


                    <div id="countdown"></div>

                    <button id="sendBtn" type="button" class="srchbtn"> تحقق</button>

                </form>



            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {


        $.ajax({
            type: 'GET',
            url: "{{ route('sendSMSotp') }}",
            data: $('#otpForm').serialize(),
            beforeSend: function() {
                $('#sendBtn').attr('disabled', true);
                document.getElementById("countdown").innerHTML = " <span id='waiting'> <i class='fa fa-spinner fa-spin fa-lg'></i> الرجاء الإنتظار </span> ";
            },
            error: function(jqXHR, exception) {
                $('#sendBtn').attr('disabled', false);
                document.getElementById("countdown").innerHTML = " <span id='error'> <i class='fa fa-close fa-lg'></i>  حدث خطأ </span>  <br> <a href='#' id='resend'>إعادة إرسال الرمز</a> ";
            },
            success: function(data) {
                // Todo: A.Fayez. Otp SMS. Don't remove this.
                // if(data.otpsms){
                //     $("#otp_number").val(data.otpsms)
                // }
                if (data.status == 1) {
                    resetCounter();
                    $('#sendBtn').attr('disabled', false);
                } else if (data.status == 0){
                    $('#sendBtn').attr('disabled', true);
                    $('#sendBtn').css('cursor', 'not-allowed');
                    document.getElementById("countdown").innerHTML = " <span id='exceedsms' style='color:red'> <i class='fa fa-close fa-lg'></i> تجاوزت الحد المتاح </span>  <br> <span>  لم يصلك الرمز ؟  <a href='#'>تواصل معنا للمساعدة</a> </span>";
                }
                else{
                    $('#sendBtn').attr('disabled', false);
                    document.getElementById("countdown").innerHTML = " <span id='error'> <i class='fa fa-close fa-lg'></i>  حدث خطأ </span>  <br> <a href='#' id='resend'>إعادة إرسال الرمز</a> ";
                }
            }
        });

    });

    function resetCounter() {
        var timeleft = 120;
        var downloadTimer = setInterval(function() {
            if (timeleft <= 0) {
                clearInterval(downloadTimer);
                document.getElementById("countdown").innerHTML = "<a href='#' id='resend'>إعادة إرسال رمز التحقق</a>";
            } else {
                document.getElementById("countdown").innerHTML = "<span id='timeleft'>" + timeleft + "</span>" + " الوقت المتبقي ";
            }
            timeleft -= 1;
        }, 1000);
    }


    $(document).on('click', '#resend', function(e) {

        $.ajax({
            type: 'GET',
            url: "{{ route('sendSMSotp') }}",
            data: $('#otpForm').serialize(),
            beforeSend: function() {
                $('#sendBtn').attr('disabled', true);
                document.getElementById("countdown").innerHTML = " <span id='waiting'> <i class='fa fa-spinner fa-spin fa-lg'></i> الرجاء الإنتظار </span> ";
            },
            error: function(jqXHR, exception) {
                $('#sendBtn').attr('disabled', false);
                document.getElementById("countdown").innerHTML = " <span id='error'> <i class='fa fa-close fa-lg'></i>  حدث خطأ </span>  <br> <a href='#' id='resend'>إعادة إرسال الرمز</a> ";
            },
            success: function(data) {

                if (data.status == 1) {
                    resetCounter();
                    $('#sendBtn').attr('disabled', false);
                } else if (data == 0){
                    $('#sendBtn').attr('disabled', true);
                    $('#sendBtn').css('cursor', 'not-allowed');
                    document.getElementById("countdown").innerHTML = " <span id='exceedsms' style='color:red'> <i class='fa fa-close fa-lg'></i> تجاوزت الحد المتاح </span>  <br> <span>  لم يصلك الرمز ؟  <a href='#'>تواصل معنا للمساعدة</a> </span>";
                }
                else{
                    $('#sendBtn').attr('disabled', false);
                    document.getElementById("countdown").innerHTML = " <span id='error'> <i class='fa fa-close fa-lg'></i>  حدث خطأ </span>  <br> <a href='#' id='resend'>إعادة إرسال الرمز</a> ";
                }
            }
        });

    });


    $(document).on('click', '#sendBtn', function(e) {
        $('#otp_numberError').addClass("d-none");
        var btn = $(this);
        $.ajax({
            type: 'POST',
            url: "{{ route('checkotpCode') }}",
            data: $('#otpForm').serialize(),
            beforeSend: function() {
                btn.attr('disabled', true);
                document.getElementById("countdown").innerHTML = " <span id='waiting'> <i class='fa fa-spinner fa-spin fa-lg'></i> الرجاء الإنتظار </span> ";
            },
            error: function(data) {

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

                btn.attr('disabled', false);
                if (data.status == 1) {
                    document.getElementById("countdown").innerHTML = "تم بنجاح";
                    let slug = "{{ route('setNewPasswordPage') }}";
                    window.location.replace(slug);
                } else if (data.status == 1)
                    document.getElementById("countdown").innerHTML = " <span id='exceedsms' style='color:red'> <i class='fa fa-close fa-lg'></i> حدث خطأ</span>  <br> <a href='#' id='resend'>إعادة إرسال الرمز</a> ";
                else
                    document.getElementById("countdown").innerHTML = " <span id='exceedsms' style='color:red'> <i class='fa fa-close fa-lg'></i>" + data.msg + "</span>  <br> <a href='#' id='resend'>إعادة إرسال الرمز</a> ";


            }
        });

    });
</script>
@endsection
