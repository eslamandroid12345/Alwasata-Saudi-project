<!DOCTYPE html>
<html>

<head>
    <link rel="icon" type="image/x-icon" href="{{ URL::to('website_style/frontend/images/favicon.ico') }}" />

    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('newWebsiteStyle/css/bootstrap-rtl.css') }}">


    <link rel="stylesheet" href="{{ asset('customerAccountStyle/assest/css/bootstrap-rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('customerAccountStyle/assest/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('customerAccountStyle/assest/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('customerAccountStyle/assest/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('customerAccountStyle/assest/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('customerAccountStyle/assest/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('customerAccountStyle/assest/css/jquery.fancybox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('customerAccountStyle/assest/css/animate.css') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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

    <section class="HomePage mb-5">

        <div class="homeCont">


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
                                    {{--<div class=" mr-4 not_bar ">
                                        <a href="{{route('customer.customer-reminders.index')}}" class="text-white">
                                            <i class="fa fa-calendar-alt"></i>
                                            التذكيرات
                                        </a>
                                    </div>--}}
                                    <div class="notifactions mr-4 not_bar notf_call">
                                        <i class="fas fa-bell"></i>
                                        @if($notifyWithoutReminders->count() != 0) <span class="note-msg"><span class="quantity" id="conversions-notif">{{$notifyWithoutReminders->count()}}</span> </span> @endif
                                        @if($reminders->count() != 0) <span class="note-msg"><span class="quantity" id="conversions-notif">{{$reminders->count()}}</span> </span> @endif

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

                                            @elseif($reminders->count() != 0)
                                                @foreach($reminders as $reminder)
                                                    <li>
                                                        <a href="{{route('customer.notifications.read',[$reminder->id,9])}}">
                                                        <div class="single_Pop d-flex">

                                                                <div class="popIcon">
                                                                    <i class="fas fa-bell"></i>
                                                                </div>
                                                                <div class="popCont" >
                                                                    {{$reminder->value}}
                                                                </div>


                                                        </div>
                                                        </a>
                                                    </li>
                                                @endforeach

                                            @else
                                            <li style="background-color:rgb(224, 224, 209,0.5)">
                                                <div class="all-note text-center">
                                                    <a href="#" style=" pointer-events: none;color: #ccc;">لايوجد لديك إشعارات جديدة </a>
                                                </div>
                                            </li>
                                            @endif
                                                <li>
                                                    <div class="single_Pop text-center">
                                                        <a href="{{route('customer.notifications.index')}}">
                                                            الذهاب لصفحة التنبيهات
                                                        </a>
                                                    </div>
                                                </li>
                                        </ul>
                                    </div>
{{--                                    <div class="notifactions not_bar mail_call">--}}
{{--                                        <i class="fas fa-envelope"></i>--}}
{{--                                        @if($unread_conversions != 0) <span class="note-msg"><span class="quantity" id="conversions-notif">{{$unread_conversions}}</span> </span> @endif--}}
{{--                                        @php--}}
{{--                                        $senders= [];--}}
{{--                                        @endphp--}}
{{--                                        <ul class="list-unstyled mail_ul">--}}
{{--                                            @if(count($unread_messages) != 0)--}}
{{--                                            @foreach($unread_messages as $message)--}}
{{--                                            @if(! in_array($message->from ,$senders) )--}}
{{--                                            <li>--}}
{{--                                            <form method="post" action="{{route('CustomernewChat')}}">--}}
{{--                                                     @csrf--}}
{{--                                                     <input type="hidden" name="receivers[]" value=" {{App\Http\Controllers\CustomerController::salesAgent()}}" />--}}
{{--                                                    <input type="hidden" name="redirect" value="0" />--}}
{{--                                                    <div class="mess__item" onclick="$(this).closest('form').submit();">--}}
{{--                                                        <div class="single_Pop d-flex">--}}
{{--                                                            <div class="popIcon">--}}
{{--                                                                <i class="fas fa-envelope"></i>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="popCont">--}}
{{--                                                                <h6>{{ @$message->sender->name }}</h6>--}}
{{--                                                                <p>--}}
{{--                                                                    @if(in_array(strtolower(pathinfo($message->message, PATHINFO_EXTENSION)) , $supported_image))--}}
{{--                                                                    {{ MyHelpers::guest_trans('image message') }}--}}
{{--                                                                    @else--}}
{{--                                                                    {{ $message->message }}--}}
{{--                                                                    @endif--}}
{{--                                                                </p>--}}
{{--                                                                <span class="time">{{ $message->created_at->diffForHumans() }}</span>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </form>--}}
{{--                                            </li>--}}
{{--                                            @endif--}}
{{--                                            @endforeach--}}
{{--                                            @else--}}
{{--                                            <li>--}}
{{--                                                <div class="all-note text-center">--}}
{{--                                                    <a href="#" style=" pointer-events: none;color: #ccc;">لايوجد لديك رسائل جديدة</a>--}}
{{--                                                </div>--}}
{{--                                            </li>--}}
{{--                                            @endif--}}
{{--                                        </ul>--}}
{{--                                    </div>--}}
                                    <div class="notifactions not_bar mail_call">
                                        <i class="fas fa-envelope"></i>
                                        @if($count != 0)
                                            <span class="note-msg">
                                                <span class="quantity" id="conversions-notif">{{ $count }}</span>
                                            </span>
                                        @endif
                                        @php
                                            $senders= [];
                                        @endphp
                                        <ul class="list-unstyled mail_ul">
                                            @if($count != 0)
                                                @foreach($messages as $message)
                                                    @if(! in_array($message['senderId'] ,$senders) )
                                                        <li>
                                                            <form method="post" action="{{route('CustomernewChat')}}">
                                                                @csrf
                                                                <input type="hidden" name="receivers[]" value=" {{App\Http\Controllers\CustomerController::salesAgent()}}" />
                                                                <input type="hidden" name="redirect" value="0" />
                                                                <div class="mess__item" onclick="$(this).closest('form').submit();">
                                                                    <div class="single_Pop d-flex">
                                                                        <div class="popIcon">
                                                                            <i class="fas fa-envelope"></i>
                                                                        </div>
                                                                        <div class="popCont">
                                                                            <h6>{{ $message['senderName'] }}</h6>
                                                                            <p>
                                                                                @if($message['text'] == '')
                                                                                   الرسالة تحتوي على ملف
                                                                                @else
                                                                                    {{ $message['text'] }}
                                                                                @endif
                                                                            </p>
                                                                            <span class="time">{{@  \Carbon\Carbon::parse($message['created_at'])->diffForHumans() }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            @else
                                                <li>
                                                    <div class="all-note text-center">
                                                        <a href="#" style=" pointer-events: none;color: #ccc;">لايوجد لديك رسائل جديدة</a>
                                                    </div>
                                                </li>
                                            @endif
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
        </div>
    </section>
    <div class="footerLast   d-block">
        <div class="container  py-5 text-center">جميع الحقوق محفوظة لـ شركة الوساطة العقارية.
        </div>
    </div>
    <script src="{{ asset('customerAccountStyle/assest/js/jQuery.js') }}"></script>
    <script src="{{ asset('customerAccountStyle/assest/js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('customerAccountStyle/assest/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('customerAccountStyle/assest/js/owl-Function.js') }}"></script>
    <script src="{{ asset('customerAccountStyle/assest/js//jquery.fancybox.min.js') }}"></script>
    <script src="{{ asset('customerAccountStyle/assest/js/function.js') }}"></script>
    <script src="{{ asset('customerAccountStyle/assest/js/wow.min.js') }}"></script>
    @yield('scripts')
</body>
</html>
