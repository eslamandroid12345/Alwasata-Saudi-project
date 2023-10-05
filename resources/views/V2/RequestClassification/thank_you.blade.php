<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>شكراً لك</title>
    <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.15.2/css/pro.min.css">
    <link rel="stylesheet" href="{{ asset('/assest/email/css/bootstrap.rtl.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/assest/email/css/bootstrap-select.css') }}">
    <link rel="stylesheet" href="{{ asset('/assest/email/css/main.css') }}">
</head>
<body style="background-color: #F3F2EF;"><!-- begin:: Page -->
<div class="page_cont  d-flex flex-wrap">
    <div class="side_content mt-5 ">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-12 mx-auto">
                    <div class="logo-top  text-center">
                        <img src="{{ asset('/assest/email/images/logo.png') }}" class="img-fluid" alt="">
                    </div>
                    <div class="step_title shadow-add  py-4 bg-white px-3 pt-3 col-lg-12">
                        @if (session()->has('message'))
                        <h4 class="mt-3 mb-4  text-center">{{session()->get('message')}}</h4>

                        @else
                        {{-- <h4 class="mt-3 mb-4  text-center">شكراً لك, تم تسجيل ردكم بنجاح</h4> --}}
                        <h4 class="mt-3 mb-4  text-center">تم تحديد الموعد بنجاح وسيتم التواصل معك في حينه بإذن الله</h4>

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('/assest/email/js/jquery.min.js') }}"></script>
<script src="{{ asset('/assest/email/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('/assest/email/js/wow.min.js') }}"></script>
<script src="{{ asset('/assest/email/js/bootstrap-select.js') }}"></script>
<script src="{{ asset('/assest/email/js/function.js') }}"></script>
</body>
</html>
