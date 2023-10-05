@extends('testWebsite.layouts.master')

@section('title') كلمة المرور @endsection


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

    .label {
        color: #004466;
        font-weight: bold;
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
            <h1>إعداد كلمة المرور</h1>

            <div class="order-my requstform">
                <p>
                    أدخل كلمة المرور التي بإمكانك استخدامها للدخول على حسابك.
                </p>

                <form action="{{ route('setNewPassword')}}" method="POST">
                    <p class="message-box alert hide"></p>

                    {{ csrf_field() }}

                    <input type="hidden" name="mobileNumber" id="mobileNumber" value="{{$mobileNumber}}">

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <label class="label">كلمة المرور</label>
                        <br>
                        <input type="password" name="password" id="password">
                        <i onclick="show('password')" class="fas fa-eye-slash" id="display"></i>
                        <br>
                        <strong style="color:darkred; font-size: 100%"> {{ $errors->first('password') }}</strong>
                    </div>


                    <br>
                    
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <label class="label">تأكيد كلمة المرور</label>
                        <br>
                        <input type="password" name="password_confirmation" id="password_confirmation">
                        <i onclick="show('password_confirmation')" class="fas fa-eye-slash" id="display"></i>
                        <br>
                        <strong style="color:darkred; font-size: 100%"> {{ $errors->first('password_confirmation') }}</strong>
                    </div>


                    <button id="sendBtn" type="submit" class="srchbtn"><i class="fas fa-paper-plane ml-2 mt-3"></i> حفظ</button>

                </form>



            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    function show(a) {
        var x = document.getElementById(a);
        var c = x.nextElementSibling
        if (x.getAttribute('type') == "password") {
            c.removeAttribute("class");
            c.setAttribute("class", "fas fa-eye");
            x.removeAttribute("type");
            x.setAttribute("type", "text");
        } else {
            x.removeAttribute("type");
            x.setAttribute('type', 'password');
            c.removeAttribute("class");
            c.setAttribute("class", "fas fa-eye-slash");
        }
    }
</script>
@endsection