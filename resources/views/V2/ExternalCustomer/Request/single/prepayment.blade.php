<div class="userFormsInfo  ">
    <div class="userFormsContainer mb-3">
        <div class="prepayInfo topRow ">
            <div class="prepayHeader">
                <div class="addBtn">
                    <button class="w-100" role="button" type="button">
                        <i class="fas fa-plus-circle"></i>
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Prepayment Info') }}
                    </button>
                </div>
            </div>
            <div class="prepaydiv  mt-3">
            @if ($reqStatus == 0 || $reqStatus == 1 || $reqStatus == 2 || $reqStatus == 4 || $reqStatus == 31)
                <div class="row tableAdminOption" id="tableAdminOption">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="check" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'check value') }}</label>
                            <input id="check" name="check" type="number" class="form-control" value="{{ old('check',$purchaseReal->cost) }}" autocomplete="sheck" autofocus readonly>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="realCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="real" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real cost') }}</label>
                            <input id="real" name="real" type="number" min="0" class="form-control" onblur="incresecalculate()" autocomplete="real" value="{{ old('real',$purchaseTsa->realCost) }}" autofocus>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="incValue" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="incr" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'increase value') }}</label>
                            <input id="incr" name="incr" type="number" class="form-control" value="{{ old('incr',$purchaseTsa->incValue) }}" autocomplete="incr" autofocus readonly>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="preValue" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="preval" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'prepayment value') }}</label>
                            <input id="preval" name="preval" type="number" min="0" class="form-control preval_m" onblur="preCoscalculate()" autocomplete="preval" value="{{ old('preval',$purchaseTsa->prepaymentVal) }}" autofocus>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="prePresent" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="prepre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'prepayment %') }}</label>
                            <input id="prepre" name="prepre" type="number" min="0" max="100" class="form-control" onblur="preCoscalculate()" value="{{ old('prepre', $purchaseTsa->prepaymentPre) }}" autocomplete="prepre" autofocus>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="preCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="precos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                            <input id="precos" name="precos" type="number" class="form-control" value="{{ old('precos',$purchaseTsa->prepaymentCos) }}" autocomplete="precos" autofocus readonly>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="netCust" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="net" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'net to the customer') }}</label>
                            <input id="net" name="net" type="number" min="0" class="form-control" autocomplete="net" value="{{ old('net',$purchaseTsa->netCustomer) }}" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="deficitCust" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="deficit" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer deficit') }}</label>
                            <input id="deficit" name="deficit" type="number" min="0" class="form-control" value="{{ old('deficit',$purchaseTsa->deficitCustomer) }}" autocomplete="deficit" autofocus>

                        </div>
                    </div>
                </div>
            @else
                <div class="row tableAdminOption" id="tableAdminOption">
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
                            <input readonly id="real" name="real" type="number" min="0" class="form-control" onblur="incresecalculate()" autocomplete="real" value="{{ $purchaseTsa->realCost }}" autofocus>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="incValue" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="incr" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'increase value') }}</label>
                            <input readonly id="incr" name="incr" type="number" class="form-control" value="{{ $purchaseTsa->incValue }}" autocomplete="incr" autofocus readonly>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="preValue" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="preval" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'prepayment value') }}</label>
                            <input readonly id="preval" name="preval" type="number" min="0" class="form-control preval_m" onblur="preCoscalculate()" autocomplete="preval" value="{{ $purchaseTsa->prepaymentVal }}" autofocus>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="prePresent" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="prepre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'prepayment %') }}</label>
                            <input readonly id="prepre" name="prepre" type="number" min="0" max="100" class="form-control" onblur="preCoscalculate()" value="{{ $purchaseTsa->prepaymentPre }}" autocomplete="prepre" autofocus>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="preCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="precos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                            <input readonly id="precos" name="precos" type="number" class="form-control" value="{{ $purchaseTsa->prepaymentCos }}" autocomplete="precos" autofocus readonly>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="netCust" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="net" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'net to the customer') }}</label>
                            <input readonly id="net" name="net" type="number" min="0" class="form-control" autocomplete="net" value="{{ $purchaseTsa->netCustomer }}" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="deficitCust" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="deficit" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer deficit') }}</label>
                            <input readonly id="deficit" name="deficit" type="number" min="0" class="form-control" value="{{ $purchaseTsa->deficitCustomer }}" autocomplete="deficit" autofocus>

                        </div>
                    </div>
                </div>
            @endif
            </div>
        </div>
    </div>
</div>

