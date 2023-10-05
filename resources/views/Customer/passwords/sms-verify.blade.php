@extends('testWebsite.layouts.master')

@section('title') تسجيل دخول @endsection


@section('pageMenu')
    @include('testWebsite.layouts.menuOfPages')
@endsection

@section('content')
    <div class="myOrders">
        <div class="container">

            <div class="head-div text-center wow fadeInUp">
                <h1>إسترجاع كلمة المرور بإستخدام رسالة الجوال</h1>
            </div>

            <div class="row">
                <div class="col-lg-9">

                    @if (session('status'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="list-unstyled">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('customer.sms.check') }}">
                        @csrf

                        <div class="form-group row">
                            <input type="hidden" name="mobile2">
                            {{--<div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                                <label for="mobile">{{ MyHelpers::guest_trans('mobile') }}<small id="requiredmobile"></small><small style="color:red;">*</small></label>
                                <input id="inputmobileError" name="mobile" class="form-control"  value="{{old('mobile')}}" type="number" autocomplete="mobile" placeholder="5xxxxxxxxx">
                                <span class="text-danger" style="color:red;" id="mobileError" role="alert"> </span>
                            </div>--}}
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                                <label for="code">رمز التحقق<small id="requiredmobile"></small><small style="color:red;">*</small></label>
                                <input name="code" class="form-control" type="number" placeholder="Code">
                                <span class="text-danger" style="color:red;"  role="alert"> </span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-info">
                                    استعادة كلمة المرور
                                </button>
                            </div>
                        </div>


                    </form>
                </div>
                <div class="col-lg-8">
                    <b><h4 style="font-width: bold;">ملاحظات :</h4></b>
                    <hr>
                    <ul>
                        <li> عند ارسال الكود واستخدامه لتعديل كلمة المرور يرجى إدخال الإيميل لإستخدامه في المرات القادمة في حالة نسيان كلمة المرور </li>
                        <li> لاتستطيع استرجاع كلمة المرور؟  <a href="{{route ('frontend.page.helpDesk') }}">تواصل معنا للمساعدة</a></li>

                    </ul>
                </div>
            </div>

        </div>
    </div>
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
