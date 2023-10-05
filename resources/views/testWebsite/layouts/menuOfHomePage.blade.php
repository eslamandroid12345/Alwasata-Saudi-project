 <!-----------Start Home Page---------->

 <section class="homePage">

     <div class="mainPage">


         <?php

            //Detect special conditions devices
            $iPod    =  (in_array("HTTP_USER_AGENT", $_SERVER)) ? stripos($_SERVER['HTTP_USER_AGENT'], "iPod") : null;
            $iPhone  = (in_array("HTTP_USER_AGENT", $_SERVER)) ? stripos($_SERVER['HTTP_USER_AGENT'], "iPhone") : null;
            $iPad    = (in_array("HTTP_USER_AGENT", $_SERVER)) ? stripos($_SERVER['HTTP_USER_AGENT'], "iPad") : null;

            ?>


         @if ($iPod || $iPhone || $iPad)
         <img src="{{ asset('newWebsiteStyle/images/Alwsata-mobile.gif') }}" alt="">
         @else
         <video autoplay loop muted>
             <source src="{{ asset('newWebsiteStyle/images/Alwsata-mobile.mp4') }}" type="video/mp4">
             المتصفح لايدعم تشغيل المقاطع
         </video>
         @endif

         <div class="img-over"></div>

     </div>


     <header>
         <nav class="navbar navbar-expand-lg main ">
             <div class="container">
                 <a class="navbar-brand" href="{{url('/')}}"><img src="{{ asset('newWebsiteStyle/images/logo.png') }}" alt=""></a>
                 <button class="navbar-toggler fas fa-bars " type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

                 </button>

                 <div class="collapse navbar-collapse " id="navbarSupportedContent">
                     <ul class="navbar-nav main ml-auto">




                     {{--
                        <li class="nav-item">
                             <a class="nav-link social-buttons" href="https://play.google.com/store/apps/details?id=com.wasata.wasata_user&hl=en&gl=US" target="_blank">
                             <i class="fab fa-apple  mr-2"></i>

                            </a>
                         </li>

                         <li class="nav-item">
                             <a class="nav-link social-buttons" href="https://play.google.com/store/apps/details?id=com.wasata.wasata_user&hl=en&gl=US" target="_blank">
                             <i class="fab fa-google-play  mr-2"></i>

                            </a>
                         </li>
                        --}}




                         <li class="nav-item">
                             <a class="nav-link" href="{{url('ar/about-us')}}">
                                 <i class="fas fa-info-circle mr-2"></i>
                                 من نحن</a>
                         </li>
                         <!--
                            <li class="nav-item">

                                <a class="nav-link" href="#" data-scroll="serv">
                                    <i class="fas fa-bullhorn mr-2"></i>
                                    الوظائف</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="#" data-scroll="vision">
                                    <i class="fas fa-cogs mr-2"></i>
                                    البرامج التمويلية</a>
                            </li>


                        <li class="nav-item">
                             <a class="nav-link" href="{{url('ar/app')}}" data-scroll="work">
                                 <i class="fas fa-calculator mr-2"></i>
                                 حاسبة التمويل</a>
                         </li>
                          -->

                          <li class="nav-item">
                             <a class="nav-link" href="{{url('ar/app')}}" data-scroll="work">
                                 <i class="fas fa-calculator mr-2"></i>
                                 تطبيقنا</a>
                         </li>

{{--                         <li class="nav-item">--}}
{{--                             <a class="nav-link" href="{{url('ar/my-requests')}}" data-scroll="work">--}}
{{--                                 <i class="fas fa-shopping-bag mr-2"></i>--}}
{{--                                 طلباتي</a>--}}
{{--                         </li>--}}

                         <!--
                            <li class="nav-item">
                                <a class="nav-link" href="{{url('ar/askforconsultant')}}" data-scroll="blog">
                                    <i class="fas fa-question-circle mr-2"></i>
                                    اطلب استشارة</a>
                            </li>
                        -->

{{--                         <li class="nav-item">--}}
{{--                             <a class="nav-link" href="{{url('/login/customer')}}" data-scroll="blog">--}}
{{--                                 <i class="fas fa-sign-in-alt mr-2"></i>--}}
{{--                                 تسجيل الدخول</a>--}}
{{--                         </li>--}}



                     </ul>
                     <div class="btn-order mt-2 mt-sm-0">
                         <a class="nav-link order-price mb-sm-0 mb-2" href="{{url('ar/request_service')}}" data-scroll="contact">
                             <i class="fas fa-dollar-sign mr-2"></i>
                             اطلب استشارة</a>
                         <a class="nav-link order-price cont-phone" href="tel:920009423" data-scroll="contact">
                             <i class="fas fa-phone mr-2"></i>
                             920009423</a>

                             {{-- <a class="nav-link order-price cont-phone" href="{{route('careers')}}" data-scroll="contact">
                             <i class="fas fa-phone mr-2"></i>
                             التقديم على  وظيفه</a> --}}
                     </div>
                 </div>
             </div>
         </nav>

     </header>


 </section>

 <div class="fix-phone">
     <div class="row">

         <a class="nav-link order-price cont-phone" href="tel:920009423" data-scroll="contact">
             <i class="fas fa-phone mr-2"></i>
             920009423</a>

         <a class="nav-link order-price cont-phone" href="{{url('ar/request_service')}}" data-scroll="contact">
             <i class="fas fa-dollar-sign mr-2"></i>
             اطلب استشارة</a>

        {{-- <a class="nav-link order-price cont-phone" href="{{route('careers')}}" data-scroll="contact">
             <i class="fas fa-dollar-sign mr-2"></i>
              التقديم على  وظيفه</a> --}}
     </div>

 </div>
