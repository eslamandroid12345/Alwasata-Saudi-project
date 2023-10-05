<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ URL::to('website_style/frontend/images/favicon.ico') }}"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="HandheldFriendly" content="true"/>

    <title>@yield('title')</title>
    <link rel="icon" type="image/x-icon" href="{{ URL::to('website_style/frontend/images/favicon.ico') }}"/>
    <link rel="stylesheet" href="{{ asset('newWebsiteStyle/css/bootstrap-rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('newWebsiteStyle/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('newWebsiteStyle/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('newWebsiteStyle/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('newWebsiteStyle/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('newWebsiteStyle/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('newWebsiteStyle/css/jquery.fancybox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('newWebsiteStyle/css/animate.css') }}">

    @yield('style')

</head>

<body>

<div class="contHeader">
    <div class="headContAria">

        <header class="single-head user_head">

            <div class="user-nav container-fluid px-lg-5">

                <div class="UserNav-cont d-flex  ">

                    <div class="userName d-flex">

                        <div class="dropdown">
                            <button class="user_btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{auth()->guard('customer')->user()->name}}
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{route('customer.profile')}}"> <i class="fas fa-user mr-3"></i> بياناتي </a>
                                <a class="dropdown-item logout" href="{{route('logout')}}"> <i class="fas fa-power-off mr-3"></i>خروج</a>
                            </div>
                        </div>

                    </div>
                    <div class="userNote d-flex">
                        <div class="notifactions mr-4 not_bar notf_call">
                            <i class="fas fa-bell"></i>
                            @if($notifyWithoutReminders->count() != 0) <span class="note-msg"><span class="quantity" id="conversions-notif">{{$notifyWithoutReminders->count()}}</span> </span> @endif

                            <ul class="list-unstyled note_ul">
                                @if($notifyWithoutReminders->count() != 0)
                                    <li>
                                        <div class="single_Pop d-flex">
                                            <div class="popIcon">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                            <div class="popCont">
                                                لديك اشعار لديك اشعار
                                            </div>
                                        </div>
                                    </li>

                                @else
                                    <li>
                                        <div class="all-note text-center">
                                            <a href="#" style=" pointer-events: none;color: #ccc;">لايوجد لديك إشعارات جديدة </a>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <div class="notifactions not_bar mail_call">
                            <i class="fas fa-envelope"></i>
                            @if($unread_conversions != 0) <span class="note-msg"><span class="quantity" id="conversions-notif">{{$unread_conversions}}</span> </span> @endif


                            @php
                                $senders= [];
                            @endphp

                            <ul class="list-unstyled mail_ul">

                                @if(count($unread_messages) != 0)
                                    @foreach($unread_messages as $message)
                                        @if(! in_array($message->from ,$senders) )

                                            <li>

                                                <form method="post" action="{{route('CustomernewChat')}}">
                                                    @csrf
                                                    <input type="hidden" name="receivers[]" value=" {{App\Http\Controllers\CustomerController::salesAgent()}}"/>
                                                    <input type="hidden" name="redirect" value="0"/>
                                                    <div class="mess__item" onclick="$(this).closest('form').submit();">

                                                        <div class="single_Pop d-flex">
                                                            <div class="popIcon">
                                                                <i class="fas fa-envelope"></i>
                                                            </div>
                                                            <div class="popCont">

                                                                <h6>{{ @$message->sender->name }}</h6>
                                                                <p>
                                                                    @if(in_array(strtolower(pathinfo($message->message, PATHINFO_EXTENSION)) , $supported_image))
                                                                        {{ MyHelpers::guest_trans('image message') }}
                                                                    @else
                                                                        {{ $message->message }}
                                                                    @endif
                                                                </p>
                                                                <span class="time">{{ $message->created_at->diffForHumans() }}</span>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>

                                            </li>

                                        @endif
                                    @endforeach
                                @endif

                                <li>
                                    <div class="all-note text-center">
                                        <a href="#" style=" pointer-events: none;color: #ccc;">لايوجد لديك رسائل جديدة</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

        </header>
    </div>
</div>

<!-- Modal -->
@yield('updateModel')
<!-- End Modal -->

<!-- Content -->
@yield('content')
<!-- End Content -->



{{--
<!-- FOOTER -->

<footer class="wow fadeInUp" data-wow-duration="2s">
    <img src="{{ asset('newWebsiteStyle/images/footer.png') }}" alt="">
<div class="container">


    <div class="row">

        <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
            <div class="footer-desc">
                <h3>الوساطة العقارية</h3>
                <div class="address d-flex">
                    <i class="fas fa-map-marker-alt mt-1 mr-2"></i>
                    <p>{!! nl2br(__('global.website_address')) !!}</p>
                </div>
            </div>
        </div>
        <!-- JUST FOR SPACE-->
        <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">

        </div>
        <!-- JUST FOR SPACE-->

        <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
            <div class="footer-desc">
                <h3>ابقى بالقرب منا</h3>
                <div class="icons-footer  ">
                    <span>
                        <a target="_blank" href="https://www.facebook.com/alwsatasa/">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </span>

                    <span>
                        <a target="_blank" href="https://www.snapchat.com/add/alwsata">
                            <i class="fab fa-snapchat"></i>
                        </a>
                    </span>

                    <span>
                        <a target="_blank" href="https://twitter.com/alwsatasa">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </span>
                    <span>
                        <a target="_blank" href="https://www.instagram.com/alwsatasa/">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </span>

                    <span>
                        <a target="_blank" href="https://www.youtube.com/channel/UC22GoF4CdghF5nv3g018IZA">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </span>
                </div>
            </div>
        </div>
        <!--
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <div class="footer-desc">
                    <h3>ابقى على تواصل</h3>
                    <div class=" mails">
                        <input type="text" placeholder="بريدك الالكتروني">
                        <button><i class="fas fa-paper-plane ml-2"></i> ارسال</button>
                    </div>
                </div>
            </div>
        -->
    </div>

</div>
</footer>



<div class="container">
    <div class="row last">
        <div class="col-lg-6">

        </div>
        <div class="col-lg-6">
            <div class="last-text text-right">
                <p>جميع الحقوق محفوظة لـ شركة الوساطة العقارية</p>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->

--}}

<script src="{{ asset('newWebsiteStyle/js/jQuery.js') }}"></script>
<script src="{{ asset('newWebsiteStyle/js/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('newWebsiteStyle/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('newWebsiteStyle/js/owl-Function.js') }}"></script>
<script src="{{ asset('newWebsiteStyle/js//jquery.fancybox.min.js') }}"></script>
<script src="{{ asset('newWebsiteStyle/js/function.js') }}"></script>
<script src="{{ asset('newWebsiteStyle/js/wow.min.js') }}"></script>

@yield('scripts')
</body>

</html>
