
<div class="card-title">
    <h3 class="text-center title-2">{{ MyHelpers::admin_trans(auth()->user()->id,'Prepayment Info') }}<i onclick="showPrepay()" class="fa fa-plus-circle text-info"></i> </h3>
</div>

<hr>

<div id="prepaydiv" style="display:block;">

    <div class="row">
        <div class="col-4">
            <div class="form-group">
                <label for="check" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'check value') }}</label>
                <input readonly id="check" name="check" type="number" class="form-control" value="{{ $purchaseReal->cost }}" autocomplete="sheck" autofocus readonly>

            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
            <button class="item" id="record" data-id="realCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                <label for="real" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real cost') }}</label>
                <input readonly id="real" name="real" type="number" min="0" class="form-control" onblur="incresecalculate()" autocomplete="real" value="{{ $purchaseTsa->realCost }}" autofocus>

            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
            <button class="item" id="record" data-id="incValue" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                <label for="incr" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'increase value') }}</label>
                <input readonly id="incr" name="incr" type="number" class="form-control" value="{{ $purchaseTsa->incValue }}" autocomplete="incr" autofocus readonly>

            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-4">
            <div class="form-group">
            <button class="item" id="record" data-id="preValue" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                <label for="preval" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'prepayment value') }}</label>
                <input readonly id="preval" name="preval" type="number" min="0" class="form-control" onblur="preCoscalculate()" autocomplete="preval" value="{{ $purchaseTsa->prepaymentVal }}" autofocus>

            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
            <button class="item" id="record" data-id="prePresent" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                <label for="prepre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'prepayment %') }}</label>
                <input readonly id="prepre" name="prepre" type="number" min="0" max="100" class="form-control" onblur="preCoscalculate()" value="{{ $purchaseTsa->prepaymentPre }}" autocomplete="prepre" autofocus>

            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
            <button class="item" id="record" data-id="preCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                <label for="precos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                <input readonly id="precos" name="precos" type="number" class="form-control" value="{{ $purchaseTsa->prepaymentCos }}" autocomplete="precos" autofocus readonly>

            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
            <button class="item" id="record" data-id="netCust" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                <label for="net" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'net to the customer') }}</label>
                <input readonly id="net" name="net" type="number" min="0" class="form-control" autocomplete="net" value="{{ $purchaseTsa->netCustomer }}" autofocus>

            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
            <button class="item" id="record" data-id="deficitCust" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                <label for="deficit" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer deficit') }}</label>
                <input readonly id="deficit" name="deficit" type="number" min="0" class="form-control" value="{{ $purchaseTsa->deficitCustomer }}" autocomplete="deficit" autofocus>

            </div>
        </div>

    </div>

</div>
