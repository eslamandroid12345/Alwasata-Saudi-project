@extends('testWebsite.layouts.master')

@section('title') تسجيل دخول @endsection


@section('pageMenu')
@include('testWebsite.layouts.menuOfPages')
@endsection

@section('content')

<div class="myOrders">
    <div class="container">
        <div class="row justify-content-center">
            @if (session('message'))
                <div class="col-12" style="text-align: center;">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                    </div>
                </div>
            @endif
            <div class="col-12 col-md-8">
                <div id="msg2" class="alert alert-dismissible" style="display:none;">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            </div>
            <input type="hidden" value="{{ URL::previous() }}" id="prviousPage">
        </div>
{{--
        <div class="head-div text-center wow fadeInUp">
            <h1>تسجيل الدخول</h1>
        </div>

        <input type="hidden" value="{{ URL::previous() }}" id="prviousPage">

        <div>

            @isset($url)
            <form method="POST" action='{{ url("login/$url") }}' aria-label="{{ __('Login') }}">
            @else
            <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
            @endisset
                @csrf


                <div class="row">
                    <div class="col-lg-6 offset-lg-3">


                        <div class="sub-login mt-5">
                            @if (session('message'))
                            <div class="col-lg-12" style="text-align: center;">
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('message') }}
                                </div>
                            </div>
                            @endif


                            <div id="msg2" class="alert alert-dismissible" style="display:none;">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>


                            <div class="form-group order-my">
                                <input type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder=" اسم المستخدم أو رقم الموبايل أو البريد الالكتروني">
                                <strong style="color:darkred; font-size: 115%"> {{ $errors->first('username') }}</strong>

                                <input type="password" class="form-control" name="password" id="password" placeholder="كلمة المرور">
                                <strong style="color:darkred; font-size: 115%"> {{ $errors->first('password') }}</strong>


                            </div>

                            <div class="form-check" style="text-align:center">
                                <input type="checkbox" name="remember_me" value="1" style=" width: 100%;padding: 10px 20px;  color: #000;text-align: right;transition: ease-in-out 0.3s;-webkit-transition: ease-in-out 0.3s;-moz-transition: ease-in-out 0.3s;-ms-transition: ease-in-out 0.3s; -o-transition: ease-in-out 0.3s;" class="form-check-input">
                                <label class="form-check-label" style="display: block;font-size: 16px;font-weight: bold;padding-right:10%">تذكرني</label>
                            </div>

                            <div class="form-group order-my">
                                <button> <i class="fas fa-user mr-2 "></i>تسجيل الدخول</button>
                                @if (Route::has('customer.password.request'))
                                <a class="mt-2 d-block" style="text-align:center" href="{{ route('customer.password.request') }}">
                                    نسيت كلمة المرور ؟ اضغط هنا
                                </a>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>--}}
    </div>

     @include('testWebsite.Pages.appsection')
</div>












@endsection

@section('scripts')
<script>
    $(document).ready(function() {

        let base_url = window.location.origin;

        var myReq = '/ar/my-requests';
        var askFund = '/ar/request_service';
        var calReq = '/';

        var linkOfMyReq = base_url + myReq;
        var linkOfAskFund = base_url + askFund;
        var linkOfCalReq = base_url + calReq;

        var link = document.getElementById("prviousPage").value;

        if (linkOfMyReq === link || linkOfAskFund === link || linkOfMyReq === linkOfCalReq )
            $('#msg2').addClass("alert-primary").removeAttr("style").html("لديك طلب مسجل بالفعل، يرجى تسجيل الدخول لمعرفة حالة طلبك والتواصل مع مستشارك");

    });
</script>
@endsection
