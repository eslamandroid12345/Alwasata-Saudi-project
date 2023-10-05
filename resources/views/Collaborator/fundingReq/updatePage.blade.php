@if ($payment->payStatus == 4 )
<!-- Status of payment-->
<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card">

            <div class="card-body">
                <div class="card-title">
                    <h3 class="text-center title-2">{{ MyHelpers::admin_trans(auth()->user()->id,'Prepayment Info') }}</h3>
                </div>
                <div class="table-data__tool-right">
                    <a href="{{ route('all.printReport',['id'=>$id])}}" target="_blank">
                        <button type="button" class="au-btn au-btn-icon au-btn--blue au-btn--small">
                            <i class="zmdi zmdi-print"></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Print Report') }}</button></a>
                </div>
                <hr>

                <div class="form-group">
                    <label for="debt" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'status') }}</label>

                    @if ($payment->payStatus == 0)

                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'draft pay') }}" autocomplete="payStatus" autofocus readonly>

                    @elseif ($payment->payStatus == 1)

                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment send to sales manager') }}" autocomplete="payStatus" autofocus readonly>

                    @elseif ($payment->payStatus == 2)

                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment canceled') }}" autocomplete="payStatus" autofocus readonly>


                    @elseif ($payment->payStatus == 3)

                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment has rejected and back to funding manager') }}" autocomplete="payStatus" autofocus readonly>


                    @elseif ($payment->payStatus == 4)

                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment send to sales agent') }}" autocomplete="payStatus" autofocus readonly>

                    @elseif ($payment->payStatus == 5)

                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment send to mortgage manager') }}" autocomplete="payStatus" autofocus readonly>


                    @elseif ($payment->payStatus == 6)

                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment rejected from mortgage manager') }}" autocomplete="payStatus" autofocus readonly>

                    @elseif ($payment->payStatus == 7)

                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment approved') }}" autocomplete="payStatus" autofocus readonly>

                    @endif

                </div>

                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="check" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'check value') }}</label>
                            <input id="check" name="check" type="number" class="form-control" value="{{ $purchaseReal->cost }}" autocomplete="sheck" autofocus readonly>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="real" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real cost') }}</label>
                            <input id="real" name="real" type="number" min="0" class="form-control" value="{{ $payment->realCost }}" onblur="incresecalculate()" autocomplete="real" value="{{ old('real') }}" autofocus>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="incr" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'increase value') }}</label>
                            <input id="incr" name="incr" type="number" class="form-control" value="{{ $payment->incValue }}" autocomplete="incr" autofocus readonly>

                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="preval" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'prepayment value') }}</label>
                            <input id="preval" name="preval" type="number" min="0" class="form-control" value="{{ $payment->prepaymentVal }}" onblur="preCoscalculate()" autocomplete="preval" value="{{ old('preval') }}" autofocus>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="prepre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'prepayment %') }}</label>
                            <input id="prepre" name="prepre" type="number" min="0" max="100" class="form-control" value="{{ $payment->prepaymentPre }}" onblur="preCoscalculate()" autocomplete="prepre" autofocus>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="precos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                            <input id="precos" name="precos" type="number" class="form-control" value="{{ $payment->prepaymentCos }}" autocomplete="precos" autofocus readonly>

                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="net" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'net to the customer') }}</label>
                            <input id="net" name="net" type="number" min="0" class="form-control" value="{{ $payment->netCustomer }}" autocomplete="net" value="{{ old('net') }}" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="deficit" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer deficit') }}</label>
                            <input id="deficit" name="deficit" type="number" min="0" class="form-control" value="{{ $payment->deficitCustomer }}" autocomplete="deficit" autofocus>

                        </div>
                    </div>

                </div>

                <br><br>

                <!-- Start Tsaheel Info -->

                <div class="card-title">
                    <h3 class="text-center title-2">{{ MyHelpers::admin_trans(auth()->user()->id,'Tasahil Info') }} <i onclick="showTsaheel()" class="fa fa-plus-circle text-info"></i> </h3>
                </div>

                <hr>

                <div id="tsaheeldiv" style="display:block;">


                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <button class="item" id="record" data-id="preVisa" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history" style="font-size: medium;"></i></button>
                                <label for="visa" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'visa card') }}</label>
                                <input id="visa" name="visa" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ $payment->visa }}" autocomplete="visa" autofocus>

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="carlo" class="control-label mb-1">>{{ MyHelpers::admin_trans(auth()->user()->id,'car loan') }}</label>
                                <input id="carlo" name="carlo" type="number" onblur="debtcalculate()" min="0" class="form-control" value="{{ $payment->carLo }}" autocomplete="carlo" autofocus>

                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="perlo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'personal loan') }}</label>
                                <input id="perlo" name="perlo" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ $payment->personalLo }}" autocomplete="perlo" autofocus>

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="realo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate loan') }}</label>
                                <input id="realo" name="realo" type="number" min="0" value=0 onblur="debtcalculate()" class="form-control" value="{{ $payment->realLo }}" autocomplete="realo" autofocus>

                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="credban" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'credit bank') }}</label>
                                <input id="credban" name="credban" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ $payment->credit }}" autocomplete="credban" autofocus>

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="other" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'other') }}</label>
                                <input id="other1" name="other1" type="number" onblur="debtcalculate()" value=0 min="0" class="form-control" value="{{ $payment->other }}" autocomplete="other" autofocus>

                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="debt" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'total debt') }}</label>
                        <input id="debt" name="debt" type="number" class="form-control" value="{{ $payment->debt }}" autocomplete="debt" autofocus readonly>
                    </div>

                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="morpre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage %') }}</label>
                                <input id="morpre" name="morpre" min="0" max="100" type="number" class="form-control" value="{{ $payment->mortPre }}" onblur="mortcalculate()" autocomplete="morpre" autofocus>

                            </div>
                        </div>
                        <div class="col-9">
                            <div class="form-group">
                                <label for="morcos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                                <input id="morcos" name="morcos" type="number" min="0" class="form-control" value="{{ $payment->mortCost }}" autocomplete="morcos" autofocus readonly>

                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="propre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Profit %') }}</label>
                                <input id="propre" name="propre" min="0" max="100" type="number" class="form-control" value="{{ $payment->proftPre }}" onblur="profcalculate()" autocomplete="propre" autofocus>

                            </div>
                        </div>
                        <div class="col-9">
                            <div class="form-group">
                                <label for="procos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                                <input id="procos" name="procos" type="number" min="0" class="form-control" value="{{ $payment->profCost }}" autocomplete="procos" autofocus readonly>

                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="valadd" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'value added') }}</label>
                                <input id="valadd" name="valadd" min="0" type="number" class="form-control" value="{{ $payment->addedVal }}" autocomplete="valadd" autofocus>

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="admfe" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'admin fees') }}</label>
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


@else



<!-- Start Prepayment Info -->
<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card">

            <div class="card-body">
                <div class="card-title">
                    <h3 class="text-center title-2">{{ MyHelpers::admin_trans(auth()->user()->id,'Prepayment Info') }}</h3>
                </div>
                <div class="table-data__tool-right">
                    <a href="{{ route('all.printReport',['id'=>$id])}}" target="_blank">
                        <button type="button" class="au-btn au-btn-icon au-btn--blue au-btn--small">
                            <i class="zmdi zmdi-print"></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Print Report') }}</button></a>
                </div>
                <hr>

                <div class="form-group">
                    <label for="debt" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'status') }}</label>

                    @if ($payment->payStatus == 0)

                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'draft pay') }}" autocomplete="payStatus" autofocus readonly>

                    @elseif ($payment->payStatus == 1)

                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment send to sales manager') }}" autocomplete="payStatus" autofocus readonly>

                    @elseif ($payment->payStatus == 2)

                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment canceled') }}" autocomplete="payStatus" autofocus readonly>


                    @elseif ($payment->payStatus == 3)

                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment has rejected and back to funding manager') }}" autocomplete="payStatus" autofocus readonly>


                    @elseif ($payment->payStatus == 4)

                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment send to sales agent') }}" autocomplete="payStatus" autofocus readonly>

                    @elseif ($payment->payStatus == 5)

                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment send to mortgage manager') }}" autocomplete="payStatus" autofocus readonly>


                    @elseif ($payment->payStatus == 6)

                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment rejected from mortgage manager') }}" autocomplete="payStatus" autofocus readonly>

                    @elseif ($payment->payStatus == 7)

                    <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment approved') }}" autocomplete="payStatus" autofocus readonly>

                    @endif

                </div>

                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="check" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'check value') }}</label>
                            <input readonly id="check" name="check" type="number" class="form-control" value="{{ $purchaseReal->cost }}" autocomplete="sheck" autofocus readonly>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="real" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real cost') }}</label>
                            <input readonly id="real" name="real" type="number" min="0" class="form-control" value="{{ $payment->realCost }}" onblur="incresecalculate()" autocomplete="real" value="{{ old('real') }}" autofocus>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="incr" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'increase value') }}</label>
                            <input readonly id="incr" name="incr" type="number" class="form-control" value="{{ $payment->incValue }}" autocomplete="incr" autofocus readonly>

                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="preval" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'prepayment value') }}</label>
                            <input readonly id="preval" name="preval" type="number" min="0" class="form-control" value="{{ $payment->prepaymentVal }}" onblur="preCoscalculate()" autocomplete="preval" value="{{ old('preval') }}" autofocus>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="prepre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'prepayment %') }}</label>
                            <input readonly id="prepre" name="prepre" type="number" min="0" max="100" class="form-control" value="{{ $payment->prepaymentPre }}" onblur="preCoscalculate()" autocomplete="prepre" autofocus>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="precos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                            <input readonly id="precos" name="precos" type="number" class="form-control" value="{{ $payment->prepaymentCos }}" autocomplete="precos" autofocus readonly>

                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="net" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'net to the customer') }}</label>
                            <input readonly id="net" name="net" type="number" min="0" class="form-control" value="{{ $payment->netCustomer }}" autocomplete="net" value="{{ old('net') }}" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="deficit" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer deficit') }}</label>
                            <input readonly id="deficit" name="deficit" type="number" min="0" class="form-control" value="{{ $payment->deficitCustomer }}" autocomplete="deficit" autofocus>

                        </div>
                    </div>

                </div>

                <br><br>

                <!-- Start Tsaheel Info -->

                <div class="card-title">
                    <h3 class="text-center title-2">{{ MyHelpers::admin_trans(auth()->user()->id,'Tasahil Info') }} <i onclick="showTsaheel()" class="fa fa-plus-circle text-info"></i> </h3>
                </div>

                <hr>

                <div id="tsaheeldiv" style="display:block;">


                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <button class="item" id="record" data-id="preVisa" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history" style="font-size: medium;"></i></button>
                                <label for="visa" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'visa card') }}</label>
                                <input readonly id="visa" name="visa" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ $payment->visa }}" autocomplete="visa" autofocus>

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="carlo" class="control-label mb-1">>{{ MyHelpers::admin_trans(auth()->user()->id,'car loan') }}</label>
                                <input readonly id="carlo" name="carlo" type="number" onblur="debtcalculate()" min="0" class="form-control" value="{{ $payment->carLo }}" autocomplete="carlo" autofocus>

                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="perlo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'personal loan') }}</label>
                                <input readonly id="perlo" name="perlo" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ $payment->personalLo }}" autocomplete="perlo" autofocus>

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="realo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate loan') }}</label>
                                <input readonly id="realo" name="realo" type="number" min="0" value=0 onblur="debtcalculate()" class="form-control" value="{{ $payment->realLo }}" autocomplete="realo" autofocus>

                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="credban" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'credit bank') }}</label>
                                <input readonly id="credban" name="credban" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ $payment->credit }}" autocomplete="credban" autofocus>

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="other" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'other') }}</label>
                                <input readonly id="other1" name="other1" type="number" onblur="debtcalculate()" value=0 min="0" class="form-control" value="{{ $payment->other }}" autocomplete="other" autofocus>

                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="debt" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'total debt') }}</label>
                        <input readonly id="debt" name="debt" type="number" class="form-control" value="{{ $payment->debt }}" autocomplete="debt" autofocus readonly>
                    </div>

                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="morpre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage %') }}</label>
                                <input readonly id="morpre" name="morpre" min="0" max="100" type="number" class="form-control" value="{{ $payment->mortPre }}" onblur="mortcalculate()" autocomplete="morpre" autofocus>

                            </div>
                        </div>
                        <div class="col-9">
                            <div class="form-group">
                                <label for="morcos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                                <input readonly id="morcos" name="morcos" type="number" min="0" class="form-control" value="{{ $payment->mortCost }}" autocomplete="morcos" autofocus readonly>

                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="propre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Profit %') }}</label>
                                <input readonly id="propre" name="propre" min="0" max="100" type="number" class="form-control" value="{{ $payment->proftPre }}" onblur="profcalculate()" autocomplete="propre" autofocus>

                            </div>
                        </div>
                        <div class="col-9">
                            <div class="form-group">
                                <label for="procos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                                <input readonly id="procos" name="procos" type="number" min="0" class="form-control" value="{{ $payment->profCost }}" autocomplete="procos" autofocus readonly>

                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="valadd" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'value added') }}</label>
                                <input readonly id="valadd" name="valadd" min="0" type="number" class="form-control" value="{{ $payment->addedVal }}" autocomplete="valadd" autofocus>

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="admfe" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'admin fees') }}</label>
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


@endif