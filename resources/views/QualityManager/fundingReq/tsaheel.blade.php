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
                            <span class="item pointer span-20"id="record" data-id="preVisa" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="visa" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'visa card') }}</label>
                            <input readonly id="visa" name="visa" min="0" type="number"onblur="debtcalculate()" class="form-control" value="{{ $purchaseTsa->visa }}" autocomplete="visa" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20"id="record" data-id="carLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="carlo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'car loan') }}</label>
                            <input readonly id="carlo" name="carlo" type="number" onblur="debtcalculate()" min="0" class="form-control" value="{{ $purchaseTsa->carLo }}" autocomplete="carlo" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20"id="record" data-id="personalLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="perlo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'personal loan') }}</label>
                            <input readonly id="perlo" name="perlo" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ $purchaseTsa->personalLo }}" autocomplete="perlo" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20"id="record" data-id="realLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="realo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate loan') }}</label>
                            <input readonly id="realo" name="realo" type="number" min="0"  onblur="debtcalculate()" class="form-control" value="{{ $purchaseTsa->realLo }}" autocomplete="realo" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20"id="record" data-id="credBank" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="credban" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'credit bank') }}</label>
                            <input readonly id="credban" name="credban" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ $purchaseTsa->credit }}" autocomplete="credban" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20"id="record" data-id="otherLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="other" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'other') }}</label>
                            <input readonly id="other1" name="other1" type="number" onblur="debtcalculate()"  min="0" class="form-control" value="{{ $purchaseTsa->other }}"  autocomplete="other" autofocus>

                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="debt" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'total debt') }}</label>
                            <input readonly id="debt" name="debt" type="number" class="form-control" value="{{ $purchaseTsa->debt }}"  autocomplete="debt" autofocus readonly>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <span class="item pointer span-20"id="record" data-id="morPresnt" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="morpre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage %') }}</label>
                            <input readonly id="morpre" name="morpre" min="0" max="100" type="number" class="form-control" value="{{ $purchaseTsa->mortPre }}" onblur="mortcalculate()" autocomplete="morpre" autofocus>

                        </div>
                    </div>
                    <div class="col-9">
                        <div class="form-group">
                            <span class="item pointer span-20"id="record" data-id="mortCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="morcos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                            <input readonly id="morcos" name="morcos" type="number" min="0" class="form-control" value="{{ $purchaseTsa->mortCost }}" autocomplete="morcos" autofocus readonly>

                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <span class="item pointer span-20"id="record" data-id="pursitPresnt" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="propre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Profit %') }}</label>
                            <input readonly id="propre" name="propre" min="0" max="100" type="number" class="form-control" value="{{ $purchaseTsa->proftPre }}" onblur="profcalculate()" autocomplete="propre" autofocus>

                        </div>
                    </div>
                    <div class="col-9">
                        <div class="form-group">
                            <span class="item pointer span-20"id="record" data-id="profCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="procos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                            <input id="procos" name="procos" type="number" min="0" class="form-control" value="{{ old('procos',$purchaseTsa->profCost) }}" autocomplete="procos" autofocus readonly>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20"id="record" data-id="addedValue" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="valadd" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'value added') }}</label>
                            <input readonly id="valadd" name="valadd" min="0" type="number" class="form-control" value="{{ $purchaseTsa->addedVal }}"  autocomplete="valadd" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20"id="record" data-id="adminFees" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="admfe" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'admin fees') }}</label>
                            <input readonly id="admfe" name="admfe" type="number" min="0" class="form-control" value="{{ $purchaseTsa->adminFee }}" autocomplete="admfe" autofocus>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@include('QualityManager.fundingReq.prepayment')

