@extends('layouts.content')
@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Funding') }}
@endsection


@section('css_style')

    <!--NEW 2/2/2020 for hijri datepicker-->
    <link href="{{url('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet"/>


    <style>
        .select2-container--open  {
  z-index: 99999999999999999999;
}
        .clearfix:before,
        .clearfix:after {
            content: " ";
            display: table;
        }

        .clearfix:after {
            clear: both;
        }

        .iconCircle {
            display: inline-block;
            border-radius: 100px;
            box-shadow: 0px 0px 5px green;
            padding: 0.5em 0.6em;
            color: green;

        }

        .iconCircle2 {
            display: inline-block;
            border-radius: 100px;
            box-shadow: 0px 0px 5px #99d6ff;
            padding: 0.5em 0.6em;
            color: #1aa3ff;

        }

        .iconCircle:hover {
            background-color: #009900;
            color: whitesmoke;
        }

        .undoApprove:hover {
            color: black;
            cursor: pointer;
        }

        .custom-control-label {
            position: relative;
            padding-left: 1.8rem;
        }


        .fn {
            color: #ccc;
            text-align: center;
        }


        /*Fun begins*/
        .tab_container {
            width: 90%;
            margin: 0 auto;
            padding-top: 70px;
            position: relative;
        }

        .fnn {
            clear: both;
            padding-top: 10px;
            display: none;
        }

        .fnnn {
            font-weight: 700;
            font-size: 18px;
            display: block;
            float: left;
            /*width: 25%;*/
            padding: 1.5em;
            color: #757575;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            background: #f0f0f0;
        }

        #tab1:checked ~ #content1,
        #tab2:checked ~ #content2,
        #tab3:checked ~ #content3,
        #tab4:checked ~ #content4,
        #tab5:checked ~ #content5 {
            display: block;
            padding: 20px;
            background: #fff;
            color: #999;
            border-bottom: 2px solid #f0f0f0;
        }

        .tab_container .tab-content p,
        .tab_container .tab-content h3 {
            -webkit-animation: fadeInScale 0.7s ease-in-out;
            -moz-animation: fadeInScale 0.7s ease-in-out;
            animation: fadeInScale 0.7s ease-in-out;
        }

        .tab_container .tab-content h3 {
            text-align: center;
        }

        .tab_container [id^="tab"]:checked + label {
            background: #fff;
            box-shadow: inset 0 3px #0CE;
        }

        .tab_container [id^="tab"]:checked + label .fa {
            color: #0CE;
        }

        label .fa {
            font-size: 1.3em;
            margin: 0 0.4em 0 0;
        }

        /*Media query*/
        @media only screen and (max-width: 930px) {
            label span {
                font-size: 14px;
            }

            label .fa {
                font-size: 14px;
            }
        }

        @media only screen and (max-width: 768px) {
            label span {
                display: none;
            }

            label .fa {
                font-size: 16px;
            }

            .tab_container {
                width: 98%;
            }
        }

        /*Content Animation*/
        @keyframes fadeInScale {
            0% {
                transform: scale(0.9);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .progressbar {
            counter-reset: step;
        }

        .progressbar li {
            list-style-type: none;
            width: 25%;
            float: left;
            font-size: 13px;
            position: relative;
            text-align: center;
            text-transform: uppercase;
            color: #7d7d7d;
        }

        .progressbar li:before {
            width: 30px;
            height: 30px;
            content: counter(step);
            counter-increment: step;
            line-height: 30px;
            border: 2px solid #7d7d7d;
            display: block;
            text-align: center;
            margin: 0 auto 10px auto;
            border-radius: 50%;
            background-color: white;
        }

        .progressbar li:after {
            width: 100%;
            height: 3px;
            content: '';
            position: absolute;
            background-color: #7d7d7d;
            top: 15px;
            left: -50%;
            z-index: -1;
        }

        .progressbar li:first-child:after {
            content: none;
        }

        .progressbar li.active {
            color: #256789;
        }

        .progressbar li.active:before {
            border-color: #1D6A96;
        }

        .progressbar li.active + li:after {
            background-color: #1D6A96;
        }

        .vertical-align {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: row;
        }


        .afnan .fa {
            display: inline-block;
            border-radius: 60px;
            /*box-shadow: 0px 0px 2px;*/
            padding: 0.5em 0.5em;
            width: 60px;
            font-size: 30px;
            text-align: center;
            text-decoration: none;
            margin: 5px 8px;
        }

        .afnan .fa:hover {
            opacity: 0.8;
        }

        .fa-star {
            background: #e6e600;
            color: white;
        }

        .fa-times {
            background: #ff3333;
            color: white;
        }

        .fa-refresh {
            background: #0077b3;
            color: white;
        }

        .disabled {
            pointer-events: none;
        }

        .sticky {
            position: fixed;
            top: 70px;
            left: 5px;
            Width: 80%;
            z-index: 99 !important;
            margin: 0 auto;

        }
    </style>

    {{--    NEW STYLE   --}}
    <style>
        .span-20 {
            width: 20px !important;
            height: 20px !important;
            line-height: 20px !important;
            cursor: pointer;
        }

        .i-20 {
            font-size: smaller !important;
        }

        .width-20 {
            width: 20% !important;
            white-space: nowrap;
            padding: 25px 10px !important;
        }

        .no-radius {
            border-radius: 0px !important;
        }

        label .fa {
            margin: 0;
        }

    </style>

@endsection

@section('customer')


    @include('GeneralManager.fundingReq.progress')
    <br>

    <div id="msg2" class="alert alert-dismissible" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>

    </div>

    @if(session()->has('message'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('message') }}
        </div>
    @endif

    @if(session()->has('message2'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('message2') }}
        </div>
    @endif



    <div id="sendingWarning" class="alert alert-info" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>

    <div id="sendingWarning1" class="alert alert-info" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>

    <div id="rejectWarning" class="alert alert-warning" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <div id="rejectWarning1" class="alert alert-warning" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>

    <div id="archiveWarning" class="alert alert-dark" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <div id="archiveWarning1" class="alert alert-dark" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>

    <div id="appWarning" class="alert alert-success" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>

    <div id="appWarning1" class="alert alert-success" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>

    <input type="hidden" value="{{$purchaseCustomer-> is_canceled}}" id="is_canceled">

    <div class="addUser my-4">
        <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
            <h3>
                @if ($purchaseCustomer-> type == 'شراء')

                    @if ($purchaseCustomer-> is_stared == 1)
                        <span> {{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Funding') }}<a href="#" class="fa fa-star" style=" background:white; color:#e6e600"></a></span>
                    @elseif ($purchaseCustomer-> is_canceled == 1)
                        <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Funding') }}<a href="#" class="fa fa-times" style=" background:white; color:#ff3333"></a></span>
                    @elseif ($purchaseCustomer-> is_followed == 1)
                        <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Funding') }}<a href="#" class="fa fa-refresh" style=" background:white; color:#0077b3"></a></span>
                    @else
                        <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Funding') }}</span>
                    @endif


                @elseif ($purchaseCustomer-> type == 'رهن' )

                    @if ($purchaseCustomer-> is_stared == 1)
                        <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage') }}<a href="#" class="fa fa-star" style=" background:white; color:#e6e600"></a></span>
                    @elseif ($purchaseCustomer-> is_canceled == 1)
                        <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage') }}<a href="#" class="fa fa-times" style=" background:white; color:#ff3333"></a></span>
                    @elseif ($purchaseCustomer-> is_followed == 1)
                        <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage') }} <a href="#" class="fa fa-refresh" style=" background:white; color:#0077b3"></a></span>
                    @else
                        <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage') }}</span>
                    @endif


                @elseif ($purchaseCustomer-> type == 'تساهيل')

                    @if ($purchaseCustomer-> is_stared == 1)
                        <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'tasheil') }}<a href="#" class="fa fa-star" style=" background:white; color:#e6e600"></a></span>

                    @elseif ($purchaseCustomer-> is_followed == 1)
                        <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'tasheil') }} <a href="#" class="fa fa-refresh" style=" background:white; color:#0077b3"></a></span>

                    @else
                        <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'tasheil') }}</span>
                    @endif


                @elseif ($purchaseCustomer-> type == 'رهن-شراء')

                    @if ($purchaseCustomer-> is_stared == 1)
                        <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur') }} <a href="#" class="fa fa-star" style=" background:white; color:#e6e600"></a></span>
                    @elseif ($purchaseCustomer-> is_canceled == 1)
                        <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur') }} <a href="#" class="fa fa-times" style=" background:white; color:#ff3333"></a></span>
                    @elseif ($purchaseCustomer-> is_followed == 1)
                        <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur') }} <a href="#" class="fa fa-refresh" style=" background:white; color:#0077b3"></a></span>
                    @else
                        <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur') }} </span>
                    @endif


                @elseif ($purchaseCustomer-> type == 'شراء-دفعة')

                    @if ($purchaseCustomer-> is_stared == 1)
                        <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Purshase-Prepay') }} <a href="#" class="fa fa-star" style=" background:white; color:#e6e600"></a></span>
                    @elseif ($purchaseCustomer-> is_canceled == 1)
                        <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Purshase-Prepay') }} <a href="#" class="fa fa-times" style=" background:white; color:#ff3333"></a></span>
                    @elseif ($purchaseCustomer-> is_followed == 1)
                        <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Purshase-Prepay') }} <a href="#" class="fa fa-refresh" style=" background:white; color:#0077b3"></a></span>
                    @else
                        <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Purshase-Prepay') }} </span>
                    @endif

                @endif
            </h3>
        </div>
    </div>



    <form action="{{ route('general.manager.updateFunding')}}" method="post" novalidate="novalidate" id="frm-update">
        @csrf



        {{--    The ID fixedTop used to make object poition is fixed on to >> you can move it to any object & delete from here--}}
        <div class="tableBar">
            <div class="topRow no-radius">
                <div class="tableUserOption flex-wrap justify-content-md-end  justify-content-center downOrder">
                    <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                        <button type="button" class="mr-3 item DarkRed" role="button" id="needActionReq" onclick="addReqToNeedActionReqFromAdmin({{$id}})" data-id="{{$id}}" title="{{MyHelpers::admin_trans(auth()->user()->id, 'Add to need action req')}}">
                            <i class="fas fa-directions"></i>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Move Reqs To Need Action') }}
                        </button>
                        @if ($purchaseCustomer->type != 'رهن-شراء' && $purchaseCustomer->type != 'شراء-دفعة' && $purchaseCustomer->statusReq != 16 && $purchaseCustomer->statusReq != 15 && $purchaseCustomer->statusReq != 14)
                            <button class="green item item mov" type="button" data-toggle="modal" data-target="#mi-modal7" id="move" data-id="{{$id}}" title="{{MyHelpers::admin_trans(auth()->user()->id, 'Move Req')}}">
                                <i class="fas fa-paper-plane mr-2"></i>
                                {{MyHelpers::admin_trans(auth()->user()->id, 'Move Req')}}
                            </button>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        <div class="tableBar" id="fixedTop">
            <div class="topRow no-radius">
                @if ($reqStatus == 12 || $reqStatus == 23 || $reqStatus == 32)
                    <div class="row align-items-center text-center text-md-left">
                        <div class="col-lg-1"></div>
                        <div class="col-lg-8">
                            <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                                <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                                    <button class="mr-3 warning item" role="button" type="button" id="reject" data-id="{{$id}}">
                                        <i class="fas fa-ban mr-2"></i>
                                        {{ MyHelpers::admin_trans(auth()->user()->id,'Reject The Request') }}
                                    </button>
                                    <button class="mr-3 mov item" role="button" type="submit" id="update" data-id="{{$id}}">
                                        <i class="fas fa-edit mr-2"></i>
                                        {{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}
                                    </button>
                                    <button class="green item" type="button" id="approve" data-id="{{$id}}">
                                        <i class="fas fa-check"></i>
                                        {{ MyHelpers::admin_trans(auth()->user()->id,'approve req') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1"></div>
                    </div>
                @else
                    <div class="row align-items-center text-center text-md-left">
                        <div class="col-lg-1"></div>
                        <div class="col-lg-8">
                            <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                                <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                                    <button disabled style="cursor: not-allowed" class="mr-3 warning item" role="button" type="button" id="reject" data-id="{{$id}}">
                                        <i class="fas fa-ban mr-2"></i>
                                        {{ MyHelpers::admin_trans(auth()->user()->id,'Reject The Request') }}
                                    </button>
                                    <button disabled style="cursor: not-allowed" class="mr-3 mov item" role="button" type="submit" id="update" data-id="{{$id}}">
                                        <i class="fas fa-edit mr-2"></i>
                                        {{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}
                                    </button>
                                    <button disabled style="cursor: not-allowed" class="green item" type="button" id="approve" data-id="{{$id}}">
                                        <i class="fas fa-check"></i>
                                        {{ MyHelpers::admin_trans(auth()->user()->id,'approve req') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1"></div>
                    </div>
                @endif
            </div>
        </div>

        <div class="tableBar">
            <div class="topRow no-radius">
                @if ($reqStatus == 12 || $reqStatus == 23 || $reqStatus == 32)
                    <div class="row align-items-center text-center text-md-left">
                        <div class="col-lg-3">
                            <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                                <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                                    <button class="mr-3 Yellow item" role="button" type="button">
                                        <a href="{{ route('all.reqHistory',$id)}}" target="_blank" class="text-white" style="text-decoration: none">
                                            <i class="fas fa-calendar mr-2"></i>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'request records') }}
                                        </a>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2"></div>
                        <div class="col-lg-7">
                            <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                                <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                                    <button id="archive" class="mr-3 warning item" role="button" type="button">
                                        <a href="{{ route('general.manager.archFunding',$id)}}" class="text-white" style="text-decoration: none">
                                            <i class="fas fa-trash mr-2"></i>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'Archive Request') }}
                                        </a>
                                    </button>

                                    <button class="warning item" type="button" id="cancel" data-id="{{$id}}" title=" {{ MyHelpers::admin_trans(auth()->user()->id,'Cancel Request') }}">
                                        <i class="fas fa-ban"></i>
                                        {{ MyHelpers::admin_trans(auth()->user()->id,'Cancel Request') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row align-items-center text-center text-md-left">
                        <div class="col-lg-3">
                            <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                                <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                                    <button class="mr-3 Yellow item" role="button" type="button">
                                        <a href="{{ route('all.reqHistory',$id)}}" target="_blank" class="text-white" style="text-decoration: none">
                                            <i class="fas fa-calendar mr-2"></i>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'request records') }}
                                        </a>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2"></div>
                        <div class="col-lg-7">
                            <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                                <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                                    <button id="archive" disabled style="cursor: not-allowed" class="mr-3 warning item" role="button" type="button">
                                        <a class="text-white" style="text-decoration: none">
                                            <i class="fas fa-trash mr-2"></i>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'Archive Request') }}
                                        </a>
                                    </button>

                                    <button disabled style="cursor: not-allowed" class="warning item" type="button" id="cancel" data-id="{{$id}}" title=" {{ MyHelpers::admin_trans(auth()->user()->id,'Cancel Request') }}">
                                        <i class="fas fa-ban"></i>
                                        {{ MyHelpers::admin_trans(auth()->user()->id,'Cancel Request') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <section class="new-content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-12">

                        @if ($purchaseCustomer-> type == 'رهن' || $purchaseCustomer-> type == 'تساهيل' )
                            <ul class="list-unstyled d-flex flex-wrab align-items-center justify-content-center topRow">
                                <li id="content5" class="tab width-20 ">
                                    <i class="fas fa-credit-card"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Prepayments and Tasahil') }}
                                </li>
                                <li id="content1" class="tab width-20">
                                    <i class="fas fa-layer-group"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Documents Info') }}
                                </li>
                                <li id="content2" class="tab width-20">
                                    <i class="fas fa-briefcase"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Funding Info') }}
                                </li>
                                <li id="content3" class="tab width-20">
                                    <i class="fas fa-home"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Real Estate Info') }}
                                </li>
                                <li id="content4" class="tab width-20 active-on">
                                    <i class="fas fa-user"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Customer Info') }}
                                </li>
                            </ul>
                        @elseif ( $purchaseCustomer-> type == 'شراء-دفعة' && (!empty ($payment)))
                            <ul class="list-unstyled d-flex flex-wrab align-items-center justify-content-center topRow">
                                <li id="content5" class="tab width-20 ">
                                    <i class="fas fa-credit-card"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Prepayments and Tasahil') }}
                                </li>
                                <li id="content1" class="tab width-20">
                                    <i class="fas fa-layer-group"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Documents Info') }}
                                </li>
                                <li id="content2" class="tab width-20">
                                    <i class="fas fa-briefcase"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Funding Info') }}
                                </li>
                                <li id="content3" class="tab width-20">
                                    <i class="fas fa-home"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Real Estate Info') }}
                                </li>
                                <li id="content4" class="tab width-20 active-on">
                                    <i class="fas fa-user"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Customer Info') }}
                                </li>
                            </ul>
                        @else
                            <ul class="list-unstyled d-flex flex-wrab align-items-center justify-content-center topRow">
                                <li id="content1" class="tab">
                                    <i class="fas fa-layer-group"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Documents Info') }}
                                </li>
                                <li id="content2" class="tab">
                                    <i class="fas fa-briefcase"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Funding Info') }}
                                </li>
                                <li id="content3" class="tab">
                                    <i class="fas fa-home"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Real Estate Info') }}
                                </li>
                                <li id="content4" class="tab active-on">
                                    <i class="fas fa-user"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Customer Info') }}
                                </li>
                            </ul>
                        @endif

                        <div class="tabs-serv">
                            <div class="tab-body">

                                @if (!empty ($payment))
                                    <input value="{{$payment->payStatus}}" id="statusPayment" type="hidden" name="statusPayment"> <!-- To pass prepayment status-->
                                @else
                                    <input value="" id="statusPayment" type="hidden" name="statusPayment">
                                @endif
                                <input value="{{$purchaseCustomer-> type}}" id="typeRequest" type="hidden" name="typeRequest"> <!-- To pass request type-->
                                <input value={{$reqStatus}} id="statusRequest" type="hidden" name="statusRequest"> <!-- To pass request status-->
                                <input value={{$id}} id="reqID" type="hidden" name="reqID"> <!-- To pass request ID-->

                                <div class="row hdie-show display-flex" id="content4-cont">
                                    <div class="col-lg-12   mb-md-0">
                                        @include('GeneralManager.fundingReq.fundingCustomer')
                                    </div>
                                </div>

                                <div class="row hdie-show display-none" id="content1-cont">
                                    <div class="col-lg-12 mb-5 mb-md-0">
                                        @include('GeneralManager.fundingReq.document')
                                    </div>
                                </div>

                                <div class="row hdie-show display-none" id="content2-cont">
                                    <div class="col-lg-12 mb-5 mb-md-0">
                                        @include('GeneralManager.fundingReq.fundingInfo')
                                    </div>
                                </div>

                                <div class="row hdie-show display-none" id="content3-cont">
                                    <div class="col-lg-12 mb-5 mb-md-0">
                                        @include('GeneralManager.fundingReq.fundingreal')
                                    </div>
                                </div>

                                @if ($purchaseCustomer-> type == 'رهن' || $purchaseCustomer-> type == 'تساهيل')
                                    <div class="row hdie-show display-none" id="content5-cont">
                                        <div class="col-lg-12   mb-md-0">
                                            @include('GeneralManager.fundingReq.tsaheel')
                                        </div>
                                    </div>
                                @endif


                                @if ( $purchaseCustomer-> type == 'شراء-دفعة' && (!empty ($payment)))
                                    <div class="row hdie-show display-none" id="content5-cont">
                                        <div class="col-lg-12   mb-md-0">
                                            @include('GeneralManager.fundingReq.updatePage')
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label style="width: 100%; display: block;" for="tab1">
                <span>
                 @include('GeneralManager.fundingReq.fundingReqInfo')
                </span>
                    </label>
                </div>

                <div class="userFormsInfo">
                    <label style="width: 100%; display: block;" for="tab1">
            <span>
                <div class="userFormsContainer mb-3">
                    <div class="userFormsDetails topRow">
                        @if ($reqStatus == 12 || $reqStatus == 23 || $reqStatus == 32)
                            <div class="row">
                            @if ($followdate != null)
                                    <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="follow" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Date') }}</label>
                                        <input id="follow" name="follow" type="date" class="form-control" value="{{ old('follow',$followdate->reminder_date) }}" autocomplete="follow">
                                    </div>
                                </div>
                                    <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="follow1" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Time') }}</label>
                                        <input id="follow1" name="follow1" type="time" class="form-control" value="{{ old('follow1',$followtime) }}" autocomplete="follow1">
                                    </div>
                                </div>
                                @else
                                    <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="follow" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Date') }}</label>
                                    <input id="follow" name="follow" type="date" class="form-control" autocomplete="follow">
                                </div>
                            </div>
                                    <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="follow" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Time') }}</label>
                                    <input id="follow1" name="follow1" type="time" class="form-control" autocomplete="follow1">
                                </div>
                            </div>
                                @endif
                            </div>
                        @else
                            <div class="row">
                                @if ($followdate != null)
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="follow" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Date') }}</label>
                                            <input readonly id="follow" name="follow" type="date" class="form-control" value="{{ old('follow',$followdate->reminder_date) }}" autocomplete="follow">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="follow1" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Time') }}</label>
                                            <input readonly id="follow1" name="follow1" type="time" class="form-control" value="{{ old('follow1',$followtime) }}" autocomplete="follow1">
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="follow" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Date') }}</label>
                                            <input readonly id="follow" name="follow" type="date" class="form-control" autocomplete="follow">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="follow" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Time') }}</label>
                                            <input readonly id="follow1" name="follow1" type="time" class="form-control" autocomplete="follow1">
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                    </div>
                </div>
            </span>
                    </label>
                </div>
            </div>

        </section>

    </form>


@endsection
@section('updateModel')
    @include('GeneralManager.fundingReq.req_records')
    @include('GeneralManager.fundingReq.documentModel')
    @include('Admin.fundingReq.moveReq')
@endsection

@section('confirmMSG')
    @include('GeneralManager.fundingReq.confirmationMsg')
    @include('GeneralManager.fundingReq.confirmSendingMsg')
    @include('GeneralManager.fundingReq.confirmRejectMsg')
@endsection

@push('scripts')
    <script>

        $(document).on('click', '#submitMove', function (e) {
            $('#submitMove').attr("disabled", true);
            document.querySelector('#submitMove').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Please Wait') }}";
            var salesAgent = document.getElementById("salesagent").value;
            var id = "{{$id}}";

            var url = "{{ route('general.manager.moveReqToAnother')}}";

            if (salesAgent != '') {
                $.get(url, {salesAgent, id}, function (data) { //data is array with two veribles (request[], ss)
                    if (data.updatereq == 1) {
                        $('#agentName').html('<label class="form-check-label" > <i class="fas fa-user"></i> استشاري المبيعات :' + data.agentName);
                        $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                    }
                    $('#mi-modal7').modal('hide');
                })

            } else
                document.getElementById('salesagentsError').innerHTML = 'الرجاء اختيار استشاري';
            document.querySelector('#submitMove').innerHTML = "تحويل";
            $('#submitMove').attr("disabled", false);
        });
        $('.select2').select2({dir:"rtl"})
    </script>
@endpush
