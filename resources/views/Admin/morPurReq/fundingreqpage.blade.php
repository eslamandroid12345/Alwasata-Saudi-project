@extends('layouts.content')

@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur') }}
@endsection

@section('css_style')

    <!--NEW 2/2/2020 for hijri datepicker-->
    <link href="{{url('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" />

    <style>
        .clearfix:before,
        .clearfix:after {
            content: " ";
            display: table;
        }

        .clearfix:after {
            clear: both;
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
            width: 25%;
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


        .custom-control-label {
            position: relative;
            padding-left: 1.8rem;
        }

        .sticky {
            position: fixed;
            top: 70px;
            left: 5px;
            Width: 80%;
            z-index: 99 !important;
            margin:0 auto;

        }
    </style>

    {{--    NEW STYLE   --}}
    <style>
        .span-20{
            width: 20px !important;
            height: 20px !important;
            line-height: 20px !important;
            cursor: pointer;
        }
        .i-20{
            font-size: smaller !important;
        }

        .width-20{
            width: 20% !important;
            white-space: nowrap;
            padding: 25px 10px !important;
        }

        .no-radius{
            border-radius: 0px !important;
        }
        label .fa{
            margin: 0;
        }


    </style>
@endsection

@section('customer')


    @include('Admin.fundingReq.progress')
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

    <div id="rejectWarning" class="alert alert-warning" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>

    <div id="archiveWarning" class="alert alert-dark" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>

    <div id="approveWarning" class="alert alert-success" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>



    <div class="addUser my-4">
        <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
            <h3>
                @if ($purchaseCustomer-> type == 'شراء')
                    <span> {{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Funding') }}</span>
                @elseif ($purchaseCustomer-> type == 'رهن')
                    <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage') }}</span>
                @elseif ($purchaseCustomer-> type == 'رهن-شراء')
                    <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur') }}</span>
                @endif
            </h3>
        </div>
    </div>

    <!--agent & tools -->
    <div class="tableBar">
        <div class="topRow">
            <div class="row align-items-center text-center text-md-left">
                <!--agent name -->
                <div class="col-lg-4">
                    <div class="selectAll">
                        <div class="form-check pl-0">
                            <label class="form-check-label"  >
                                <i class="fas fa-user"></i>
                                استشاري المبيعات :
                                {{ $agentInfo->name }}
                            </label>
                        </div>
                    </div>
                </div>
                <!--agent name -->

                <div class="col-lg-8">
                    <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                        <div class="addBtn  mt-lg-0 mt-3 orderBtns">

                            <button class="pink item" id="addQuality" data-id="{{$id}}" title="{{MyHelpers::admin_trans(auth()->user()->id, 'Add To Quality')}}">
                                <i class="fas fa-paper-plane mr-2"></i>
                                {{MyHelpers::admin_trans(auth()->user()->id, 'Add To Quality')}}
                            </button>

                            <button class="mr-3 green item" id="tasks" data-id="{{$id}}"  title="{{MyHelpers::admin_trans(auth()->user()->id, 'tasks')}}">
                                <a href="{{route('all.taskReq', $id) }}" class="text-white" style="text-decoration: none">
                                    <i class="fas fa-file-download mr-2"></i>
                                    {{MyHelpers::admin_trans(auth()->user()->id, 'tasks')}}
                                </a>

                            </button>


                            @if ($purchaseCustomer->type != 'رهن-شراء' && $purchaseCustomer->type != 'شراء-دفعة' && $purchaseCustomer->statusReq != 16 &&  $purchaseCustomer->statusReq != 15 && $purchaseCustomer->statusReq != 14)
                                <button class=" item mov" id="move" data-id="{{$id}}" title="{{MyHelpers::admin_trans(auth()->user()->id, 'Move Req')}}">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    {{MyHelpers::admin_trans(auth()->user()->id, 'Move Req')}}
                                </button>
                            @endif

                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--agent & tools -->


    <form action="{{ route('admin.updateFunding')}}" method="post" novalidate="novalidate">
        @csrf

        {{--    The ID fixedTop used to make object poition is fixed on to >> you can move it to any object & delete from here--}}
        <div class="tableBar" id="fixedTop">
            <div class="topRow no-radius">
                <div class="row align-items-center text-center text-md-left">
                    <div class="col-lg-3">
                        <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                            <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                                <button class="mr-3 warning item" role="button" type="button">
                                    <a href="{{ route('all.reqHistory',$id)}}" target="_blank" class="text-white" style="text-decoration: none">
                                        <i class="fas fa-calendar mr-2"></i>
                                        {{ MyHelpers::admin_trans(auth()->user()->id,'request records') }}
                                    </a>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6"></div>
                    <div class="col-lg-3">
                        <div class="tableUserOption   flex-wrap justify-content-md-end  justify-content-center downOrder">
                            <div class="addBtn  mt-lg-0 mt-3 orderBtns">
                                <button class="mr-3 green item" type="submit" id="update" {{ $reqStatus != 26 ? '' : 'disabled'}} title="{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}">
                                    <i class="fas fa-floppy mr-2"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}
                                </button>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

            @if(!empty ($payment) && $purchaseCustomer-> type == 'شراء-دفعة')
                <div  class="userFormsInfo">
                    <div class="userFormsContainer mb-3">
                        <div class="userFormsDetails topRow">
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <div class="tableUserOption flex-wrap justify-content-md-end  justify-content-center downOrder text-center" style="display: block">
                                        <div class="addBtn  mt-lg-0 mt-3 orderBtns text-center">
                                            <button role="button" type="button" id="updatePay" data-id="{{$payment->req_id}}" class="mr-3 green item" >
                                                <i class="fa fa-floppy-o"></i>
                                                {{ MyHelpers::admin_trans(auth()->user()->id,'Update') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Prepayment') }}
                                            </button>
                                            <button role="button" type="button" id="sendPay" data-id="{{$payment->req_id}}" class="mr-3 mov item" >
                                                <i class="fa fa-send"></i>
                                                {{ MyHelpers::admin_trans(auth()->user()->id,'Send') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Prepayment') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>


        <section class="new-content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-12">
                        <ul class="list-unstyled d-flex flex-wrab align-items-center justify-content-center topRow">
                            <li id="content5" class="tab width-20 " >
                                <i class="fas fa-credit-card"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Prepayments and Tasahil') }}
                            </li>
                            <li id="content1" class="tab width-20" >
                                <i class="fas fa-layer-group"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Documents Info') }}
                            </li>
                            <li id="content2" class="tab width-20" >
                                <i class="fas fa-briefcase"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Funding Info') }}
                            </li>
                            <li id="content3" class="tab width-20" >
                                <i class="fas fa-home"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Real Estate Info') }}
                            </li>
                            <li id="content4" class="tab width-20 active-on" >
                                <i class="fas fa-user"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Customer Info') }}
                            </li>
                        </ul>

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
                                        @include('Admin.morPurReq.fundingCustomer')
                                    </div>
                                </div>

                                <div class="row hdie-show display-none" id="content1-cont">
                                    <div class="col-lg-12 mb-5 mb-md-0">
                                        @include('Admin.morPurReq.document')
                                    </div>
                                </div>

                                <div class="row hdie-show display-none" id="content2-cont">
                                    <div class="col-lg-12 mb-5 mb-md-0">
                                        @include('Admin.morPurReq.fundingInfo')
                                    </div>
                                </div>

                                <div class="row hdie-show display-none" id="content3-cont">
                                    <div class="col-lg-12 mb-5 mb-md-0">
                                        @include('Admin.morPurReq.fundingreal')
                                    </div>
                                </div>

                                <div class="row hdie-show display-none" id="content5-cont">
                                    <div class="col-lg-12   mb-md-0">
                                        @include('Admin.morPurReq.updatePage')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label style="width: 100%; display: block;" for="tab1">
                    <span>
                         @include('Admin.morPurReq.fundingReqInfo')
                    </span>
                    </label>

                </div>

                <div  class="userFormsInfo">
                    <label style="width: 100%; display: block;" for="tab1">
                    <span>
                        <div class="userFormsContainer mb-3">
                            <div class="userFormsDetails topRow">
                                @if ($reqStatus != 26)
                                    <!-- Status req of Sales agent-->
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

                                        @if ($followdate_agent != null)
                                        <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="follow" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Date') }} - الاستشاري</label>
                                            <input readonly type="date" class="form-control" value="{{ $followdate_agent->reminder_date }}" autocomplete="follow">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="follow1" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Time') }} - الاستشاري</label>
                                            <input readonly type="time" class="form-control" value="{{ $followtime_agent }}" autocomplete="follow1">
                                        </div>
                                    </div>
                                        @else
                                        <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="follow" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Date') }} - الاستشاري</label>
                                            <input readonly type="date" class="form-control"  autocomplete="follow">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="follow1" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Time') }} - الاستشاري</label>
                                            <input readonly type="time" class="form-control"  autocomplete="follow1">
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
                                                <input disabled id="follow" name="follow" type="date" class="form-control" value="{{ old('follow',$followdate->reminder_date) }}" autocomplete="follow">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label for="follow1" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Time') }}</label>
                                                <input disabled id="follow1" name="follow1" type="time" class="form-control" value="{{ old('follow1',$followtime) }}" autocomplete="follow1">
                                            </div>
                                        </div>
                                        @else
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label for="follow" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Date') }}</label>
                                                <input disabled id="follow" name="follow" type="date" class="form-control" autocomplete="follow">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label for="follow" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Time') }}</label>
                                                <input id="follow1" name="follow1" type="time" class="form-control" autocomplete="follow1">
                                            </div>
                                        </div>
                                        @endif

                                        @if ($followdate_agent != null)
                                        <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="follow" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Date') }} - الاستشاري</label>
                                            <input readonly type="date" class="form-control" value="{{ $followdate_agent->reminder_date }}" autocomplete="follow">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="follow1" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Time') }} - الاستشاري</label>
                                            <input readonly type="time" class="form-control" value="{{ $followtime_agent }}" autocomplete="follow1">
                                        </div>
                                    </div>
                                        @else
                                        <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="follow" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Date') }} - الاستشاري</label>
                                            <input readonly type="date" class="form-control"  autocomplete="follow">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="follow1" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Time') }} - الاستشاري</label>
                                            <input readonly type="time" class="form-control"  autocomplete="follow1">
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
    @include('Admin.morPurReq.req_records')
    @include('Admin.morPurReq.documentModel')
    @include('Admin.morPurReq.moveReq')
    @include('Helpers.addPhone')
@endsection

@section('confirmMSG')
    @include('Admin.morPurReq.confirmQualitySendMsg')
    @include('Admin.morPurReq.confirmationMsg')
    @include('Admin.morPurReq.confirmSendingMsg')
    @include('Admin.morPurReq.confirmRejectMsg')
@endsection


@section('js')
    <script>
        //---------------------to show wraning msg---------------
        $(document).ready(function() {

            var status = document.getElementById("statusRequest").value;

            if (status == 18) { //sending to sales manager
                document.getElementById('sendingWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has sent to Sales Manager, you cannot edit anything') }}";
                document.getElementById('sendingWarning').style.display = "block";
            }

            /*  if (status == 19) { //sending to sales agent
                  document.getElementById('sendingWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request with Sales Agent, you cannot edit anything') }}";
              document.getElementById('sendingWarning').style.display = "block";
          }
          */


            if (status == 20) { //reject from sales manager
                document.getElementById('rejectWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button>  {{ MyHelpers::admin_trans(auth()->user()->id,'The request has rejected from Sales Manager and back to Mortgage Manager') }}";
                document.getElementById('rejectWarning').style.display = "block";
            }

            if (status == 21) { //sending to funding manager
                document.getElementById('sendingWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has sent to Funding Manager, you cannot edit anything') }}";
                document.getElementById('sendingWarning').style.display = "block";
            }

            if (status == 22) { //reject from funding manager
                document.getElementById('rejectWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has rejected and back to Sales Manager') }}";
                document.getElementById('rejectWarning').style.display = "block";
            }

            if (status == 23) { //sending to genral manager
                document.getElementById('sendingWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has sent to General Manager, you cannot edit anything') }}";
                document.getElementById('sendingWarning').style.display = "block";
            }




            if (status == 24) { //canceled  in mortgage manager
                document.getElementById('archiveWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has canceled by mortgage manager') }}";
                document.getElementById('archiveWarning').style.display = "block";
            }

            /*  if (status == 27) { //canceled
                  document.getElementById('archiveWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button>  The request canceled.. <a href='{{ route('general.manager.restMorPur',$id)}}'> Recancel?</a>";
              document.getElementById('archiveWarning').style.display = "block";
          } */

            if (status == 27) { //canceled
                document.getElementById('archiveWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button>  {{ MyHelpers::admin_trans(auth()->user()->id,'The request has canceled by general manager') }}";
                document.getElementById('archiveWarning').style.display = "block";
            }

            if (status == 25) { //reject from genral manager
                document.getElementById('rejectWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has rejected and back to Funding Manager') }}";
                document.getElementById('rejectWarning').style.display = "block";
            }


            if (status == 26) { //Approved
                document.getElementById('approveWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has Completed') }}";
                document.getElementById('approveWarning').style.display = "block";
            }





        });
        //--------------------------
        $(document).ready(function() {

            var today = new Date().toISOString().split("T")[0];


            $('#jointbirth').attr("max", today);
            $('#birth').attr("max", today);
            $('#jointbirth').attr("max", today);
            $('#follow').attr("min", today + 'T00:00:00');


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
                /*
                            document.getElementById("jointmadany").style.display = "none";
                            document.getElementById("jointmadany1").style.display = "none";
                            document.getElementById("jointaskary").style.display = "none";
                            document.getElementById("jointaskary1").style.display = "none";

                */

            }
        }
        //----------------------------

        //----------------------------
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



        //-----------------------------------------------

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

        //----------------------------

        $(document).ready(function() {


            $('input[name="realtype"]').click(function() {
                if ($(this).attr('id') == 'other') {
                    document.getElementById("othervalue").style.display = "block";
                } else {
                    document.getElementById("othervalue").style.display = "none";
                    document.getElementById("otherinput").value = "";
                }
            });
        });

        //////////////////////////////////

        function monthlycalculate() {
            var pres = document.getElementById("dedp").value;
            var salary = document.getElementById("salary").value;

            document.getElementById("monthIn").value = ((pres * salary) / 100);
        }

        //////////////////////////////////////

        $(document).on('click', '#record', function(e) {

            var coloum = $(this).attr('data-id');
            var reqID = document.getElementById("reqID").value;

            // var body = document.getElementById("records");

            $.get("{{ route('all.reqRecords') }}", {
                coloum: coloum,
                reqID: reqID
            }, function(data) {

                $('#records').empty();



                if (data.status == 1) {



                    $.each(data.histories, function(i, value) {

                        var fn = $("<tr/>").attr('id', value.id);



                        if (value.comment == null) {

if (value.switch != null)
    name = value.switch+' / ' + value.name;
else
    name = value.name;

} else
name = value.comment;

                        fn.append($("<td/>", {
                            text: name
                        })).append($("<td/>", {
                            text: value.value
                        })).append($("<td/>", {
                            text: value.updateValue_at
                        }));

                        $('#records').append(fn);
                    });



                    // body.append(fn)

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



            }).fail(function(data) {


                document.getElementById('archiveWarning').innerHTML = "<button type='button' class='close' data-dismiss='alert'>&times;</button>{{ MyHelpers::admin_trans(auth()->user()->id,'Try Again') }}!";
                document.getElementById('archiveWarning').style.display = "block";


            });


        })

        /////////////////////////////////

        $(document).on('click', '#upload', function(e) {

            $('#nameError').text('');
            $('#fileError').text('');
            document.getElementById("filename").value = "";
            document.getElementById("file").value = "";
            //alert('h');
            $('#myModal1').modal('show');

        })

        //////////////////////////////

        $('#file-form').submit(function(event) {

            event.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                url: "{{ route('agent.uploadFile')}}",
                data: formData,
                type: 'post',
                async: false,
                processData: false,
                contentType: false,
                success: function(response) {

                    // console.log(response);
                    $('#myModal1').modal('hide');

                    $('#st').empty(); // to prevent dublicate of same data in each click , so i will empty before start the loop!
                    $.each(response, function(i, value) { //for loop , I put data as value

                        var docID = value.id;

                        var url = '{{ route("agent.openFile", ":docID") }}';
                        url = url.replace(':docID', docID);
                        var url2 = '{{ route("agent.downFile", ":docID") }}';
                        url2 = url2.replace(':docID', docID);


                        //  alert(docID);
                        var fn = $("<tr/>").attr('id', value.id); // if i want to add html tag or anything in html i have to define as verible $(); <tr/> : that mean create start and close tage; att (..,..) : mean add attrubite to this tage , here i add id attribute to use it in watever i want
                        fn.append($("<td/>", {
                            text: value.name
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
                error: function(xhr) {

                    var errors = xhr.responseJSON;

                    if ($.isEmptyObject(errors) == false) {

                        $.each(errors.errors, function(key, value) {

                            var ErrorID = '#' + key + 'Error';
                            // $(ErrorID).removeClass("d-none");
                            $(ErrorID).text(value);

                        })

                    }

                }
            });

        });
        ///////////////////////////////////

        $(document).on('click', '#delete', function(e) {

            var id = $(this).attr('data-id');

            var modalConfirm = function(callback) {


                $("#mi-modal").modal('show');


                $("#modal-btn-si").on("click", function() {
                    callback(true);
                    $("#mi-modal").modal('hide');
                });

                $("#modal-btn-no").on("click", function() {
                    callback(false);
                    $("#mi-modal").modal('hide');
                });
            };

            modalConfirm(function(confirm) {
                if (confirm) {

                    $.post("{{ route('agent.deleFile') }}", {
                        id: id
                    }, function(data) { // we pass var id in the request with id as name ; we use $.post : because we pass our data to server and got response

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

        /////////////////////////////////////////
        $(document).on('click', '#send', function(e) {

            var id = $(this).attr('data-id');

            var modalConfirm = function(callback) {


                $("#mi-modal4").modal('show');


                $("#modal-btn-si4").on("click", function() {
                    callback(true);
                    $("#mi-modal4").modal('hide');
                });

                $("#modal-btn-no4").on("click", function() {
                    callback(false);
                    $("#mi-modal4").modal('hide');
                });
            };

            modalConfirm(function(confirm) {
                if (confirm) {

                    var comment = document.getElementById("comment").value;
                    $.post("{{ route('agent.sendMorPur')}}", {
                        id: id,
                        comment: comment
                    }, function(data) {
                        //  console.log(data);
                        var url = '{{ route("agent.morPurRequest", ":reqID") }}';
                        url = url.replace(':reqID', data.id);

                        if (data.status == 1) {
                            window.location.href = url; //using a named route

                        } else {

                            $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

                        }

                    });

                } else {
                    //No send
                }
            });


        });

        /////////////////////////////////////////


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


        ////////////////////////////////////////////

        //-------------End tsaheel function -------------------

        //--------------CHECK MOBILE------------------------
        function changeMobile() {
            document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
            $('#checkMobile').removeClass('btn-success');
            $('#checkMobile').removeClass('btn-danger');
            $('#checkMobile').addClass('btn-info');

        }

        $(document).on('click', '#checkMobile', function(e) {



            $('#checkMobile').attr("disabled", true);
            document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Loading') }}";


            var mobile = document.getElementById('mobile').value;
            var regex = new RegExp(/^(05)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/);

            console.log(regex.test(mobile));

            if (mobile != null && regex.test(mobile)) {
                document.getElementById('error').innerHTML = "";

                $.post("{{ route('all.checkMobile') }}", {
                    mobile: mobile
                }, function(data) {
                    if (data.errors) {
                    if (data.errors.mobile) {
                        $('#mobile-error').html(data.errors.mobile[0])
                    }
                } if (data == "no") {
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


                }).fail(function(data) {


                });



            } else {
                document.getElementById('error').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Enter Valid Mobile Number (10 digits) and starts 05') }} ";
                document.getElementById('error').display = "block";
                document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
                $('#checkMobile').attr("disabled", false);

            }



        });

        //--------------END CHECK MOBILE------------------------


        ////////////////////////////////////////////
    </script>
    <!--  NEW 2/2/2020 hijri datepicker  -->
    <script src="{{url('js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
    <script type="text/javascript">
        $(function() {
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
        });
    </script>
    <script type="text/javascript">
        $("#convertToHij").click(function() {
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
                    success: function(response) {
                        // alert(response);
                        $("#hijri-date").val($.trim(response));
                    },
                    error: function() {
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

        $("#convertToGreg").click(function() {

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
                    success: function(response) {
                        // alert(response);
                         $("#birth").val($.trim(response));
                        calculate();
                    },
                    error: function() {
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
    <script>


        $(document).on('click', '#move', function(e) {



            document.getElementById("salesagent").value = '';
            document.getElementById('salesagentsError').innerHTML = '';

            var id = $(this).attr('data-id');


            $('#frm-update1').find('#id1').val(id);

            document.getElementById("movedReqID").value = id;

            $('#mi-modal7').modal('show');


        });



        //-----------------------------------------------

        $(document).on('click', '#submitMove', function(e) {



            $('#submitMove').attr("disabled", true);
            document.querySelector('#submitMove').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Please Wait') }}";

            var salesAgent = document.getElementById("salesagent").value;
            var id = document.getElementById("movedReqID").value;



            var url = "{{ route('admin.moveReqToAnother')}}";


            if (salesAgent != '') {

                $.get(url, {
                    salesAgent: salesAgent,
                    id: id
                }, function(data) { //data is array with two veribles (request[], ss)


                    if (data.updatereq == 1) {

                        document.getElementById('agentName').innerHTML = data.agentName;
                        $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

                    }


                    $('#mi-modal7').modal('hide');



                })

            } else
                document.getElementById('salesagentsError').innerHTML = 'الرجاء اختيار استشاري';
            document.querySelector('#submitMove').innerHTML = "تحويل";
            $('#submitMove').attr("disabled", false);


        });


        $(document).on('click', '#addQuality', function(e) {

            document.getElementById("qualityError").innerHTML ='';
            var quality = '';

            var id = $(this).attr('data-id');

            $('#msg2').removeClass(["alert-success", "alert-danger"]).removeAttr("style").html("");


            var modalConfirm = function(callback) {


                $("#mi-modal5").modal('show');


                $("#modal-btn-si5").on("click", function() {


                    quality = document.getElementById("qulityManager").value;

                    $("#mi-modal5").modal('hide');
                    if (quality != '') {
                        callback(true);
                        $("#mi-modal5").modal('hide');
                    } else
                        document.getElementById("qualityError").innerHTML = 'الحقل مطلوب';

                });


                $("#modal-btn-no5").on("click", function() {
                    callback(false);
                    $("#mi-modal5").modal('hide');
                });
            };

            modalConfirm(function(confirm) {
                if (confirm) {

                    $.get("{{route('admin.addReqToQuality')}}", {
                        id: id, quality: quality,
                    }, function(data) {

                        console.log(data);
                        if (data.status != 0) {
                            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

                        } else
                            $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

                    });



                } else {
                    //reject
                }
            });


        });

    </script>
    @include('Helpers.addPhoneScript')
@endsection
