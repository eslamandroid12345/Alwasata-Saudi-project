@extends('testWebsite.layouts.master')

@section('title') طلباتي @endsection


@section('pageMenu')
@include('testWebsite.layouts.menuOfPages')
@endsection

@section('content')
<div class="myOrders">
    <div class="container">
        <div class="head-div text-center">
            <h1>طلباتي</h1>

            <div class="order-my requstform">
                <p>
                    أدخل رقم الطلب الخاص بطلبك.
                </p>

                <form>
                    <p class="message-box alert hide"></p>

                    @if ($id != '' && $id != null)
                    <p class="dublicate-alert alert alert-danger">
                        {{ MyHelpers::guest_trans('You already have a request')}} <span class="text-danger">{{$id}}</span>
                    </p>

                    @endif

                    {{ csrf_field() }}

                    <input type="text" name="order_number" id="" placeholder="رقم الطلب ">
                    <button id="sendBtn" type="button" class="srchbtn"><i class="fas fa-paper-plane ml-2 mt-3"></i> ارسال</button>

{{--                    <p>لديك حساب عميل ؟ <a href="{{url('/login/customer')}}">قم بتسجيل الدخول</a></p>--}}
                    <p>لديك حساب عميل ؟ <a href="{{url('/ar/app')}}">قم بتسجيل الدخول</a></p>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    jQuery(function(e) {

        $(document).on('keypress', '[name=order_number]', function(e) {
            if (e.which == 10 || e.which == 13) {
                e.preventDefault();
                $('#sendBtn').click();
            }
        });

        $(document).on('click', '#sendBtn', function(e) {

            e.preventDefault();
            var dublicate = $('.dublicate-alert');
            var loader = $('.message-box');
            var btn = $(this);

            dublicate.addClass('hide');


            $.ajax({
                type: 'GET',
                url: "{{ route('frontend.page.check_order_status') }}",
                data: $('.requstform form').serialize(),
                beforeSend: function() {
                    btn.attr('disabled', true);
                    loader.html("<i class='fa fa-spinner fa-spin fa-lg'></i> {{ MyHelpers::guest_trans('loading') }}").removeClass('hide alert-danger').addClass('alert-success');
                },
                error: function(jqXHR, exception) {
                    btn.attr('disabled', false);
                    loader.html("{{MyHelpers::guest_trans('The selected order number is invalid.') }}").removeClass('alert-success').addClass('alert-danger');
                },
                success: function(data) {
                    btn.attr('disabled', false);
                    if (data.status == 11) { //pending request
                        loader.html(data.msg).removeClass('alert-danger').addClass('alert-success');
                    } else if (data.status == 1) { // request
                        if (data.customer.isVerified == 1) {
                            /*let slug = " route('customer.login') ";
                            window.location.replace(slug);*/
                        } else {
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
                        }
                    } else {
                        loader.html(data.msg).removeClass('alert-success').addClass('alert-danger');
                    }

                }
            });

        })
    });
</script>
@endsection
