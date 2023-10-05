@extends('layouts.content')
@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Funding') }}
@endsection


@section('css_style')

<!--NEW 2/2/2020 for hijri datepicker-->
<link href="{{url('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" />


<style>
    .swal-overlay{
        z-index: 99900000;
    }
    .u-bank-action{
        list-style: none;
    }
    .u-bank-action li{
        display: inline-block;
    }
    .u-bank-action li button{
        padding: 2px 10px;
        font-size: 13px;
        border: 0;
        border-radius: 2px;
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

    #tab1:checked~#content1,
    #tab2:checked~#content2,
    #tab3:checked~#content3,
    #tab4:checked~#content4,
    #tab5:checked~#content5 {
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

    .tab_container [id^="tab"]:checked+label {
        background: #fff;
        box-shadow: inset 0 3px #0CE;
    }

    .tab_container [id^="tab"]:checked+label .fa {
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

    .progressbar li.active+li:after {
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

{{-- NEW STYLE   --}}
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


@include('FundingManager.fundingReq.progress')

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

<div id="approveWarning" class="alert alert-success" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>

<div id="appWarning1" class="alert alert-success" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>


<input type="hidden" value="{{$purchaseCustomer-> type}}" id="typeReq">
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


            @elseif ($purchaseCustomer-> type == 'رهن')

            @if ($purchaseCustomer-> is_stared == 1)
            <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage') }}<a href="#" class="fa fa-star" style=" background:white; color:#e6e600"></a></span>
            @elseif ($purchaseCustomer-> is_canceled == 1)
            <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage') }}<a href="#" class="fa fa-times" style=" background:white; color:#ff3333"></a></span>
            @elseif ($purchaseCustomer-> is_followed == 1)
            <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage') }} <a href="#" class="fa fa-refresh" style=" background:white; color:#0077b3"></a></span>
            @else
            <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage') }}</span>
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


<form action="{{ route('funding.manager.updateFunding')}}" method="post" novalidate="novalidate" id="frm-update">
    @csrf

    {{-- The ID fixedTop used to make object poition is fixed on to >> you can move it to any object & delete from here--}}
    <div class="tableBar" id="fixedTop">

            @if ( ($reqStatus == 6 || $reqStatus == 8 || $reqStatus == 13 ) && (empty ($payment) || $payment->payStatus ==9 || $payment->payStatus ==2 || $payment->payStatus ==8) )
            <div class="topRow no-radius">
            <div class="row ">
                <div class="col-lg-12">
                    <div class="tableUserOption   flex-wrap downOrder">
                        <div class="addBtn  mt-lg-12 text-center orderBtns">
                            <button class="mr-3 Yellow item" role="button" type="button">
                                <a href="{{ route('all.reqHistory',$id)}}" target="_blank" class="text-white" style="text-decoration: none">
                                    <i class="fas fa-calendar mr-2"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'request records') }}
                                </a>
                            </button>
                            {{--
                                <button class="mr-3 warning item" id="archive" role="button" type="button">
                                <a href="{{ route('funding.manager.archFunding',$id)}}" class="text-white" style="text-decoration: none">
                            <i class="fas fa-trash mr-2"></i>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Archive Request') }}
                            </a>
                            </button>
                            --}}

                            @if ($reqStatus != 8 )
                            <button class="mr-3 Cloud item" id="archive" role="button" type="button">
                                <a href="{{ route('funding.manager.archFunding',$id)}}" class="text-white" style="text-decoration: none">
                                    <i class="fas fa-retweet"></i>
                                    تحويل الطلب للمتابعة
                                </a>
                            </button>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            </div>

            <div class="topRow no-radius">
            <div class="row ">
                <div class="col-lg-12">
                    <div class="tableUserOption   flex-wrap downOrder">
                        <div class="addBtn  mt-lg-12 text-center orderBtns">

                            <span style="margin-right:250px">
                                <button class="green item" type="submit" id="update" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}">
                                    <i class="fas fa-floppy"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}
                                </button>
                                <button class="warning item" type="button" id="reject" data-id="{{$id}}" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Reject The Request') }}">
                                    <i class="fas fa-ban"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Reject The Request') }}
                                </button>
                                <button class="mov item" type="button" id="send" data-id="{{$id}}" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Send The Request') }}">
                                    <i class="fas fa-paper-plane"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Send The Request') }}
                                </button>
                                @if (app()->environment('production'))
                                <button class="item" type="button" id="send-bank" data-id="{{$id}}" title="">
                                    <i class="fas fa-paper-plane"></i>
                                    ارسال الطلب الي موظف بنك
                                </button>
                            @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            @elseif ( ($reqStatus == 6 || $reqStatus == 8 || $reqStatus == 13 ) && !empty ($payment) && $payment->payStatus != 9 && $payment->payStatus != 2 && $payment->payStatus != 8)
            <div class="topRow no-radius">
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
                <div class="col-lg-5"></div>
                <div class="col-lg-4">
                    <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                        <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                            <button class="green item" type="submit" id="update" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}">
                                <i class="fas fa-floppy"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}
                            </button>
                            <button class="warning item" type="button" id="reject" data-id="{{$id}}" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Reject The Request') }}">
                                <i class="fas fa-ban"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Reject The Request') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            @else
            <div class="topRow no-radius">
            <div class="row ">
                <div class="col-lg-12">
                    <div class="tableUserOption   flex-wrap downOrder">
                        <div class="addBtn  mt-lg-12 text-center orderBtns">
                            <button class="mr-3 Yellow item" role="button" type="button">
                                <a href="{{ route('all.reqHistory',$id)}}" target="_blank" class="text-white" style="text-decoration: none">
                                    <i class="fas fa-calendar mr-2"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'request records') }}
                                </a>
                            </button>
                            {{--
                                <button class="mr-3 warning item" id="archive" role="button" type="button" disabled style="cursor: not-allowed">
                                <a class="text-white" style="text-decoration: none">
                                    <i class="fas fa-trash mr-2"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Archive Request') }}
                                </a>
                            </button>
                            --}}


                            @if ($reqStatus != 8 )
                            <button class="mr-3 Cloud item" id="archive" role="button" type="button" disabled style="cursor: not-allowed">
                                  <i class="fas fa-retweet"></i>
                                    تحويل الطلب للمتابعة
                            </button>
                            @endif


                        </div>
                    </div>
                </div>
            </div>
            </div>

            <div class="topRow no-radius">
            <div class="row ">
                <div class="col-lg-12">
                    <div class="tableUserOption   flex-wrap downOrder">
                        <div class="addBtn  mt-lg-12 text-center orderBtns">

                            <span style="margin-right:250px">
                                <button class="green item" type="submit" id="update" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}" disabled style="cursor: not-allowed">
                                    <i class="fas fa-floppy"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}
                                </button>
                                <button class="warning item" type="button" id="reject" data-id="{{$id}}" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Reject The Request') }}" disabled style="cursor: not-allowed">
                                    <i class="fas fa-ban"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Reject The Request') }}
                                </button>
                                <button class="mov item" type="button" id="send" data-id="{{$id}}" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Send The Request') }}" disabled style="cursor: not-allowed">
                                    <i class="fas fa-paper-plane"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Send The Request') }}
                                </button>
                                @if (app()->environment('production'))
                                    <button class="item" type="button" id="send-bank" data-id="{{$id}}" title="">
                                        <i class="fas fa-paper-plane"></i>
                                        ارسال الطلب الي موظف بنك
                                    </button>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            @endif

    </div>

    @if ( ($reqStatus == 6 || $reqStatus == 8 || $reqStatus == 13 ) )

    <!-- Status req of Sales manager-->


    <!-- Prepayment-->
    @if ($purchaseCustomer-> type == 'رهن-شراء')
    <div class="row">
        <div class="col-4"> </div>
        @if (empty ($purchaseTsa))
        <div class="col-4">
            <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                    <button class="mr-3 item Red2" role="button" type="button">
                        <a href="{{ route('funding.manager.prepayementPage',$id)}}" class="text-white" style="text-decoration: none">
                            <i class="fa fa-credit-card mr-2"></i>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Create Prepaymrnt') }}
                        </a>
                    </button>
                </div>
            </div>
        </div>
        @else
        <div class="col-4">
            <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                    <button class="mr-3 warning item" role="button" type="button">
                        <a href="{{ route('funding.manager.updatePrepaymentPage',$id)}}" class="text-white" style="text-decoration: none">
                            <i class="fa fa-credit-card mr-2"></i>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'View Prepaymrnt') }}
                        </a>
                    </button>
                </div>
            </div>
        </div>
        @endif
        <div class="col-4"> </div>
    </div>
    @endif


    @if (($purchaseCustomer-> type == 'شراء' || $purchaseCustomer-> type == 'شراء-دفعة') && ($reqStatus == 6 || $reqStatus == 8 || $reqStatus == 13) )
    @if (empty ($payment))
    <div class="row">
        <div class="col-4"> </div>
        <div class="col-4">
            <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                    <button class="mr-3 Red2 item" role="button" type="button">
                        <a href="{{ route('funding.manager.prepayementPage',$id)}}" class="text-white" style="text-decoration: none">
                            <i class="fa fa-credit-card mr-2"></i>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Create Prepaymrnt') }}
                        </a>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-4"> </div>
    </div>
    @else

    <div class="row">
        @if ($payment->payStatus == 0 && ($reqStatus == 6 || $reqStatus == 8 || $reqStatus == 13))
        <div class="col-1">
        </div>
        <div class="col-3">
            <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                    @if ($payment->payStatus == 0 || $payment->payStatus == 1 ||$payment->payStatus == 3 || $payment->payStatus == 4 || $payment->payStatus == 6)
                    <button id="cancel" class="mr-3 warning item" role="button" type="button">
                        <a href="{{ route('funding.manager.cancelPrepayment',$id)}}" class="text-white" style="text-decoration: none">
                            <i class="fa fa-trash mr-2"></i>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Cancel Payment') }}
                        </a>
                    </button>
                    @else
                    <button id="cancel" disabled style="cursor: not-allowed" class="mr-3 warning item" role="button" type="button">
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Cancel Payment') }}
                    </button>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                    <button id="updatePay" data-id="{{$id}}" class="mr-3 Green item" role="button" type="button">
                        <i class="fa fa-edit mr-2"></i>
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Update Payment') }}
                    </button>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                    <button id="sendPay" data-id="{{$id}}" class="mr-3 Pink item" role="button" type="button">
                        <i class="fa fa-send mr-2"></i>
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Send Payment') }}
                    </button>
                </div>
            </div>
        </div>
        <div class="col-1">
        </div>

        @elseif($payment->payStatus == 7 && ($reqStatus == 6 || $reqStatus == 8 || $reqStatus == 13))


        <div class="col-3">
            <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                    <button id="cancel" class="mr-3 warning item" role="button" type="button">
                        <a href="{{ route('funding.manager.cancelPrepayment',$id)}}" class="text-white" style="text-decoration: none">
                            <i class="fa fa-trash mr-2"></i>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Cancel Payment') }}
                        </a>
                    </button>
                </div>
            </div>
        </div>

        <div class="col-3">
            <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                    <button id="rejectPay" data-id="{{$id}}" class="mr-3 Red2 item" role="button" type="button">
                        <i class="fa fa-ban mr-2"></i>
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Reject Payment') }}
                    </button>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                    <button id="updatePay" data-id="{{$id}}" class="mr-3 Green item" role="button" type="button">
                        <i class="fa fa-edit mr-2"></i>
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Update Payment') }}
                    </button>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                    <button id="appPay" data-id="{{$id}}" class="mr-3 Pink item" role="button" type="button">
                        <i class="fa fa-check mr-2"></i>
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Approve Payment') }}
                    </button>
                </div>
            </div>
        </div>



        @elseif($payment->payStatus == 9 )
        <div class="col-1">
        </div>

        <div class="col-3">
            <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                    <button disabled style="cursor: not-allowed" id="rejectPay" data-id="{{$id}}" class="mr-3 Red2 item" role="button" type="button">
                        <i class="fa fa-ban mr-2"></i>
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Reject Payment') }}
                    </button>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                    <button disabled style="cursor: not-allowed" id="updatePay" data-id="{{$id}}" class="mr-3 Green item" role="button" type="button">
                        <i class="fa fa-edit mr-2"></i>
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Update Payment') }}
                    </button>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                    <button disabled style="cursor: not-allowed" id="appPay" data-id="{{$id}}" class="mr-3 Pink item" role="button" type="button">
                        <i class="fa fa-check mr-2"></i>
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Approve Payment') }}
                    </button>
                </div>
            </div>
        </div>

        <div class="col-1">
        </div>

        @else

        <div class="col-3">
            <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                <div class="addBtn  mt-lg-0 mt-3 orderBtns">

                    @if ($payment->payStatus == 0 || $payment->payStatus == 1 ||$payment->payStatus == 3 || $payment->payStatus == 4 || $payment->payStatus == 6)
                    <button id="cancel" class="mr-3 warning item" role="button" type="button">
                        <a href="{{ route('funding.manager.cancelPrepayment',$id)}}" class="text-white" style="text-decoration: none">
                            <i class="fa fa-trash mr-2"></i>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Cancel Payment') }}
                        </a>
                    </button>
                    @else
                    <button id="cancel" disabled style="cursor: not-allowed" class="mr-3 warning item" role="button" type="button">
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Cancel Payment') }}
                    </button>
                    @endif

                </div>
            </div>
        </div>

        <div class="col-3">
            <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                    <button disabled style="cursor: not-allowed" id="updatePay" data-id="{{$id}}" class="mr-3 Green item" role="button" type="button">
                        <i class="fa fa-edit mr-2"></i>
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Update Payment') }}
                    </button>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                    <button disabled style="cursor: not-allowed" id="sendPay" data-id="{{$id}}" class="mr-3 Pink item" role="button" type="button">
                        <i class="fa fa-send mr-2"></i>
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Send Payment') }}
                    </button>
                </div>
            </div>
        </div>
        <div class="col-1">
        </div>



        @endif


    </div>

    @endif

    @endif





    <!-- EndPrepayment-->


    @else
    <!-- Prepayment-->
    @if ($purchaseCustomer-> type == 'رهن-شراء')
    @if (empty ($purchaseTsa))

    <div class="row">

        <div class="col-4"> </div>

        <div class="col-4">
            <a href="{{ route('funding.manager.prepayementPage',$id)}}">
                <button disabled type="button" style="cursor: not-allowed" class="btn btn-block btn-outline-primary"><i class="fa fa-credit-card"></i> {{ MyHelpers::admin_trans(auth()->user()->id,'Create Prepaymrnt') }}</button>
            </a>

        </div>
        @else
        <div class="col-4">
            <a href="{{ route('funding.manager.updatePrepaymentPage',$id)}}">
                <button disabled type="button" style="cursor: not-allowed" class="btn btn-block btn-outline-primary"><i class="fa fa-credit-card"></i> {{ MyHelpers::admin_trans(auth()->user()->id,'View Prepaymrnt') }}</button>
            </a>
        </div>

        <div class="col-4"> </div>

    </div>

    @endif

    @endif
    @if ( $purchaseCustomer-> type == 'شراء-دفعة' && ($reqStatus == 6 || $reqStatus == 8 || $reqStatus == 13))
    @if (empty ($payment))
    <div class="row">

        <div class="col-5"> </div>

        <div class="col-4">
            <a href="{{ route('funding.manager.prepayementPage',$id)}}">
                <button disabled type="button" style="cursor: not-allowed" class="btn btn-block btn-outline-primary"><i class="fa fa-credit-card"></i> {{ MyHelpers::admin_trans(auth()->user()->id,'Create Prepaymrnt') }}</button>
            </a>
        </div>

        <div class="col-4"> </div>

    </div>
    @else

    <div class="row">

        <div class="col-1"> </div>


        <!---->
        @if ($payment->payStatus == 0)

        <div class="col-3">
            @if ($payment->payStatus == 0 || $payment->payStatus == 1 ||$payment->payStatus == 3 || $payment->payStatus == 4 || $payment->payStatus == 6)
            <a href="{{ route('funding.manager.cancelPrepayment',$id)}}">
                <button type="button" id="cancel" class="btn btn-block btn-outline-danger"><i class="fa fa-trash"></i> {{ MyHelpers::admin_trans(auth()->user()->id,'Cancel Payment') }}</button>
            </a>
            @else
            <button disabled style="cursor: not-allowed" type="button" id="cancel" class="btn btn-block btn-outline-danger"><i class="fa fa-trash"></i> {{ MyHelpers::admin_trans(auth()->user()->id,'Cancel Payment') }}</button>
            @endif
        </div>

        <div class="col-3">
            <button type="button" id="updatePay" data-id="{{$id}}" class="btn Green btn-block"><i class="fa fa-floppy-o"></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Update Payment') }}</button>
        </div>

        <div class="col-3">
            <button type="button" id="sendPay" data-id="{{$id}}" class="btn btn-info btn-block"><i class="fa fa-send"></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Send Payment') }}</button>
        </div>

        @elseif($payment->payStatus == 7 )
        <div class="col-4">
        </div>

        <div class="col-3">
            <button type="button" id="appPay" data-id="{{$id}}" class="btn btn-info btn-block"><i class="fa fa-check"></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Approve Payment') }}</button>
        </div>

        <div class="col-4">
        </div>


        @else

        <div class="col-3">

            <button disabled type="button" style="cursor: not-allowed" id="cancel" style="cursor: not-allowed" class="btn btn-block btn-outline-danger"><i class="fa fa-trash"></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel Payment') }}</button>
        </div>

        <div class="col-3">
            <button disabled type="button" id="updatePay" style="cursor: not-allowed" data-id="{{$id}}" class="btn Green btn-block"><i class="fa fa-floppy-o"></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Update Payment') }}</button>
        </div>

        <div class="col-3">
            <button disabled type="button" id="sendPay" style="cursor: not-allowed" data-id="{{$id}}" class="btn btn-info btn-block"><i class="fa fa-send"></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Send Payment') }}</button>
        </div>



        @endif
        <!---->

        <div class="col-1"> </div>

    </div>

    @endif

    @endif
    <!-- EndPrepayment-->
    @endif



    <section class="new-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">

                    @if ($purchaseCustomer-> type == 'رهن')
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
                            <input value={{$reqStatus}} id="statusRequest" type="hidden" name="statusRequest"> <!-- To pass request status-->
                            <input value={{$id}} id="reqID" type="hidden" name="reqID"> <!-- To pass request ID-->


                            <div class="row hdie-show display-flex" id="content4-cont">
                                <div class="col-lg-12   mb-md-0">
                                    @include('FundingManager.fundingReq.fundingCustomer')
                                </div>
                            </div>

                            <div class="row hdie-show display-none" id="content1-cont">
                                <div class="col-lg-12 mb-5 mb-md-0">
                                    @include('FundingManager.fundingReq.document')
                                </div>
                            </div>

                            <div class="row hdie-show display-none" id="content2-cont">
                                <div class="col-lg-12 mb-5 mb-md-0">
                                    @include('FundingManager.fundingReq.fundingInfo')
                                </div>
                            </div>

                            <div class="row hdie-show display-none" id="content3-cont">
                                <div class="col-lg-12 mb-5 mb-md-0">
                                    @include('FundingManager.fundingReq.fundingreal')
                                </div>
                            </div>

                            @if ($purchaseCustomer-> type == 'رهن')
                            <div class="row hdie-show display-none" id="content5-cont">
                                <div class="col-lg-12   mb-md-0">
                                    @include('FundingManager.fundingReq.tsaheel')
                                </div>
                            </div>
                            @endif


                            @if ( $purchaseCustomer-> type == 'شراء-دفعة' && (!empty ($payment)))
                            <div class="row hdie-show display-none" id="content5-cont">
                                <div class="col-lg-12   mb-md-0">
                                    @include('FundingManager.fundingReq.updatePage')
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
                        @include('FundingManager.fundingReq.fundingReqInfo')
                    </span>
                </label>
            </div>
            <div class="userFormsInfo">
                <label style="width: 100%; display: block;" for="tab1">
                    <span>
                        <div class="userFormsContainer mb-3">
                            <div class="userFormsDetails topRow">
                                @if ( ( $reqStatus == 6 || $reqStatus == 8 || $reqStatus == 13 ) )
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
@include('FundingManager.fundingReq.req_records')
@include('FundingManager.fundingReq.documentModel')
@endsection

@section('confirmMSG')
@include('FundingManager.fundingReq.confirmationMsg')
@include('FundingManager.fundingReq.confirmSendingMsg')
@include('FundingManager.fundingReq.BankTransferModal')
@include('FundingManager.fundingReq.confirmSendingWithPayMsg')
@include('FundingManager.fundingReq.confirmRejectMsg')
@include('FundingManager.fundingReq.confirmSendingMsgPay')
@include('FundingManager.fundingReq.confirmApproveMsg')
@endsection
