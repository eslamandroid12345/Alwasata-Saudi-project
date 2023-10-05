@if (!empty ($payment))
@if (($payment->payStatus == 4 || $payment->payStatus == 3 ) && ($reqStatus == 6 || $reqStatus == 8 || $reqStatus == 13))
<!-- Status of payment-->
<div class="userFormsInfo  ">
    <div class="headER topRow text-center ">
        <i class="fas fa-layer-group"></i>
        <h4>{{ MyHelpers::admin_trans(auth()->user()->id,'Prepayment Info') }}</h4>
    </div>
    <div class="userFormsContainer mb-3">
        <div class="userFormsDetails topRow">
            <div class="row tableAdminOption" id="tableAdminOption">
                <div class="col-12" style="margin-bottom: 30px">
                    <div class="tableUserOption flex-wrap justify-content-md-end  justify-content-center downOrder text-center" style="display: block">
                        <div class="addBtn  mt-lg-0 mt-3 orderBtns text-center">
                            <a href="{{ route('all.printReport',['id'=>$id])}}" target="_blank">
                                <button type="button" role="button" class="text-center mr-3 green item">
                                    <i class="fas fa-cloud-upload-alt"></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Print Report') }}</button></a>
                        </div>
                    </div>
                </div>
                <div class="col-12" style="margin-bottom: 15px">
                    <div class="form-group">
                        <label for="debt" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'status') }}</label>

                        @if ($payment->payStatus == 0)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'draft in funding manager') }}" autocomplete="payStatus" autofocus readonly>

                        @elseif ($payment->payStatus == 1)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'wating for sales maanger') }}" autocomplete="payStatus" autofocus readonly>

                        @elseif ($payment->payStatus == 2)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'funding manager canceled') }}" autocomplete="payStatus" autofocus readonly>


                        @elseif ($payment->payStatus == 3)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'rejected from sales maanger') }}" autocomplete="payStatus" autofocus readonly>


                        @elseif ($payment->payStatus == 4)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'wating for sales agent') }}" autocomplete="payStatus" autofocus readonly>

                        @elseif ($payment->payStatus == 5)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'wating for mortgage maanger') }}" autocomplete="payStatus" autofocus readonly>


                        @elseif ($payment->payStatus == 6)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'rejected from mortgage maanger') }}" autocomplete="payStatus" autofocus readonly>

                        @elseif ($payment->payStatus == 7)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'approve from mortgage maanger') }}" autocomplete="payStatus" autofocus readonly>

                        @elseif ($payment->payStatus == 8)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'mortgage manager canceled') }}" autocomplete="payStatus" autofocus readonly>

                        @elseif ($payment->payStatus == 9)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment is completed') }}" autocomplete="payStatus" autofocus readonly>

                        @endif

                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="check" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'check value') }}</label>
                        <input id="check" name="check" type="number" class="form-control" value="{{  old('check',$purchaseReal->cost) }}" autocomplete="sheck" autofocus readonly>

                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="realCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="real" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real cost') }}</label>
                        <input id="real" name="real" type="number" min="0" class="form-control" value="{{ old('real',$payment->realCost) }}" onblur="incresecalculate()" autocomplete="real" value="{{ old('real') }}" autofocus>

                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="incValue" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="incr" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'increase value') }}</label>
                        <input id="incr" name="incr" type="number" class="form-control" value="{{ old('incr',$payment->incValue) }}" autocomplete="incr" autofocus readonly>

                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="preValue" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="preval" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'prepayment value') }}</label>
                        <input id="preval" name="preval" type="number" min="0" class="form-control" value="{{ old('preval',$payment->prepaymentVal) }}" onblur="preCoscalculate()" autocomplete="preval" value="{{ old('preval') }}" autofocus>

                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="prePresent" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="prepre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'prepayment %') }}</label>
                        <input id="prepre" name="prepre" type="number" min="0" max="100" class="form-control" value="{{ old('prepre',$payment->prepaymentPre) }}" onblur="preCoscalculate()" autocomplete="prepre" autofocus>

                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="preCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="precos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                        <input id="precos" name="precos" type="number" class="form-control" value="{{ old('precos',$payment->prepaymentCos) }}" autocomplete="precos" autofocus readonly>

                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="netCust" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="net" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'net to the customer') }}</label>
                        <input id="net" name="net" type="number" min="0" class="form-control" value="{{ old('net',$payment->netCustomer) }}" autocomplete="net" value="{{ old('net') }}" autofocus>

                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="deficitCust" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="deficit" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer deficit') }}</label>
                        <input id="deficit" name="deficit" type="number" min="0" class="form-control" value="{{ old('deficit',$payment->deficitCustomer) }}" autocomplete="deficit" autofocus>

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
                            <input id="visa" name="visa" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ old('visa',$payment->visa) }}" autocomplete="visa" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="carLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="carlo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'car loan') }}</label>
                            <input id="carlo" name="carlo" type="number" onblur="debtcalculate()" min="0" class="form-control" value="{{ old('carlo', $payment->carLo) }}" autocomplete="carlo" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="personalLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="perlo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'personal loan') }}</label>
                            <input id="perlo" name="perlo" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ old('perlo', $payment->personalLo) }}" autocomplete="perlo" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="realLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="realo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate loan') }}</label>
                            <input id="realo" name="realo" type="number" min="0" onblur="debtcalculate()" class="form-control realo_missedFiledInput FiledInput" value="{{ old('realo',$payment->realLo) }}" autocomplete="realo" >


                            <small style="color:#e60000" class="d-none realo_missedFileds missedFileds">الحقل مطلوب</small>


                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="credBank" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="credban" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'credit bank') }}</label>
                            <input id="credban" name="credban" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ old('credban',$payment->credit) }}" autocomplete="credban" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20 " id="record" data-id="realDisposition" data-comment="بيانات التساهيل" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="credban" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'Real estate disposition') }}</label>
                            <input id="Real_estate_disposition_value_tsaheel" name="Real_estate_disposition_value_tsaheel" min="0" type="number" onblur="debtcalculate()" class="form-control m_disposition_value" value="{{  $purchaseTsa->Real_estate_disposition_value }}" autocomplete="Real_estate_disposition_value_tsaheel" autofocus>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20 " id="record" data-id="purchaseTax" data-comment="بيانات التساهيل" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="credban" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'Purchase tax') }}</label>
                            <input id="purchase_tax_value_tsaheel" name="purchase_tax_value_tsaheel" min="0" type="number" onblur="debtcalculate()" class="form-control m_purchase_tax_value" value="{{  $purchaseTsa->purchase_tax_value }}" autocomplete="purchase_tax_value_tsaheel" autofocus>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="otherLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="other" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'other') }}</label>
                            <input id="other1" name="other1" type="number" onblur="debtcalculate()" min="0" class="form-control" value="{{ old('other1',$payment->other) }}" autocomplete="other" autofocus>

                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="debt" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'total debt') }}</label>
                            <input id="debt" name="debt" type="number" class="form-control" value="{{ old('debt',$payment->debt ) }}" autocomplete="debt" autofocus readonly>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="morPresnt" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="morpre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage %') }}</label>
                            <input id="morpre" name="morpre" min="0" max="100" type="number" class="form-control" value="{{ old('morpre',$payment->mortPre) }}" autocomplete="morpre" autofocus>

                        </div>
                    </div>
                    <div class="col-9">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="mortCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="morcos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                            <input id="morcos" name="morcos" type="number" min="0" class="form-control" value="{{ old('morcos',$payment->mortCost) }}" autocomplete="morcos" autofocus>

                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="pursitPresnt" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="propre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Profit %') }}</label>
                            <input id="propre" name="propre" min="0" max="100" type="number" class="form-control" value="{{ old('propre',$payment->proftPre) }}" autocomplete="propre" autofocus>

                        </div>
                    </div>
                    <div class="col-9">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="profCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="procos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                            <input id="procos" name="procos" type="number" min="0" class="form-control" value="{{ old('procos',$payment->profCost) }}" autocomplete="procos" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="addedValue" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="valadd" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'value added') }}</label>
                            <input id="valadd" name="valadd" min="0" type="number" class="form-control" value="{{ old('valadd',$payment->addedVal) }}" autocomplete="valadd" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="adminFees" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="admfe" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'admin fees') }}</label>
                            <input id="admfe" name="admfe" type="number" min="0" class="form-control" value="{{ old('admfe',$payment->adminFee) }}" autocomplete="admfe" autofocus>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('MortgageCalculator.mortgage_info')
<!-- Start Tsaheel Info -->
<!-- End Prepayment Info -->
@else
<!-- Status of payment-->
<div class="userFormsInfo  ">
    <div class="headER topRow text-center ">
        <i class="fas fa-layer-group"></i>
        <h4>{{ MyHelpers::admin_trans(auth()->user()->id,'Prepayment Info') }}</h4>
    </div>
    <br>
    <div class="userFormsContainer mb-3">
        <div class="userFormsDetails topRow">
            <div class="row tableAdminOption" id="tableAdminOption">
                <div class="col-12" style="margin-bottom: 30px">
                    <div class="tableUserOption flex-wrap justify-content-md-end  justify-content-center downOrder text-center" style="display: block">
                        <div class="addBtn  mt-lg-0 mt-3 orderBtns text-center">
                            <a href="{{ route('all.printReport',['id'=>$id])}}" target="_blank">
                                <button disabled style="cursor: not-allowed" type="button" role="button" class="text-center mr-3 green item">
                                    <i class="fas fa-cloud-upload-alt"></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Print Report') }}</button></a>
                        </div>
                    </div>
                </div>
                <div class="col-12" style="margin-bottom: 15px">
                    <div class="form-group">
                        <label for="debt" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'status') }}</label>

                        @if ($payment->payStatus == 0)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'draft in funding manager') }}" autocomplete="payStatus" autofocus readonly>

                        @elseif ($payment->payStatus == 1)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'wating for sales maanger') }}" autocomplete="payStatus" autofocus readonly>

                        @elseif ($payment->payStatus == 2)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'funding manager canceled') }}" autocomplete="payStatus" autofocus readonly>


                        @elseif ($payment->payStatus == 3)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'rejected from sales maanger') }}" autocomplete="payStatus" autofocus readonly>


                        @elseif ($payment->payStatus == 4)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'wating for sales agent') }}" autocomplete="payStatus" autofocus readonly>

                        @elseif ($payment->payStatus == 5)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'wating for mortgage maanger') }}" autocomplete="payStatus" autofocus readonly>


                        @elseif ($payment->payStatus == 6)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'rejected from mortgage maanger') }}" autocomplete="payStatus" autofocus readonly>

                        @elseif ($payment->payStatus == 7)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'approve from mortgage maanger') }}" autocomplete="payStatus" autofocus readonly>

                        @elseif ($payment->payStatus == 8)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'mortgage manager canceled') }}" autocomplete="payStatus" autofocus readonly>

                        @elseif ($payment->payStatus == 9)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment is completed') }}" autocomplete="payStatus" autofocus readonly>

                        @endif
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
                        <span class="item pointer span-20" id="record" data-id="realCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="real" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real cost') }}</label>
                        <input readonly id="real" name="real" type="number" min="0" class="form-control" value="{{ $payment->realCost }}" onblur="incresecalculate()" autocomplete="real" value="{{ old('real') }}" autofocus>

                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="incValue" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="incr" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'increase value') }}</label>
                        <input readonly id="incr" name="incr" type="number" class="form-control" value="{{ $payment->incValue }}" autocomplete="incr" autofocus readonly>

                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="preValue" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="preval" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'prepayment value') }}</label>
                        <input readonly id="preval" name="preval" type="number" min="0" class="form-control" value="{{ $payment->prepaymentVal }}" onblur="preCoscalculate()" autocomplete="preval" value="{{ old('preval') }}" autofocus>

                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="prePresent" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="prepre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'prepayment %') }}</label>
                        <input readonly id="prepre" name="prepre" type="number" min="0" max="100" class="form-control" value="{{ $payment->prepaymentPre }}" onblur="preCoscalculate()" autocomplete="prepre" autofocus>

                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="preCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="precos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                        <input readonly id="precos" name="precos" type="number" class="form-control" value="{{ $payment->prepaymentCos }}" autocomplete="precos" autofocus readonly>

                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="netCust" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="net" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'net to the customer') }}</label>
                        <input readonly id="net" name="net" type="number" min="0" class="form-control" value="{{ $payment->netCustomer }}" autocomplete="net" value="{{ old('net') }}" autofocus>

                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="deficitCust" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
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
                            <span class="item pointer span-20" id="record" data-id="carLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="carlo" class="control-label mb-1">>{{ MyHelpers::admin_trans(auth()->user()->id,'car loan') }}</label>
                            <input readonly id="carlo" name="carlo" type="number" onblur="debtcalculate()" min="0" class="form-control" value="{{ $payment->carLo }}" autocomplete="carlo" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="personalLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="perlo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'personal loan') }}</label>
                            <input readonly id="perlo" name="perlo" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ $payment->personalLo }}" autocomplete="perlo" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="realLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="realo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate loan') }}</label>
                            <input readonly id="realo" name="realo" type="number" min="0" onblur="debtcalculate()" class="form-control" value="{{ $payment->realLo }}" autocomplete="realo" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="credBank" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="credban" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'credit bank') }}</label>
                            <input readonly id="credban" name="credban" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ $payment->credit }}" autocomplete="credban" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20 " id="record" data-id="realDisposition" data-comment="بيانات التساهيل" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="credban" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'Real estate disposition') }}</label>
                            <input readonly id="Real_estate_disposition_value_tsaheel" name="Real_estate_disposition_value_tsaheel" min="0" type="number" onblur="debtcalculate()" class="form-control m_disposition_value" value="{{  $purchaseTsa->Real_estate_disposition_value }}" autocomplete="Real_estate_disposition_value_tsaheel" autofocus>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20 " id="record" data-id="purchaseTax" data-comment="بيانات التساهيل" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="credban" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'Purchase tax') }}</label>
                            <input readonly id="purchase_tax_value_tsaheel" name="purchase_tax_value_tsaheel" min="0" type="number" onblur="debtcalculate()" class="form-control m_purchase_tax_value" value="{{  $purchaseTsa->purchase_tax_value }}" autocomplete="purchase_tax_value_tsaheel" autofocus>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="otherLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="other" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'other') }}</label>
                            <input readonly id="other1" name="other1" type="number" onblur="debtcalculate()" min="0" class="form-control" value="{{ $payment->other }}" autocomplete="other" autofocus>

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
                            <span class="item pointer span-20" id="record" data-id="morPresnt" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="morpre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage %') }}</label>
                            <input readonly id="morpre" name="morpre" min="0" max="100" type="number" class="form-control" value="{{ $payment->mortPre }}" onblur="mortcalculate()" autocomplete="morpre" autofocus>

                        </div>
                    </div>
                    <div class="col-9">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="mortCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="morcos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                            <input readonly id="morcos" name="morcos" type="number" min="0" class="form-control" value="{{ $payment->mortCost }}" autocomplete="morcos" autofocus readonly>

                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="pursitPresnt" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="propre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Profit %') }}</label>
                            <input readonly id="propre" name="propre" min="0" max="100" type="number" class="form-control" value="{{ $payment->proftPre }}" autocomplete="propre" autofocus>

                        </div>
                    </div>
                    <div class="col-9">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="profCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="procos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                            <input readonly id="procos" name="procos" type="number" min="0" class="form-control" value="{{ $payment->profCost }}" autocomplete="procos" autofocus readonly>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="addedValue" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="valadd" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'value added') }}</label>
                            <input readonly id="valadd" name="valadd" min="0" type="number" class="form-control" value="{{ $payment->addedVal }}" autocomplete="valadd" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="adminFees" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="admfe" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'admin fees') }}</label>
                            <input readonly id="admfe" name="admfe" type="number" min="0" class="form-control" value="{{ $payment->adminFee }}" autocomplete="admfe" autofocus>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('MortgageCalculator.mortgage_info')
<!-- Start Tsaheel Info -->
<!-- End Prepayment Info -->
@endif



@elseif (!empty ($paymentForDisplayonly))
<!-- Status of payment-->
<div class="userFormsInfo  ">
    <div class="headER topRow text-center ">
        <i class="fas fa-layer-group"></i>
        <h4>{{ MyHelpers::admin_trans(auth()->user()->id,'Prepayment Info') }}</h4>
    </div>
    <br>
    <div class="userFormsContainer mb-3">
        <div class="userFormsDetails topRow">
            <div class="row tableAdminOption" id="tableAdminOption">
                <div class="col-12" style="margin-bottom: 30px">
                    <div class="tableUserOption flex-wrap justify-content-md-end  justify-content-center downOrder text-center" style="display: block">
                        <div class="addBtn  mt-lg-0 mt-3 orderBtns text-center">
                            <a href="{{ route('all.printReport',['id'=>$id])}}" target="_blank">
                                <button disabled style="cursor: not-allowed" type="button" role="button" class="text-center mr-3 green item">
                                    <i class="fas fa-cloud-upload-alt"></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Print Report') }}</button></a>
                        </div>
                    </div>
                </div>
                <div class="col-12" style="margin-bottom: 15px">
                    <div class="form-group">
                        <label for="debt" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'status') }}</label>

                        @if ($paymentForDisplayonly->payStatus == 0)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'draft in funding manager') }}" autocomplete="payStatus" autofocus readonly>

                        @elseif ($paymentForDisplayonly->payStatus == 1)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'wating for sales maanger') }}" autocomplete="payStatus" autofocus readonly>

                        @elseif ($paymentForDisplayonly->payStatus == 2)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'funding manager canceled') }}" autocomplete="payStatus" autofocus readonly>


                        @elseif ($paymentForDisplayonly->payStatus == 3)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'rejected from sales maanger') }}" autocomplete="payStatus" autofocus readonly>


                        @elseif ($paymentForDisplayonly->payStatus == 4)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'wating for sales agent') }}" autocomplete="payStatus" autofocus readonly>

                        @elseif ($paymentForDisplayonly->payStatus == 5)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'wating for mortgage maanger') }}" autocomplete="payStatus" autofocus readonly>


                        @elseif ($paymentForDisplayonly->payStatus == 6)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'rejected from mortgage maanger') }}" autocomplete="payStatus" autofocus readonly>

                        @elseif ($paymentForDisplayonly->payStatus == 7)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'approve from mortgage maanger') }}" autocomplete="payStatus" autofocus readonly>

                        @elseif ($paymentForDisplayonly->payStatus == 8)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'mortgage manager canceled') }}" autocomplete="payStatus" autofocus readonly>

                        @elseif ($paymentForDisplayonly->payStatus == 9)

                        <input id="payStatus" name="payStatus" type="text" class="form-control" value="{{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment is completed') }}" autocomplete="payStatus" autofocus readonly>

                        @endif
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
                        <span class="item pointer span-20" id="record" data-id="realCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="real" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real cost') }}</label>
                        <input readonly id="real" name="real" type="number" min="0" class="form-control" value="{{ $paymentForDisplayonly->realCost }}" onblur="incresecalculate()" autocomplete="real" value="{{ old('real') }}" autofocus>

                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="incValue" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="incr" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'increase value') }}</label>
                        <input readonly id="incr" name="incr" type="number" class="form-control" value="{{ $paymentForDisplayonly->incValue }}" autocomplete="incr" autofocus readonly>

                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="preValue" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="preval" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'prepayment value') }}</label>
                        <input readonly id="preval" name="preval" type="number" min="0" class="form-control" value="{{ $paymentForDisplayonly->prepaymentVal }}" onblur="preCoscalculate()" autocomplete="preval" value="{{ old('preval') }}" autofocus>

                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="prePresent" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="prepre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'prepayment %') }}</label>
                        <input readonly id="prepre" name="prepre" type="number" min="0" max="100" class="form-control" value="{{ $paymentForDisplayonly->prepaymentPre }}" onblur="preCoscalculate()" autocomplete="prepre" autofocus>

                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="preCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="precos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                        <input readonly id="precos" name="precos" type="number" class="form-control" value="{{ $paymentForDisplayonly->prepaymentCos }}" autocomplete="precos" autofocus readonly>

                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="netCust" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="net" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'net to the customer') }}</label>
                        <input readonly id="net" name="net" type="number" min="0" class="form-control" value="{{ $paymentForDisplayonly->netCustomer }}" autocomplete="net" value="{{ old('net') }}" autofocus>

                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="deficitCust" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="deficit" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer deficit') }}</label>
                        <input readonly id="deficit" name="deficit" type="number" min="0" class="form-control" value="{{ $paymentForDisplayonly->deficitCustomer }}" autocomplete="deficit" autofocus>

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
                            <input readonly id="visa" name="visa" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ $paymentForDisplayonly->visa }}" autocomplete="visa" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="carLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="carlo" class="control-label mb-1">>{{ MyHelpers::admin_trans(auth()->user()->id,'car loan') }}</label>
                            <input readonly id="carlo" name="carlo" type="number" onblur="debtcalculate()" min="0" class="form-control" value="{{ $paymentForDisplayonly->carLo }}" autocomplete="carlo" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="personalLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="perlo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'personal loan') }}</label>
                            <input readonly id="perlo" name="perlo" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ $paymentForDisplayonly->personalLo }}" autocomplete="perlo" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="realLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="realo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate loan') }}</label>
                            <input readonly id="realo" name="realo" type="number" min="0" onblur="debtcalculate()" class="form-control" value="{{ $paymentForDisplayonly->realLo }}" autocomplete="realo" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="credBank" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="credban" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'credit bank') }}</label>
                            <input readonly id="credban" name="credban" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ $paymentForDisplayonly->credit }}" autocomplete="credban" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="otherLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="other" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'other') }}</label>
                            <input readonly id="other1" name="other1" type="number" onblur="debtcalculate()" min="0" class="form-control" value="{{ $paymentForDisplayonly->other }}" autocomplete="other" autofocus>

                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="debt" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'total debt') }}</label>
                            <input readonly id="debt" name="debt" type="number" class="form-control" value="{{ $paymentForDisplayonly->debt }}" autocomplete="debt" autofocus readonly>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="morPresnt" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="morpre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage %') }}</label>
                            <input readonly id="morpre" name="morpre" min="0" max="100" type="number" class="form-control" value="{{ $paymentForDisplayonly->mortPre }}" onblur="mortcalculate()" autocomplete="morpre" autofocus>

                        </div>
                    </div>
                    <div class="col-9">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="mortCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="morcos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                            <input readonly id="morcos" name="morcos" type="number" min="0" class="form-control" value="{{ $paymentForDisplayonly->mortCost }}" autocomplete="morcos" autofocus readonly>

                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="pursitPresnt" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="propre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Profit %') }}</label>
                            <input readonly id="propre" name="propre" min="0" max="100" type="number" class="form-control" value="{{ $paymentForDisplayonly->proftPre }}" autocomplete="propre" autofocus>

                        </div>
                    </div>
                    <div class="col-9">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="profCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="procos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                            <input readonly id="procos" name="procos" type="number" min="0" class="form-control" value="{{ $paymentForDisplayonly->profCost }}" autocomplete="procos" autofocus readonly>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="addedValue" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="valadd" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'value added') }}</label>
                            <input readonly id="valadd" name="valadd" min="0" type="number" class="form-control" value="{{ $paymentForDisplayonly->addedVal }}" autocomplete="valadd" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="adminFees" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="admfe" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'admin fees') }}</label>
                            <input readonly id="admfe" name="admfe" type="number" min="0" class="form-control" value="{{ $paymentForDisplayonly->adminFee }}" autocomplete="admfe" autofocus>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('MortgageCalculator.mortgage_info')
<!-- Start Tsaheel Info -->
<!-- End Prepayment Info -->
@endif
