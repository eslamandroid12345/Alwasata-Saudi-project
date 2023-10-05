@extends('testWebsite.layouts.master')

@section('title') استرجاع كلمة المرور @endsection


@section('pageMenu')
@include('testWebsite.layouts.menuOfPages')
@endsection

@section('content')
<div class="myOrders">
    <div class="container">

        <div class="head-div text-center wow fadeInUp">
            <h1>استرجاع كلمة المرور </h1>
        </div>
        <div>

            <h4>استرجاع كلمة المرور بإستخدام البريد الإلكتروني</h4>
            <hr>
            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif


            <form method="POST" action="{{ route('customer.password.email') }}">
                @csrf

                <div class="form-group row">
                    <label for="email" class="col-lg-12 col-form-label">البريد الإلكتروني</label>

                    <div class="col-lg-12">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary">
                            استرجاع
                        </button>
                    </div>
                </div>

                <a href="{{route('customer.sms.get')}}">إسترجاع بإستخدام رسالة الجوال ؟</a>

            </form>
        </div>
        <!-- end card-body -->
    </div>
    <!-- end card -->

    <!-- end row -->

</div>
<!-- end col -->
<!-- end row -->

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
            e.preventDefault();
            var loader = $('.message-box');
            $('#nameError').addClass("d-none");
            $('#mobileError').addClass("d-none");
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

                    btn.attr('disabled', false);
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


    function setNewPassword(mobile) {


    }
</script>
@endsection