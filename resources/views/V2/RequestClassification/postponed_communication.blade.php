<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>تحديد موعد للتواصل</title>
    <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.15.2/css/pro.min.css">
    <link rel="stylesheet" href="{{ asset('assest/email/css/bootstrap.rtl.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assest/email/css/bootstrap-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assest/email/css/main.css') }}">
</head>
<body><!-- begin:: Page -->

<div class="page_cont  d-flex flex-wrap">

    <div class="side_content ">
        <div class="header px-2 py-2 text-center">
            <img src="{{ asset('/assest/email/images/logo.png') }}" class="img-fluid py-5" alt="">

        </div>
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="nav-top    py-4">
                        <div class="step active text-center">

          <span><svg xmlns="http://www.w3.org/2000/svg" width="23.729" height="23.734" viewBox="0 0 23.729 23.734">
            <path id="Icon_awesome-wpforms" data-name="Icon awesome-wpforms"
                  d="M23.729,4.538V23.7a2.262,2.262,0,0,1-2.288,2.288H2.288A2.27,2.27,0,0,1,0,23.69V4.538A2.262,2.262,0,0,1,2.288,2.25H21.446A2.261,2.261,0,0,1,23.729,4.538ZM21.753,23.69V4.538a.314.314,0,0,0-.307-.307h-.493L15.111,8.182,11.864,5.539,8.623,8.182,2.781,4.226H2.288a.314.314,0,0,0-.307.307V23.69A.314.314,0,0,0,2.288,24H21.446a.307.307,0,0,0,.307-.307ZM7.955,10.407v1.96H4.062v-1.96Zm0,3.941v1.976H4.062V14.347Zm.588-7.8L11.4,4.231H5.127L8.543,6.545Zm11.123,3.861v1.96H9.285v-1.96Zm0,3.941v1.976H9.285V14.347Zm-4.481-7.8L18.6,4.231H12.33l2.855,2.315ZM19.666,18.3v1.976H14.4V18.3h5.265Z"
                  transform="translate(0 -2.25)" fill="#fff"/>
          </svg>
          </span>
                            <h4 class="ml-3">طلب تأجيل التواصل</h4>
                        </div>
                    </div>
                    <hr>
                    <div class="step_title py-4 text-center">
                        {{-- <h4 class="mt-2   ">ادخل الموعد المناسب لك</h4>
                        <p>
                           يرجي ادخال موعد فى خلال الايام التالية :-
                            <br/>
                            الأحد - الخميس
                            9 صباحا - 6 مساء
                        </p> --}}
                        <h4 class="mt-2   "> تحديد موعد للتواصل</h4>
                        <p>

                            يرجى ادخال الموعد المناسب للتواصل علما بأن أوقات الدوام من الأحد للخميس من الساعة ٩ صباحا إلى الساعة ٦ مساء
                            يمكنك التواصل خارج هذه الأوقات بتحميل تطبيقنا (<a href="https://alwsata.com.sa/ar/app">انقر هنا</a>) وترك رسالة لمستشارك وسيتواصل معك في أقرب وقت ممكن
                            سعيدون بخدمتك، شركة الوساطة العقارية
                        </p>
                    </div>
                    <form action="{{ route('postponed_new_date') }}" method="post">
                        @csrf
                        <input type="hidden" value="{{ $requestId }}" name="request_id">
{{--                        <div class="row mt-2">--}}
{{--                            <div class="col-lg-6 mb-2">--}}
{{--                                <label for="">هل طلبت تأجيل التواصل ؟</label>--}}
{{--                                <input type="checkbox" name="postponed_status" class="form-control">--}}
{{--                            </div>--}}
{{--                        </div>--}}
                         <div class="row mt-4">
                            <div class="col-lg-6 mb-4">
                                <label for="">التاريخ</label>
                                <input type="date" name="new_date" min="{{\Carbon\Carbon::today()->toDateString()}}" class="form-control" placeholder="التاريخ" required>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <label for="">الوقت</label>
                                <input type="time" name="new_time" class="form-control" placeholder="الوقت" required>
                            </div>
                            <div class="col-12">
                                <div class="text-right">
                                    <a href="#">
                                        <button class="bg-primary btn btn-primary px-5 w-100 bg-main"> حفظ الموعد <i class="fal fa-arrow-left pl-2"></i></button>
                                    </a>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="text-right">
                                    <br>
                                    <a href="{{ url('/customer/postponed-status/' . $requestId) }}">
                                        <button type="button" class="bg-danger btn btn-danger px-5 w-100"> لم أطلب تأجيل التواصل </button>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assest/email/js/jquery.min.js') }}"></script>
<script src="{{ asset('assest/email/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assest/email/js/wow.min.js') }}"></script>
<script src="{{ asset('assest/email/js/bootstrap-select.js') }}"></script>
<script src="{{ asset('assest/email/js/function.js') }}"></script>
</body>
</html>
