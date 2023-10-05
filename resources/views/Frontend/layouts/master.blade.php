<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ URL::to('website_style/frontend/images/favicon.ico') }}" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="HandheldFriendly" content="true" />
    <title>@yield('title')</title>
    <meta name="description" content="company - @yield('title')">

    <meta property="og:title" content="@yield('title')" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ Request::fullUrl() }}" />
    <meta property="og:image" content="@yield('image')" />
    <meta property="og:site_name" content='company' />
    <meta property="og:description" content="@yield('description')" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:title" content="@yield('title')" />
    <meta name="twitter:description" content="@yield('description')" />
    <meta name="twitter:image" content="@yield('image')" />
    <meta name="twitter:url" content="{{ Request::fullUrl() }}">
    <meta name="twitter:site_name" content='company' />
    <meta name="description" content="@yield('description')" />
    <link rel="canonical" href="{{ Request::fullUrl() }}" />


    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ url('website_style/frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- <link rel="stylesheet" type="text/css" href="{{ asset('website_style/backend/plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}"> -->

    <link rel="stylesheet" href="{{ asset('website_style/frontend/css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('website_style/frontend/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('website_style/frontend/css/slick.css') }}">

    <link rel="stylesheet" href="{{ URL::to('website_style/backend/plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('website_style/frontend/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('website_style/frontend/css/media.css') }}">

    <!-- jquery-calenders Plugin -->
    <link rel="stylesheet" href="{{ URL::to('website_style/backend/plugins/jquery.calendars.package/css/redmond.calendars.picker.css') }}">
    <link href="{{ asset('website_style/frontend/css/jquery.jConveyorTicker.min.css') }}" rel="stylesheet">

    <script type="text/javascript" src="{{ asset('website_style/frontend/js/jquery.min.js') }}"></script>
    <script src="{{ asset('website_style/frontend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('website_style/frontend/js/jquery-ui.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
    <script src="{{ asset('website_style/frontend/js/jquery.numeric.js') }}"></script>
    <script src="{{ URL::to('website_style/backend/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('website_style/frontend/js/jquery.marquee.min.js') }}"></script>

    <!-- jquery-calenders Plugin -->
    <script src="{{ URL::to('website_style/backend/plugins/jquery.calendars.package/js/jquery.plugin.min.js') }}"></script>
    <script src="{{ URL::to('website_style/backend/plugins/jquery.calendars.package/js/jquery.calendars.all.js') }}"></script>
    <script src="{{ URL::to('website_style/backend/plugins/jquery.calendars.package/js/jquery.calendars.ummalqura.js') }}"></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-89450741-3"></script>

    <!--NEW 2/2/2020 for hijri datepicker-->
    <link href="{{url('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" />


    <style>
        svg:not(:root) {
            overflow: hidden;
            direction: rtl;
        }

        a {
            color: #000;
        }

        /* header */

        .header {
            float: right;
        }

        .contactinfo {}

        .header ul {
            margin: 0;
            padding: 0;
            list-style: none;
            overflow: hidden;
        }

        .header li a {
            display: block;
            padding: 20px 20px;
            text-decoration: none;
        }


        /* menu */

        .header .menu {
            clear: both;
            max-height: 0;
            transition: max-height .2s ease-out;
        }

        /* menu icon */

        .header .menu-icon {
            cursor: pointer;
            display: inline-block;
            float: left;
            padding: 10px 10px;
            position: relative;
            user-select: none;
        }

        .header .menu-icon .navicon {
            background: #333;
            display: block;
            height: 2px;
            position: relative;
            transition: background .2s ease-out;
            width: 18px;
        }

        .header .menu-icon .navicon:before,
        .header .menu-icon .navicon:after {
            background: #333;
            content: '';
            display: block;
            height: 100%;
            position: absolute;
            transition: all .2s ease-out;
            width: 100%;
        }

        .header .menu-icon .navicon:before {
            top: 5px;
        }

        .header .menu-icon .navicon:after {
            top: -5px;
        }

        /* menu btn */

        .header .menu-btn {
            display: none;
        }

        .header .menu-btn:checked~.menu {
            max-height: 240px;
        }

        .header .menu-btn:checked~.menu-icon .navicon {
            background: transparent;
        }

        .header .menu-btn:checked~.menu-icon .navicon:before {
            transform: rotate(-45deg);
        }

        .header .menu-btn:checked~.menu-icon .navicon:after {
            transform: rotate(45deg);
        }

        .header .menu-btn:checked~.menu-icon:not(.steps) .navicon:before,
        .header .menu-btn:checked~.menu-icon:not(.steps) .navicon:after {
            top: 0;
        }

        /* 48em = 768px */

        @media (min-width: 48em) {
            .header li {
                float: left;
            }

            .header li a {
                padding: 15px 30px;
            }

            .header .menu {
                clear: none;
                float: right;
                max-height: none;
            }

            .header .menu-icon {
                display: none;
            }
        }

        html {
            zoom: 0.8;
        }

        .errorFiled {
            background-color: #ffdddd;
            position: relative;
            animation: shake .1s linear;
            animation-iteration-count: 3;
            border: 1px solid red;
            outline: none;
        }

        select option:checked {
            background-color: red;
        }
    </style>

    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-89450741-3');
    </script>
    <script>
        if (window.location.pathname == "/thankyou") {
            gtag('event', 'conversion', {
                'send_to': 'AW-831160911/rAjsCPDbsHgQz4SqjAM'
            });
        }
    </script>
</head>



<body>

    <div class="wrapper">
        <!-- Header -->
        <header class="wow fadeInDown">

            <div class="container-fluid">

                <div class="row">

                    <div style="float:right;" class="logo">

                        <a href="{{url('/')}}" title=""><img src="{{asset('website_style/frontend/images/logo.png')}}"></a>

                    </div>

                    <header class="header" style="font-weight:bold; font-size:large ;float:left;">

                        <input class="menu-btn" type="checkbox" id="menu-btn" />
                        <label class="menu-icon" for="menu-btn"><span class="navicon"></span></label>

                        <ul class="menu">
                            <li><a href="{{url('ar/request_service')}}" title="{{ MyHelpers::guest_trans('Ask for funding') }}">{{ MyHelpers::guest_trans('Ask for funding') }}</a></li>
                            <!--     <li><a href="#" title="{{ MyHelpers::guest_trans('Financing programs') }}">{{ MyHelpers::guest_trans('Financing programs') }}</a></li> -->
                            <!--    <li><a href="#" title="{{ MyHelpers::guest_trans('Real East offres') }}">{{ MyHelpers::guest_trans('Real East offres') }}</a></li>-->
                            <li><a href="{{url('ar/askforconsultant')}}" title="{{ MyHelpers::guest_trans('Ask for a consultant') }}">{{ MyHelpers::guest_trans('Ask for a consultant') }}</a></li>
                            <li><a href="{{url('ar/my-requests')}}" title="{{ MyHelpers::guest_trans('My Req') }}">{{ MyHelpers::guest_trans('My Req') }}</a></li>
                        </ul>

                    </header>

                    <!--
                    <div class="navigation" style="float: left;padding: 25px 0 0 0;margin-left: 150px;">

                        <ul>

                            <li><a href="{{url('page/request_service')}}" title="Ask for funding">Ask for funding</a></li>
                            <li><a href="#" title="Financing programs">Financing programs</a></li>
                            <li><a href="#" title="Real East offres">Real East offres</a></li>
                            <li><a href="{{url('page/askforconsultant')}}" title="Ask for a consultant">Ask for a consultant</a></li>
                            <li><a href="{{url('page/my-requests')}}" title="My Requests">My Req</a></li>

                        </ul>

                    </div> 
    



                    <div style="float:left;" class="contactinfo">

                        <figure><img src="{{asset('website_style/frontend/images/ico_phone.png')}}"></figure>

                        <div class="txt">

                            <span>{{ MyHelpers::guest_trans('Contact Us Now') }}</span>

                            <strong>900009423</strong>

                        </div>

                    </div>

                    -->

                </div>

            </div>

        </header>
        <!-- End Header -->


        <!-- Content -->
        @yield('content')
        <!-- End Content -->

        <!-- Footer -->
        @include('Frontend.includes.footer')
        <!-- End Footer -->
    </div>

    <script src="{{ asset('website_style/backend/js/main.js') }}"></script>
    <script src="{{ asset('website_style/frontend/js/slick.min.js') }}"></script>
    <script src="{{ asset('website_style/frontend/js/wow.js') }}"></script>
    <script src="{{ asset('website_style/frontend/js/script.js') }}"></script>

    <script>
        function trimNumber(s) {
            while (s.substr(0, 1) == '0' && s.length > 1) {
                s = s.substr(1, 9999);
            }
            return s;
        }
        $(document).on('input', '[name=mobile],[name=client_mobile],[name=solidarity_mobile],[name=phone],[name=mobile_number]', function(e) {
            var mobile = $(this).val();
            if (mobile) {
                $(this).val(trimNumber(mobile));
            }
        });
    </script>


    <script type="text/javascript">
        // Banner Range Slider JS

        $(function() {

            var tooltip = $('<div id="tooltip" />').css({

                position: 'absolute',

                top: 0,

                left: 40

            }).hide();

            tooltip.text($('#storlek_testet').val());

            $("#storlekslider").slider({

                range: "max",

                min: 1000,

                max: 20000,

                value: 20000,

                slide: function(event, ui) {

                    tooltip.text(ui.value);

                    $("#storlek_testet").val(+ui.value);

                    $(ui.value).val($('#storlek_testet').val());

                },

                change: function(event, ui) {}

            }).find(".ui-slider-handle").append(tooltip).hover(function() {

                tooltip.show()

            }, function() {

                tooltip.hide()

            })

            $("#storlek_testet").keyup(function() {

                $("#storlekslider").slider("value", $(this).val())

            });

        });



        $(function() {

            var tooltip = $('<div id="tooltip" />').css({

                position: 'absolute',

                top: 0,

                left: 40

            }).hide();

            tooltip.text($('#storlek_testet2').val());

            $("#storlekslider2").slider({

                range: "max",

                min: 1000,

                max: 20000,

                value: 20000,

                slide: function(event, ui) {

                    tooltip.text(ui.value);

                    $("#storlek_testet2").val(+ui.value);

                    $(ui.value).val($('#storlek_testet2').val());

                },

                change: function(event, ui) {}

            }).find(".ui-slider-handle").append(tooltip).hover(function() {

                tooltip.show()

            }, function() {

                tooltip.hide()

            })

            $("#storlek_testet2").keyup(function() {

                $("#storlekslider2").slider("value", $(this).val())

            });

        });







        $(function() {

            var tooltip = $('<div id="tooltip" />').css({

                position: 'absolute',

                top: 0,

                left: 40

            }).hide();

            tooltip.text($('#storlek_testet3').val());

            $("#storlekslider3").slider({

                range: "max",

                min: 0,

                max: 100,

                value: 100,

                slide: function(event, ui) {

                    tooltip.text(ui.value);

                    $("#storlek_testet3").val(+ui.value);

                    $(ui.value).val($('#storlek_testet3').val());

                },

                change: function(event, ui) {}

            }).find(".ui-slider-handle").append(tooltip).hover(function() {

                tooltip.show()

            }, function() {

                tooltip.hide()

            })

            $("#storlek_testet3").keyup(function() {

                $("#storlekslider3").slider("value", $(this).val())

            });

        });





        $(function() {

            var tooltip = $('<div id="tooltip" />').css({

                position: 'absolute',

                top: 0,

                left: 40

            }).hide();

            tooltip.text($('#storlek_testet4').val());

            $("#storlekslider4").slider({

                range: "max",

                min: 0,

                max: 20,

                value: 20,

                slide: function(event, ui) {

                    tooltip.text(ui.value);

                    $("#storlek_testet4").val(+ui.value);

                    $(ui.value).val($('#storlek_testet4').val());

                },

                change: function(event, ui) {}

            }).find(".ui-slider-handle").append(tooltip).hover(function() {

                tooltip.show()

            }, function() {

                tooltip.hide()

            })

            $("#storlek_testet4").keyup(function() {

                $("#storlekslider4").slider("value", $(this).val())

            });

        });





        $(function() {

            var tooltip = $('<div id="tooltip" />').css({

                position: 'absolute',

                top: 0,

                left: 40

            }).hide();

            tooltip.text($('#storlek_testet5').val());

            $("#storlekslider5").slider({

                range: "max",

                min: 0,

                max: 20000,

                value: 20000,

                slide: function(event, ui) {

                    tooltip.text(ui.value);

                    $("#storlek_testet5").val(+ui.value);

                    $(ui.value).val($('#storlek_testet5').val());

                },

                change: function(event, ui) {}

            }).find(".ui-slider-handle").append(tooltip).hover(function() {

                tooltip.show()

            }, function() {

                tooltip.hide()

            })

            $("#storlek_testet5").keyup(function() {

                $("#storlekslider5").slider("value", $(this).val())

            });

        });
    </script>
    <script type="text/javascript">
        jQuery(function(e) {
            const site_lang = 'en';
            console.log(site_lang);

            if ($('.new-headlines .marquee').length > 0) {
                $('.new-headlines .marquee').marquee({
                    allowCss3Support: true,
                    css3easing: 'linear',
                    easing: 'linear',
                    delayBeforeStart: 100,
                    direction: 'left',
                    duplicated: false,
                    duration: 10000,
                    gap: 1,
                    pauseOnCycle: false,
                    pauseOnHover: true,
                    startVisible: false
                });
            }

            $(document).on('click', '#newsLetterSaveBtn', function(e) {
                e.preventDefault();
                var btn = $(this);

                //Validate email
                if (!$.trim($('#newsletter_from [name="email"]').val())) {
                    alert("{{ MyHelpers::guest_trans('Email Required') }}");
                    return false;
                }

                var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                if (!regex.test($.trim($('#newsletter_from [name="email"]').val()))) {
                    alert("{{ MyHelpers::guest_trans('Invalid Email') }}");
                    return false;
                }

                $.ajax({
                    /*    dataType: 'json',
                        type: 'POST',
                        url: "/#",
                        data: $('#newsletter_from').serialize(),
                        beforeSend: function() {
                            btn.attr('disabled', true);
                        },
                        error: function(jqXHR, exception) {
                            btn.attr('disabled', false);
                            var msg = formatErrorMessage(jqXHR, exception);
                            alert(msg);
                        },
                        success: function(data) {
                            btn.attr('disabled', false);
                            if (data.status == 1) {
                                $('#newsletter_from')[0].reset();
                            }
                            alert(data.msg);

                        }
                        */
                });
            });
        });
    </script>
    @yield('scripts')

</body>

</html>