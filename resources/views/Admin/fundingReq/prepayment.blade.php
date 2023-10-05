
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
                <div class="row tableAdminOption" id="tableAdminOption">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="check" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'check value') }}</label>
                            <input id="check" name="check" type="number" class="form-control" value="{{ old('check',$purchaseReal->cost) }}" autocomplete="sheck" autofocus readonly>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20" data-id="realCost" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="real" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real cost') }}</label>
                            <input id="real" name="real" type="number" min="0" class="form-control" onblur="incresecalculate()" autocomplete="real" value="{{ old('real',$purchaseTsa->realCost) }}" autofocus>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20" data-id="incValue" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="incr" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'increase value') }}</label>
                            <input id="incr" name="incr" type="number" class="form-control" value="{{ old('incr',$purchaseTsa->incValue) }}" autocomplete="incr" autofocus readonly>

                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20" data-id="preValue" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="preval" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'prepayment value') }}</label>
                            <input id="preval" name="preval" type="number" min="0" class="form-control" onblur="preCoscalculate()" autocomplete="preval" value="{{ old('preval',$purchaseTsa->prepaymentVal) }}" autofocus>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20" data-id="prePresent" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="prepre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'prepayment %') }}</label>
                            <input id="prepre" name="prepre" type="number" min="0" max="100" class="form-control" onblur="preCoscalculate()" value="{{ old('prepre', $purchaseTsa->prepaymentPre) }}" autocomplete="prepre" autofocus>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20" data-id="preCost" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="precos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                            <input id="precos" name="precos" type="number" class="form-control" value="{{ old('precos',$purchaseTsa->prepaymentCos) }}" autocomplete="precos" autofocus readonly>

                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20" data-id="netCust" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="net" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'net to the customer') }}</label>
                            <input id="net" name="net" type="number" min="0" class="form-control" autocomplete="net" value="{{ old('net',$purchaseTsa->netCustomer) }}" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20" data-id="deficitCust" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="deficit" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer deficit') }}</label>
                            <input id="deficit" name="deficit" type="number" min="0" class="form-control" value="{{ old('deficit',$purchaseTsa->deficitCustomer) }}" autocomplete="deficit" autofocus>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
