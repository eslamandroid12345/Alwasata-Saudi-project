@if ($reqStatus == 23 )
    <!-- Status of payment-->
    <div  class="userFormsInfo  ">
        <div class="headER topRow text-center ">
            <i class="fas fa-layer-group"></i>
            <h4>{{ MyHelpers::admin_trans(auth()->user()->id,'Prepayment Info') }}</h4>
        </div>
        <div class="userFormsContainer mb-3">
            <div class="userFormsDetails topRow">

                <div class="row tableAdminOption">
                    <div class="col-md-12 mb-4">
                        <div class="tableUserOption flex-wrap justify-content-md-end  justify-content-center downOrder text-center" style="display: block">
                            <div class="addBtn  mt-lg-0 mt-3 orderBtns text-center">
                                <a href="{{ route('all.aqarReport',['id'=>$id])}}" target="_blank">
                                    <button type="button" role="button" class="text-center mr-3 green item">
                                        <i class="fas fa-print"></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Print Report') }}</button></a>
                            </div>
                        </div>
                    </div>
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

            </div>
        </div>
    </div>
    <!-- Start Tsaheel Info -->
    <div class="userFormsInfo  ">
        <div class="userFormsContainer mb-3">
            <div class="tsaheelInfo topRow ">
                <div class="tsaheelHeader">
                    <div class="addBtn">
                        <button class="w-100" role="button" type="button">
                            <i class="fas fa-plus-circle"></i>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Tasahil Info') }}
                        </button>
                    </div>
                </div>
                <div class="tsaheeldiv  mt-3">
                    <div class="row tableAdminOption" id="tableAdminOption">
                        <div class="col-6">
                            <div class="form-group">
                                <span class="item pointer span-20" id="record" data-id="preVisa" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                <label for="visa" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'visa card') }}</label>
                                <input id="visa" name="visa" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ $payment->visa }}" autocomplete="visa" autofocus>

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="carlo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'car loan') }}</label>
                                <input id="carlo" name="carlo" type="number" onblur="debtcalculate()" min="0" class="form-control" value="{{ $payment->carLo }}" autocomplete="carlo" autofocus>

                            </div>
                        </div>
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
                        <div class="col-12">
                            <div class="form-group">
                                <label for="debt" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'total debt') }}</label>
                                <input id="debt" name="debt" type="number" class="form-control" value="{{ $payment->debt }}" autocomplete="debt" autofocus readonly>
                            </div>
                        </div>
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
            </div>
        </div>
    </div>
    <!-- Start Tsaheel Info -->
    <!-- End Prepayment Info -->
@else
    <!-- Status of payment-->
    <div  class="userFormsInfo  ">
        <div class="headER topRow text-center ">
            <i class="fas fa-layer-group"></i>
            <h4>{{ MyHelpers::admin_trans(auth()->user()->id,'Prepayment Info') }}</h4>
        </div>
        <div class="userFormsContainer mb-3">
            <div class="userFormsDetails topRow">

                <div class="row tableAdminOption">
                    <div class="col-md-12 mb-4">
                        <div class="tableUserOption flex-wrap justify-content-md-end  justify-content-center downOrder text-center" style="display: block">
                            <div class="addBtn  mt-lg-0 mt-3 orderBtns text-center">
                                <a href="{{ route('all.aqarReport',['id'=>$id])}}" target="_blank">
                                    <button type="button" role="button" class="text-center mr-3 green item">
                                        <i class="fas fa-print"></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Print Report') }}</button></a>

                            </div>
                        </div>
                    </div>
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

            </div>
        </div>
    </div>
    <!-- Start Tsaheel Info -->
    <div class="userFormsInfo  ">
        <div class="userFormsContainer mb-3">
            <div class="tsaheelInfo topRow ">
                <div class="tsaheelHeader">
                    <div class="addBtn">
                        <button class="w-100" role="button" type="button">
                            <i class="fas fa-plus-circle"></i>
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Tasahil Info') }}
                        </button>
                    </div>
                </div>
                <div class="tsaheeldiv  mt-3">
                    <div class="row tableAdminOption" id="tableAdminOption">
                        <div class="col-6">
                            <div class="form-group">
                                <span class="item pointer span-20" id="record" data-id="preVisa" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
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
                        <div class="col-12">
                            <div class="form-group">
                                <label for="debt" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'total debt') }}</label>
                                <input readonly id="debt" name="debt" type="number" class="form-control" value="{{ $payment->debt }}" autocomplete="debt" autofocus readonly>
                            </div>
                        </div>

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
            </div>
        </div>
    </div>
    <!-- Start Tsaheel Info -->
    <!-- End Prepayment Info -->
@endif
