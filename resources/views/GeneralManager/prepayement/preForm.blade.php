<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card">

            <div class="card-body">
                <div class="card-title">
                    <h3 class="text-center title-2">Prepayment Information</h3>
                </div>
                <hr>

                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="check" class="control-label mb-1">Check Value</label>
                            <input id="check" name="check" type="number" class="form-control" value="{{ $purchaseReal->cost }}" autocomplete="sheck" autofocus readonly>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="real" class="control-label mb-1">Real Cost</label>
                            <input id="real" name="real" type="number" min="0" class="form-control" onblur="incresecalculate()" autocomplete="real" value="{{ old('real') }}" autofocus>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="incr" class="control-label mb-1">Increase Value</label>
                            <input id="incr" name="incr" type="number" class="form-control" autocomplete="incr" autofocus readonly>

                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="preval" class="control-label mb-1">Prepayment Value</label>
                            <input id="preval" name="preval" type="number" min="0" class="form-control" onblur="preCoscalculate()" autocomplete="preval" value="{{ old('preval') }}" autofocus>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="prepre" class="control-label mb-1">Prepayment %</label>
                            <input id="prepre" name="prepre" type="number" min="0" max="100" class="form-control" onblur="preCoscalculate()" autocomplete="prepre" autofocus>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="precos" class="control-label mb-1">Prepayment Cost</label>
                            <input id="precos" name="precos" type="number" class="form-control" autocomplete="precos" autofocus readonly>

                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="net" class="control-label mb-1">Net to the customer</label>
                            <input id="net" name="net" type="number" min="0" class="form-control" autocomplete="net" value="{{ old('net') }}" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="deficit" class="control-label mb-1"> Customer Deficit</label>
                            <input id="deficit" name="deficit" type="number" min="0" class="form-control" autocomplete="deficit" autofocus>

                        </div>
                    </div>

                </div>

                <br><br>

                @include('FundingManager.prepayement.tsaheelForm')

            </div>
        </div>
    </div>
</div>