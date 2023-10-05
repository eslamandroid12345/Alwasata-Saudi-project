<div class="card-title">
    <h3 class="text-center title-2">Tsaheel Information <i onclick="showTsaheel()" class="fa fa-plus-circle text-info"></i> </h3>
</div>

<hr>

<div id="tsaheeldiv" style="display:block;">

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="visa" class="control-label mb-1">Visa Card</label>
                <input id="visa" name="visa" min="0" type="number" value=0 onblur="debtcalculate()" class="form-control" autocomplete="visa" autofocus>

            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label for="carlo" class="control-label mb-1">Car Loan</label>
                <input id="carlo" name="carlo" type="number" value=0 onblur="debtcalculate()" min="0" class="form-control" autocomplete="carlo" autofocus>

            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="perlo" class="control-label mb-1">Personal Loan</label>
                <input id="perlo" name="perlo" min="0" type="number" value=0 onblur="debtcalculate()" class="form-control" autocomplete="perlo" autofocus>

            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label for="realo" class="control-label mb-1">Real Estate Loan</label>
                <input id="realo" name="realo" type="number" min="0" value=0 onblur="debtcalculate()"  class="form-control" autocomplete="realo" autofocus>

            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="credban" class="control-label mb-1">Credit Bank</label>
                <input id="credban" name="credban" min="0" type="number" value=0 onblur="debtcalculate()" class="form-control" autocomplete="credban" autofocus>

            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label for="other" class="control-label mb-1">Other</label>
                <input id="other" name="other" type="number" onblur="debtcalculate()" value=0 min="0" class="form-control" autocomplete="other" autofocus>

            </div>
        </div>

    </div>

    <div class="form-group">
        <label for="debt" class="control-label mb-1">Total Debt</label>
        <input id="debt" name="debt" type="number" value=0 class="form-control" autocomplete="debt"  autofocus readonly>
    </div>

    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label for="morpre" class="control-label mb-1">Mortgage %</label>
                <input id="morpre" name="morpre" min="0" max="100" type="number" class="form-control" onblur="mortcalculate()" autocomplete="morpre" autofocus>

            </div>
        </div>
        <div class="col-9">
            <div class="form-group">
                <label for="morcos" class="control-label mb-1">Mortgage Cost</label>
                <input id="morcos" name="morcos" type="number" min="0" class="form-control" autocomplete="morcos" autofocus readonly>

            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label for="propre" class="control-label mb-1">Profit %</label>
                <input id="propre" name="propre" min="0" max="100" type="number" class="form-control" onblur="profcalculate()" autocomplete="propre" autofocus>

            </div>
        </div>
        <div class="col-9">
            <div class="form-group">
                <label for="procos" class="control-label mb-1">Profit Cost</label>
                <input id="procos" name="procos" type="number" min="0" class="form-control" autocomplete="procos" autofocus readonly>

            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="valadd" class="control-label mb-1">Value Added</label>
                <input id="valadd" name="valadd" min="0" type="number" class="form-control" autocomplete="valadd" autofocus>

            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label for="admfe" class="control-label mb-1">Admin Fees</label>
                <input id="admfe" name="admfe" type="number" min="0" class="form-control" autocomplete="admfe" autofocus >

            </div>
        </div>

    </div>

</div>