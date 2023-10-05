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
            @if ($reqStatus == 3||$reqStatus == 5   || $reqStatus == 7 || $reqStatus == 10)
                <div class="row tableAdminOption" id="tableAdminOption">
                    <div class="col-12 mb-4">
                        <div class="tableUserOption flex-wrap justify-content-md-end  justify-content-center downOrder text-center" style="display: block">
                            <div class="addBtn  mt-lg-0 mt-3 orderBtns text-center">
                                <a href="{{ route('all.printReport',['id'=>$id])}}" target="_blank">
                                    <button type="button" class="text-center mr-3 green item" role="button" >
                                        <i class="fas fa-credit-card"></i> {{ MyHelpers::admin_trans(auth()->user()->id,'Print Report') }}
                                    </button>
                                </a>

                                <small id="approveTsaheel" data-id="{{$id}}" title="اعتماد تقرير تساهيل" class="iconCircle"><i class="fa fa-check"></i></small>

                                @if ($purchaseCustomer->is_approved_by_salesManager == 0)
                                    <small class="undoApprove" data-id="{{$id}}" style="font-size: medium; padding:5px;" title="التراجع عن التعميد" id="undoTsaheelApprove"></small>
                                @endif

                                @if ($purchaseCustomer->is_approved_by_salesManager == 1)
                                    <small class="undoApprove" data-id="{{$id}}" style="font-size: medium; padding:5px;" title="التراجع عن التعميد" id="undoTsaheelApprove"><i class="fa fa-history i-20 i-20"></i></small>
                                @endif

                                <small style="padding:15pt;font-weight: normal;" id="approveMsg"></small>
                            </div>
                        </div>

                    </div>
                    <hr>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="preVisa" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="visa" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'visa card') }}</label>
                            <input id="visa" name="visa" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{  old('visa',$purchaseTsa->visa) }}" autocomplete="visa" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="carLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="carlo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'car loan') }}</label>
                            <input id="carlo" name="carlo" type="number" onblur="debtcalculate()" min="0" class="form-control" value="{{ old('carlo',$purchaseTsa->carLo) }}" autocomplete="carlo" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="personalLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="perlo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'personal loan') }}</label>
                            <input id="perlo" name="perlo" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ old('perlo',$purchaseTsa->personalLo) }}" autocomplete="perlo" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="realLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="realo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate loan') }}</label>
                            <input id="realo" name="realo" type="number" min="0" onblur="debtcalculate()" class="form-control" value="{{ old('realo',$purchaseTsa->realLo) }}" autocomplete="realo" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="credBank" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="credban" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'credit bank') }}</label>
                            <input id="credban" name="credban" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ old('credban',$purchaseTsa->credit) }}" autocomplete="credban" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="otherLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="other" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'other') }}</label>
                            <input id="other1" name="other1" type="number" onblur="debtcalculate()" min="0" class="form-control" value="{{ old('other1',$purchaseTsa->other) }}" autocomplete="other" autofocus>

                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="debt" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'total debt') }}</label>
                            <input id="debt" name="debt" type="number" class="form-control" value="{{ old('debt',$purchaseTsa->debt) }}" autocomplete="debt" autofocus readonly>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="morPresnt" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="morpre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage %') }}</label>
                            <input id="morpre" name="morpre" min="0" max="100" type="number" class="form-control" value="{{ old('morpre',$purchaseTsa->mortPre) }}" autocomplete="morpre" autofocus>

                        </div>
                    </div>
                    <div class="col-9">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="mortCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="morcos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                            <input id="morcos" name="morcos" type="number" min="0" class="form-control" value="{{ old('morcos',$purchaseTsa->mortCost) }}" autocomplete="morcos" autofocus>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="pursitPresnt" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="propre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Profit %') }}</label>
                            <input id="propre" name="propre" min="0" max="100" type="number" class="form-control" value="{{ old('propre',$purchaseTsa->proftPre) }}" onblur="profcalculate()" autocomplete="propre" autofocus>

                        </div>
                    </div>
                    <div class="col-9">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="profCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="procos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                            <input id="" name="procos" type="number" min="0" class="form-control" value="{{ old('procos',$payment->profCost) }}" autocomplete="procos" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="addedValue" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="valadd" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'value added') }}</label>
                            <input id="valadd" name="valadd" min="0" type="number" class="form-control" value="{{ old('valadd',$purchaseTsa->addedVal) }}" autocomplete="valadd" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="adminFees" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="admfe" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'admin fees') }}</label>
                            <input id="admfe" name="admfe" type="number" min="0" class="form-control" value="{{ old('admfe',$purchaseTsa->adminFee) }}" autocomplete="admfe" autofocus>

                        </div>
                    </div>
                </div>
            @else
                <div class="row tableAdminOption" id="tableAdminOption">
                    <div class="col-12 mb-4">
                        <div class="tableUserOption flex-wrap justify-content-md-end  justify-content-center downOrder text-center" style="display: block">
                            <div class="addBtn  mt-lg-0 mt-3 orderBtns text-center">
                                <a href="{{ route('all.printReport',['id'=>$id])}}" target="_blank">
                                    <button type="button" class="text-center mr-3 green item" role="button" >
                                        <i class="fas fa-credit-card"></i> {{ MyHelpers::admin_trans(auth()->user()->id,'Print Report') }}
                                    </button>
                                </a>

                                <small id="approveTsaheel" data-id="{{$id}}" title="اعتماد تقرير تساهيل" class="iconCircle"><i class="fa fa-check"></i></small>

                                @if ($purchaseCustomer->is_approved_by_salesManager == 0)
                                    <small class="undoApprove" data-id="{{$id}}" style="font-size: medium; padding:5px;" title="التراجع عن التعميد" id="undoTsaheelApprove"></small>
                                @endif

                                @if ($purchaseCustomer->is_approved_by_salesManager == 1)
                                    <small class="undoApprove" data-id="{{$id}}" style="font-size: medium; padding:5px;" title="التراجع عن التعميد" id="undoTsaheelApprove"><i class="fa fa-history i-20 i-20"></i></small>
                                @endif

                                <small style="padding:15pt;font-weight: normal;" id="approveMsg"></small>
                            </div>
                        </div>

                    </div>
                    <hr>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="preVisa" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="visa" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'visa card') }}</label>
                            <input readonly id="visa" name="visa" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ $purchaseTsa->visa }}" autocomplete="visa" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="carLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="carlo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'car loan') }}</label>
                            <input readonly id="carlo" name="carlo" type="number" onblur="debtcalculate()" min="0" class="form-control" value="{{ $purchaseTsa->carLo }}" autocomplete="carlo" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="personalLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="perlo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'personal loan') }}</label>
                            <input readonly id="perlo" name="perlo" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ $purchaseTsa->personalLo }}" autocomplete="perlo" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="realLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="realo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate loan') }}</label>
                            <input readonly id="realo" name="realo" type="number" min="0" onblur="debtcalculate()" class="form-control" value="{{ $purchaseTsa->realLo }}" autocomplete="realo" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="credBank" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="credban" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'credit bank') }}</label>
                            <input readonly id="credban" name="credban" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ $purchaseTsa->credit }}" autocomplete="credban" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="otherLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="other" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'other') }}</label>
                            <input readonly id="other1" name="other1" type="number" onblur="debtcalculate()" min="0" class="form-control" value="{{ $purchaseTsa->other }}" autocomplete="other" autofocus>

                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="debt" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'total debt') }}</label>
                            <input readonly id="debt" name="debt" type="number" class="form-control" value="{{ $purchaseTsa->debt }}" autocomplete="debt" autofocus readonly>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="morPresnt" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="morpre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage %') }}</label>
                            <input readonly id="morpre" name="morpre" min="0" max="100" type="number" class="form-control" value="{{ $purchaseTsa->mortPre }}" onblur="mortcalculate()" autocomplete="morpre" autofocus>

                        </div>
                    </div>
                    <div class="col-9">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="mortCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="morcos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                            <input readonly id="morcos" name="morcos" type="number" min="0" class="form-control" value="{{ $purchaseTsa->mortCost }}" autocomplete="morcos" autofocus readonly>

                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="pursitPresnt" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="propre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Profit %') }}</label>
                            <input readonly id="propre" name="propre" min="0" max="100" type="number" class="form-control" value="{{ $purchaseTsa->proftPre }}" onblur="profcalculate()" autocomplete="propre" autofocus>

                        </div>
                    </div>
                    <div class="col-9">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="profCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="procos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                            <input readonly id="procos" name="procos" type="number" min="0" class="form-control" value="{{ $purchaseTsa->profCost }}" autocomplete="procos" autofocus readonly>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="addedValue" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="valadd" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'value added') }}</label>
                            <input readonly id="valadd" name="valadd" min="0" type="number" class="form-control" value="{{ $purchaseTsa->addedVal }}" autocomplete="valadd" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="adminFees" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="admfe" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'admin fees') }}</label>
                            <input readonly id="admfe" name="admfe" type="number" min="0" class="form-control" value="{{ $purchaseTsa->adminFee }}" autocomplete="admfe" autofocus>

                        </div>
                    </div>
                </div>
            @endif
            </div>
        </div>
    </div>
</div>


@include('SalesManager.fundingReq.prepayment')

