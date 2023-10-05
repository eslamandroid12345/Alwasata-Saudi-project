<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <link rel="stylesheet" href="{{ url('assest/css/bootstrap-rtl.css') }}">
    <link rel="stylesheet" href="{{ url('assest/css/all.min.css') }}"> --}}

    <link rel="stylesheet" href="{{url('themes/theme1/assets/css/plugin.min.css')}}" />
    <link rel="stylesheet" href="{{url('themes/theme1/assets/css/main.css')}}" />

    <link rel="stylesheet" href="{{ url('assest/css/fontawesome.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ url('assest/css/style.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ url('themes/theme1/assets/css/style.css') }}"> --}}
    <link rel="stylesheet" href="{{ url('assest/css/calculater.css') }}">
    <link rel="stylesheet" href="{{ url('assest/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ url('assest/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ url('assest/css/jquery.fancybox.min.css') }}">
    <link rel="stylesheet" href="{{ url('assest/css/animate.css') }}">

    <link rel="stylesheet" href="{{ url('themes/theme1/assets/css/dt-style.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}"> --}}
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>

    <!-- Title Page-->
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/tokenize2.min.css') }}">
    <style>
        .tableUserOption .addBtn button {
            background: #E67681;
        }

        button.mov {
            background-color: #887BFF !important;
        }

        .rounded {
            border-radius: 5px;
        }

        .w-btn.rounded {
            border-radius: 50px !important;
        }

        .w-btn {
            padding: 10px 40px;
            border: 0;
            background: #0f5b94;
            color: #fff;
            border-radius: 5px;
            display: inline-block;
            cursor: pointer;
            outline: 0;
            transition: 0.3s;
            box-shadow: 0px 4px 15px #00000021;
        }

        table.data-table td:last-child {
            /*text-align: left;*/
        }

        button:disabled {
            cursor: not-allowed;
        }

        .HomePage .sidePar {
            z-index: 5;
        }

        .table.table-sm thead th:not(.sorting),
        .table.table-sm thead td:not(.sorting),
        .table.table-sm tbody th:not(.sorting),
        .table.table-sm tbody td:not(.sorting) {
            padding: 5px 10px;
        }

        .table.table-sm thead th.sorting,
        .table.table-sm thead td.sorting,
        .table.table-sm tbody th.sorting,
        .table.table-sm tbody td.sorting {
            padding: 5px 15px;
        }

        .tFex {
            position: relative !important;
            width: 100% !important;
        }

        .dataTables_filter {
            display: none;
        }

        span.redBg {
            background: #E67681;
        }

        .pointer {
            cursor: pointer;
        }

        .dataTables_info {
            margin-left: 15px;
            font-size: smaller;
        }

        .dataTables_paginate {
            color: #333;
            font-size: smaller;
        }

        .dataTables_paginate, .dataTables_info {
            margin-bottom: 10px;
            margin-top: 10px;
        }

        .middle-screen {
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }

        table {
            width: 100%;
            text-align: center;
        }

        td {
            /*width: 15%;*/
        }

        .reqNum {
            width: 0.5%;
        }

        .reqDate {
            text-align: center;
        }

        .reqType {
            width: 2%;
        }

        .commentStyle {
            max-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        tr:hover td {
            background: #d1e0e0
        }

        .newReq {
            background: rgba(98, 255, 0, 0.4) ! important;
        }

        .needFollow {
            background: rgba(12, 211, 255, 0.3) ! important;
        }

        .noNeed {
            background: rgba(0, 0, 0, 0.2) ! important;
        }

        .wating {
            background: rgba(255, 255, 0, 0.2) ! important;
        }

        .watingReal {
            background: rgba(0, 255, 42, 0.2) ! important;
        }

        .rejected {
            background: rgba(255, 12, 0, 0.2) ! important;
        }
        .tech-support-button{
            position: fixed;
            bottom: 0px;
            left: 0px;
            background: #0f5b94;
            margin: 30px;
            z-index: 1000;
        }
    </style>
@stack('styles')
@yield('css_style')

<!-- JQuery JS-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <!-- Select2 Css-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/3.0.0/select2.css"   />
<link rel="stylesheet" type="text/css" href="{{ url('interface_style/vendor/select2/select2.min.css') }}" />
--}}

<!-- Data Tabel -->
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.11.2/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css"/>

    <!--FOR TOGGLE STYLE-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.css">

    <!-- for hijri datepicker-->
    <link href="{{url('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet"/>

    <style>
        .dataTables_paginate {
            direction: rtl;
        }

        .data-table-parent {
            background-color: white;
            padding: 30px;
        }

        .dataTables_filter label input[type="search"] {
            margin: 0 5px;
            border: 1px solid #ccc;
            padding: 0 5px;
        }

        .dataTables_length label select {
            border: 1px solid #ccc;
        }

        table.dataTable {
            width: 100% !important;
        }


        .countBuble {
            padding: 2px 4px 2px 4px;
            background-color: #235C7A;
            color: white;
            font-size: 0.69em;
            border-radius: 50%;
            box-shadow: 1px 1px 1px gray;
        }

        #note {
            position: absolute;
            font-size: medium;
            z-index: 99999;
            top: 0;
            left: 0;
            right: 0;
            color: aliceblue;
            text-align: center;
            line-height: 2.5;
            overflow: hidden;
            -webkit-box-shadow: 0 0 5px black;
            -moz-box-shadow: 0 0 5px black;
            box-shadow: 0 0 5px black;
        }

        @-webkit-keyframes slideDown {

            0%,
            100% {
                -webkit-transform: translateY(-50px);
            }

            10%,
            90% {
                -webkit-transform: translateY(0px);
            }
        }

        @-moz-keyframes slideDown {

            0%,
            100% {
                -moz-transform: translateY(-50px);
            }

            10%,
            90% {
                -moz-transform: translateY(0px);
            }
        }

        .cssanimations.csstransforms #note {
            -webkit-transform: translateY(-50px);
            -webkit-animation: slideDown 2.5s 1.0s 1 ease forwards;
            -moz-transform: translateY(-50px);
            -moz-animation: slideDown 2.5s 1.0s 1 ease forwards;
        }

        .announce-line {
            float: left;
            display: inline-block;
            padding-left: 25px;
            color: black;
            cursor: pointer;
            font-size: medium;
            text-decoration: underline;
            font-weight: bold;
            margin: auto;
        }

        .announce-line:hover {
            float: left;
            display: inline-block;
            padding-left: 25px;
            color: white;
            text-decoration: underline;
            font-weight: bold;
            margin: auto;

        }

        #attach {
            text-align: center;
            font-size: medium;
            color: cornsilk;
            text-decoration: underline;

        }

        #attach:hover .text-white {
            color: white !important;
        }

        table th[rowspan],
        table td[rowspan] {
            vertical-align: middle;
        }
    </style>

    <style>
        .clearBoth {
            clear: both;
        }

        .select2-request + .select2-container .select2-selection--single {
            height: 43px !important;
        }

        .select2-request + .select2-container {
            width: 100% !important;
        }

        .missedFiledInput {
            border: 3px solid #e67681;
            border-radius: 4px;
            background-color: #ffe6e6;
        }
    </style>
    <link rel="stylesheet" href="{{url('themes/theme1/assets/css/main.css')}}" />
    {{--pwa--}}
    @laravelPWA

</head>

@auth
    <script src="{{ asset('js/enable-push.js') }}" defer></script>
@endauth

<body>

@if (session('EnsureThereIsNoCalculaterSuggestion'))
    <div id="EnsureThereIsNoCalculaterSuggestion"></div>
@endif
<div class="main-app">
    <div class="main-aside-overlay"></div>
    <!-- begin:: header-mobile  -->
    <div class="main-header-mobile">
      <div class="main-header-mobile__logo">
        <a href=""> <img src="/themes/theme1/assets/images/logo.png" alt="" /></a>
      </div>
      <div class="main-header-mobile__toolbar">
        <button class="aside_mobile_toggle main-header-mobile__toggler">
          <div class="fa-solid fa-ellipsis"></div>
        </button>
        <button class="header_mobile_toggle main-header-mobile__toggler"><i class="fas fa-ellipsis-v"></i></button>
      </div>
    </div>
    <!-- end:: header-mobile  -->
    <div class="main-grid--root">
      <!-- begin:: grid-fluid-page -->
      <div class="main-grid-fluid-page">
        <!-- begin:: aside -->
        <div class="main-aside">
          <button class="aside_mobile_close d-lg-none"><i class="fa fa-times"></i></button>
          <div class="main-aside-logo">
            <div class="logo-small"><img src="/themes/theme1/assets/images/logo.png" alt="" /></div>
            <div class="logo-large"><img src="/themes/theme1/assets/images/logo-large.png" alt="" /></div>
          </div>
          @include('themes.theme1.layouts.sideBar')
          {{-- <div class="main-aside-menu-wrapper">
            <div class="main-aside-menu scroll">
              <ul class="main-menu__nav">
                <li class="main-menu__item">
                  <a class="main-menu__link menu-toggle" href="javascript:;">
                    <span class="main-menu__link-icon">
                      <svg id="Group_3186" data-name="Group 3186" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                        <g id="Rectangle_1795" data-name="Rectangle 1795" fill="#fff" stroke="#116a9d" stroke-width="1">
                          <rect width="18" height="18" rx="2" stroke="none"></rect>
                          <rect x="0.5" y="0.5" width="17" height="17" rx="1.5" fill="none"></rect>
                        </g>
                        <g id="Group_3185" data-name="Group 3185" transform="translate(3.18 4.5)">
                          <line id="Line_4" data-name="Line 4" x2="11.64" fill="none" stroke="#116a9d" stroke-linecap="round" stroke-width="1"></line>
                          <line id="Line_5" data-name="Line 5" x2="11.64" transform="translate(0 3)" fill="none" stroke="#116a9d" stroke-linecap="round" stroke-width="1"></line>
                          <line id="Line_6" data-name="Line 6" x2="11.64" transform="translate(0 6)" fill="none" stroke="#116a9d" stroke-linecap="round" stroke-width="1"></line>
                          <line id="Line_7" data-name="Line 7" x2="11.64" transform="translate(0 9)" fill="none" stroke="#116a9d" stroke-linecap="round" stroke-width="1"></line>
                        </g>
                      </svg>
                    </span>
                    <span class="main-menu__link-text">الطلبات</span>
                    <span class="main-menu__ver-arrow">
                      <svg xmlns="http://www.w3.org/2000/svg" width="6.291" height="11.003" viewBox="0 0 6.291 11.003">
                        <path
                          id="Icon_ionic-ios-arrow-back"
                          data-name="Icon ionic-ios-arrow-back"
                          d="M1.9,5.5,6.06,1.34A.786.786,0,0,0,4.946.23L.229,4.943A.785.785,0,0,0,.206,6.028l4.737,4.746a.786.786,0,0,0,1.114-1.11Z"
                          transform="translate(0)"
                          fill="#2c2c2c"
                        ></path>
                      </svg>
                    </span>
                  </a>
                  <div class="menu-submenu">
                    <ul class="menu-subnav">
                      <li class="main-menu__item">
                        <a class="main-menu__link" href="">
                          <div class="main-menu__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="4" height="8" viewBox="0 0 4 8">
                              <path id="Icon_ionic-md-arrow-dropleft" data-name="Icon ionic-md-arrow-dropleft" d="M17.5,9l-4,4,4,4Z" transform="translate(-13.5 -9)" fill="#798992"></path>
                            </svg>
                          </div>
                          <span class="main-menu__link-text">جميع الطلبات</span>
                        </a>
                      </li>
                      <li class="main-menu__item">
                        <a class="main-menu__link" href="">
                          <div class="main-menu__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="4" height="8" viewBox="0 0 4 8">
                              <path id="Icon_ionic-md-arrow-dropleft" data-name="Icon ionic-md-arrow-dropleft" d="M17.5,9l-4,4,4,4Z" transform="translate(-13.5 -9)" fill="#798992"></path>
                            </svg>
                          </div>
                          <span class="main-menu__link-text">الطلبات المستلمة</span>
                        </a>
                      </li>
                      <li class="main-menu__item">
                        <a class="main-menu__link" href="">
                          <div class="main-menu__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="4" height="8" viewBox="0 0 4 8">
                              <path id="Icon_ionic-md-arrow-dropleft" data-name="Icon ionic-md-arrow-dropleft" d="M17.5,9l-4,4,4,4Z" transform="translate(-13.5 -9)" fill="#798992"></path>
                            </svg>
                          </div>
                          <span class="main-menu__link-text">الطلبات المتابعة</span>
                        </a>
                      </li>
                      <li class="main-menu__item">
                        <a class="main-menu__link" href="">
                          <div class="main-menu__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="4" height="8" viewBox="0 0 4 8">
                              <path id="Icon_ionic-md-arrow-dropleft" data-name="Icon ionic-md-arrow-dropleft" d="M17.5,9l-4,4,4,4Z" transform="translate(-13.5 -9)" fill="#798992"></path>
                            </svg>
                          </div>
                          <span class="main-menu__link-text">الطلبات المميزة</span>
                        </a>
                      </li>
                      <li class="main-menu__item">
                        <a class="main-menu__link" href="">
                          <div class="main-menu__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="4" height="8" viewBox="0 0 4 8">
                              <path id="Icon_ionic-md-arrow-dropleft" data-name="Icon ionic-md-arrow-dropleft" d="M17.5,9l-4,4,4,4Z" transform="translate(-13.5 -9)" fill="#798992"></path>
                            </svg>
                          </div>
                          <span class="main-menu__link-text">الطلبات المؤرشفة</span>
                        </a>
                      </li>
                      <li class="main-menu__item">
                        <a class="main-menu__link" href="">
                          <div class="main-menu__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="4" height="8" viewBox="0 0 4 8">
                              <path id="Icon_ionic-md-arrow-dropleft" data-name="Icon ionic-md-arrow-dropleft" d="M17.5,9l-4,4,4,4Z" transform="translate(-13.5 -9)" fill="#798992"></path>
                            </svg>
                          </div>
                          <span class="main-menu__link-text">الطلبات المكتملة</span>
                        </a>
                      </li>
                      <li class="main-menu__item">
                        <a class="main-menu__link" href="">
                          <div class="main-menu__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="4" height="8" viewBox="0 0 4 8">
                              <path id="Icon_ionic-md-arrow-dropleft" data-name="Icon ionic-md-arrow-dropleft" d="M17.5,9l-4,4,4,4Z" transform="translate(-13.5 -9)" fill="#798992"></path>
                            </svg>
                          </div>
                          <span class="main-menu__link-text">طلبات الشراء - الدفعة</span>
                        </a>
                      </li>
                      <li class="main-menu__item">
                        <a class="main-menu__link" href="">
                          <div class="main-menu__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="4" height="8" viewBox="0 0 4 8">
                              <path id="Icon_ionic-md-arrow-dropleft" data-name="Icon ionic-md-arrow-dropleft" d="M17.5,9l-4,4,4,4Z" transform="translate(-13.5 -9)" fill="#798992"></path>
                            </svg>
                          </div>
                          <span class="main-menu__link-text">الطلبات الإضافية</span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </li>
                <li class="main-menu__item">
                  <a class="main-menu__link menu-toggle" href="javascript:;">
                    <span class="main-menu__link-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18.574" height="16.641" viewBox="0 0 18.574 16.641">
                        <g id="Group_3168" data-name="Group 3168" transform="translate(0.5 0.5)">
                          <path
                            id="Path_30"
                            data-name="Path 30"
                            d="M14.228,591.566v-2.294a3.022,3.022,0,0,0-3.022-3.022H4.442a3.022,3.022,0,0,0-3.022,3.022v2.294"
                            transform="translate(-1.42 -575.975)"
                            fill="none"
                            stroke="#116a9d"
                            stroke-linecap="round"
                            stroke-miterlimit="10"
                            stroke-width="1"
                          ></path>
                          <circle id="Ellipse_14" data-name="Ellipse 14" cx="3.505" cy="3.505" r="3.505" transform="translate(2.899 0)" fill="none" stroke="#116a9d" stroke-linecap="round" stroke-miterlimit="10" stroke-width="1"></circle>
                          <path
                            id="Path_31"
                            data-name="Path 31"
                            d="M23.58,566.05a3.5,3.5,0,0,1,0,7.01"
                            transform="translate(-12.308 -566.05)"
                            fill="none"
                            stroke="#116a9d"
                            stroke-linecap="round"
                            stroke-miterlimit="10"
                            stroke-width="1"
                          ></path>
                          <path
                            id="Path_32"
                            data-name="Path 32"
                            d="M30.47,585.3s3.23.727,2.747,5.8"
                            transform="translate(-15.693 -575.508)"
                            fill="none"
                            stroke="#116a9d"
                            stroke-linecap="round"
                            stroke-miterlimit="10"
                            stroke-width="1"
                          ></path>
                        </g>
                      </svg>
                    </span>
                    <span class="main-menu__link-text">العملاء</span>
                    <span class="main-menu__ver-arrow">
                      <svg xmlns="http://www.w3.org/2000/svg" width="6.291" height="11.003" viewBox="0 0 6.291 11.003">
                        <path
                          id="Icon_ionic-ios-arrow-back"
                          data-name="Icon ionic-ios-arrow-back"
                          d="M1.9,5.5,6.06,1.34A.786.786,0,0,0,4.946.23L.229,4.943A.785.785,0,0,0,.206,6.028l4.737,4.746a.786.786,0,0,0,1.114-1.11Z"
                          transform="translate(0)"
                          fill="#2c2c2c"
                        ></path>
                      </svg>
                    </span>
                  </a>
                </li>
                <li class="main-menu__item">
                  <a class="main-menu__link menu-toggle" href="javascript:;">
                    <span class="main-menu__link-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18.014" viewBox="0 0 18 18.014">
                        <g id="ticket" transform="translate(-72.188 -72)">
                          <path
                            id="Path_2758"
                            data-name="Path 2758"
                            d="M89.062,76.532a.489.489,0,0,0-.622-.059,1.956,1.956,0,0,1-2.711-2.721.5.5,0,0,0-.059-.617l-.563-.563A1.96,1.96,0,0,0,83.723,72h0a1.959,1.959,0,0,0-1.385.573l-9.582,9.611a1.96,1.96,0,0,0,0,2.765l.377.372a.494.494,0,0,0,.656.034,1.941,1.941,0,0,1,2.736,2.731.489.489,0,0,0,.029.661l.695.695a1.961,1.961,0,0,0,2.77,0h0l9.592-9.592a1.961,1.961,0,0,0,0-2.77ZM79.323,88.747a.981.981,0,0,1-1.385,0l-.4-.4a2.916,2.916,0,0,0-4-4l-.083-.083a.981.981,0,0,1,0-1.385l6.523-6.548,5.882,5.882Zm9.592-9.592-2.369,2.369-5.882-5.887,2.359-2.369a.962.962,0,0,1,.69-.289h0a.978.978,0,0,1,.69.284l.3.3a2.939,2.939,0,0,0,3.93,3.93l.279.279a.976.976,0,0,1,0,1.385Z"
                            transform="translate(0)"
                            fill="#116a9d"
                          ></path>
                          <path
                            id="Path_2759"
                            data-name="Path 2759"
                            d="M169.609,216.41l-2.373-2.374a.981.981,0,0,0-1.385,0h0l-2.315,2.315a.981.981,0,0,0,0,1.385l2.374,2.373a.981.981,0,0,0,1.385,0l2.315-2.315a.969.969,0,0,0,.024-1.375C169.629,216.41,169.624,216.4,169.609,216.41Zm-3,3-2.378-2.373,2.315-2.315h0l2.373,2.373Z"
                            transform="translate(-86.606 -134.813)"
                            fill="#116a9d"
                          ></path>
                        </g>
                      </svg>
                    </span>
                    <span class="main-menu__link-text">التذاكر</span>
                    <span class="main-menu__ver-arrow">
                      <svg xmlns="http://www.w3.org/2000/svg" width="6.291" height="11.003" viewBox="0 0 6.291 11.003">
                        <path
                          id="Icon_ionic-ios-arrow-back"
                          data-name="Icon ionic-ios-arrow-back"
                          d="M1.9,5.5,6.06,1.34A.786.786,0,0,0,4.946.23L.229,4.943A.785.785,0,0,0,.206,6.028l4.737,4.746a.786.786,0,0,0,1.114-1.11Z"
                          transform="translate(0)"
                          fill="#2c2c2c"
                        ></path>
                      </svg>
                    </span>
                  </a>
                </li>
                <li class="main-menu__item">
                  <a class="main-menu__link menu-toggle" href="javascript:;">
                    <span class="main-menu__link-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19">
                        <g id="Analytics_2" data-name="Analytics 2" transform="translate(-1 -1)">
                          <rect
                            id="Rectangle_1796"
                            data-name="Rectangle 1796"
                            width="18"
                            height="18"
                            rx="1.5"
                            transform="translate(1.5 1.5)"
                            fill="none"
                            stroke="#116a9d"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1"
                          ></rect>
                          <rect id="Rectangle_1797" data-name="Rectangle 1797" width="2" height="12" transform="translate(9.5 4.5)" fill="none" stroke="#116a9d" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"></rect>
                          <rect id="Rectangle_1798" data-name="Rectangle 1798" width="2" height="6" transform="translate(14.5 10.5)" fill="none" stroke="#116a9d" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"></rect>
                          <rect id="Rectangle_1799" data-name="Rectangle 1799" width="2" height="9" transform="translate(4.5 7.5)" fill="none" stroke="#116a9d" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"></rect>
                        </g>
                      </svg>
                    </span>
                    <span class="main-menu__link-text">الرسوم البيانية</span>
                    <span class="main-menu__ver-arrow">
                      <svg xmlns="http://www.w3.org/2000/svg" width="6.291" height="11.003" viewBox="0 0 6.291 11.003">
                        <path
                          id="Icon_ionic-ios-arrow-back"
                          data-name="Icon ionic-ios-arrow-back"
                          d="M1.9,5.5,6.06,1.34A.786.786,0,0,0,4.946.23L.229,4.943A.785.785,0,0,0,.206,6.028l4.737,4.746a.786.786,0,0,0,1.114-1.11Z"
                          transform="translate(0)"
                          fill="#2c2c2c"
                        ></path>
                      </svg>
                    </span>
                  </a>
                </li>
                <li class="main-menu__item">
                  <a class="main-menu__link menu-toggle" href="javascript:;">
                    <span class="main-menu__link-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="17.599" height="18.22" viewBox="0 0 17.599 18.22">
                        <g id="chat" transform="translate(-2.1 -1.2)">
                          <path
                            id="Path_2760"
                            data-name="Path 2760"
                            d="M11.565,37.775a3.256,3.256,0,0,0-2.366-1.6c-.769-.207-.739-.5-.71-.739l.059-.118a.029.029,0,0,1,.03-.03c.03-.059.089-.118.118-.177A1.807,1.807,0,0,0,8.9,34.7c.03-.059.03-.118.059-.177,0-.03.03-.059.03-.089l.089-.089.207-.237.03-.03a1.289,1.289,0,0,0,.177-.5v-.089c0-.089.03-.148.03-.237v-.177a.782.782,0,0,0-.3-.621.267.267,0,0,1,.03-.148,2.252,2.252,0,0,0,.089-.621,1.673,1.673,0,0,0-.237-.946,1.881,1.881,0,0,0-.8-.71c-.059-.03-.148-.059-.207-.089a3.218,3.218,0,0,0-1.183-.207H6.655a2.493,2.493,0,0,0-.887.177c-.03.03-.089.03-.089.059a1.023,1.023,0,0,0-.325.3l-.03.059c-.03.059-.03.059-.059.059a.394.394,0,0,1-.148.089.64.64,0,0,0-.355.325,1.889,1.889,0,0,0-.177.651,5.013,5.013,0,0,0,.03.651c0,.089.03.177.03.266v.03a.618.618,0,0,0-.325.621v.207c0,.089.03.177.03.237v.089a1.267,1.267,0,0,0,.148.473c.059.089.089.148.148.177a.784.784,0,0,0,.177.148,2.8,2.8,0,0,0,.325.739v.03l.03.03.059.089.03.03c.03.03.03.03.03.059l.03.03v.03c.03.207.089.5-.71.739a3.529,3.529,0,0,0-2.366,1.6,1.955,1.955,0,0,0-.148.769v.562a.392.392,0,0,0,.385.385h0a.392.392,0,0,0,.385-.385v-.532a1.854,1.854,0,0,1,.059-.473c.266-.621,1.183-.976,1.893-1.183q1.464-.4,1.242-1.6a.657.657,0,0,0-.148-.325l-.03-.03c0-.03-.03-.03-.03-.059L5.738,34.7a3.205,3.205,0,0,1-.237-.562.6.6,0,0,0-.237-.355l-.059-.03c-.03,0-.03-.03-.059-.059a.1.1,0,0,1-.03-.059.42.42,0,0,1-.059-.177l.059-.118c0-.059-.03-.118-.03-.177v-.089h.059l.089-.03a.462.462,0,0,0,.207-.385,1.746,1.746,0,0,0-.059-.414c0-.059-.03-.118-.03-.177a1.766,1.766,0,0,1,0-.562.636.636,0,0,1,.059-.3A1.221,1.221,0,0,0,5.738,31a.68.68,0,0,0,.207-.237l.03-.059a.231.231,0,0,1,.118-.118,2.1,2.1,0,0,1,.621-.118h.207a2.3,2.3,0,0,1,.917.177c.059.03.089.03.148.059a.821.821,0,0,1,.444.385,1.013,1.013,0,0,1,.118.532,2.589,2.589,0,0,1-.059.473,1.006,1.006,0,0,0-.03.3v.089a.505.505,0,0,0,.03.355.529.529,0,0,0,.3.266v.089a.375.375,0,0,1-.03.177.178.178,0,0,1-.03.118.25.25,0,0,1-.059.177l-.03.03-.059.03-.207.207a.879.879,0,0,0-.177.385c0,.03,0,.059-.03.059a2.748,2.748,0,0,1-.148.3c-.03.03-.03.059-.059.089l-.207.3a.557.557,0,0,0-.059.237q-.222,1.2,1.242,1.6a2.793,2.793,0,0,1,1.893,1.183,1.3,1.3,0,0,1,.059.473v.532a.373.373,0,0,0,.385.385h0a.392.392,0,0,0,.385-.385v-.562A1.135,1.135,0,0,0,11.565,37.775Zm-.237,1.568Zm8.37-.8a1.865,1.865,0,0,0-.148-.769,3.256,3.256,0,0,0-2.4-1.6c-.769-.207-.739-.5-.71-.739l.059-.148.03-.03c.03-.059.089-.118.118-.177a2.686,2.686,0,0,0,.207-.414,1.233,1.233,0,0,0,.059-.177V34.4c.03-.03.059-.059.089-.059l.03-.03.03-.03.177-.207.03-.03a1.289,1.289,0,0,0,.177-.5v-.089c0-.089.03-.148.03-.237v-.177a.782.782,0,0,0-.3-.621.267.267,0,0,1,.03-.148,2.607,2.607,0,0,0,.089-.621,1.673,1.673,0,0,0-.237-.946,1.881,1.881,0,0,0-.8-.71v-.03a.5.5,0,0,0-.148-.059,3.113,3.113,0,0,0-1.183-.207H14.67a2.06,2.06,0,0,0-.887.177c-.059.03-.089.059-.118.059a1.55,1.55,0,0,0-.325.3l-.03.089-.03.03a.029.029,0,0,1-.03.03.27.27,0,0,1-.148.089.64.64,0,0,0-.355.325,1.889,1.889,0,0,0-.177.651V32.1c0,.089.03.177.03.266v.03a.618.618,0,0,0-.325.621v.177a.912.912,0,0,0,.03.266v.089a2.216,2.216,0,0,0,.148.473c.059.059.089.148.148.177a.784.784,0,0,0,.177.148,2.43,2.43,0,0,0,.325.739v.03l.089.118.03.03c.03.03.03.03.03.059l.03.059c.03.207.089.5-.71.739a6.474,6.474,0,0,0-1.213.473l-.03.03a3.545,3.545,0,0,1,.5.621,5.782,5.782,0,0,1,.946-.355q1.464-.4,1.242-1.6a.657.657,0,0,0-.148-.325l-.03-.03c0-.03-.03-.03-.03-.059l-.118-.148a3.205,3.205,0,0,1-.237-.562.589.589,0,0,0-.237-.355l-.059-.03a.064.064,0,0,1-.059-.059.1.1,0,0,1-.03-.059.42.42,0,0,1-.059-.177l.089-.148c0-.059-.03-.118-.03-.177v-.089h.059l.089-.03a.4.4,0,0,0,.207-.385,1.79,1.79,0,0,0-.059-.444c0-.059-.03-.089-.03-.148a1.766,1.766,0,0,1,0-.562.742.742,0,0,1,.059-.3h0A1.99,1.99,0,0,0,13.724,31a1.293,1.293,0,0,0,.207-.237l.03-.059a.231.231,0,0,1,.118-.118,1.75,1.75,0,0,1,.592-.118h.207a2.3,2.3,0,0,1,.917.177c.059.03.089.03.148.059a.941.941,0,0,1,.444.385,1.013,1.013,0,0,1,.118.532,2.589,2.589,0,0,1-.059.473.819.819,0,0,0-.03.266v.089a.505.505,0,0,0,.03.355.529.529,0,0,0,.3.266v.089a.375.375,0,0,1-.03.177l-.03.118a.269.269,0,0,1-.089.207l-.03.089-.177.177a.842.842,0,0,0-.177.414c0,.03,0,.03-.03.059a1.363,1.363,0,0,1-.148.3c0,.03-.03.059-.059.089h-.03l-.089.148-.059.089v.059a.557.557,0,0,0-.059.237q-.222,1.2,1.242,1.6A2.793,2.793,0,0,1,18.87,38.1a1.34,1.34,0,0,1,.089.473v.532a.413.413,0,0,0,.089.266.321.321,0,0,0,.266.118h0a.373.373,0,0,0,.385-.385v-.118C19.7,38.6,19.7,38.544,19.7,38.544Z"
                            transform="translate(0 -20.07)"
                            fill="#116a9d"
                          ></path>
                          <path
                            id="Path_2761"
                            data-name="Path 2761"
                            d="M10.973,9.008a.467.467,0,0,1-.473-.473V3.359A2.168,2.168,0,0,1,12.659,1.2h.03c1.183.03,1.923,0,2.78,0h1.508a2.184,2.184,0,0,1,1.331.444,2.135,2.135,0,0,1,.858,1.627A4.13,4.13,0,0,1,18.9,5.1a3.371,3.371,0,0,1-.532.887A3.54,3.54,0,0,1,16.5,7.263a3.207,3.207,0,0,1-1.065.148H12.748L11.683,8.476l-.355.355A.416.416,0,0,1,10.973,9.008Zm1.686-7.069a1.444,1.444,0,0,0-1.449,1.42V7.973l1.035-1.035.03-.03a.584.584,0,0,1,.414-.177h2.751a3.849,3.849,0,0,0,.887-.118,3.017,3.017,0,0,0,1.479-1.035,2.622,2.622,0,0,0,.414-.739A4.216,4.216,0,0,0,18.427,3.3a1.346,1.346,0,0,0-.562-1.094,1.4,1.4,0,0,0-.887-.3H15.5c-.917.03-1.656.03-2.839.03Z"
                            transform="translate(-5.916)"
                            fill="#116a9d"
                          ></path>
                          <path
                            id="Path_2762"
                            data-name="Path 2762"
                            d="M41.568,3.511V8.6a.424.424,0,0,1-.148.325.445.445,0,0,1-.325.118.62.62,0,0,1-.325-.118l-.562-.562c-.3-.3-.562-.562-.769-.739H36.421a1.109,1.109,0,0,1-.325-.03l-.3-.03a5.069,5.069,0,0,0,.739-.651h2.928a.584.584,0,0,1,.414.177l.03.03.562.562.266.266.089.059v-4.5A1.3,1.3,0,0,0,39.5,2.21H37.279a2.04,2.04,0,0,0-.532-.71h2.78A2.035,2.035,0,0,1,41.568,3.511Z"
                            transform="translate(-23.732 -0.211)"
                            fill="#116a9d"
                          ></path>
                        </g>
                      </svg>
                    </span>
                    <span class="main-menu__link-text">محادثات العملاء</span>
                    <span class="main-menu__ver-arrow">
                      <svg xmlns="http://www.w3.org/2000/svg" width="6.291" height="11.003" viewBox="0 0 6.291 11.003">
                        <path
                          id="Icon_ionic-ios-arrow-back"
                          data-name="Icon ionic-ios-arrow-back"
                          d="M1.9,5.5,6.06,1.34A.786.786,0,0,0,4.946.23L.229,4.943A.785.785,0,0,0,.206,6.028l4.737,4.746a.786.786,0,0,0,1.114-1.11Z"
                          transform="translate(0)"
                          fill="#2c2c2c"
                        ></path>
                      </svg>
                    </span>
                  </a>
                </li>
                <li class="main-menu__item">
                  <a class="main-menu__link menu-toggle" href="javascript:;">
                    <span class="main-menu__link-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="17.599" height="17.468" viewBox="0 0 17.599 17.468">
                        <g id="_Group_" data-name=" Group " transform="translate(-38 -39.966)">
                          <path
                            id="_Compound_Path_"
                            data-name=" Compound Path "
                            d="M53.721,39.966a1.884,1.884,0,0,0-1.864,1.639c-.741.56-4.107,2.964-7.288,2.964H40a2.006,2.006,0,0,0-2,2.008v3.132a2,2,0,0,0,1.99,2h.268l1.309,4.272a2,2,0,1,0,3.843-1.1,13.524,13.524,0,0,1-.432-3.144c3.185.182,6.2,2.409,6.882,2.944a1.876,1.876,0,0,0,3.738-.24V41.85a1.88,1.88,0,0,0-1.876-1.884Zm-9.546,10.94H40.987v-5.53h3.189Zm-5.368-1.2V46.584A1.2,1.2,0,0,1,40,45.376h.184v5.53H40a1.189,1.189,0,0,1-1.2-1.181Zm5.066,6.822a1.183,1.183,0,0,1-.932-.061,1.2,1.2,0,0,1-.6-.722L41.1,51.713h3.069a14.457,14.457,0,0,0,.46,3.375,1.2,1.2,0,0,1-.758,1.443Zm1.109-5.6V45.362a12.081,12.081,0,0,0,5.044-1.622,19.53,19.53,0,0,0,1.818-1.127V53.661A17.7,17.7,0,0,0,50.1,52.554,12.316,12.316,0,0,0,44.983,50.93Zm9.808,3.513a1.07,1.07,0,1,1-2.139,0V41.849a1.07,1.07,0,0,1,2.139,0Z"
                            transform="translate(0)"
                            fill="#116a9d"
                          ></path>
                        </g>
                      </svg>
                    </span>
                    <span class="main-menu__link-text">سجل التعميمات</span>
                    <span class="main-menu__ver-arrow">
                      <svg xmlns="http://www.w3.org/2000/svg" width="6.291" height="11.003" viewBox="0 0 6.291 11.003">
                        <path
                          id="Icon_ionic-ios-arrow-back"
                          data-name="Icon ionic-ios-arrow-back"
                          d="M1.9,5.5,6.06,1.34A.786.786,0,0,0,4.946.23L.229,4.943A.785.785,0,0,0,.206,6.028l4.737,4.746a.786.786,0,0,0,1.114-1.11Z"
                          transform="translate(0)"
                          fill="#2c2c2c"
                        ></path>
                      </svg>
                    </span>
                  </a>
                </li>
                <li class="main-menu__item">
                  <a class="main-menu__link menu-toggle" href="javascript:;">
                    <span class="main-menu__link-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="17.599" height="17.599" viewBox="0 0 17.599 17.599">
                        <path
                          id="support"
                          d="M20.746,12.41v-.463a6.947,6.947,0,1,0-13.894,0v.463A1.852,1.852,0,0,0,5,14.262v2.779a1.852,1.852,0,0,0,1.852,1.852h.083a1.389,1.389,0,0,0,2.7-.463V12.873a1.389,1.389,0,0,0-1.852-1.311,6.021,6.021,0,0,1,12,0,1.389,1.389,0,0,0-1.815,1.311v5.557a1.389,1.389,0,0,0,1.389,1.389h.153a3.242,3.242,0,0,1-2.932,1.852H15.189v-.463A1.389,1.389,0,1,0,13.8,22.6h2.779a4.168,4.168,0,0,0,4.14-3.7h.028A1.852,1.852,0,0,0,22.6,17.041V14.262A1.852,1.852,0,0,0,20.746,12.41ZM5.926,17.041V14.262a.926.926,0,0,1,.926-.926v4.631A.926.926,0,0,1,5.926,17.041ZM8.242,12.41a.463.463,0,0,1,.463.463v5.557a.463.463,0,1,1-.926,0V12.873A.463.463,0,0,1,8.242,12.41ZM13.8,21.672a.463.463,0,1,1,.463-.463v.463Zm5.557-2.779a.463.463,0,0,1-.463-.463V12.873a.463.463,0,0,1,.926,0v5.557A.463.463,0,0,1,19.357,18.894Zm2.316-1.852a.926.926,0,0,1-.926.926V13.336a.926.926,0,0,1,.926.926Z"
                          transform="translate(-5 -5)"
                          fill="#116a9d"
                        ></path>
                      </svg>
                    </span>
                    <span class="main-menu__link-text">الدعم الفني</span>
                    <span class="main-menu__ver-arrow">
                      <svg xmlns="http://www.w3.org/2000/svg" width="6.291" height="11.003" viewBox="0 0 6.291 11.003">
                        <path
                          id="Icon_ionic-ios-arrow-back"
                          data-name="Icon ionic-ios-arrow-back"
                          d="M1.9,5.5,6.06,1.34A.786.786,0,0,0,4.946.23L.229,4.943A.785.785,0,0,0,.206,6.028l4.737,4.746a.786.786,0,0,0,1.114-1.11Z"
                          transform="translate(0)"
                          fill="#2c2c2c"
                        ></path>
                      </svg>
                    </span>
                  </a>
                </li>
              </ul>
            </div>
          </div> --}}
        </div>
        <!-- end:: aside -->
        <div class="main-grid-content-page">
          <!-- begin:: header -->
          @include('themes.theme1.layouts.topSide')
          <!-- end:: header -->
          <!-- begin:: main-content-page-grid -->
          <div class="main-content-page-grid">
            <div class="top-header bg-white mb-3 px-4 py-3">
              <div class="d-lg-flex align-items-center justify-content-between">
                <ol class="breadcrumb mb-lg-0">
                  <li class="breadcrumb-item"><a href="#">الرئيسية </a></li>
                  <li class="breadcrumb-item active">{{isset($title)? $title : 'Page Title'}}</li>
                </ol>
                    <div class="table-action d-flex align-items-center flex-wrap">
                        @yield('nav_actions')
                    </div>

              </div>
            </div>
            <div class="main-content-page">
              <!-- begin::row  -->
              @if (0)
                <div class="row mb-4">
                    <div class="col-12">
                    <div class="favorites bg-white rounded border p-2 p-0">
                        <div class="widget-favorite d-inline-flex align-items-center bg-primary rounded-5 px-3 py-2 mb-1 ms-2">
                        <div class="widget-icon ms-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13.756" height="13.131" viewBox="0 0 13.756 13.131">
                            <path
                                id="Icon_feather-star"
                                data-name="Icon feather-star"
                                d="M9.378,3l1.971,3.993,4.407.644-3.189,3.106.753,4.388L9.378,13.058,5.436,15.131l.753-4.388L3,7.637l4.407-.644Z"
                                transform="translate(-2.5 -2.5)"
                                fill="#fff"
                                stroke="#fff"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1"
                            ></path>
                            </svg>
                        </div>
                        <h6 class="widget-title font-medium text-white">احمد قاسم</h6>
                        </div>
                        <div class="widget-favorite d-inline-flex align-items-center bg-primary rounded-5 px-3 py-2 mb-1 ms-2">
                        <div class="widget-icon ms-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13.756" height="13.131" viewBox="0 0 13.756 13.131">
                            <path
                                id="Icon_feather-star"
                                data-name="Icon feather-star"
                                d="M9.378,3l1.971,3.993,4.407.644-3.189,3.106.753,4.388L9.378,13.058,5.436,15.131l.753-4.388L3,7.637l4.407-.644Z"
                                transform="translate(-2.5 -2.5)"
                                fill="#fff"
                                stroke="#fff"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1"
                            ></path>
                            </svg>
                        </div>
                        <h6 class="widget-title font-medium text-white">احمد قاسم</h6>
                        </div>
                    </div>
                    </div>
                </div>

              @endif
              <!-- end::row  -->
              <!-- begin::row  -->
              <div class="row">
                @yield('customer')
              </div>
              <!-- end::row  -->
              @if (Route::currentRouteName() != "agent.addCustomerWithReq")
                <div class="fixed-icon" data-bs-toggle="modal" data-bs-target="#modalAddClient">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25.425" height="21.166" viewBox="0 0 25.425 21.166">
                    <g id="Icon_feather-user-plus" data-name="Icon feather-user-plus" transform="translate(1 1)">
                        <path
                        id="Path_2554"
                        data-name="Path 2554"
                        d="M17.471,28.889v-2.13A4.259,4.259,0,0,0,13.212,22.5H5.759A4.259,4.259,0,0,0,1.5,26.759v2.13"
                        transform="translate(-1.5 -9.723)"
                        fill="none"
                        stroke="#fff"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        ></path>
                        <path
                        id="Path_2555"
                        data-name="Path 2555"
                        d="M15.268,8.759A4.259,4.259,0,1,1,11.009,4.5,4.259,4.259,0,0,1,15.268,8.759Z"
                        transform="translate(-3.023 -4.5)"
                        fill="none"
                        stroke="#fff"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        ></path>
                        <path id="Path_2556" data-name="Path 2556" d="M30,12v6.389" transform="translate(-9.77 -6.676)" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        <path id="Path_2557" data-name="Path 2557" d="M31.889,16.5H25.5" transform="translate(-8.464 -7.982)" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                    </g>
                    </svg>
                </div>
              @endif
              <!-- begin::modal  -->
              <div class="modal fade" id="modalFilter" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">اضافة عميل</h5>
                      <button class="btn-close ms-0 shadow-none" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                      <form action="">
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                              <label>اسم العميل</label>
                              <input class="form-control" type="text" value="123456798" />
                            </div>
                          </div>
                          <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <label>حالة الطلب</label>
                              <select class="selectpicker" data-live-search="true">
                                <option value="1">مكتمل </option>
                                <option value="1">مكتمل </option>
                                <option value="1">مكتمل </option>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <label>راتب العميل</label>
                              <input class="form-control" type="text" value="432" />
                            </div>
                          </div>
                          <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <label>راتب العميل</label>
                              <input class="form-control" type="text" value="432" />
                            </div>
                          </div>
                          <div class="col-12">
                            <div class="form-group">
                              <label>تصنيف استشاري المبيعات</label>
                              <select class="select2 form-control" multiple="multiple">
                                <option value="1" selected="selected">مرفوض </option>
                                <option value="2">يبحث عن عقار </option>
                                <option value="3">مرفوض </option>
                                <option value="4">يبحث عن عقار</option>
                                <option value="1" selected="selected">مرفوض </option>
                                <option value="2">يبحث عن عقار </option>
                                <option value="3">مرفوض </option>
                                <option value="4">يبحث عن عقار </option>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <label>مصدر المعاملة</label>
                              <select class="selectpicker" data-live-search="true">
                                <option value="1">مكتمل </option>
                                <option value="1">مكتمل </option>
                                <option value="1">مكتمل</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <label>الهاتف</label>
                              <input class="form-control" type="text" value="432" />
                            </div>
                          </div>
                          <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <label>تاريخ الميلاد</label>
                              <div class="input-icon">
                                <input class="form-control datetimepicker_1" type="text" />
                                <div class="icon">
                                  <svg id="calendar" xmlns="http://www.w3.org/2000/svg" width="17.396" height="16.989" viewBox="0 0 17.396 16.989">
                                    <path
                                      id="Path_2784"
                                      data-name="Path 2784"
                                      d="M19.1,24.906H6.714a2.5,2.5,0,0,1-2.5-2.5V16.412a.626.626,0,0,1,1.252,0V22.4a1.252,1.252,0,0,0,1.252,1.252H19.1A1.252,1.252,0,0,0,20.354,22.4V11.814A1.252,1.252,0,0,0,19.1,10.562H6.714a1.252,1.252,0,0,0-1.252,1.252V14.28a.626.626,0,0,1-1.252,0V11.814a2.5,2.5,0,0,1,2.5-2.5H19.1a2.5,2.5,0,0,1,2.5,2.5V22.4A2.5,2.5,0,0,1,19.1,24.906Z"
                                      transform="translate(-4.21 -7.917)"
                                      fill="#6c757d"
                                    ></path>
                                    <path
                                      id="Path_2785"
                                      data-name="Path 2785"
                                      d="M18.476,11.849H4.836a.626.626,0,1,1,0-1.252h13.64a.626.626,0,0,1,0,1.252ZM9.23,8.9A.626.626,0,0,1,8.6,8.275V5.486a.626.626,0,1,1,1.252,0V8.275A.626.626,0,0,1,9.23,8.9Zm7.355,0a.626.626,0,0,1-.626-.626V5.486a.626.626,0,1,1,1.252,0V8.275A.626.626,0,0,1,16.585,8.9Z"
                                      transform="translate(-4.21 -4.86)"
                                      fill="#6c757d"
                                    ></path>
                                  </svg>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <label>حالة الملاحظة</label>
                              <select class="selectpicker" data-live-search="true">
                                <option value="1">مكتمل </option>
                                <option value="1">مكتمل </option>
                                <option value="1">مكتمل</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <label>نوع الطلب</label>
                              <select class="selectpicker" data-live-search="true">
                                <option value="1">مكتمل </option>
                                <option value="1">مكتمل </option>
                                <option value="1">مكتمل</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <label>تاريخ الطلب ( من )</label>
                              <div class="input-icon">
                                <input class="form-control datetimepicker_1" type="text" />
                                <div class="icon">
                                  <svg id="calendar" xmlns="http://www.w3.org/2000/svg" width="17.396" height="16.989" viewBox="0 0 17.396 16.989">
                                    <path
                                      id="Path_2784"
                                      data-name="Path 2784"
                                      d="M19.1,24.906H6.714a2.5,2.5,0,0,1-2.5-2.5V16.412a.626.626,0,0,1,1.252,0V22.4a1.252,1.252,0,0,0,1.252,1.252H19.1A1.252,1.252,0,0,0,20.354,22.4V11.814A1.252,1.252,0,0,0,19.1,10.562H6.714a1.252,1.252,0,0,0-1.252,1.252V14.28a.626.626,0,0,1-1.252,0V11.814a2.5,2.5,0,0,1,2.5-2.5H19.1a2.5,2.5,0,0,1,2.5,2.5V22.4A2.5,2.5,0,0,1,19.1,24.906Z"
                                      transform="translate(-4.21 -7.917)"
                                      fill="#6c757d"
                                    ></path>
                                    <path
                                      id="Path_2785"
                                      data-name="Path 2785"
                                      d="M18.476,11.849H4.836a.626.626,0,1,1,0-1.252h13.64a.626.626,0,0,1,0,1.252ZM9.23,8.9A.626.626,0,0,1,8.6,8.275V5.486a.626.626,0,1,1,1.252,0V8.275A.626.626,0,0,1,9.23,8.9Zm7.355,0a.626.626,0,0,1-.626-.626V5.486a.626.626,0,1,1,1.252,0V8.275A.626.626,0,0,1,16.585,8.9Z"
                                      transform="translate(-4.21 -4.86)"
                                      fill="#6c757d"
                                    ></path>
                                  </svg>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <label>تاريخ الطلب ( الى )</label>
                              <div class="input-icon">
                                <input class="form-control datetimepicker_1" type="text" />
                                <div class="icon">
                                  <svg id="calendar" xmlns="http://www.w3.org/2000/svg" width="17.396" height="16.989" viewBox="0 0 17.396 16.989">
                                    <path
                                      id="Path_2784"
                                      data-name="Path 2784"
                                      d="M19.1,24.906H6.714a2.5,2.5,0,0,1-2.5-2.5V16.412a.626.626,0,0,1,1.252,0V22.4a1.252,1.252,0,0,0,1.252,1.252H19.1A1.252,1.252,0,0,0,20.354,22.4V11.814A1.252,1.252,0,0,0,19.1,10.562H6.714a1.252,1.252,0,0,0-1.252,1.252V14.28a.626.626,0,0,1-1.252,0V11.814a2.5,2.5,0,0,1,2.5-2.5H19.1a2.5,2.5,0,0,1,2.5,2.5V22.4A2.5,2.5,0,0,1,19.1,24.906Z"
                                      transform="translate(-4.21 -7.917)"
                                      fill="#6c757d"
                                    ></path>
                                    <path
                                      id="Path_2785"
                                      data-name="Path 2785"
                                      d="M18.476,11.849H4.836a.626.626,0,1,1,0-1.252h13.64a.626.626,0,0,1,0,1.252ZM9.23,8.9A.626.626,0,0,1,8.6,8.275V5.486a.626.626,0,1,1,1.252,0V8.275A.626.626,0,0,1,9.23,8.9Zm7.355,0a.626.626,0,0,1-.626-.626V5.486a.626.626,0,1,1,1.252,0V8.275A.626.626,0,0,1,16.585,8.9Z"
                                      transform="translate(-4.21 -4.86)"
                                      fill="#6c757d"
                                    ></path>
                                  </svg>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <label>تاريخ الإفراغ ( من )</label>
                              <div class="input-icon">
                                <input class="form-control datetimepicker_1" type="text" />
                                <div class="icon">
                                  <svg id="calendar" xmlns="http://www.w3.org/2000/svg" width="17.396" height="16.989" viewBox="0 0 17.396 16.989">
                                    <path
                                      id="Path_2784"
                                      data-name="Path 2784"
                                      d="M19.1,24.906H6.714a2.5,2.5,0,0,1-2.5-2.5V16.412a.626.626,0,0,1,1.252,0V22.4a1.252,1.252,0,0,0,1.252,1.252H19.1A1.252,1.252,0,0,0,20.354,22.4V11.814A1.252,1.252,0,0,0,19.1,10.562H6.714a1.252,1.252,0,0,0-1.252,1.252V14.28a.626.626,0,0,1-1.252,0V11.814a2.5,2.5,0,0,1,2.5-2.5H19.1a2.5,2.5,0,0,1,2.5,2.5V22.4A2.5,2.5,0,0,1,19.1,24.906Z"
                                      transform="translate(-4.21 -7.917)"
                                      fill="#6c757d"
                                    ></path>
                                    <path
                                      id="Path_2785"
                                      data-name="Path 2785"
                                      d="M18.476,11.849H4.836a.626.626,0,1,1,0-1.252h13.64a.626.626,0,0,1,0,1.252ZM9.23,8.9A.626.626,0,0,1,8.6,8.275V5.486a.626.626,0,1,1,1.252,0V8.275A.626.626,0,0,1,9.23,8.9Zm7.355,0a.626.626,0,0,1-.626-.626V5.486a.626.626,0,1,1,1.252,0V8.275A.626.626,0,0,1,16.585,8.9Z"
                                      transform="translate(-4.21 -4.86)"
                                      fill="#6c757d"
                                    ></path>
                                  </svg>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <label>تاريخ الإفراغ ( إلى )</label>
                              <div class="input-icon">
                                <input class="form-control datetimepicker_1" type="text" />
                                <div class="icon">
                                  <svg id="calendar" xmlns="http://www.w3.org/2000/svg" width="17.396" height="16.989" viewBox="0 0 17.396 16.989">
                                    <path
                                      id="Path_2784"
                                      data-name="Path 2784"
                                      d="M19.1,24.906H6.714a2.5,2.5,0,0,1-2.5-2.5V16.412a.626.626,0,0,1,1.252,0V22.4a1.252,1.252,0,0,0,1.252,1.252H19.1A1.252,1.252,0,0,0,20.354,22.4V11.814A1.252,1.252,0,0,0,19.1,10.562H6.714a1.252,1.252,0,0,0-1.252,1.252V14.28a.626.626,0,0,1-1.252,0V11.814a2.5,2.5,0,0,1,2.5-2.5H19.1a2.5,2.5,0,0,1,2.5,2.5V22.4A2.5,2.5,0,0,1,19.1,24.906Z"
                                      transform="translate(-4.21 -7.917)"
                                      fill="#6c757d"
                                    ></path>
                                    <path
                                      id="Path_2785"
                                      data-name="Path 2785"
                                      d="M18.476,11.849H4.836a.626.626,0,1,1,0-1.252h13.64a.626.626,0,0,1,0,1.252ZM9.23,8.9A.626.626,0,0,1,8.6,8.275V5.486a.626.626,0,1,1,1.252,0V8.275A.626.626,0,0,1,9.23,8.9Zm7.355,0a.626.626,0,0,1-.626-.626V5.486a.626.626,0,1,1,1.252,0V8.275A.626.626,0,0,1,16.585,8.9Z"
                                      transform="translate(-4.21 -4.86)"
                                      fill="#6c757d"
                                    ></path>
                                  </svg>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <label>جهة العمل</label>
                              <select class="selectpicker" data-live-search="true">
                                <option value="1">مدني </option>
                                <option value="1">مدني </option>
                                <option value="1">مدني</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <label>جهة نزول الراتب</label>
                              <select class="selectpicker" data-live-search="true">
                                <option value="1">بنك الانماء </option>
                                <option value="1">بنك الانماء </option>
                                <option value="1">بنك الانماء</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <label>جهة التمويل</label>
                              <select class="selectpicker" data-live-search="true">
                                <option value="1">بنك الرياض </option>
                                <option value="1">بنك الرياض </option>
                                <option value="1">بنك الرياض</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="form-group mb-0">
                          <button class="btn btn-success w-100 py-2 btn-lg">اضافة</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <!-- end::modal  -->

              <!-- begin::modal  -->
                @include('themes.theme1.Agent.add-customer-modal')
              <!-- end::modal  -->
            </div>
          </div>
          <!-- end:: main-content-page-grid -->
        </div>
      </div>
      <!-- end:: grid-fluid-page -->
    </div>
  </div>
@if(false)
<!-----------Start Home Page---------->
<section class="HomePage mb-5">
    <!-----------Announcement---------->
    @isset($announces)
        @foreach($announces as $announces)
            @if (auth()->user()->role != 13)
                @if( App\Http\Controllers\AdminController::ifThereRoleAndUsersAnnounce($announces->id) == 'true')
                    @if( App\Http\Controllers\AdminController::getSeenAnnounce($announces->id) == 'true')
                        @if( App\Http\Controllers\AdminController::getRoleAnnounce($announces->id) == 'true' || App\Http\Controllers\AdminController::getUsersAnnounce($announces->id) == 'true')
                            @include('themes.theme1.layouts.announce_block')
                            @break;
                        @endif

                    @endif
                @else
                    @if( App\Http\Controllers\AdminController::getSeenAnnounce($announces->id) == 'true')
                        @include('themes.theme1.layouts.announce_block')
                        @break;
                    @endif
                @endif
            @endif
        @endforeach
    @endisset
<!-----------Announcement---------->


    @include('themes.theme1.layouts.sideBar')

    <div class="homeCont">

        @include('themes.theme1.layouts.topSide')

        <div class="toogle">
            <i class="fas fa-exchange-alt"></i>
        </div>

        <div class="container-fluid px-lg-5">
            <div class="ContTabelPage">
                {{--@if(isset($message) && !empty($message))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ $message }}
                    </div>
                @endif
                @if ( session()->has('message') )
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session()->get('message') }}
                    </div>
                @endif
                <div id="msg2" class="alert alert-dismissible" style="display:none;">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>--}}
                @yield('customer')
            </div>
        </div>
    </div>
</section>

<div class="clearBoth"></div>
<div class="footerLast d-block">

    @if (auth()->user() && in_array(auth()->user()->role,  [0,1,2,3,5,6]) && false)
        <button type="button" class="btn btn-primary tech-support-button " data-toggle="modal" data-target="#exampleModal">
            الدعم الفني
            <i class="fa fa-comments" aria-hidden="true"></i>
        </button>

    @endif
    <div class="container  py-5 text-center">
        {{ MyHelpers::admin_trans(auth()->user()->id,'Copyright © 2020 Alwasat. All rights reserved.') }}
        {{ MyHelpers::admin_trans(auth()->user()->id,'Alwasata') }}

    </div>
</div>
<div class="clearBoth"></div>
@endif
{{-- support modal --}}
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">الدعم الفني</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="/techniqal-support" id="techniqal-support" enctype="multipart/form-data" method="post">
            @csrf
            <div class="modal-body">
                {{-- <div class="form-group">
                    {!! Form::label('title', "عنوان الرساله", []) !!}
                    {!! Form::text('title', null, ['class' => 'form-control']) !!}
                </div> --}}
                <div class="form-group">
                    {!! Form::label('title', "نوع الرساله", []) !!}
                    {!! Form::select('msg_type', MyHelpers::TechniqalMsgType(), null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('descrebtion', "نص الرساله", []) !!}
                    {!! Form::textarea('descrebtion', null, ['class' => 'form-control', 'required' => '']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('files', "ارفاق ملفات", []) !!}
                    {!! Form::file('files[]', ['class' => 'form-control', 'multiple' => "multiple", 'accept' => 'jpeg,png,bmp,gif,svg,mp4,qt']) !!}
                </div>
                <p id="message-tech"></p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
              <button type="submit" class="btn btn-primary send-btn-tech">ارسال</button>
            </div>
        </form>
      </div>
    </div>
  </div>
{{-- support modal end --}}
@yield('updateModel')
@yield('confirmMSG')

<script src="{{url('/themes/theme1/assets/js/plugin.min.js')}}"></script>
<script src="{{url('/themes/theme1/assets/js/function.js')}}"></script>

<!-- Jquery JS-->
<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- for hijri datepicker-->
<script src="{{url('js/bootstrap-hijri-datetimepicker.min.js')}}"></script>

<!-- App JS-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- <script src="{{ url('assest/js/bootstrap.bundle.js') }}"></script> old --}}
<script src="{{ url('assest/js/owl.carousel.min.js') }}"></script>
<script src="{{ url('assest/js/owl-Function.js') }}"></script>
<script src="{{ url('assest/js//jquery.fancybox.min.js') }}"></script>
<script src="{{ url('assest/js/function.js') }}"></script>
<script src="{{ url('assest/js/wow.min.js') }}"></script>

<script src="{{ url('assest/js/popper.min.js') }}"></script>
<script src="{{ url('assest/js/bootstrap.min.js') }}"></script>

<!-- Sweet Alerts-->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<!-- DataTabel JS-->
<script src="https://cdn.datatables.net/1.11.2/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>

<!-- TOGGLE STYLE-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js"></script>
<script src="{{ asset('js/tokenize2.min.js') }}"></script>

<script>
    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    $(document).ready(function () {
        $('.select2-request').select2();
        if ($("#EnsureThereIsNoCalculaterSuggestion").length != 0) {

            swal({
                title: "تنبيه!",
                text: " {{ session('EnsureThereIsNoCalculaterSuggestion') }}",
                icon: 'error',
                button: 'موافق',
            });

        }
    });

    function chbx_toggle(source) {
        var checkboxes = document.querySelectorAll('input[type="checkbox"]');
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i] != source)
                checkboxes[i].checked = source.checked;
        }
    }

    function getReqests() {
        var array = []
        var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].value != "on" && checkboxes[i].value != "off") {
                var val = parseInt(checkboxes[i].value);
                array.push(val);
            }
        }
        console.log(array);
        alert(array);
    }

    $(document).on('click', '#close', function (e) {

        var id = $(this).attr('data-id');

        $.get("{{ route('seenAnnounce') }}", {
            id: id
        }, function (data) { // we pass var id in the request with id as name ; we use $.post : because we pass our data to server and got response

            if (data.status == 1) {

                console.log(data.message);
                note = document.getElementById("note");
                note.style.display = 'none';

            } else {

                alert(data.message);
            }


        })


    });

    function numberWithCommas(number) {
        return number
            .replace(/\D/g, "")
            .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function check_rejection(that) {
        var val = that.value;
        if (val == 16) {
            $('#reasonOfCancelation').css('display', 'block');
        } else {
            $('#reasonOfCancelation').css('display', 'none');
        }
    }

    function addReqToNeedActionReqFromAdmin(id) {
        swal({
            title: 'هل أنت متأكد',
            icon: 'warning',
            buttons: ["إلغاء", "نعم"],
        }).then(function (inputValue) {
            if (inputValue != null) {
                $.ajax({
                    url: "{{ route('admin.addToNeedActionReq') }}",
                    type: "GET",
                    data: {
                        'id': id
                    },
                    success: function (data) {

                        swal({
                            title: 'تم!',
                            text: data.message,
                            icon: 'success',
                            timer: '2500'
                        })
                    },
                    error: function () {
                        swal({
                            title: 'خطأ',
                            text: data.message,
                            icon: 'error',
                            timer: '2500'
                        })
                    }
                });
            } else {
            }

        });
    }

    function addReqToNeedActionReqFromAdminArray(array) {
        swal({
            title: 'هل انت متأكد',
            icon: 'warning',
            buttons: ["إلغاء", "نعم"],
        }).then(function (inputValue) {
            if (inputValue != null) {
                $.ajax({
                    url: "{{ route('admin.addToNeedActionReqArray') }}",
                    type: "GET",
                    data: {
                        'array': array
                    },
                    beforeSend: function () {
                        $('#submitMove3').attr("disabled", true);
                        $("#submitMove3").html("<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Please Wait') }}");
                    },
                    success: function (data) {

                        console.log(data);

                        swal({
                            title: 'تم!',
                            text: data.message,
                            icon: 'success',
                            timer: '2500'
                        });

                        $("#submitMove3").html("تحويل");
                        $('#submitMove3').attr("disabled", false);
                    },
                    error: function (data) {
                        console.log(data);

                        swal({
                            title: 'خطأ',
                            text: data.message,
                            icon: 'error',
                            timer: '2500'
                        });

                        $("#submitMove3").html("تحويل");
                        $('#submitMove3').attr("disabled", false);
                    }
                });
            } else {

            }

        });
    }

    function changeRole(val) {
        const elm = $('.bank-delegate')
        const bank = $("#bank_id")
        const subdomain = $("#subdomain")

        if (parseInt(val) === 13) {
            elm.removeClass('d-none')
            // bank.prop('required', !0)
            // subdomain.prop('required', !0)
        } else {
            elm.addClass('d-none')
            bank.val(null)
            subdomain.val(null)
            // bank.prop('required', !1)
        }
    }

    window.confirmMessage = function (text, title) {
        return swal({
            title: title || "@lang('messages.areYouSure')",
            text,
            icon: "warning",
            dangerMode: true,
            closeOnClickOutside: false,
            closeOnEsc: false,
            buttons: {
                confirm: {
                    text: "@lang('global.yes')",
                    value: true,
                    closeModal: false
                },
                cancel: {
                    text: "@lang('global.cancel')",
                    value: null,
                    closeModal: true,
                    visible: true,
                },
            },
        })
    }
    window.alertSuccess = function (text) {
        swal({
            icon: "success",
            text,
            buttons: {
                confirm: "@lang('global.done')",
            }
        })
    }
    window.alertError = function (text, title) {
        swal({
            icon: "error",
            title,
            text,
            buttons: {
                confirm: "@lang('global.done')",
            }
        })
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Access-Control-Allow-Headers': '*',
        }
    });

    $(document).on('submit', '#techniqal-support', function(e){
        e.preventDefault();
        // var postData = new FormData($('#techniqal-support')[0]);
        $('.send-btn-tech').html(`جاري الارسال ...`);
        $('.send-btn-tech').prop('disabled', true);
        var postData = new FormData(this);
        $.ajax({
            url : "{{url('/techniqal-support')}}",
            type: "POST",
            data : postData,
            processData: false,
            contentType: false,
            success:function(res, textStatus, jqXHR){
                $('.send-btn-tech').html(`ارسال`);
                $('#exampleModal').modal('toggle');
                swal(res['message'], '', "success");
                // $('#message-tech').html(res['message']);
                $('.send-btn-tech').prop('disabled', false);
                $("#techniqal-support input").val(null);
                $("#techniqal-support textarea").val('');
            },
            error: function(res){
                //if fails
                $('#message-tech').html(res['message']);
                $('.send-btn-tech').html(`ارسال`);
                $('.send-btn-tech').prop('disabled', false);
            }
        });
    });
</script>

{{-- By Doaa Alastal  --}}
<!-- This Section created because any script in scripts section can't be implemented  for unknown reason !! -->
<!-- Because this yield not stack A.Fayez !! -->
@yield('js')
{{-- By Doaa Alastal  --}}


@yield('scripts')
@stack('scripts')
</body>

</html>
