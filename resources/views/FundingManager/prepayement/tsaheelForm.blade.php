
@if (!empty($purchaseTsa))
<div class="card-title">
    <h3 class="text-center title-2">{{ MyHelpers::admin_trans(auth()->user()->id,'Tasahil Info') }} <i onclick="showTsaheel()" class="fa fa-plus-circle text-info"></i> </h3>
</div>

<hr>

<div id="tsaheeldiv" style="display:block;">

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="visa" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'visa card') }}</label>
                <input readonly id="visa" name="visa" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ $purchaseTsa->visa }}" autocomplete="visa" autofocus>

            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label for="carlo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'car loan') }}</label>
                <input readonly id="carlo" name="carlo" type="number" onblur="debtcalculate()" min="0" class="form-control" value="{{ $purchaseTsa->carLo }}" autocomplete="carlo" autofocus>

            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="perlo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'personal loan') }}</label>
                <input readonly id="perlo" name="perlo" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ $purchaseTsa->personalLo }}" autocomplete="perlo" autofocus>

            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label for="realo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate loan') }}</label>
                <input readonly id="realo" name="realo" type="number" min="0" onblur="debtcalculate()" class="form-control" value="{{ $purchaseTsa->realLo }}" autocomplete="realo" autofocus>

            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="credban" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'credit bank') }}</label>
                <input readonly id="credban" name="credban" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ $purchaseTsa->credit }}" autocomplete="credban" autofocus>

            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label for="other" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'other') }}</label>
                <input readonly id="other1" name="other1" type="number" onblur="debtcalculate()" min="0" class="form-control" value="{{ $purchaseTsa->other }}" autocomplete="other" autofocus>

            </div>
        </div>

    </div>

    <div class="form-group">
        <label for="debt" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'total debt') }}</label>
        <input readonly id="debt" name="debt" type="number" class="form-control" value="{{ $purchaseTsa->debt }}" autocomplete="debt" autofocus readonly>
    </div>

    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label for="morpre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage %') }}</label>
                <input readonly id="morpre" name="morpre" min="0" max="100" type="number" class="form-control" value="{{ $purchaseTsa->mortPre }}" onblur="mortcalculate()" autocomplete="morpre" autofocus>

            </div>
        </div>
        <div class="col-9">
            <div class="form-group">
                <label for="morcos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                <input readonly id="morcos" name="morcos" type="number" min="0" class="form-control" value="{{ $purchaseTsa->mortCost }}" autocomplete="morcos" autofocus readonly>

            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label for="propre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Profit %') }}</label>
                <input readonly id="propre" name="propre" min="0" max="100" type="number" class="form-control" value="{{ $purchaseTsa->proftPre }}" onblur="profcalculate()" autocomplete="propre" autofocus>

            </div>
        </div>
        <div class="col-9">
            <div class="form-group">
                <label for="procos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                <input readonly id="procos" name="procos" type="number" min="0" class="form-control" value="{{ $purchaseTsa->profCost }}" autocomplete="procos" autofocus readonly>

            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="valadd" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'value added') }}</label>
                <input readonly id="valadd" name="valadd" min="0" type="number" class="form-control" value="{{ $purchaseTsa->addedVal }}" autocomplete="valadd" autofocus>

            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label for="admfe" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'admin fees') }}</label>
                <input readonly id="admfe" name="admfe" type="number" min="0" class="form-control" value="{{ $purchaseTsa->adminFee }}" autocomplete="admfe" autofocus>

            </div>
        </div>

    </div>


</div>

@else




<div class="card-title">
    <h3 class="text-center title-2">{{ MyHelpers::admin_trans(auth()->user()->id,'Tasahil Info') }}  <i onclick="showTsaheel()" class="fa fa-plus-circle text-info"></i> </h3>
</div>

<hr>

<div id="tsaheeldiv" style="display:block;">

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="visa" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'visa card') }}</label>
                <input id="visa" name="visa" min="0" type="number" value=0 onblur="debtcalculate()" class="form-control" autocomplete="visa" autofocus>

            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label for="carlo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'car loan') }}</label>
                <input id="carlo" name="carlo" type="number" value=0 onblur="debtcalculate()" min="0" class="form-control" autocomplete="carlo" autofocus>

            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="perlo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'personal loan') }}</label>
                <input id="perlo" name="perlo" min="0" type="number" value=0 onblur="debtcalculate()" class="form-control" autocomplete="perlo" autofocus>

            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label for="realo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate loan') }}</label>
                <input id="realo" name="realo" type="number" min="0" value=0 onblur="debtcalculate()"  class="form-control" autocomplete="realo" autofocus>

            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="credban" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'credit bank') }}</label>
                <input id="credban" name="credban" min="0" type="number" value=0 onblur="debtcalculate()" class="form-control" autocomplete="credban" autofocus>

            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label for="other" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'other') }}</label>
                <input id="other" name="other" type="number" onblur="debtcalculate()" value=0 min="0" class="form-control" autocomplete="other" autofocus>

            </div>
        </div>

    </div>

    <div class="form-group">
        <label for="debt" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'total debt') }}</label>
        <input id="debt" name="debt" type="number" value=0 class="form-control" autocomplete="debt"  autofocus readonly>
    </div>

    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label for="morpre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage %') }}</label>
                <input id="morpre" name="morpre" min="0" max="100" type="number" class="form-control" onblur="mortcalculate()" autocomplete="morpre" autofocus>

            </div>
        </div>
        <div class="col-9">
            <div class="form-group">
                <label for="morcos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                <input id="morcos" name="morcos" type="number" min="0" class="form-control" autocomplete="morcos" autofocus readonly>

            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label for="propre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Profit %') }}</label>
                <input id="propre" name="propre" min="0" max="100" type="number" class="form-control" onblur="profcalculate()" autocomplete="propre" autofocus>

            </div>
        </div>
        <div class="col-9">
            <div class="form-group">
                <label for="procos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                <input id="procos" name="procos" type="number" min="0" class="form-control" autocomplete="procos" autofocus readonly>

            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="valadd" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'value added') }}</label>
                <input id="valadd" name="valadd" min="0" type="number" class="form-control" autocomplete="valadd" autofocus>

            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label for="admfe" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'admin fees') }}</label>
                <input id="admfe" name="admfe" type="number" min="0" class="form-control" autocomplete="admfe" autofocus >

            </div>
        </div>

    </div>

</div>

@endif


