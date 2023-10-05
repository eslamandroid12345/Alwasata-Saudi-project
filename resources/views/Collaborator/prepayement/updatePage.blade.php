@extends('layouts.content')


@section('css_style')

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
        width: 100%;
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
</style>
@endsection

@section('customer')

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

<div id="rejectWarning" class="alert alert-danger" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>

<div id="archiveWarning" class="alert alert-dark" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>


@if ($payment->payStatus == 4 )
<!-- Status of payment-->

<form action="{{ route('collaborator.updatePrepayment')}}" method="post" novalidate="novalidate">
    @csrf

    <div class="tab_container">
        <input class="fnn" id="tab1" type="radio" name="tabs" checked>
        <label class="fnnn" for="tab1"><i class="fa fa-credit-card"></i><span>Payment</span></label>





        <input value="{{$payment->req_id}}" id="reqID" type="hidden" name="reqID"> <!-- To pass request ID-->
        <input value="{{$payment->payStatus}}" id="status" type="hidden" name="status"> <!-- To pass request ID-->

        <section id="content1" class="tab-content fnn">
            <p>

                <!-- Start Prepayment Info -->

                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <div class="card">

                            <div class="card-body">
                                <div class="card-title">
                                    <h3 class="text-center title-2">Prepayment Information</h3>
                                </div>
                                <hr>


                                <div class="form-group">
                                    <label for="debt" class="control-label mb-1">Status</label>

                                    @if ($payment->payStatus == 0)

                                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="Draft" autocomplete="payStatus" autofocus readonly>

                                    @elseif ($payment->payStatus == 1)

                                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="Wating  for Sales Manager Approval" autocomplete="payStatus" autofocus readonly>

                                    @elseif ($payment->payStatus == 2)

                                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="Canceled" autocomplete="payStatus" autofocus readonly>

                                    @elseif ($payment->payStatus == 3)

                                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="Rejected from Sales Manager" autocomplete="payStatus" autofocus readonly>

                                    @elseif ($payment->payStatus == 4)

                                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="Wating for Sales Agent Modifing" autocomplete="payStatus" autofocus readonly>

                                    @elseif ($payment->payStatus == 5)

                                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="Wating  for Mortgage Manager Approval" autocomplete="payStatus" autofocus readonly>


                                    @elseif ($payment->payStatus == 6)

                                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="Rejected from Mortgage Manager" autocomplete="payStatus" autofocus readonly>

                                    @elseif ($payment->payStatus == 7)

                                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="Approved" autocomplete="payStatus" autofocus readonly>

                                    @endif

                                </div>

                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="check" class="control-label mb-1">Check Value</label>
                                            <input id="check" name="check" type="number" class="form-control" value="{{ $purchaseReal->cost }}" autocomplete="sheck" autofocus readonly>

                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="real" class="control-label mb-1">Real Cost</label>
                                            <input id="real" name="real" type="number" min="0" class="form-control" value="{{ $payment->realCost }}" onblur="incresecalculate()" autocomplete="real" value="{{ old('real') }}" autofocus>

                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="incr" class="control-label mb-1">Increase Value</label>
                                            <input id="incr" name="incr" type="number" class="form-control" value="{{ $payment->incValue }}" autocomplete="incr" autofocus readonly>

                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="preval" class="control-label mb-1">Prepayment Value</label>
                                            <input id="preval" name="preval" type="number" min="0" class="form-control" value="{{ $payment->prepaymentVal }}" onblur="preCoscalculate()" autocomplete="preval" value="{{ old('preval') }}" autofocus>

                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="prepre" class="control-label mb-1">Prepayment %</label>
                                            <input id="prepre" name="prepre" type="number" min="0" max="100" class="form-control" value="{{ $payment->prepaymentPre }}" onblur="preCoscalculate()" autocomplete="prepre" autofocus>

                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="precos" class="control-label mb-1">Prepayment Cost</label>
                                            <input id="precos" name="precos" type="number" class="form-control" value="{{ $payment->prepaymentCos }}" autocomplete="precos" autofocus readonly>

                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="net" class="control-label mb-1">Net to the customer</label>
                                            <input id="net" name="net" type="number" min="0" class="form-control" value="{{ $payment->netCustomer }}" autocomplete="net" value="{{ old('net') }}" autofocus>

                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="deficit" class="control-label mb-1"> Customer Deficit</label>
                                            <input id="deficit" name="deficit" type="number" min="0" class="form-control" value="{{ $payment->deficitCustomer }}" autocomplete="deficit" autofocus>

                                        </div>
                                    </div>

                                </div>

                                <br><br>

                                <!-- Start Tsaheel Info -->

                                <div class="card-title">
                                    <h3 class="text-center title-2">Tsaheel Information <i onclick="showTsaheel()" class="fa fa-plus-circle text-info"></i> </h3>
                                </div>

                                <hr>

                                <div id="tsaheeldiv" style="display:block;">

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="visa" class="control-label mb-1">Visa Card</label>
                                                <input id="visa" name="visa" min="0" type="number" value=0 onblur="debtcalculate()" class="form-control" value="{{ $payment->visa }}" autocomplete="visa" autofocus>

                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="carlo" class="control-label mb-1">Car Loan</label>
                                                <input id="carlo" name="carlo" type="number" value=0 onblur="debtcalculate()" min="0" class="form-control" value="{{ $payment->carLo }}" autocomplete="carlo" autofocus>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="perlo" class="control-label mb-1">Personal Loan</label>
                                                <input id="perlo" name="perlo" min="0" type="number" value=0 onblur="debtcalculate()" class="form-control" value="{{ $payment->personalLo }}" autocomplete="perlo" autofocus>

                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="realo" class="control-label mb-1">Real Estate Loan</label>
                                                <input id="realo" name="realo" type="number" min="0" value=0 onblur="debtcalculate()" class="form-control" value="{{ $payment->realLo }}" autocomplete="realo" autofocus>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="credban" class="control-label mb-1">Credit Bank</label>
                                                <input id="credban" name="credban" min="0" type="number" value=0 onblur="debtcalculate()" class="form-control" value="{{ $payment->credit }}" autocomplete="credban" autofocus>

                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="other" class="control-label mb-1">Other</label>
                                                <input id="other" name="other" type="number" onblur="debtcalculate()" value=0 min="0" class="form-control" value="{{ $payment->other }}" autocomplete="other" autofocus>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <label for="debt" class="control-label mb-1">Total Debt</label>
                                        <input id="debt" name="debt" type="number" value=0 class="form-control" value="{{ $payment->debt }}" autocomplete="debt" autofocus readonly>
                                    </div>

                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="morpre" class="control-label mb-1">Mortgage %</label>
                                                <input id="morpre" name="morpre" min="0" max="100" type="number" class="form-control" value="{{ $payment->mortPre }}" onblur="mortcalculate()" autocomplete="morpre" autofocus>

                                            </div>
                                        </div>
                                        <div class="col-9">
                                            <div class="form-group">
                                                <label for="morcos" class="control-label mb-1">Mortgage Cost</label>
                                                <input id="morcos" name="morcos" type="number" min="0" class="form-control" value="{{ $payment->mortCost }}" autocomplete="morcos" autofocus readonly>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="propre" class="control-label mb-1">Profit %</label>
                                                <input id="propre" name="propre" min="0" max="100" type="number" class="form-control" value="{{ $payment->proftPre }}" onblur="profcalculate()" autocomplete="propre" autofocus>

                                            </div>
                                        </div>
                                        <div class="col-9">
                                            <div class="form-group">
                                                <label for="procos" class="control-label mb-1">Profit Cost</label>
                                                <input id="procos" name="procos" type="number" min="0" class="form-control" value="{{ $payment->profCost }}" autocomplete="procos" autofocus readonly>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="valadd" class="control-label mb-1">Value Added</label>
                                                <input id="valadd" name="valadd" min="0" type="number" class="form-control" value="{{ $payment->addedVal }}" autocomplete="valadd" autofocus>

                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="admfe" class="control-label mb-1">Admin Fees</label>
                                                <input id="admfe" name="admfe" type="number" min="0" class="form-control" value="{{ $payment->adminFee }}" autocomplete="admfe" autofocus>

                                            </div>
                                        </div>

                                    </div>

                                </div>


                                <!-- End Tsaheel Info -->

                            </div>
                        </div>
                    </div>
                </div>

                <!-- End Prepayment Info -->


            </p>
        </section>


    </div>

    <div class="tab_container" style=" padding-top: 8px;" disabled>
        <input class="fnn" name="tabs" disabled>
        <label class="fnnn" style="width: 100%; display: block;background: #fff;color: #999;border-bottom: 2px solid #f0f0f0;" for="tab1">
            <span>

                <div class="row">



                    <div class="col-2">
                    </div>

                    <div class="col-4">
                        <button type="submit" id="update" class="btn btn-success btn-block"><i class="fa fa-floppy-o"></i>Update</button>
                    </div>

                    <div class="col-4">
                        <button type="button" id="send" data-id="{{$payment->req_id}}" class="btn btn-info btn-block"><i class="fa fa-send"></i>Send</button>
                    </div>



                </div>


            </span>
        </label>
    </div>

    <div class="tab_container" style=" padding-top: 8px;" disabled>
        <input class="fnn" name="tabs" disabled>
        <label class="fnnn" style="width: 100%; display: block;background: #fff;color: #999;border-bottom: 2px solid #f0f0f0;" for="tab1">
            <span>

                <div class="row">


                    <div class="col-4">
                    </div>


                    @if ($request->type != 'رهن-شراء')

                    <div class="col-4">
                        <a href="{{ route('collaborator.fundingRequest',$id)}}">
                            <button type="button" class="btn btn-block btn-outline-primary"><i class="fa fa-credit-card"></i>View Request</button>
                        </a>
                    </div>

                    @else

                    <div class="col-4">
                        <a href="{{ route('collaborator.morPurRequest',$id)}}">
                            <button type="button" class="btn btn-block btn-outline-primary"><i class="fa fa-credit-card"></i>View Request</button>
                        </a>
                    </div>


                    @endif
                    <div class="col-4">
                    </div>



                </div>


            </span>
        </label>
    </div>



</form>


@else


<form action="{{ route('collaborator.updatePrepayment')}}" method="post" novalidate="novalidate">
    @csrf

    <div class="tab_container">
        <input class="fnn" id="tab1" type="radio" name="tabs" checked>
        <label class="fnnn" for="tab1"><i class="fa fa-credit-card"></i><span>Payment</span></label>





        <input value="{{$payment->req_id}}" id="reqID" type="hidden" name="reqID"> <!-- To pass request ID-->
        <input value="{{$payment->payStatus}}" id="status" type="hidden" name="status"> <!-- To pass request ID-->


        <section id="content1" class="tab-content fnn">
            <p>

                <!-- Start Prepayment Info -->

                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <div class="card">

                            <div class="card-body">
                                <div class="card-title">
                                    <h3 class="text-center title-2">Prepayment Information</h3>
                                </div>
                                <hr>


                                <div class="form-group">
                                    <label for="debt" class="control-label mb-1">Status</label>

                                    @if ($payment->payStatus == 0)

                                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="Draft" autocomplete="payStatus" autofocus readonly>

                                    @elseif ($payment->payStatus == 1)

                                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="Wating Sales Manager Approval" autocomplete="payStatus" autofocus readonly>

                                    @elseif ($payment->payStatus == 2)

                                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="Canceled" autocomplete="payStatus" autofocus readonly>

                                    @elseif ($payment->payStatus == 3)

                                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="Rejected from Sales Manager" autocomplete="payStatus" autofocus readonly>


                                    @elseif ($payment->payStatus == 4)

                                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="Wating for Sales Agent Modifing" autocomplete="payStatus" autofocus readonly>

                                    @elseif ($payment->payStatus == 5)

                                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="Wating  for Mortgage Manager Approval" autocomplete="payStatus" autofocus readonly>

                                    @elseif ($payment->payStatus == 6)

                                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="Rejected from Mortgage Manager" autocomplete="payStatus" autofocus readonly>

                                    @elseif ($payment->payStatus == 7)

                                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="Approved" autocomplete="payStatus" autofocus readonly>

                                    @endif

                                </div>

                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="check" class="control-label mb-1">Check Value</label>
                                            <input readonly id="check" name="check" type="number" class="form-control" value="{{ $purchaseReal->cost }}" autocomplete="sheck" autofocus readonly>

                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="real" class="control-label mb-1">Real Cost</label>
                                            <input readonly id="real" name="real" type="number" min="0" class="form-control" value="{{ $payment->realCost }}" onblur="incresecalculate()" autocomplete="real" value="{{ old('real') }}" autofocus>

                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="incr" class="control-label mb-1">Increase Value</label>
                                            <input readonly id="incr" name="incr" type="number" class="form-control" value="{{ $payment->incValue }}" autocomplete="incr" autofocus readonly>

                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="preval" class="control-label mb-1">Prepayment Value</label>
                                            <input readonly id="preval" name="preval" type="number" min="0" class="form-control" value="{{ $payment->prepaymentVal }}" onblur="preCoscalculate()" autocomplete="preval" value="{{ old('preval') }}" autofocus>

                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="prepre" class="control-label mb-1">Prepayment %</label>
                                            <input readonly id="prepre" name="prepre" type="number" min="0" max="100" class="form-control" value="{{ $payment->prepaymentPre }}" onblur="preCoscalculate()" autocomplete="prepre" autofocus>

                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="precos" class="control-label mb-1">Prepayment Cost</label>
                                            <input readonly id="precos" name="precos" type="number" class="form-control" value="{{ $payment->prepaymentCos }}" autocomplete="precos" autofocus readonly>

                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="net" class="control-label mb-1">Net to the customer</label>
                                            <input readonly id="net" name="net" type="number" min="0" class="form-control" value="{{ $payment->netCustomer }}" autocomplete="net" value="{{ old('net') }}" autofocus>

                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="deficit" class="control-label mb-1"> Customer Deficit</label>
                                            <input readonly id="deficit" name="deficit" type="number" min="0" class="form-control" value="{{ $payment->deficitCustomer }}" autocomplete="deficit" autofocus>

                                        </div>
                                    </div>

                                </div>

                                <br><br>

                                <!-- Start Tsaheel Info -->

                                <div class="card-title">
                                    <h3 class="text-center title-2">Tsaheel Information <i class="fa fa-plus-circle text-info"></i> </h3>
                                </div>

                                <hr>

                                <div id="tsaheeldiv" style="display:block;">

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="visa" class="control-label mb-1">Visa Card</label>
                                                <input readonly id="visa" name="visa" min="0" type="number" value=0 onblur="debtcalculate()" class="form-control" value="{{ $payment->visa }}" autocomplete="visa" autofocus>

                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="carlo" class="control-label mb-1">Car Loan</label>
                                                <input readonly id="carlo" name="carlo" type="number" value=0 onblur="debtcalculate()" min="0" class="form-control" value="{{ $payment->carLo }}" autocomplete="carlo" autofocus>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="perlo" class="control-label mb-1">Personal Loan</label>
                                                <input readonly id="perlo" name="perlo" min="0" type="number" value=0 onblur="debtcalculate()" class="form-control" value="{{ $payment->personalLo }}" autocomplete="perlo" autofocus>

                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="realo" class="control-label mb-1">Real Estate Loan</label>
                                                <input readonly id="realo" name="realo" type="number" min="0" value=0 onblur="debtcalculate()" class="form-control" value="{{ $payment->realLo }}" autocomplete="realo" autofocus>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="credban" class="control-label mb-1">Credit Bank</label>
                                                <input readonly id="credban" name="credban" min="0" type="number" value=0 onblur="debtcalculate()" class="form-control" value="{{ $payment->credit }}" autocomplete="credban" autofocus>

                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="other" class="control-label mb-1">Other</label>
                                                <input readonly id="other" name="other" type="number" onblur="debtcalculate()" value=0 min="0" class="form-control" value="{{ $payment->other }}" autocomplete="other" autofocus>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <label for="debt" class="control-label mb-1">Total Debt</label>
                                        <input readonly id="debt" name="debt" type="number" value=0 class="form-control" value="{{ $payment->debt }}" autocomplete="debt" autofocus readonly>
                                    </div>

                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="morpre" class="control-label mb-1">Mortgage %</label>
                                                <input readonly id="morpre" name="morpre" min="0" max="100" type="number" class="form-control" value="{{ $payment->mortPre }}" onblur="mortcalculate()" autocomplete="morpre" autofocus>

                                            </div>
                                        </div>
                                        <div class="col-9">
                                            <div class="form-group">
                                                <label for="morcos" class="control-label mb-1">Mortgage Cost</label>
                                                <input readonly id="morcos" name="morcos" type="number" min="0" class="form-control" value="{{ $payment->mortCost }}" autocomplete="morcos" autofocus readonly>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="propre" class="control-label mb-1">Profit %</label>
                                                <input readonly id="propre" name="propre" min="0" max="100" type="number" class="form-control" value="{{ $payment->proftPre }}" onblur="profcalculate()" autocomplete="propre" autofocus>

                                            </div>
                                        </div>
                                        <div class="col-9">
                                            <div class="form-group">
                                                <label for="procos" class="control-label mb-1">Profit Cost</label>
                                                <input readonly id="procos" name="procos" type="number" min="0" class="form-control" value="{{ $payment->profCost }}" autocomplete="procos" autofocus readonly>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="valadd" class="control-label mb-1">Value Added</label>
                                                <input readonly id="valadd" name="valadd" min="0" type="number" class="form-control" value="{{ $payment->addedVal }}" autocomplete="valadd" autofocus>

                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="admfe" class="control-label mb-1">Admin Fees</label>
                                                <input readonly id="admfe" name="admfe" type="number" min="0" class="form-control" value="{{ $payment->adminFee }}" autocomplete="admfe" autofocus>

                                            </div>
                                        </div>

                                    </div>

                                </div>


                                <!-- End Tsaheel Info -->

                            </div>
                        </div>
                    </div>
                </div>

                <!-- End Prepayment Info -->


            </p>
        </section>


    </div>

    <div class="tab_container" style=" padding-top: 8px;" disabled>
        <input class="fnn" name="tabs" disabled>
        <label class="fnnn" style="width: 100%; display: block;background: #fff;color: #999;border-bottom: 2px solid #f0f0f0;" for="tab1">
            <span>

                <div class="row">


                    <div class="col-2">
                    </div>

                    <div class="col-4">
                        <button disabled type="submit" id="update" class="btn btn-success btn-block"><i class="fa fa-floppy-o"></i>Update</button>
                    </div>

                    <div class="col-4">
                        <button disabled type="button" id="send" data-id="{{$payment->req_id}}" class="btn btn-info btn-block"><i class="fa fa-send"></i>Send</button>
                    </div>



                </div>


            </span>
        </label>
    </div>


    <div class="tab_container" style=" padding-top: 8px;" disabled>
        <input class="fnn" name="tabs" disabled>
        <label class="fnnn" style="width: 100%; display: block;background: #fff;color: #999;border-bottom: 2px solid #f0f0f0;" for="tab1">
            <span>

                <div class="row">


                    <div class="col-4">
                    </div>


                    @if ($request->type != 'رهن-شراء')

                    <div class="col-4">
                        <a href="{{ route('collaborator.fundingRequest',$id)}}">
                            <button type="button" class="btn btn-block btn-outline-primary"><i class="fa fa-credit-card"></i>View Request</button>
                        </a>
                    </div>

                    @else

                    <div class="col-4">
                        <a href="{{ route('collaborator.morPurRequest',$id)}}">
                            <button type="button" class="btn btn-block btn-outline-primary"><i class="fa fa-credit-card"></i>View Request</button>
                        </a>
                    </div>


                    @endif
                    <div class="col-4">
                    </div>



                </div>


            </span>
        </label>
    </div>




</form>

@endif





@endsection

@section('confirmMSG')
@include('Collaborator.prepayement.confirmSendingMsg')
@endsection



@section('scripts')
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //------------------------------------

    $(document).ready(function() {

        var status = document.getElementById("status").value;


        if (status == 5) { //send to mortgage 
            document.getElementById('sendingWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> The prepayment send to mortgage manager";
            document.getElementById('sendingWarning').style.display = "block";
        }

        if (status == 1) { //send to mortgage 
            document.getElementById('sendingWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> The prepayment send to sales manager";
            document.getElementById('sendingWarning').style.display = "block";
        }


        if (status == 2) { //canceled payment
            document.getElementById('archiveWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> The prepayment canceled";
            document.getElementById('archiveWarning').style.display = "block";
        }

        if (status == 3) { //rejected payment
            document.getElementById('rejectWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> The prepayment rejected from sales manager";
            document.getElementById('rejectWarning').style.display = "block";
        }

        if (status == 7) { //approved
            document.getElementById('approveWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> The prepayment approved";
            document.getElementById('approveWarning').style.display = "block";
        }

        if (status == 6) {
            document.getElementById('rejectWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> The prepayment rejected and back to sales manager";
            document.getElementById('rejectWarning').style.display = "block";
        }


    });


    //--------------------------------------
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
            document.getElementById("visa").value = "";
            document.getElementById("carlo").value = "";
            document.getElementById("perlo").value = "";
            document.getElementById("realo").value = "";
            document.getElementById("credban").value = "";
            document.getElementById("other").value = "";
            document.getElementById("debt").value = "";
            document.getElementById("morpre").value = "";
            document.getElementById("morcos").value = "";
            document.getElementById("propre").value = "";
            document.getElementById("procos").value = "";
            document.getElementById("valadd").value = "";
            document.getElementById("admfe").value = "";

        }
    }

    //-----------------------------------------------
    function debtcalculate() {
        var visa = parseInt(document.getElementById("visa").value);
        var car = parseInt(document.getElementById("carlo").value);

        var personal = parseInt(document.getElementById("perlo").value);
        var realEstat = parseInt(document.getElementById("realo").value);

        var credit = parseInt(document.getElementById("credban").value);
        var other = parseInt(document.getElementById("other").value);

        document.getElementById("debt").value = visa + car + personal + realEstat + credit + other;
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





    //-----------------------------------------------


    //-----------------------------------------------

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
                $.get("{{ route('collaborator.sendPrepayment')}}", {
                    id: id, comment: comment
                }, function(data) {
                    var url = '{{ route("collaborator.updatePrepaymentPage", ":reqID") }}';
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

    //-----------------------------------------------





    ////////////////////////////////////////////
</script>