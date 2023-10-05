<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ MyHelpers::guest_trans('Login') }} </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <!-- Fivicon -->
    <link rel="shortcut icon" href="{{ url('interface_style/images/icon/favicon1.png')  }}">

    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ url('interface_style/vendor/bootstrap-4.1/bootstrap.min.css') }}" />

    <!--===============================================================================================-->

    <link rel="stylesheet" type="text/css" href="{{ url('interface_style/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ url('interface_style/fonts/Linearicons-Free-v1.0.0/icon-font.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ url('interface_style/css/util.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('interface_style/css/main.css') }}" />
    <!--===============================================================================================-->
    <style>
    html {
            zoom: 0.85;
        }
    </style>
</head>

<body>

    <div class="limiter">

        <div class="container-login100" style="background-image: url(    {{ url('interface_style/images/img-01.jpg') }} );">
            <div class="wrap-login100 p-t-190 p-b-30">

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="login100-form-avatar">

                        <img src="{{ url('interface_style/images/avatar-01.png') }}" alt="AVATAR">
                    </div>

                    <span class="login100-form-title p-t-20 p-b-45">
                        {{ MyHelpers::guest_trans('Alwasat Real Estate') }}
                        <br>
                        Alwasata Real Estate
                    </span>


                    @if( $errors->first() != 'الحقل مطلوب')
                    <strong style="color:darkred; font-size: 115%"> {{ $errors->first() }}</strong>
                    @endif

                    <div dir="rtl" class="wrap-input100 validate-input m-b-10" data-validate="Username is required">

                        <input id="username" type="text" class="form-control input100 @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" autocomplete="username" autofocus placeholder=" {{ MyHelpers::guest_trans('Username') }}">
                        <span class="focus-input100"></span>


                        @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong style="color:darkred; font-size: 125%">{{ $message }}</strong>
                        </span>
                        @enderror





                    </div>

                    <div dir="rtl" class="wrap-input100 validate-input m-b-10" data-validate="Password is required">

                        <input id="password" type="password" class="form-control input100 @error('password') is-invalid @enderror" name="password" autocomplete="current-password" placeholder=" {{ MyHelpers::guest_trans('Password') }}">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong style="color:darkred; font-size: 125%">{{ $message }}</strong>
                        </span>
                        @enderror

                        <span class="focus-input100"></span>


                    </div>

                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" value="{{ old('remember') ? 'checked' : '' }}">

                                <label class="login-checkbox" style="color:aliceblue; font-family: Montserrat-Regular;" for="remember">
                                    {{ MyHelpers::guest_trans('Remember Me') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="container-login100-form-btn p-t-10">

                        <button type="submit" class="login100-form-btn">
                            {{ MyHelpers::guest_trans('Login') }}
                        </button>
                    </div>


                    <div class="text-center w-full p-t-25 p-b-230">
                        @if (Route::has('password.request'))
                        <!--  <a class=" txt1" href="{{ route('password.request') }}">-->
                        <a class=" txt1" href="#">
                            {{ MyHelpers::guest_trans('Forgot Your Password?') }}
                        </a>
                        @endif
                    </div>

                    <!--
                    <div class="text-center w-full" style="color:aliceblue;font-family: Montserrat-Regular;"> {{ MyHelpers::guest_trans('Dont you have account?') }}
                          <a class="txt1" href="{{ route('register') }}">
                        <a class="txt1" href="#">
                            {{ MyHelpers::guest_trans('Sign Up Here') }}

                        </a>
                    </div>
                    -->
                </form>
            </div>
        </div>
    </div>


</body>

</html>
