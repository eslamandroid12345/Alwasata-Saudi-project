<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ url('assest/css/bootstrap-rtl.css') }}">
    <link rel="stylesheet" href="{{ url('assest/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('assest/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ url('assest/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('assest/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ url('assest/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ url('assest/css/jquery.fancybox.min.css') }}">
    <link rel="stylesheet" href="{{ url('assest/css/animate.css') }}">

    <!-- Title Page-->
    <title> {{ MyHelpers::admin_trans(auth()->user()->id,'Messages') }}</title>

    <link rel="stylesheet" href="{{asset('assest/css/chatStyle.css')}}">

    <style>
        .pointer {
            cursor: pointer;
        }

        .unread {
            background-color: #f16060
        }

        .clearBoth {
            clear: both;
        }

        #loading {
            position: absolute !important;
            width: 100%;
            height: 100%;
            position: fixed;
            background-color: #003b67f7;
            z-index: 99;
            display: grid;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
        }

        #loading img {
            animation: spin 2.5s infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>

    <!-- JQuery JS-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <!-- Select2 Css-->
    <link rel="stylesheet" type="text/css" href="{{ url('interface_style/vendor/select2/select2.min.css') }}" />

    {{--pwa--}}
    @laravelPWA

</head>

@auth
<script src="{{ asset('js/enable-push.js') }}" defer></script>
@endauth

<body>


    <!-----------Start Home Page---------->

    <section class="HomePage mb-5">

        @include('layouts.sideBar')

        <div class="homeCont">

            @include('layouts.topSide')

            <div class="toogle">
                <i class="fas fa-exchange-alt"></i>
            </div>


            <div class="container-fluid px-lg-5">
                <div class="ContTabelPage">

                    <!-- For demo purpose-->
                    <header class="text-center">
                        <h1 class="display-4 text-white">{{ MyHelpers::admin_trans(auth()->user()->id,'Messages') }}</h1>
                        <br>
                    </header>
                    <div class="row rounded-lg overflow-hidden ">
                        <!-- Users box-->

                        <div class="col-12 col-md-6 offset-md-3 px-0">
                            <!--New Message-->
                            <div class="tableAdminOption">
                                <span class="pointer" type="button" role="button" data-toggle="modal" data-target="#NewMessage" id="NewMessageBtn">
                                    <i class="fas fa-plus"></i>
                                </span>
                            </div>
                            <!--New Message-->
                            <br>


                            <div class="bg-white">

                                <div class="bg-gray px-4 py-2 bg-light">
                                    <p class="h5 mb-0 py-1">
                                        احدث الرسائل
                                    </p>

                                    @if($unread_conversions == 0)
                                    <p>{{ MyHelpers::admin_trans(auth()->user()->id,'You dont have new messages') }}</p>
                                    @else
                                    <p>{{ MyHelpers::admin_trans(auth()->user()->id,'You have') }} {{ MyHelpers::admin_trans(auth()->user()->id,'new messages') }}
                                        <span>{{$unread_conversions}}</span>
                                        {{ MyHelpers::admin_trans(auth()->user()->id,'conversions') }}
                                    </p>
                                    @endif
                                </div>


                                <div class="messages-box" id="list-loading">
                                    <div id="loading"><img src="{{ url('assest/images/loadingLogo.png') }}" alt=""> </div>
                                </div>

                                <div class="messages-box" style="display: none;" id="list-users">
                                    <div class="list-group rounded-0 correspondents" id="correspondents">
                                        {{--/ Edited By Doaa Alastal /--}}
                                        {{--/ Code will be call from ajax request ( in chat-sub view ) /--}}
                                    </div>
                                </div>

                            </div>


                        </div>
                        <!-- Chat Box-->

                    </div>

                </div>
            </div>

        </div>
    </section>


    <div class="clearBoth"></div>
    <div class="footerLast d-block">
        <div class="container  py-5 text-center">
            {{ MyHelpers::admin_trans(auth()->user()->id,'Copyright © 2020 Alwasat. All rights reserved.') }}
            {{ MyHelpers::admin_trans(auth()->user()->id,'Alwasata') }}

        </div>
    </div>
    <div class="clearBoth"></div>

    <!-- Modal -->
    <div class="modal fade" id="NewMessage" tabindex="-1" role="dialog" aria-labelledby="NewMessage" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <form method="post" action="{{route('newChat')}}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ MyHelpers::admin_trans(auth()->user()->id,'Choose Receivers of your new message') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-left">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="form-group">
                                    <select class="form-control select2" id="receivers" name="receivers[]" multiple>
                                        {{--/ Edited By Doaa Alastal /--}}
                                        {{--/ Code will be call from ajax request /--}}
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="modal-btn-no" class="btn btn-danger" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                        <button type="submit" id="modal-btn-si" class="btn btn-success">{{ MyHelpers::admin_trans(auth()->user()->id,'Open') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Chat') }} </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Model-->




    <script>
        function hideLodingDiv() {
            $('#list-loading').css('display', 'none');
            $('#list-users').css('display', 'block');
        }
    </script>
    <!-- Jquery JS-->
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- App JS-->
    <script src=" {{ url('interface_style/vendor/select2/select2.min.js') }}"></script>
    <script src="{{ url('assest/js/bootstrap.bundle.js') }}"></script>
    <script src="{{ url('assest/js/owl.carousel.min.js') }}"></script>
    <script src="{{ url('assest/js/owl-Function.js') }}"></script>
    <script src="{{ url('assest/js//jquery.fancybox.min.js') }}"></script>
    <script src="{{ url('assest/js/function.js') }}"></script>
    <script src="{{ url('assest/js/wow.min.js') }}"></script>
    <script src="{{ url('assest/js/popper.min.js') }}"></script>
    <script src="{{ url('assest/js/bootstrap.min.js') }}"></script>
    <script>
        $('#NewMessage').on('shown.bs.modal', function() {
            $('#receivers').trigger('focus');
            $('.modal-backdrop.show').removeClass('modal-backdrop');
        });
        $(document).ready(function() {
            console.log('ready');
            $('.ContTabelPage').removeClass('ContTabelPage').addClass('container py-5 px-4');
            $('.homeCont').addClass("noBG");
            $.ajax({
                type: "GET",
                url: "{{route('chat.ajax')}}",
                data: '',
                cache: false,
                beforeSend: function() {
                    console.log('get messages ....');
                },
                success: function(data) {
                    $('#correspondents').html(data);
                },
                error: function(jqXHR) {
                    if (jqXHR.status && (jqXHR.status == 400 || jqXHR.status == 500)) {
                        var result = JSON.parse(jqXHR.responseText);
                    } else {
                    }
                },
            });
            $.ajax({
                type: "GET",
                url: "{{route('chat.receivers.ajax')}}",
                data: '',
                cache: false,
                beforeSend: function() {
                    console.log('get messages ....');
                },
                success: function(data) {
                    var receivers = data;
                    $('#receivers').find('option').remove();
                    var content = '';
                    $.each(receivers, function(index, receiver) {
                        $("#receivers")
                            .append('<option value="' + receiver.id + '"> ' + receiver.name + '</option>');
                    });
                },
                complete: function() {
                    hideLodingDiv();
                },
                error: function(jqXHR) {
                    if (jqXHR.status && (jqXHR.status == 400 || jqXHR.status == 500)) {
                        var result = JSON.parse(jqXHR.responseText);
                    } else {
                    }
                },
            });
        });
    </script>
</body>
</html>
