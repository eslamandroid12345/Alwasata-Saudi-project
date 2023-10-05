@extends('layouts.content')
@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Funding') }}
@endsection
@section('css_style')
    <script src="{{ asset('backend/sweetalert/sweetalert2.min.js') }}"></script>
    <link href="{{ asset('backend/sweetalert/sweetalert2.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link href="{{url('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet"/>
    <style>
        .unselectable {
            -webkit-user-select: none;
            -webkit-touch-callout: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        #records tr td {
            -webkit-user-select: none;
            -webkit-touch-callout: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        .clearfix:before,
        .clearfix:after {
            content: " ";
            display: table;
        }
        .clearfix:after {
            clear: both;
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
        .fa-trash {
            background: #bfbfbf;
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
        .requiredFiled {
            border: 3px solid #e67681;
            border-radius: 4px;
            background-color: #ffe6e6;
        }
        .selectBankResult {
            float: left;
            background-color: #39ac73;
            color: white;
            font-weight: bold;
        }
        .selectBankResult:hover {
            background-color: #53c68c;
            color: white;
            cursor: pointer;
        }
    </style>
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

        .width-16 {
            width: 16.66% !important;
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
    <div id="msg2" class="alert alert-dismissible" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <div id="msg3" class="alert alert-dismissible" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <div class="alert alert-danger" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    @if(session()->has('message'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" id="message">&times;</button>
            {{ session()->get('message') }}
        </div>
    @endif
    @if(session()->has('message2'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" id="message2">&times;</button>
            {{ session()->get('message2') }}
        </div>
    @endif
    @if(session()->has('message3'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" id="message3">&times;</button>
            {{ session()->get('message3') }}
        </div>
    @endif
    @if(session()->has('message4'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('message4') }}
        </div>
    @endif
    <!--SEEKING FOR PROPERTY AND CITY OF REAL-->
    @if(session()->has('message5'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" id="message5">&times;</button>
            {{ session()->get('message5') }}
        </div>
    @endif
    <!---->
    @if(session()->has('message6'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" id="message6">&times;</button>
            {{ session()->get('message6') }}
        </div>
    @endif
    <!---->
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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

    <div class="addUser my-4">
        <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
            <h3>
              عرض طلب
            </h3>
        </div>
    </div>
    <form action="{{ route('proper.request.update')}}" method="post" novalidate="novalidate" id="frm-update">
        <input id="request_id" name="request_id" type="hidden" value="{{ $purchaseCustomer->id }}">
    @csrf
        <!-- Status req of Sales agent-->
            <div class="tableBar">
                <div class="topRow">
                    <div class="row align-items-center text-center text-md-left">
                        <div class="col-lg-2">
                            <div class="selectAll">
                                <div class="form-check pl-0">
                                    <label class="form-check-label"> خيارات الطلب</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center  ">
                                <div class="addBtn  mt-lg-0 mt-3 orderBtns spansBtn">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <div class="tableBar" id="fixedTop">
            <div class="topRow no-radius">
                    <div class="row align-items-center text-center text-md-left">
                        @if (auth()->user()->role != '6')
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
                        <div class="col-lg-3"></div>
                        @endif

                        <div class="col-lg-6">
                            <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                                <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                                    <button class="green item" type="submit" id="update" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}">
                                        <i class="fas fa-edit"></i>
                                        {{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>

        <section class="new-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                            <ul class="list-unstyled d-flex flex-wrab align-items-center justify-content-center topRow">
                                <li id="content3" class="tab width-16 @if(session()->has('message5')) active-on @endif ">
                                        <i class="fas fa-home"></i>
                                        {{ MyHelpers::admin_trans(auth()->user()->id,'Real Estate Info') }}
                                </li>
                                <li id="content1" class="tab width-16 ">
                                    <i class="fas fa-layer-group"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Documents Info') }}
                                </li>
                                <li id="content4" class="tab width-16 active-on">
                                    <i class="fas fa-user"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Customer Info') }}
                                </li>
                            </ul>
                        <div class="tabs-serv">
                            <div class="tab-body">
                                <div class="row hdie-show @if(session()->has('message5')) display-flex @endif " id="content4-cont">
                                    <div class="col-lg-12   mb-md-0 ">
                                        @include('Proper.Request.single.fundingCustomer')
                                    </div>
                                </div>
                                    <div class="row hdie-show " id="content3-cont">
                                        <div class="col-lg-12 mb-5 mb-md-0">
                                            @include('Agent.fundingReq.fundingreal')
                                        </div>
                                    </div>

                                <input type="hidden" name="reqID" value="{{$purchaseCustomer->id}}">
                                <div class="row hdie-show   display-none " id="content1-cont">
                                    <div class="col-lg-12 mb-5 mb-md-0">
                                        @include('Proper.Request.single.document')
                                    </div>
                                </div>
                            </div>
                            @include('Proper.Request.single.fundingReqInfo')
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </form>
@endsection
@section('updateModel')
    @include('Proper.Request.single.req_records')
    @include('Proper.Request.single.documentModel')
    @include('Helpers.addPhone')
@endsection

@section('confirmMSG')
    @include('Proper.Request.single.confirmationMsg')
    @include('Proper.Request.single.confirmSendingMsg')
    @include('Proper.Request.single.confirmSendingMsgPay')
@endsection

@section('scripts')
    <script>
        //---------------------to show wraning msg---------------
        $(document).ready(function () {
            var status = document.getElementById("statusRequest").value;
            var requestID = document.getElementById("reqId").value;
            var statusPay = document.getElementById("statusPayment").value;
            var classAgent = document.getElementById("classAgent").value;
            var is_customer_reopen_request = document.getElementById("is_customer_reopen_request").value;
            if (status == 0)
                updateNewReq(requestID);
            var checkCanceled = document.getElementById("is_canceled").value;
            var type = document.getElementById("typeReq").value;
            if (checkCanceled == 1) {
                document.getElementById('archiveWarning1').innerHTML = "<button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request is canceled, you cannot edit anything until restore it') }}";
                document.getElementById('archiveWarning1').style.display = "block";
            }
            if (type == 'تساهيل') {
                if (status == 30) { //reject from general manager
                    document.getElementById('sendingWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button>{{ MyHelpers::admin_trans(auth()->user()->id,'The request has sent to Mortgage Manager, you cannot edit anything') }}";
                    document.getElementById('sendingWarning1').style.display = "block";
                }
                if (status == 31) { //reject from general manager
                    document.getElementById('rejectWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has rejected and back to sales agent') }}";
                    document.getElementById('rejectWarning1').style.display = "block";
                }
                if (status == 32) { //sending to general manager
                    document.getElementById('sendingWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has sent to General Manager, you cannot edit anything') }}";
                    document.getElementById('sendingWarning1').style.display = "block";
                }
                if (status == 33) { //reject from general manager
                    document.getElementById('rejectWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button>   {{ MyHelpers::admin_trans(auth()->user()->id,'The request has rejected and back to Mortgage Manager') }}";
                    document.getElementById('rejectWarning1').style.display = "block";
                }
                if (status == 34) { //canceled from general manager
                    document.getElementById('archiveWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has canceled') }} ";
                    document.getElementById('archiveWarning1').style.display = "block";
                }
                if (status == 35) { //APProved
                    document.getElementById('appWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has Completed') }}";
                    document.getElementById('appWarning1').style.display = "block";
                }

            }
            if ((status != 6 && status != 13) || statusPay == '' || statusPay == 2 || statusPay == 8 || statusPay == 9) {
                if (status == 5) { //in sales manager
                    document.getElementById('sendingWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request is archived by Sales Manager, you cannot edit anything') }}";
                    document.getElementById('sendingWarning1').style.display = "block";
                }

                if ((status == 0 || status == 1) && is_customer_reopen_request) { //archived in funding manager
                    document.getElementById('sendingWarning1').innerHTML = "<button type='button' class='close' data-dismiss='alert'>&times;</button> العميل أعاد فتح الطلب";
                    document.getElementById('sendingWarning1').style.display = "block";
                }

                if (status == 8) { //archived in funding manager
                    document.getElementById('sendingWarning1').innerHTML = "<button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request is archived by Funding Manager, you cannot edit anything') }}";
                    document.getElementById('sendingWarning1').style.display = "block";
                }


                if (status == 15) { //canceled from general manager
                    document.getElementById('archiveWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has canceled') }} ";
                    document.getElementById('archiveWarning1').style.display = "block";
                }


                if (status == 3) { //sending to sales manager
                    document.getElementById('sendingWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has sent to Sales Manager, you cannot edit anything') }}";
                    document.getElementById('sendingWarning1').style.display = "block";
                }

                if (status == 4) { //reject from sales manager
                    document.getElementById('rejectWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has rejected from Sales Manager and redirect to Sales Agent') }}";
                    document.getElementById('rejectWarning1').style.display = "block";
                }
                if (status == 2) {

                    if (classAgent != 65) {
                        document.getElementById('archiveWarning1').innerHTML = "<button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request is archived, you cannot edit anything until restore it!') }}";
                        document.getElementById('archiveWarning1').style.display = "block";

                    } else {
                        document.getElementById('archiveWarning1').innerHTML = "<button type='button' class='close' data-dismiss='alert'>&times;</button>   العميل قام بإلغاء الطلب - {{ MyHelpers::admin_trans(auth()->user()->id,'The request is archived, you cannot edit anything until restore it!') }}";
                        document.getElementById('archiveWarning1').style.display = "block";

                    }
                }
                if (status == 6) { //wating for funding manager
                    document.getElementById('sendingWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has sent to Funding Manager, you cannot edit anything') }}";
                    document.getElementById('sendingWarning1').style.display = "block";
                }

                if (status == 9) { //sending to mortgage manager
                    document.getElementById('sendingWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button>{{ MyHelpers::admin_trans(auth()->user()->id,'The request has sent to Mortgage Manager, you cannot edit anything') }}";
                    document.getElementById('sendingWarning1').style.display = "block";
                }

                if (status == 16) { //APProved
                    document.getElementById('appWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has Completed') }}";
                    document.getElementById('appWarning1').style.display = "block";
                }


                if (status == 7) { //reject and back to sales manager
                    document.getElementById('rejectWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has rejected and back to sales manager,  you cannot edit anything') }}";
                    document.getElementById('rejectWarning1').style.display = "block";
                }


                if (status == 13 && type == 'شراء') { //reject from general manager
                    document.getElementById('rejectWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button>  {{ MyHelpers::admin_trans(auth()->user()->id,'The request has rejected and back to Funding Manager') }}";
                    document.getElementById('rejectWarning1').style.display = "block";
                }
                if (status == 13 && type == 'رهن') { //reject from general manager
                    document.getElementById('rejectWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button>   {{ MyHelpers::admin_trans(auth()->user()->id,'The request has rejected and back to Mortgage Manager') }}";
                    document.getElementById('rejectWarning1').style.display = "block";
                }
                if (status == 13 && type == 'شراء-دفعة') { //reject from general manager
                    document.getElementById('rejectWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button>  {{ MyHelpers::admin_trans(auth()->user()->id,'The request has rejected and back to Funding Manager') }}";
                    document.getElementById('rejectWarning1').style.display = "block";
                }
                if (status == 14) { //archived in general manager
                    document.getElementById('archiveWarning1').innerHTML = "<button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request is archived by General Manager, you cannot edit anything') }}";
                    document.getElementById('archiveWarning1').style.display = "block";
                }
                if (status == 10) { //reject and back to sales manager
                    document.getElementById('rejectWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has rejected and back to sales manager,  you cannot edit anything') }}";
                    document.getElementById('rejectWarning1').style.display = "block";
                }

                if (status == 11) { //archived in mortgage manager
                    document.getElementById('archiveWarning1').innerHTML = "<button type='button' class='close' data-dismiss='alert'>&times;</button>  The request is archived by Mortgage Manager, you cannot edit anything until restore it!";
                    document.getElementById('archiveWarning1').style.display = "block";
                }

                if (status == 12) { //sending to general manager
                    document.getElementById('sendingWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has sent to General Manager, you cannot edit anything') }}";
                    document.getElementById('sendingWarning1').style.display = "block";
                }
            }
            if ($("#message6").length != 0) {
                swal({
                    title: "خطأ!",
                    text: "{{ MyHelpers::admin_trans(auth()->user()->id,'Calculater record is required') }}",
                    icon: 'error',
                    button: 'موافق',
                });

            } else if ($("#message5").length != 0) {
                swal({
                    title: "خطأ!",
                    text: "{{ session()->get('message5') }}",
                    icon: 'error',
                    button: 'موافق',
                });
            } else if ($("#message").length != 0) {
                swal({
                    title: "تم!",
                    text: "{{ MyHelpers::admin_trans(auth()->user()->id,'Update Succesffuly') }}",
                    icon: 'success',
                });
            } else if ($("#message2").length != 0) {
                swal({
                    title: "خطأ!",
                    text: "{{ session()->get('message2') }}",
                    icon: 'danger',
                });
            }
        });
        //------------------------------------
        $(document).ready(function () {
            var today = new Date().toISOString().split("T")[0];
            $('#jointbirth').attr("max", today);
            $('#birth').attr("max", today);
            $('#jointbirth').attr("max", today);
            $('#follow').attr("min", today);
            var customer_birth = document.getElementById('birth').value;
            var joint_birth = document.getElementById('jointbirth')?.value;
            if (customer_birth != '')
                calculate();
            if (joint_birth != '')
                calculate1();
        });
        //-----------------------------------
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //-----------------------------------------------
        function updateNewReq(id) {
            //console.log (id);
            $.post("{{ route('agent.updateNewReq') }}", {
                id: id
            }, function (data) {
            });
        }
        //------------------------------------------
        function checkWork(that) {
            if (that.value == 1) {
                if ((document.getElementById("madany1")) != null) {
                    document.getElementById("madany").style.display = "none";
                    document.getElementById("madany1").style.display = "none";
                }
                if ((document.getElementById("madany2")) != null) {
                    document.getElementById("madany2").style.display = "none";
                    document.getElementById("madany3").style.display = "none";
                }
                document.getElementById("madany_work").value = "";
                document.getElementById("job_title").value = "";
                document.getElementById("askary_work").value = "";
                document.getElementById("rank").value = "";
                if ((document.getElementById("askary2")) != null) {
                    document.getElementById("askary2").style.display = "block";
                    document.getElementById("askary3").style.display = "block";
                }
                if ((document.getElementById("askary")) != null) {
                    document.getElementById("askary1").style.display = "block";
                    document.getElementById("askary").style.display = "block";
                }
            } else if (that.value == 2) {
                if ((document.getElementById("askary1")) != null) {
                    document.getElementById("askary1").style.display = "none";
                    document.getElementById("askary").style.display = "none";
                }
                if ((document.getElementById("askary2")) != null) {
                    document.getElementById("askary2").style.display = "none";
                    document.getElementById("askary3").style.display = "none";
                }
                document.getElementById("askary_work").value = "";
                document.getElementById("rank").value = "";
                document.getElementById("madany_work").value = "";
                document.getElementById("job_title").value = "";
                if ((document.getElementById("madany2")) != null) {
                    document.getElementById("madany2").style.display = "block";
                    document.getElementById("madany3").style.display = "block";
                }
                if ((document.getElementById("madany1")) != null) {
                    document.getElementById("madany").style.display = "block";
                    document.getElementById("madany1").style.display = "block";
                }
            } else {
                if ((document.getElementById("askary2")) != null) {
                    document.getElementById("askary2").style.display = "none";
                    document.getElementById("askary3").style.display = "none";
                }
                if ((document.getElementById("madany2")) != null) {
                    document.getElementById("madany2").style.display = "none";
                    document.getElementById("madany3").style.display = "none";
                }
                if ((document.getElementById("madany1")) != null) {
                    document.getElementById("madany").style.display = "none";
                    document.getElementById("madany1").style.display = "none";
                }
                if ((document.getElementById("askary1")) != null) {
                    document.getElementById("askary1").style.display = "none";
                    document.getElementById("askary").style.display = "none";
                }
                document.getElementById("askary_work").value = "";
                document.getElementById("rank").value = "";
                document.getElementById("madany_work").value = "";
                document.getElementById("job_title").value = "";
            }
        }
        //------------------------------------------
        function checkWork_caculater(that) {
            if (that.value == 1) {
                document.getElementById("askary_caculater").style.display = "block";
            } else {
                document.getElementById("askary_caculater").style.display = "none";
                document.getElementById("rank_caculater").value = "";
            }
        }
        function checkWork_joint_caculater(that) {
            if (that.value == 1) {
                document.getElementById("joint_askary").style.display = "block";
            } else {
                document.getElementById("joint_askary").style.display = "none";
                document.getElementById("joint_rank_caculater").value = "";
            }
        }
        //----------------------------
        function checkObligation(that) {
            if (that.value == "yes")
                document.getElementById("obligations_value").readOnly = false;
            else {
                document.getElementById("obligations_value").readOnly = true;
                document.getElementById("obligations_value").value = '';
            }
        }

        //----------------------------

        function checkDistress(that) {
            if (that.value == "yes")
                document.getElementById("financial_distress_value").readOnly = false;
            else {
                document.getElementById("financial_distress_value").readOnly = true;
                document.getElementById("financial_distress_value").value = '';
            }
        }

        //----------------------------

        /////////////////////////////////////////////

        function checkWork2(that) {
            if (that.value == 1) {
                if ((document.getElementById("jointmadany1")) != null) {
                    document.getElementById("jointmadany").style.display = "none";
                    document.getElementById("jointmadany1").style.display = "none";
                }
                if ((document.getElementById("jointmadany2")) != null) {
                    document.getElementById("jointmadany2").style.display = "none";
                    document.getElementById("jointmadany3").style.display = "none";
                }
                document.getElementById("jointmadany_work").value = "";
                document.getElementById("jointjob_title").value = "";

                document.getElementById("jointaskary_work").value = "";
                document.getElementById("jointrank").value = "";

                if ((document.getElementById("jointaskary2")) != null) {
                    document.getElementById("jointaskary2").style.display = "block";
                    document.getElementById("jointaskary3").style.display = "block";
                }

                if ((document.getElementById("jointaskary")) != null) {
                    document.getElementById("jointaskary1").style.display = "block";
                    document.getElementById("jointaskary").style.display = "block";
                }

            } else if (that.value == 2) {

                if ((document.getElementById("jointaskary1")) != null) {
                    document.getElementById("jointaskary1").style.display = "none";
                    document.getElementById("jointaskary").style.display = "none";

                }
                if ((document.getElementById("jointaskary2")) != null) {
                    document.getElementById("jointaskary2").style.display = "none";
                    document.getElementById("jointaskary3").style.display = "none";
                }
                document.getElementById("jointaskary_work").value = "";
                document.getElementById("jointrank").value = "";
                document.getElementById("jointmadany_work").value = "";
                document.getElementById("jointjob_title").value = "";
                if ((document.getElementById("jointmadany2")) != null) {
                    document.getElementById("jointmadany2").style.display = "block";
                    document.getElementById("jointmadany3").style.display = "block";
                }
                if ((document.getElementById("jointmadany1")) != null) {
                    document.getElementById("jointmadany").style.display = "block";
                    document.getElementById("jointmadany1").style.display = "block";
                }
            } else {
                if ((document.getElementById("jointaskary2")) != null) {
                    document.getElementById("jointaskary2").style.display = "none";
                    document.getElementById("jointaskary3").style.display = "none";
                }
                if ((document.getElementById("jointmadany2")) != null) {
                    document.getElementById("jointmadany2").style.display = "none";
                    document.getElementById("jointmadany3").style.display = "none";
                }
                if ((document.getElementById("jointmadany1")) != null) {
                    document.getElementById("jointmadany").style.display = "none";
                    document.getElementById("jointmadany1").style.display = "none";
                }
                if ((document.getElementById("jointaskary1")) != null) {
                    document.getElementById("jointaskary1").style.display = "none";
                    document.getElementById("jointaskary").style.display = "none";
                }
                document.getElementById("jointaskary_work").value = "";
                document.getElementById("jointrank").value = "";
                document.getElementById("jointmadany_work").value = "";
                document.getElementById("jointjob_title").value = "";
            }
        }

        //----------------------------

        function showJoint() {
            var x = document.getElementById("jointdiv");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
                document.getElementById("jointfunding_source").value = "";
                document.getElementById("jointsalary_source").value = "";
                document.getElementById("jointwork").value = "";
                document.getElementById("jointbirth").value = "";
                document.getElementById("jointage").value = "";
                document.getElementById("jointmobile").value = "";
                document.getElementById("jointname").value = "";
                document.getElementById("jointmadany_work").value = "";
                document.getElementById("jointjob_title").value = "";
                document.getElementById("jointaskary_work").value = "";
                document.getElementById("jointrank").value = "";
            }
        }
        function calculate() {
            var date = new Date(document.getElementById('birth').value);
            var dateString = (((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + date.getFullYear());
            var now = new Date();
            var today = new Date(now.getYear(), now.getMonth(), now.getDate());
            var yearNow = now.getYear();
            var monthNow = now.getMonth();
            var dateNow = now.getDate();
            var dob = new Date(dateString.substring(6, 10),
                dateString.substring(0, 2) - 1,
                dateString.substring(3, 5)
            );
            var yearDob = dob.getYear();
            var monthDob = dob.getMonth();
            var dateDob = dob.getDate();
            var age = {};
            var ageString = "";
            var yearString = "";
            var monthString = "";
            var dayString = "";
            yearAge = yearNow - yearDob;
            if (monthNow >= monthDob)
                var monthAge = monthNow - monthDob;
            else {
                yearAge--;
                var monthAge = 12 + monthNow - monthDob;
            }

            if (dateNow >= dateDob)
                var dateAge = dateNow - dateDob;
            else {
                monthAge--;
                var dateAge = 31 + dateNow - dateDob;

                if (monthAge < 0) {
                    monthAge = 11;
                    yearAge--;
                }
            }
            age = {
                years: yearAge,
                months: monthAge,
                days: dateAge
            };
            if (age.years > 1) yearString = " {{ MyHelpers::admin_trans(auth()->user()->id,'years') }}";
            else yearString = " {{ MyHelpers::admin_trans(auth()->user()->id,'year') }}";
            if (age.months > 1) monthString = "  {{ MyHelpers::admin_trans(auth()->user()->id,'months') }}";
            else monthString = " {{ MyHelpers::admin_trans(auth()->user()->id,'month') }}";
            if (age.days > 1) dayString = " {{ MyHelpers::admin_trans(auth()->user()->id,'days') }}";
            else dayString = " {{ MyHelpers::admin_trans(auth()->user()->id,'day') }}";
            if ((age.years > 0) && (age.months > 0) && (age.days > 0))
                ageString = age.years + yearString + ", " + age.months + monthString + ", {{ MyHelpers::admin_trans(auth()->user()->id,'and') }} " + age.days + dayString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
            else if ((age.years == 0) && (age.months == 0) && (age.days > 0))
                ageString = "{{ MyHelpers::admin_trans(auth()->user()->id,'Only') }} " + age.days + dayString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}";
            else if ((age.years > 0) && (age.months == 0) && (age.days == 0))
                ageString = age.years + yearString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}. ";
            else if ((age.years > 0) && (age.months > 0) && (age.days == 0))
                ageString = age.years + yearString + " {{ MyHelpers::admin_trans(auth()->user()->id,'and') }} " + age.months + monthString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
            else if ((age.years == 0) && (age.months > 0) && (age.days > 0))
                ageString = age.months + monthString + " {{ MyHelpers::admin_trans(auth()->user()->id,'and') }} " + age.days + dayString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
            else if ((age.years > 0) && (age.months == 0) && (age.days > 0))
                ageString = age.years + yearString + " {{ MyHelpers::admin_trans(auth()->user()->id,'and') }} " + age.days + dayString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
            else if ((age.years == 0) && (age.months > 0) && (age.days == 0))
                ageString = age.months + monthString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
            else ageString = "{{ MyHelpers::admin_trans(auth()->user()->id,'Could not calculate age') }}!";
            document.getElementById('age').value = ageString;
        }

        //-------------------------------
        function calculate1() {
            var date = new Date(document.getElementById('jointbirth').value);
            var dateString = (((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + date.getFullYear());
            var now = new Date();
            var today = new Date(now.getYear(), now.getMonth(), now.getDate());
            var yearNow = now.getYear();
            var monthNow = now.getMonth();
            var dateNow = now.getDate();
            var dob = new Date(dateString.substring(6, 10),
                dateString.substring(0, 2) - 1,
                dateString.substring(3, 5)
            );
            var yearDob = dob.getYear();
            var monthDob = dob.getMonth();
            var dateDob = dob.getDate();
            var age = {};
            var ageString = "";
            var yearString = "";
            var monthString = "";
            var dayString = "";
            yearAge = yearNow - yearDob;
            if (monthNow >= monthDob)
                var monthAge = monthNow - monthDob;
            else {
                yearAge--;
                var monthAge = 12 + monthNow - monthDob;
            }
            if (dateNow >= dateDob)
                var dateAge = dateNow - dateDob;
            else {
                monthAge--;
                var dateAge = 31 + dateNow - dateDob;

                if (monthAge < 0) {
                    monthAge = 11;
                    yearAge--;
                }
            }
            age = {
                years: yearAge,
                months: monthAge,
                days: dateAge
            };

            if (age.years > 1) yearString = " {{ MyHelpers::admin_trans(auth()->user()->id,'years') }}";
            else yearString = " {{ MyHelpers::admin_trans(auth()->user()->id,'year') }}";
            if (age.months > 1) monthString = "  {{ MyHelpers::admin_trans(auth()->user()->id,'months') }}";
            else monthString = " {{ MyHelpers::admin_trans(auth()->user()->id,'month') }}";
            if (age.days > 1) dayString = " {{ MyHelpers::admin_trans(auth()->user()->id,'days') }}";
            else dayString = " {{ MyHelpers::admin_trans(auth()->user()->id,'day') }}";


            if ((age.years > 0) && (age.months > 0) && (age.days > 0))
                ageString = age.years + yearString + ", " + age.months + monthString + ", {{ MyHelpers::admin_trans(auth()->user()->id,'and') }} " + age.days + dayString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
            else if ((age.years == 0) && (age.months == 0) && (age.days > 0))
                ageString = "{{ MyHelpers::admin_trans(auth()->user()->id,'Only') }} " + age.days + dayString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}";
            else if ((age.years > 0) && (age.months == 0) && (age.days == 0))
                ageString = age.years + yearString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}. ";
            else if ((age.years > 0) && (age.months > 0) && (age.days == 0))
                ageString = age.years + yearString + " {{ MyHelpers::admin_trans(auth()->user()->id,'and') }} " + age.months + monthString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
            else if ((age.years == 0) && (age.months > 0) && (age.days > 0))
                ageString = age.months + monthString + " {{ MyHelpers::admin_trans(auth()->user()->id,'and') }} " + age.days + dayString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
            else if ((age.years > 0) && (age.months == 0) && (age.days > 0))
                ageString = age.years + yearString + " {{ MyHelpers::admin_trans(auth()->user()->id,'and') }} " + age.days + dayString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
            else if ((age.years == 0) && (age.months > 0) && (age.days == 0))
                ageString = age.months + monthString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
            else ageString = "{{ MyHelpers::admin_trans(auth()->user()->id,'Could not calculate age') }}!";

            document.getElementById('jointage').value = ageString;
        }
        function monthlycalculate() {
            var pres = document.getElementById("dedp").value;
            var salary = document.getElementById("salary").value;
            document.getElementById("monthIn").value = ((pres * salary) / 100);
        }
        $(document).on('click', '#record', function (e) {
            var coloum = $(this).attr('data-id');
            var reqID = document.getElementById("request_id").value;
            var hide_negative_comment = "{{$hide_negative_comment}}";
            var negative_agent = "{{$history_negative_agent}}";
            var to_count_historiy = 0;
            $.get("{{ route('all.reqRecords') }}", {
                coloum: coloum,
                reqID: reqID
            }, function (data) {
                $('#records').empty();
                if (data.status == 1) {
                    $.each(data.histories, function (i, value) {
                        if (coloum == 'class_agent' || coloum == 'comment') {
                            if (hide_negative_comment == 1) {
                                if (value.user_id != negative_agent) {
                                    to_count_historiy++;
                                    var fn = $("<tr/>").attr('id', value.id);
                                    var name = '';
                                    if (value.comment == null) {
                                        if (value.switch != null)
                                            name = value.switch + ' / ' + value.name;
                                        else
                                            name = value.name;
                                    } else
                                        name = value.name + ' / ' + value.comment;
                                    fn.append($("<td/>", {
                                        text: name
                                    })).append($("<td/>", {
                                        text: value.value
                                    })).append($("<td/>", {
                                        text: value.updateValue_at
                                    }));

                                    $('#records').append(fn);
                                }
                            } else {
                                to_count_historiy++;
                                var fn = $("<tr/>").attr('id', value.id);

                                var name = '';

                                if (value.comment == null) {

                                    if (value.switch != null)
                                        name = value.switch + ' / ' + value.name;
                                    else
                                        name = value.name;
                                } else
                                    name = value.name + ' / ' + value.comment;
                                fn.append($("<td/>", {
                                    text: name
                                })).append($("<td/>", {
                                    text: value.value
                                })).append($("<td/>", {
                                    text: value.updateValue_at
                                }));
                                $('#records').append(fn);
                            }
                        } else {
                            to_count_historiy++;
                            var fn = $("<tr/>").attr('id', value.id);
                            var name = '';
                            if (value.comment == null) {
                                if (value.switch != null)
                                    name = value.switch + ' / ' + value.name;
                                else
                                    name = value.name;
                            } else
                                name = value.name + ' / ' + value.comment;
                            fn.append($("<td/>", {
                                text: name
                            })).append($("<td/>", {
                                text: value.value
                            })).append($("<td/>", {
                                text: value.updateValue_at
                            }));
                            $('#records').append(fn);
                        }
                    });
                    if (to_count_historiy == 0) {
                        var fn = $("<tr/>");
                        fn.append($("<td/>", {
                            text: ""
                        })).append($("<td/>", {
                            text: 'لايوجد تحديثات'
                        })).append($("<td/>", {
                            text: ""
                        }));
                        $('#records').append(fn);
                    }
                    $('#myModal').modal('show');
                }
                if (data.status == 0) {
                    var fn = $("<tr/>");
                    fn.append($("<td/>", {
                        text: ""
                    })).append($("<td/>", {
                        text: data.message
                    })).append($("<td/>", {
                        text: ""
                    }));
                    $('#records').append(fn);
                    $('#myModal').modal('show');
                }
            }).fail(function (data) {
                document.getElementById('archiveWarning').innerHTML = "<button type='button' class='close' data-dismiss='alert'>&times;</button>{{ MyHelpers::admin_trans(auth()->user()->id,'Try Again') }}!";
                document.getElementById('archiveWarning').style.display = "block";
            });
        })
        $(document).on('click', '#upload', function (e) {
            $('#nameError').text('');
            $('#fileError').text('');
            document.getElementById("filename").value = "";
            document.getElementById("file").value = "";
            $('#myModal1').modal('show');
        })
        //////////////////////////////
        $('#file-form').submit(function (event) {
            event.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                url: "{{ route('proper.request.uploadFile')}}",
                data: formData,
                type: 'post',
                async: false,
                processData: false,
                contentType: false,
                success: function (response) {
                    // console.log(response);
                    $('#myModal1').modal('hide');
                    $('#st').empty(); // to prevent dublicate of same data in each click , so i will empty before start the loop!
                    $.each(response, function (i, value) { //for loop , I put data as value
                        var docID = value.id;
                        var url = '{{ route("agent.openFile", ":docID") }}';
                        url = url.replace(':docID', docID);
                        var url2 = '{{ route("agent.downFile", ":docID") }}';
                        url2 = url2.replace(':docID', docID);
                        var fn = $("<tr/>").attr('id', value.id); // if i want to add html tag or anything in html i have to define as verible $(); <tr/> : that mean create start and close tage; att (..,..) : mean add attrubite to this tage , here i add id attribute to use it in watever i want
                        fn.append($("<td/>", {
                            text: value.user.name
                        })).append($("<td/>", {
                            text: value.filename
                        })).append($("<td/>", {
                            text: value.upload_date
                        })).append($("<td/>", {
                            html: " <div class='tableAdminOption'><span  class='item pointer' data-toggle='tooltip' data-placement='top' title='{{ MyHelpers::admin_trans(auth()->user()->id,'Open') }}'> <a href='" + url + "' target='_blank'> <i class='fa fa-eye'></i></a></span><span  class='item pointer' data-toggle='tooltip' data-placement='top' title='{{ MyHelpers::admin_trans(auth()->user()->id,'Download') }}'><a href='" + url2 + "' target='_blank'><i class='fa fa-download'></i></a></span><span id='delete' data-id=" + docID + " class='item pointer'  data-toggle='tooltip' data-placement='top' title='{{ MyHelpers::admin_trans(auth()->user()->id,'Delete') }}'><i class='fa fa-trash'></i></span></div>"
                        }))
                        $('#st').append(fn);
                    })
                },
                error: function (xhr) {
                    var errors = xhr.responseJSON;
                    if ($.isEmptyObject(errors) == false) {
                        $.each(errors.errors, function (key, value) {
                            var ErrorID = '#' + key + 'Error';
                            // $(ErrorID).removeClass("d-none");
                            $(ErrorID).text(value);
                        })
                    }
                }
            });
        });
        ///////////////////////////////////
        $(document).on('click', '#delete', function (e) {
            var id = $(this).attr('data-id');
            var modalConfirm = function (callback) {
                $("#mi-modal").modal('show');
                $("#modal-btn-si").on("click", function () {
                    callback(true);
                    $("#mi-modal").modal('hide');
                });
                $("#modal-btn-no").on("click", function () {
                    callback(false);
                    $("#mi-modal").modal('hide');
                });
            };
            modalConfirm(function (confirm) {
                if (confirm) {
                    $.post("{{ route('proper.delete.file') }}", {
                        id: id
                    }, function (data) { // we pass var id in the request with id as name ; we use $.post : because we pass our data to server and got response
                        if (data.status == 1) {
                            var d = ' # ' + id;
                            var test = d.replace(/\s/g, ''); // to remove all spaces in var d , to find the <tr/> that i deleted and reomve it
                            $(test).remove(); // remove by #id
                            var rowCount = document.querySelectorAll('#docTable tbody tr').length;
                            //alert(rowCount);
                            if (rowCount == 0) { //if table become empty
                                var fn = $("<tr/>");
                                fn.append($("<td/>", {
                                    html: "<h3 class='text-center text-secondary'>{{ MyHelpers::admin_trans(auth()->user()->id,'No Attached') }}</h3>"
                                }).attr('colspan', '4'));
                                $('#st').append(fn);
                            }
                            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                        } else {
                            $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

                        }
                    })
                } else {
                    //No delete
                }
            });
        });
        //--------Tsaheel Page functiones---------------------
        function incresecalculate() {
            var check = document.getElementById("check").value;
            var real = document.getElementById("real").value;
            document.getElementById("incr").value = (check - real);
        }
        //------------------------------------
        function preCoscalculate() {
            var prepaymentValue = parseInt(document.getElementById("preval").value);
            var presentage = parseInt(document.getElementById("prepre").value);
            document.getElementById("precos").value = prepaymentValue + (prepaymentValue * (presentage / 100));
        }
        //---------------------------------------
        function showPrepay() {
            var x = document.getElementById("prepaydiv");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
                document.getElementById("check").value = "";
                document.getElementById("real").value = "";
                document.getElementById("incr").value = "";
                document.getElementById("preval").value = "";
                document.getElementById("prepre").value = "";
                document.getElementById("precos").value = "";
                document.getElementById("net").value = "";
                document.getElementById("deficit").value = "";
            }
        }
        //-----------------------------------------------
        function debtcalculate() {
            var visa = parseInt(document.getElementById("visa").value);
            var car = parseInt(document.getElementById("carlo").value);
            var personal = parseInt(document.getElementById("perlo").value);
            var realEstat = parseInt(document.getElementById("realo").value);
            var credit = parseInt(document.getElementById("credban").value);
            var other = parseInt(document.getElementById("other1").value);
            var debt = document.getElementById("debt");
            debt.value = visa + car + personal + realEstat + credit + other;
            mortcalculate()
            profcalculate()
        }
        //-----------------------------------------------
        function mortcalculate() {
            var morpre = parseInt(document.getElementById("morpre").value);
            var debt = parseInt(document.getElementById("debt").value);
            document.getElementById("morcos").value = debt * (morpre / 100);
        }
        //--------------------------------------------------
        function setCheckCost() {
            var realCost = parseInt(document.getElementById("realcost").value);
            document.getElementById("check").value = realCost;
            incresecalculate();
        }
        //-----------------------------------------------
        function profcalculate() {
            var propre = parseInt(document.getElementById("propre").value);
            var debt = parseInt(document.getElementById("debt").value);
            document.getElementById("procos").value = debt * (propre / 100);
        }
        function changeMobile() {
            document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
            $('#checkMobile').removeClass('btn-success');
            $('#checkMobile').removeClass('btn-danger');
            $('#checkMobile').addClass('btn-info');
        }
        $(document).on('click', '#checkMobile', function (e) {
            $('#checkMobile').attr("disabled", true);
            document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Loading') }}";
            var mobile = document.getElementById('mobile').value;
            if (mobile != null /*&& regex.test(mobile)*/) {
                document.getElementById('error').innerHTML = "";

                $.post("{{ route('all.checkMobile') }}", {
                    mobile: mobile
                }, function (data) {
                    if (data == "error") {
                        document.getElementById('error').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Enter Valid Mobile Number (9 digits) and starts 5') }}";
                        document.getElementById('error').display = "block";
                        document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
                        $('#checkMobile').attr("disabled", false);
                    }
                    if ($.trim(data) == "no") {
                        document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-check'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
                        $('#checkMobile').removeClass('btn-info');
                        $('#checkMobile').addClass('btn-success');
                        $('#checkMobile').attr("disabled", false);
                    } else {
                        document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-times'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Not') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
                        $('#checkMobile').removeClass('btn-info');
                        $('#checkMobile').addClass('btn-danger');
                        $('#checkMobile').attr("disabled", false);
                    }
                }).fail(function (data) {
                });
            } else {
                document.getElementById('error').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Enter Valid Mobile Number (9 digits) and starts 5') }}";
                document.getElementById('error').display = "block";
                document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
                $('#checkMobile').attr("disabled", false);

            }
        });
        function incresecalculate() {
            var check = document.getElementById("check").value;
            var real = document.getElementById("real").value;
            document.getElementById("incr").value = (check - real);
        }
        //------------------------------------
        function preCoscalculate() {
            var prepaymentValue = parseInt(document.getElementById("preval").value);
            var presentage = parseInt(document.getElementById("prepre").value);
            document.getElementById("precos").value = prepaymentValue + (prepaymentValue * (presentage / 100));
        }
        //---------------------------------------
        function showTsaheel() {
            var x = document.getElementById("tsaheeldiv");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
        function checkCollaborator(that) {
            if (that.value == 2) {
                $('#collaboratorDiv2').removeAttr('hidden');
            } else {
                $('#collaboratorDiv2').attr('hidden', true);
                document.getElementById("collaborator").value = "";
            }
        }
        //----------------------------

        function debtcalculate() {
            var visa = parseInt(document.getElementById("visa").value);
            var car = parseInt(document.getElementById("carlo").value);
            var personal = parseInt(document.getElementById("perlo").value);
            var realEstat = parseInt(document.getElementById("realo").value);
            var credit = parseInt(document.getElementById("credban").value);
            var other = parseInt(document.getElementById("other1").value);
            document.getElementById("debt").value = visa + car + personal + realEstat + credit + other;
            var disposition_tsaheel = parseInt(document.getElementById("Real_estate_disposition_value_tsaheel").value);
            var purchaseTaxTsaheel = parseInt(document.getElementById("purchase_tax_value_tsaheel").value);
            document.getElementById("debt").value = visa + car + personal + realEstat + credit + other + disposition_tsaheel + purchaseTaxTsaheel;
            mortcalculate()
            profcalculate()
        }

        //-----------------------------------------------
        function mortcalculate() {
            var morpre = parseInt(document.getElementById("morpre").value);
            var debt = parseInt(document.getElementById("debt").value);
            document.getElementById("morcos").value = debt * (morpre / 100);
        }

        //-----------------------------------------------
        function profcalculate() {
            var propre = parseInt(document.getElementById("propre").value);
            var debt = parseInt(document.getElementById("debt").value);


            document.getElementById("procos").value = debt * (propre / 100);
        }

        //--------------End PREPAYMENT-----------------
    </script>
    <!--  NEW 2/2/2020 hijri datepicker  -->
    <script src="{{url('js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $("#hijri-date").hijriDatePicker({
                hijri: true,
                format: "YYYY/MM/DD",
                hijriFormat: 'iYYYY-iMM-iDD',
                showSwitcher: false,
                showTodayButton: true,
                showClose: true
            });

            $("#hijri-date1").hijriDatePicker({
                hijri: true,
                format: "YYYY/MM/DD",
                hijriFormat: 'iYYYY-iMM-iDD',
                showSwitcher: false,
                showTodayButton: true,
                showClose: true
            });

            $("#hijri_date_caculater").hijriDatePicker({
                hijri: true,
                format: "YYYY/MM/DD",
                hijriFormat: 'iYYYY-iMM-iDD',
                showSwitcher: false,
                showTodayButton: true,
                showClose: true
            });

            $("#joint_hijri_date_caculater").hijriDatePicker({
                hijri: true,
                format: "YYYY/MM/DD",
                hijriFormat: 'iYYYY-iMM-iDD',
                showSwitcher: false,
                showTodayButton: true,
                showClose: true
            });

            $("#job_tenure_caculater").hijriDatePicker({
                hijri: true,
                format: "YYYY/MM/DD",
                hijriFormat: 'iYYYY-iMM-iDD',
                showSwitcher: false,
                showTodayButton: true,
                showClose: true
            });

            $("#joint_job_tenure_caculater").hijriDatePicker({
                hijri: true,
                format: "YYYY/MM/DD",
                hijriFormat: 'iYYYY-iMM-iDD',
                showSwitcher: false,
                showTodayButton: true,
                showClose: true
            });


            $("#hiring_date").hijriDatePicker({
                hijri: true,
                format: "YYYY/MM/DD",
                hijriFormat: 'iYYYY-iMM-iDD',
                showSwitcher: false,
                showTodayButton: true,
                showClose: true
            });
            $("#joint_hiring_date").hijriDatePicker({
                hijri: true,
                format: "YYYY/MM/DD",
                hijriFormat: 'iYYYY-iMM-iDD',
                showSwitcher: false,
                showTodayButton: true,
                showClose: true
            });
        });
    </script>
    <script type="text/javascript">
        $("#convertToHij").click(function () {
            // alert($("#birth").val());
            if ($("#birth").val() == "") {
                alert("{{ MyHelpers::admin_trans(auth()->user()->id,'Enter a date') }}");
            } else {
                $.ajax({
                    url: "{{ URL('all/convertToHijri') }}",
                    type: "POST",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "gregorian": $("#birth").val(),
                    },
                    success: function (response) {
                        // alert(response);
                        $("#hijri-date").val($.trim(response));
                    },
                    error: function () {
                        swal({
                            title: "{{ MyHelpers::admin_trans(auth()->user()->id,'Failed') }}!",
                            text: "{{ MyHelpers::admin_trans(auth()->user()->id,'Try Again') }}.",
                            html: true,
                            type: "error",
                        });
                    }
                });
            }
        });

        $("#convertToGreg").click(function () {

            if ($("#hijri-date").val() == "") {
                alert("{{ MyHelpers::admin_trans(auth()->user()->id,'Enter a date') }}");
            } else {
                $.ajax({
                    url: "{{ URL('all/convertToGregorian') }}",
                    type: "POST",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "hijri": $("#hijri-date").val(),
                    },
                    success: function (response) {

                        // alert(response);
                        $("#birth").val($.trim(response));
                        calculate();
                    },
                    error: function () {
                        swal({
                            title: "{{ MyHelpers::admin_trans(auth()->user()->id,'Failed') }}!",
                            text: "{{ MyHelpers::admin_trans(auth()->user()->id,'Try Again') }}.",
                            html: true,
                            type: "error",
                        });
                    }
                });
            }
        });
    </script>
    @include('Helpers.autocomplete-districts')
    @include('Helpers.addPhoneScript',['prefix' => 'proper'])
    @include('MortgageCalculator.mortgageCalculatorScript')
@endsection
