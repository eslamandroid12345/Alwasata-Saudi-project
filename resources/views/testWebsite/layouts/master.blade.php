<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ URL::to('website_style/frontend/images/favicon.ico') }}"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="HandheldFriendly" content="true"/>

    <title>@yield('title')</title>
    @laravelPWA
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

<!------------JS AND CSS AND GOOGLE ANALYTIC---------------->

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-89450741-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'UA-89450741-3');
    </script>

    <!-- Google Tag Manager -->
    <script>
        (function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-PM4HZH2');
    </script>
    <!-- End Google Tag Manager -->

    <!------------JS AND CSS AND GOOGLE ANALYTIC---------------->

    <!------------Microsoft code---------------->
    <script>
        (function (w, d, t, r, u) {
            var f, n, i;
            w[u] = w[u] || [], f = function () {
                var o = {
                    ti: "56342411"
                };
                o.q = w[u], w[u] = new UET(o), w[u].push("pageLoad")
            }, n = d.createElement(t), n.src = r, n.async = 1, n.onload = n.onreadystatechange = function () {
                var s = this.readyState;
                s && s !== "loaded" && s !== "complete" || (f(), n.onload = n.onreadystatechange = null)
            }, i = d.getElementsByTagName(t)[0], i.parentNode.insertBefore(n, i)
        })(window, document, "script", "//bat.bing.com/bat.js", "uetq");
    </script>
    <!------------Microsoft code---------------->

    <!--NEW 2/2/2020 for hijri datepicker-->
    <link href="{{url('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet"/>

</head>
@auth
    <script src="{{ asset('js/enable-push.js') }}" defer></script>
@endauth

<body>

<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PM4HZH2" height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->

<!-- Home Menu -->
@yield('homeMenu')
<!-- End Content -->

<!-- Home Menu -->
@yield('pageMenu')
<!-- End Content -->

<!-- Content -->
@yield('content')
<!-- End Content -->

<!-- modal -->
@yield('modal')
<!-- End modal -->

<!-- FOOTER -->

<footer class="wow fadeInUp" data-wow-duration="2s">
    <img src="{{ asset('newWebsiteStyle/images/footer.png') }}" alt="">
    <div class="container">

        <div class="row">

            <div class="col-lg-4 col-md-6 mb-sm-4 mb-lg-0">
                <div class="footer-desc">
                    <h3>الوساطة العقارية</h3>
                    <div class="address d-flex">
                        <i class="fas fa-map-marker-alt mt-1 mr-2"></i>
                        <p>{!! nl2br(__('global.website_address')) !!}
                            <br>
                            <a href="{{url('ar/privacy_policy')}}" style="color:white;">{{__('global.privacy_policy')}}</a> |
                            <a href="{{route('careers')}}" style="color:white;">انضم إلينا</a>
                        </p>
                    </div>
                </div>
            </div>
            <!-- JUST FOR SPACE-->
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0 d-lg-block d-none">

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
    <div class="last">

        <div class="last-text text-center">
            <p>جميع الحقوق محفوظة لـ شركة الوساطة العقارية</p>
        </div>

    </div>
</div>

<!-- FOOTER -->

<script src="{{ asset('newWebsiteStyle/js/jQuery.js') }}"></script>
<script src="{{ asset('newWebsiteStyle/js/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('newWebsiteStyle/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('newWebsiteStyle/js/owl-Function.js') }}"></script>
<script src="{{ asset('newWebsiteStyle/js//jquery.fancybox.min.js') }}"></script>
<script src="{{ asset('newWebsiteStyle/js/function.js') }}"></script>
<script src="{{ asset('newWebsiteStyle/js/wow.min.js') }}"></script>

<script>
    $(document).on('click', '.social-buttons', function (e) {

        var button_id = $(this).attr('id');

        $.get("{{ route('update_soical_clicktimes') }}", {button_id: button_id}, function (data) {
        }).fail(function (data) {

        });


    });
</script>

@yield('scripts')
</body>

</html>
